/**
 * jQuery gMap v3
 *
 * @url         http://www.smashinglabs.pl/gmap
 * @author      Sebastian Poreba <sebastian.poreba@gmail.com>
 * @version     3.3.3
 * @date        27.12.2012
 */
/*jslint white: false, undef: true, regexp: true, plusplus: true, bitwise: true, newcap: true, strict: true, devel: true, maxerr: 50, indent: 4 */
/*global window, jQuery, $, google, $googlemaps */
(function($) {
  'use strict';

  /**
     * Internals and experimental section
     */
  var Cluster = function() {
    this.markers = [];
    this.mainMarker = false;
    this.icon = 'http://www.google.com/mapfiles/marker.png';
  };

  /**
     * For iterating over all clusters to find if any is close enough to be merged with marker
     *
     * @param marker
     * @param currentSize - calculated as viewport percentage (opts.clusterSize).
     * @return bool.
     */
  Cluster.prototype.dist = function(marker) {
    return Math.sqrt(Math.pow(this.markers[0].latitude - marker.latitude, 2) +
            Math.pow(this.markers[0].longitude - marker.longitude, 2));
  };

  Cluster.prototype.setIcon = function(icon) {
    this.icon = icon;
  };

  Cluster.prototype.addMarker = function(marker) {
    this.markers[this.markers.length] = marker;
  };

  /**
     * returns one marker if there is only one or
     * returns special cloister marker if there are more
     */
  Cluster.prototype.getMarker = function() {
    if (this.mainmarker) {return this.mainmarker; }
    var gicon, title;
    if (this.markers.length > 1) {
      gicon = new $googlemaps.MarkerImage('http://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=' + this.markers.length + '%7cff776b%7c000000');
      title = 'cluster of ' + this.markers.length + ' markers';
    } else {
      gicon = new $googlemaps.MarkerImage(this.icon);
      title = this.markers[0].title;
    }
    this.mainmarker = new $googlemaps.Marker({
      position: new $googlemaps.LatLng(this.markers[0].latitude, this.markers[0].longitude),
      icon: gicon,
      title: title,
      map: null
    });
    return this.mainmarker;
  };

  // global google maps objects
  var $googlemaps = google.maps,
      $geocoder = new $googlemaps.Geocoder(),
      $markersToLoad = 0,
      overQueryLimit = 0,
      methods = {};
  methods = {
    /**
         * initialisation/internals
         */

    init: function(options) {
      var k,
          // Build main options before element iteration
          opts = $.extend({}, $.fn.gMap.defaults, options);

                // recover icon array
                for (k in $.fn.gMap.defaults.icon) {
        if (!opts.icon[k]) {
          opts.icon[k] = $.fn.gMap.defaults.icon[k];
        }
                }

      // Iterate through each element
      return this.each(function() {
        var $this = $(this),
                    center = methods._getMapCenter.apply($this, [opts]),
                    i, $data;

        if (opts.zoom == 'fit') {
					          opts.zoomFit = true;
                    opts.zoom = methods._autoZoom.apply($this, [opts]);
        }

        var mapOptions = {
          zoom: opts.zoom,
          center: center,
          mapTypeControl: opts.mapTypeControl,
          mapTypeControlOptions: {},
          zoomControl: opts.zoomControl,
          zoomControlOptions: {},
          panControl: opts.panControl,
          panControlOptions: {},
          scaleControl: opts.scaleControl,
          scaleControlOptions: {},
          streetViewControl: opts.streetViewControl,
          streetViewControlOptions: {},
          mapTypeId: opts.maptype,
          scrollwheel: opts.scrollwheel,
          maxZoom: opts.maxZoom,
          minZoom: opts.minZoom
        };
        if (opts.controlsPositions.mapType) {mapOptions.mapTypeControlOptions.position = opts.controlsPositions.mapType}
        if (opts.controlsPositions.zoom) {mapOptions.zoomControlOptions.position = opts.controlsPositions.zoom}
        if (opts.controlsPositions.pan) {mapOptions.panControlOptions.position = opts.controlsPositions.pan}
        if (opts.controlsPositions.scale) {mapOptions.scaleControlOptions.position = opts.controlsPositions.scale}
        if (opts.controlsPositions.streetView) {mapOptions.streetViewControlOptions.position = opts.controlsPositions.streetView}

        if (opts.styles) {mapOptions.styles = opts.styles}

        mapOptions.mapTypeControlOptions.style = opts.controlsStyle.mapType;
        mapOptions.zoomControlOptions.style = opts.controlsStyle.zoom;

        mapOptions = $.extend(mapOptions, opts.extra);

        // Create map and set initial options
        var $gmap = new $googlemaps.Map(this, mapOptions);

        if (opts.log) {console.log('map center is:'); }
        if (opts.log) {console.log(center); }

        // Create map and set initial options

        $this.data('$gmap', $gmap);

        $this.data('gmap', {
                    'opts': opts,
                    'gmap': $gmap,
                    'markers': [],
                    'markerKeys' : {},
                    'infoWindow': null,
                    'clusters': []
        });

        // Check for map controls
        if (opts.controls.length !== 0) {
                    // Add custom map controls
                    for (i = 0; i < opts.controls.length; i += 1) {
            $gmap.controls[opts.controls[i].pos].push(opts.controls[i].div);
                    }
        }

        if (opts.clustering.enabled) {
                    $data = $this.data('gmap');
                    (function(markers) {$data.markers = markers;}(opts.markers));
                    methods._renderCluster.apply($this, []);

                    $googlemaps.event.addListener($gmap, 'bounds_changed', function() {
            methods._renderCluster.apply($this, []);
                    });
        } else {
                    if (opts.markers.length !== 0) {
            methods.addMarkers.apply($this, [opts.markers]);
                    }
        }

        methods._onComplete.apply($this, []);
      });
    },


    _delayedMode: false,

    /**
         * Check every 100ms if all markers were loaded, then call onComplete
         */
    _onComplete: function() {
      var $data = this.data('gmap'),
                that = this;
      if ($markersToLoad !== 0) {
        window.setTimeout(function() {methods._onComplete.apply(that, []); }, 100);
        return;
      }
      if (methods._delayedMode) {
        var center = methods._getMapCenter.apply(this, [$data.opts, true]);
        methods._setMapCenter.apply(this, [center]);
				if ($data.opts.zoomFit) {
					var zoom = methods._autoZoom.apply(this, [$data.opts, true]);
					$data.gmap.setZoom(zoom);
				}
      }
      $data.opts.onComplete();
    },

    /**
         * set map center when map is loaded (check every 100ms)
         */
    _setMapCenter: function(center) {
      var $data = this.data('gmap');
      if ($data && $data.opts.log) {console.log('delayed setMapCenter called'); }
      if ($data && $data.gmap !== undefined && $markersToLoad == 0) {
        $data.gmap.setCenter(center);
      } else {
        var that = this;
        window.setTimeout(function() {methods._setMapCenter.apply(that, [center]); }, 100);
      }
    },

    /**
         * calculate boundaries, optimised and independent from Google Maps
         */
    _boundaries: null,
    _getBoundaries: function(opts) {
      // if(methods._boundaries) {return methods._boundaries; }
      var markers = opts.markers, i;
      var mostN = 1000,
                mostE = -1000,
                mostW = 1000,
                mostS = -1000;
      if (markers) {
        for (i = 0; i < markers.length; i += 1) {
                    if (!markers[i].latitude || !markers[i].longitude) continue;

                    if (mostN > markers[i].latitude) {mostN = markers[i].latitude; }
                    if (mostE < markers[i].longitude) {mostE = markers[i].longitude; }
                    if (mostW > markers[i].longitude) {mostW = markers[i].longitude; }
                    if (mostS < markers[i].latitude) {mostS = markers[i].latitude; }
                    if (opts.log) {
                      console.log(markers[i].latitude, markers[i].longitude, mostN, mostE, mostW, mostS);
                    }
        }
        methods._boundaries = {N: mostN, E: mostE, W: mostW, S: mostS};
      }

      if (mostN == -1000) methods._boundaries = {N: 0, E: 0, W: 0, S: 0};

      return methods._boundaries;
    },

    /**
         * Priorities order:
         * - latitude & longitude in options
         * - address in options
         * - latitude & longitude of first marker having it
         * - address of first marker having it
         * - failsafe (0,0)
         *
         * Note: with geocoding returned value is (0,0) and callback sets map center. It's not very nice nor efficient.
         *       It is quite good idea to use only first option
         */
    _getMapCenter: function(opts, fromMarkers) {
      // Create new object to geocode addresses

      var center,
                that = this, // 'that' scope fix in geocoding
                i,
                selectedToCenter,
                most; //hoisting

      if (opts.markers.length && (opts.latitude == 'fit' || opts.longitude == 'fit')) {
        if (fromMarkers) {
          opts.markers = methods._convertMarkers(data.markers);
        }

        most = methods._getBoundaries(opts);
        center = new $googlemaps.LatLng((most.N + most.S) / 2, (most.E + most.W) / 2);
        if (opts.log) {
          console.log(fromMarkers, most, center);
        }
        return center;
      }

      if (opts.latitude && opts.longitude) {
        // lat & lng available, return
        center = new $googlemaps.LatLng(opts.latitude, opts.longitude);
        return center;
      } else {
        center = new $googlemaps.LatLng(0, 0);
      }

      // Check for address to center on
      if (opts.address) {
        // Get coordinates for given address and center the map
        $geocoder.geocode(
            {address: opts.address},
            function(result, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                methods._setMapCenter.apply(that, [result[0].geometry.location]);
                        } else {
                if (opts.log) {console.log('Geocode was not successful for the following reason: ' + status); }
                        }
            }
        );
        return center;
      }

      // Check for a marker to center on (if no coordinates given)
      if (opts.markers.length > 0) {
        selectedToCenter = null;

        for (i = 0; i < opts.markers.length; i += 1) {
                    if (opts.markers[i].setCenter) {
            selectedToCenter = opts.markers[i];
            break;
                    }
        }

        if (selectedToCenter === null) {
                    for (i = 0; i < opts.markers.length; i += 1) {
            if (opts.markers[i].latitude && opts.markers[i].longitude) {
              selectedToCenter = opts.markers[i];
              break;
            }
            if (opts.markers[i].address) {
              selectedToCenter = opts.markers[i];
            }
                    }
        }
        // failed to find any reasonable marker (it's quite impossible BTW)
        if (selectedToCenter === null) {
                    return center;
        }

        if (selectedToCenter.latitude && selectedToCenter.longitude) {
                    return new $googlemaps.LatLng(selectedToCenter.latitude, selectedToCenter.longitude);
        }

        // Check if the marker has an address
        if (selectedToCenter.address) {
                    // Get the coordinates for given marker address and center
                    $geocoder.geocode(
                        {address: selectedToCenter.address},
                        function(result, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                  methods._setMapCenter.apply(that, [result[0].geometry.location]);
                } else {
                  if (opts.log) {console.log('Geocode was not successful for the following reason: ' + status); }
                }
                        }
                    );
        }
      }
      return center;
    },


    /**
         * clustering
         */
    _renderCluster: function() {
      var $data = this.data('gmap'),
                markers = $data.markers,
                clusters = $data.clusters,
                opts = $data.opts,
                i,
                j,
                viewport;

      for (i = 0; i < clusters.length; i += 1) {
        clusters[i].getMarker().setMap(null);
      }
      clusters.length = 0;

      viewport = $data.gmap.getBounds();

      if (!viewport) {
        var that = this;
        window.setTimeout(function() {methods._renderCluster.apply(that); }, 1000);
        return;
      }

      var ne = viewport.getNorthEast(),
                sw = viewport.getSouthWest(),
                width = ne.lat() - sw.lat(),
                // height = ne.lng() - sw.lng(), // unused
                clusterable = [],
                best,
                bestDist,
                maxSize = width * opts.clustering.clusterSize / 100,
                dist,
                newCluster;

      for (i = 0; i < markers.length; i += 1) {
        if (markers[i].latitude < ne.lat() &&
            markers[i].latitude > sw.lat() &&
                    markers[i].longitude < ne.lng() &&
                    markers[i].longitude > sw.lng()) {
                    clusterable[clusterable.length] = markers[i];
        }
      }

      if (opts.log) {console.log('number of markers ' + clusterable.length + '/' + markers.length); }
      if (opts.log) {console.log('cluster radius: ' + maxSize); }

      for (i = 0; i < clusterable.length; i += 1) {
        bestDist = 10000;
        best = -1;
        for (j = 0; j < clusters.length; j += 1) {
                    dist = clusters[j].dist(clusterable[i]);
                    if (dist < maxSize) {
            bestDist = dist;
            best = j;
            if (opts.clustering.fastClustering) {break; }
                    }
        }
        if (best === -1) {
                    newCluster = new Cluster();
                    newCluster.addMarker(clusterable[i]);
                    clusters[clusters.length] = newCluster;
        } else {
                    clusters[best].addMarker(clusterable[i]);
        }
      }

      if (opts.log) {console.log('Total clusters in viewport: ' + clusters.length); }

      for (j = 0; j < clusters.length; j += 1) {
        clusters[j].getMarker().setMap($data.gmap);
      }
    },

    _processMarker: function(marker, gicon, gshadow, location) {
      var $data = this.data('gmap'),
                $gmap = $data.gmap,
                opts = $data.opts,
                gmarker,
                markeropts;

      if (location === undefined) {
        location = new $googlemaps.LatLng(marker.latitude, marker.longitude);
      }

      if (!gicon) {

        // Set icon properties from global options
        var _gicon = {
                    image: opts.icon.image,
                    iconSize: new $googlemaps.Size(opts.icon.iconsize[0], opts.icon.iconsize[1]),
                    iconAnchor: new $googlemaps.Point(opts.icon.iconanchor[0], opts.icon.iconanchor[1]),
                    infoWindowAnchor: new $googlemaps.Size(opts.icon.infowindowanchor[0], opts.icon.infowindowanchor[1])
        };
        gicon = new $googlemaps.MarkerImage(_gicon.image, _gicon.iconSize, null, _gicon.iconAnchor);
      }

      if (!gshadow) {
        var _gshadow = {
                    image: opts.icon.shadow,
                    iconSize: new $googlemaps.Size(opts.icon.shadowsize[0], opts.icon.shadowsize[1]),
                    anchor: (_gicon && _gicon.iconAnchor) ? _gicon.iconAnchor : new $googlemaps.Point(opts.icon.iconanchor[0], opts.icon.iconanchor[1])
        };
      }

      markeropts = {
        position: location,
        icon: gicon,
        title: marker.title,
        map: null,
        draggable: ((marker.draggable === true) ? true : false)
                };

      if (!opts.clustering.enabled) {markeropts.map = $gmap; }

      gmarker = new $googlemaps.Marker(markeropts);
      gmarker.setShadow(gshadow);
      $data.markers.push(gmarker);

      if (marker.key) {$data.markerKeys[marker.key] = gmarker; }

      // Set HTML and check if info window should be opened
      var infoWindow;
      if (marker.html) {
        var infoContent = typeof(marker.html) === 'string' ? opts.html_prepend + marker.html + opts.html_append : marker.html;
        var infoOpts = {
                    content: infoContent,
                    pixelOffset: marker.infoWindowAnchor
        };

        if (opts.log) {console.log('setup popup with data'); }
        if (opts.log) {console.log(infoOpts); }
        infoWindow = new $googlemaps.InfoWindow(infoOpts);

        $googlemaps.event.addListener(gmarker, 'click', function() {
                    if (opts.log) {console.log('opening popup ' + marker.html); }
                    if (opts.singleInfoWindow && $data.infoWindow) {$data.infoWindow.close(); }
                    infoWindow.open($gmap, gmarker);
                    $data.infoWindow = infoWindow;
        });
      }
      if (marker.html && marker.popup) {
        if (opts.log) {console.log('opening popup ' + marker.html); }
        infoWindow.open($gmap, gmarker);
        $data.infoWindow = infoWindow;
      }

      if (marker.onDragEnd) {
        $googlemaps.event.addListener(gmarker, 'dragend', function(event) {
                    if (opts.log) {console.log('drag end');}
                    marker.onDragEnd(event);
        });
      }

    },

    _convertMarkers: function(googleMarkers) {
      var markers = [], i;
      for (i = 0; i < googleMarkers.length; i += 1) {
        markers[i] = {
                    latitude: googleMarkers[i].getPosition().lat(),
                    longitude: googleMarkers[i].getPosition().lng()
        };
      }
      return markers;
    },

    _geocodeMarker: function(marker, gicon, gshadow) {
      var that = this;
      $geocoder.geocode({'address': marker.address}, function(results, status) {
        if (status === $googlemaps.GeocoderStatus.OK) {
                    $markersToLoad -= 1;
                    if (that.data('gmap').opts.log) {console.log('Geocode was successful with point: ', results[0].geometry.location); }
                    methods._processMarker.apply(that, [marker, gicon, gshadow, results[0].geometry.location]);
        } else {
                    if (status === $googlemaps.GeocoderStatus.OVER_QUERY_LIMIT) {
            if ((!that.data('gmap').opts.noAlerts) && (overQueryLimit === 0)) {alert('Error: too many geocoded addresses! Switching to 1 marker/s mode.'); }

            overQueryLimit += 1000;
            window.setTimeout(function() {
              methods._geocodeMarker.apply(that, [marker, gicon, gshadow]);
            }, overQueryLimit);
                    }
                    if (that.data('gmap').opts.log) {console.log('Geocode was not successful for the following reason: ' + status); }
        }
      });
    },

    _autoZoom: function(options, fromMarkers) {
      var data = $(this).data('gmap'),
                opts = $.extend({}, data ? data.opts : {}, options),
                i, boundaries, resX, resY, baseScale = 39135.758482;
      if (opts.log) {console.log('autozooming map');}

      if (fromMarkers) {
        opts.markers = methods._convertMarkers(data.markers);
      }

      boundaries = methods._getBoundaries(opts);

      resX = (boundaries.E - boundaries.W) * 111000 / this.width();
      resY = (boundaries.S - boundaries.N) * 111000 / this.height();

      for (i = 2; i < 20; i += 1) {
        if (resX > baseScale || resY > baseScale) {
                    break;
        }
        baseScale = baseScale / 2;
      }
      return i - 2;
    },

    /**
         * public methods section
         */

    /**
         * add array of markers
         * @param markers
         */
    addMarkers: function(markers) {
      var opts = this.data('gmap').opts;

      if (markers.length !== 0) {
        if (opts.log) {console.log('adding ' + markers.length + ' markers');}
        // Loop through marker array
        for (var i = 0; i < markers.length; i += 1) {
                    methods.addMarker.apply(this, [markers[i]]);
        }
      }
    },

    /**
         * add single marker
         * @param marker
         */
    addMarker: function(marker) {
      var opts = this.data('gmap').opts;

      if (opts.log) {console.log('putting marker at ' + marker.latitude + ', ' + marker.longitude + ' with address ' + marker.address + ' and html ' + marker.html); }

      // Create new icon
      // Set icon properties from global options
      var _gicon = {
        image: opts.icon.image,
        iconSize: new $googlemaps.Size(opts.icon.iconsize[0], opts.icon.iconsize[1]),
        iconAnchor: new $googlemaps.Point(opts.icon.iconanchor[0], opts.icon.iconanchor[1]),
        infoWindowAnchor: new $googlemaps.Size(opts.icon.infowindowanchor[0], opts.icon.infowindowanchor[1])
      },
          _gshadow = {
            image: opts.icon.shadow,
            iconSize: new $googlemaps.Size(opts.icon.shadowsize[0], opts.icon.shadowsize[1]),
            anchor: new $googlemaps.Point(opts.icon.shadowanchor[0], opts.icon.shadowanchor[1])
          };

      // not very nice, but useful
      marker.infoWindowAnchor = _gicon.infoWindowAnchor;

      if (marker.icon) {
        // Overwrite global options
        if (marker.icon.image) { _gicon.image = marker.icon.image; }
        if (marker.icon.iconsize) { _gicon.iconSize = new $googlemaps.Size(marker.icon.iconsize[0], marker.icon.iconsize[1]); }

        if (marker.icon.iconanchor) { _gicon.iconAnchor = new $googlemaps.Point(marker.icon.iconanchor[0], marker.icon.iconanchor[1]); }
        if (marker.icon.infowindowanchor) { _gicon.infoWindowAnchor = new $googlemaps.Size(marker.icon.infowindowanchor[0], marker.icon.infowindowanchor[1]); }

        if (marker.icon.shadow) { _gshadow.image = marker.icon.shadow; }
        if (marker.icon.shadowsize) { _gshadow.iconSize = new $googlemaps.Size(marker.icon.shadowsize[0], marker.icon.shadowsize[1]); }

        if (marker.icon.shadowanchor) { _gshadow.anchor = new $googlemaps.Point(marker.icon.shadowanchor[0], marker.icon.shadowanchor[1]); }
      }

      var gicon = new $googlemaps.MarkerImage(_gicon.image, _gicon.iconSize, null, _gicon.iconAnchor);
      var gshadow = new $googlemaps.MarkerImage(_gshadow.image, _gshadow.iconSize, null, _gshadow.anchor);

      // Check if address is available
      if (marker.address) {
        // Check for reference to the marker's address
        if (marker.html === '_address') {
                    marker.html = marker.address;
        }

        if (marker.title == '_address') {
                    marker.title = marker.address;
        }

        if (opts.log) {console.log('geocoding marker: ' + marker.address); }
        // Get the point for given address
        $markersToLoad += 1;
        methods._delayedMode = true;
        methods._geocodeMarker.apply(this, [marker, gicon, gshadow]);
      } else {
        // Check for reference to the marker's latitude/longitude
        if (marker.html === '_latlng') {
                    marker.html = marker.latitude + ', ' + marker.longitude;
        }

        if (marker.title == '_latlng') {
                    marker.title = marker.latitude + ', ' + marker.longitude;
        }

        // Create marker
        var gpoint = new $googlemaps.LatLng(marker.latitude, marker.longitude);
        methods._processMarker.apply(this, [marker, gicon, gshadow, gpoint]);
      }
    },

    /**
         *
         */
    removeAllMarkers: function() {
      var markers = this.data('gmap').markers, markerKeys = this.data('gmap').markerKeys, i;

      for (i = 0; i < markers.length; i += 1) {
        markers[i].setMap(null);
        delete markers[i];
      }
      markers.length = 0;

      for (i in markerKeys) {
        delete markerKeys[i];
      }
    },

    removeMarker: function(marker) {
      var markers = this.data('gmap').markers, markerKeys = this.data('gmap').markerKeys, i = markers.indexOf(marker);

      if (i !== -1) {
        markers[i].setMap(null);
        delete markers[i];
        markers.splice(i, 1);
      }

      for (i in markerKeys) {
        if (markerKeys[i] === marker) {
          delete markerKeys[i];
        }
      }
    },

    /**
         * get marker by key, if set previously
         * @param key
         */
    getMarker: function(key) {
      return this.data('gmap').markerKeys[key];
    },

    /**
         * should be called if DOM element was resized
         * @param nasty
         */
    fixAfterResize: function(nasty) {
      var data = this.data('gmap');
      $googlemaps.event.trigger(data.gmap, 'resize');

      if (nasty) {
        data.gmap.panTo(new google.maps.LatLng(0, 0));
      }
      data.gmap.panTo(this.gMap('_getMapCenter', data.opts));
    },

    /**
         * change zoom, works with 'fit' option as well
         * @param zoom
         */
    setZoom: function(zoom, opts, fromMarkers) {
      var $map = this.data('gmap').gmap;
      if (zoom === 'fit') {
        zoom = methods._autoZoom.apply(this, [opts, fromMarkers]);
      }
      $map.setZoom(parseInt(zoom));
    },

    changeSettings: function(options) {
      var data = this.data('gmap'),
                markers = [], i, originalMarkers;
      if (options.markers === undefined) {
        options.markers = methods._convertMarkers(data.markers);
      }
      else if (options.markers.length !== 0 && options.markers[0].latitude === undefined) {
        options.markers = methods._convertMarkers(options.markers);
      }

      if (options.zoom) methods.setZoom.apply(this, [options.zoom, options]);
      if (options.latitude || options.longitude) {
        data.gmap.panTo(methods._getMapCenter.apply(this, [options]));
      }

      // add controls and maptype
    },

    mapclick: function(callback) {
      google.maps.event.addListener(this.data('gmap').gmap, 'click', function(event) {
        callback(event.latLng);
      });
    },

    geocode: function(address, callback, errorCallback) {
      $geocoder.geocode({'address': address}, function(results, status) {
        if (status === $googlemaps.GeocoderStatus.OK) {
                    callback(results[0].geometry.location);
        } else if (errorCallback) {
                    errorCallback(results, status);
        }
      });
    },

    getRoute: function(options) {

      var $data = this.data('gmap'),
          $gmap = $data.gmap,
          $directionsDisplay = new $googlemaps.DirectionsRenderer(),
          $directionsService = new $googlemaps.DirectionsService(),
          $travelModes = { 'BYCAR': $googlemaps.DirectionsTravelMode.DRIVING, 'BYBICYCLE': $googlemaps.DirectionsTravelMode.BICYCLING, 'BYFOOT': $googlemaps.DirectionsTravelMode.WALKING },
          $travelUnits = { 'MILES': $googlemaps.DirectionsUnitSystem.IMPERIAL, 'KM': $googlemaps.DirectionsUnitSystem.METRIC },
          displayObj = null,
          travelMode = null,
          travelUnit = null,
          unitSystem = null;

      // look if there is an individual or otherwise a default object for this call to display route text informations
      if (options.routeDisplay !== undefined) {
        displayObj = (options.routeDisplay instanceof jQuery) ? options.routeDisplay[0] : ((typeof options.routeDisplay == 'string') ? $(options.routeDisplay)[0] : null);
      } else if ($data.opts.routeFinder.routeDisplay !== null) {
        displayObj = ($data.opts.routeFinder.routeDisplay instanceof jQuery) ? $data.opts.routeFinder.routeDisplay[0] : ((typeof $data.opts.routeFinder.routeDisplay == 'string') ? $($data.opts.routeFinder.routeDisplay)[0] : null);
      }

      // set route renderer to map
      $directionsDisplay.setMap($gmap);
      if (displayObj !== null) {
        $directionsDisplay.setPanel(displayObj);
      }

      // get travel mode and unit
      travelMode = ($travelModes[$data.opts.routeFinder.travelMode] !== undefined) ? $travelModes[$data.opts.routeFinder.travelMode] : $travelModes['BYCAR'];
      travelUnit = ($travelUnits[$data.opts.routeFinder.travelUnit] !== undefined) ? $travelUnits[$data.opts.routeFinder.travelUnit] : $travelUnits['KM'];

      // build request
      var request = {
        origin: options.from,
        destination: options.to,
        travelMode: travelMode,
        unitSystem: travelUnit
      };

      // send request
      $directionsService.route(request, function(result, status) {
        // show the rout or otherwise show an error message in a defined container for route text information
        if (status == $googlemaps.DirectionsStatus.OK) {
                    $directionsDisplay.setDirections(result);
        } else if (displayObj !== null) {
                    $(displayObj).html($data.opts.routeFinder.routeErrors[status]);
        }
      });
      return this;
    }
  };


  // Main plugin function
  $.fn.gMap = function(method) {
    // Method calling logic
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' + method + ' does not exist on jQuery.gmap');
    }
  };

  // Default settings
  $.fn.gMap.defaults = {
    log: false,
    address: '',
    latitude: null,
    longitude: null,
    zoom: 3,
    maxZoom: null,
    minZoom: null,
    markers: [],
    controls: {},
    scrollwheel: true,
    maptype: google.maps.MapTypeId.ROADMAP,

    mapTypeControl: true,
    zoomControl: true,
    panControl: false,
    scaleControl: false,
    streetViewControl: true,

    controlsPositions: {
      mapType: null,
      zoom: null,
      pan: null,
      scale: null,
      streetView: null
    },
    controlsStyle: {
      mapType: google.maps.MapTypeControlStyle.DEFAULT,
      zoom:	google.maps.ZoomControlStyle.DEFAULT
    },

    singleInfoWindow: true,

    html_prepend: '<div class="gmap_marker">',
    html_append: '</div>',
    icon: {
      image: 'http://www.google.com/mapfiles/marker.png',
      iconsize: [20, 34],
      iconanchor: [9, 34],
      infowindowanchor: [9, 2],
      shadow: 'http://www.google.com/mapfiles/shadow50.png',
      shadowsize: [37, 34],
      shadowanchor: [9, 34]
    },

    onComplete: function() {},

    routeFinder: {
      travelMode: 'BYCAR',
      travelUnit: 'KM',
      routeDisplay: null,
      routeErrors:	{
        'INVALID_REQUEST': 'The provided request is invalid.',
        'NOT_FOUND': 'One or more of the given addresses could not be found.',
        'OVER_QUERY_LIMIT': 'A temporary error occured. Please try again in a few minutes.',
        'REQUEST_DENIED': 'An error occured. Please contact us.',
        'UNKNOWN_ERROR': 'An unknown error occured. Please try again.',
        'ZERO_RESULTS': 'No route could be found within the given addresses.'
      }
    },

    clustering: {
      enabled: false,
      fastClustering: false,
      clusterCount: 10,
      clusterSize: 40 //radius as % of viewport width
    },
    extra: {}
  };
}(jQuery));


(function(){var n=this,t=n._,r={},e=Array.prototype,u=Object.prototype,i=Function.prototype,a=e.push,o=e.slice,c=e.concat,l=u.toString,f=u.hasOwnProperty,s=e.forEach,p=e.map,h=e.reduce,v=e.reduceRight,d=e.filter,g=e.every,m=e.some,y=e.indexOf,b=e.lastIndexOf,x=Array.isArray,_=Object.keys,j=i.bind,w=function(n){return n instanceof w?n:this instanceof w?(this._wrapped=n,void 0):new w(n)};"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=w),exports._=w):n._=w,w.VERSION="1.4.4";var A=w.each=w.forEach=function(n,t,e){if(null!=n)if(s&&n.forEach===s)n.forEach(t,e);else if(n.length===+n.length){for(var u=0,i=n.length;i>u;u++)if(t.call(e,n[u],u,n)===r)return}else for(var a in n)if(w.has(n,a)&&t.call(e,n[a],a,n)===r)return};w.map=w.collect=function(n,t,r){var e=[];return null==n?e:p&&n.map===p?n.map(t,r):(A(n,function(n,u,i){e[e.length]=t.call(r,n,u,i)}),e)};var O="Reduce of empty array with no initial value";w.reduce=w.foldl=w.inject=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),h&&n.reduce===h)return e&&(t=w.bind(t,e)),u?n.reduce(t,r):n.reduce(t);if(A(n,function(n,i,a){u?r=t.call(e,r,n,i,a):(r=n,u=!0)}),!u)throw new TypeError(O);return r},w.reduceRight=w.foldr=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),v&&n.reduceRight===v)return e&&(t=w.bind(t,e)),u?n.reduceRight(t,r):n.reduceRight(t);var i=n.length;if(i!==+i){var a=w.keys(n);i=a.length}if(A(n,function(o,c,l){c=a?a[--i]:--i,u?r=t.call(e,r,n[c],c,l):(r=n[c],u=!0)}),!u)throw new TypeError(O);return r},w.find=w.detect=function(n,t,r){var e;return E(n,function(n,u,i){return t.call(r,n,u,i)?(e=n,!0):void 0}),e},w.filter=w.select=function(n,t,r){var e=[];return null==n?e:d&&n.filter===d?n.filter(t,r):(A(n,function(n,u,i){t.call(r,n,u,i)&&(e[e.length]=n)}),e)},w.reject=function(n,t,r){return w.filter(n,function(n,e,u){return!t.call(r,n,e,u)},r)},w.every=w.all=function(n,t,e){t||(t=w.identity);var u=!0;return null==n?u:g&&n.every===g?n.every(t,e):(A(n,function(n,i,a){return(u=u&&t.call(e,n,i,a))?void 0:r}),!!u)};var E=w.some=w.any=function(n,t,e){t||(t=w.identity);var u=!1;return null==n?u:m&&n.some===m?n.some(t,e):(A(n,function(n,i,a){return u||(u=t.call(e,n,i,a))?r:void 0}),!!u)};w.contains=w.include=function(n,t){return null==n?!1:y&&n.indexOf===y?n.indexOf(t)!=-1:E(n,function(n){return n===t})},w.invoke=function(n,t){var r=o.call(arguments,2),e=w.isFunction(t);return w.map(n,function(n){return(e?t:n[t]).apply(n,r)})},w.pluck=function(n,t){return w.map(n,function(n){return n[t]})},w.where=function(n,t,r){return w.isEmpty(t)?r?null:[]:w[r?"find":"filter"](n,function(n){for(var r in t)if(t[r]!==n[r])return!1;return!0})},w.findWhere=function(n,t){return w.where(n,t,!0)},w.max=function(n,t,r){if(!t&&w.isArray(n)&&n[0]===+n[0]&&65535>n.length)return Math.max.apply(Math,n);if(!t&&w.isEmpty(n))return-1/0;var e={computed:-1/0,value:-1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;a>=e.computed&&(e={value:n,computed:a})}),e.value},w.min=function(n,t,r){if(!t&&w.isArray(n)&&n[0]===+n[0]&&65535>n.length)return Math.min.apply(Math,n);if(!t&&w.isEmpty(n))return 1/0;var e={computed:1/0,value:1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;e.computed>a&&(e={value:n,computed:a})}),e.value},w.shuffle=function(n){var t,r=0,e=[];return A(n,function(n){t=w.random(r++),e[r-1]=e[t],e[t]=n}),e};var k=function(n){return w.isFunction(n)?n:function(t){return t[n]}};w.sortBy=function(n,t,r){var e=k(t);return w.pluck(w.map(n,function(n,t,u){return{value:n,index:t,criteria:e.call(r,n,t,u)}}).sort(function(n,t){var r=n.criteria,e=t.criteria;if(r!==e){if(r>e||r===void 0)return 1;if(e>r||e===void 0)return-1}return n.index<t.index?-1:1}),"value")};var F=function(n,t,r,e){var u={},i=k(t||w.identity);return A(n,function(t,a){var o=i.call(r,t,a,n);e(u,o,t)}),u};w.groupBy=function(n,t,r){return F(n,t,r,function(n,t,r){(w.has(n,t)?n[t]:n[t]=[]).push(r)})},w.countBy=function(n,t,r){return F(n,t,r,function(n,t){w.has(n,t)||(n[t]=0),n[t]++})},w.sortedIndex=function(n,t,r,e){r=null==r?w.identity:k(r);for(var u=r.call(e,t),i=0,a=n.length;a>i;){var o=i+a>>>1;u>r.call(e,n[o])?i=o+1:a=o}return i},w.toArray=function(n){return n?w.isArray(n)?o.call(n):n.length===+n.length?w.map(n,w.identity):w.values(n):[]},w.size=function(n){return null==n?0:n.length===+n.length?n.length:w.keys(n).length},w.first=w.head=w.take=function(n,t,r){return null==n?void 0:null==t||r?n[0]:o.call(n,0,t)},w.initial=function(n,t,r){return o.call(n,0,n.length-(null==t||r?1:t))},w.last=function(n,t,r){return null==n?void 0:null==t||r?n[n.length-1]:o.call(n,Math.max(n.length-t,0))},w.rest=w.tail=w.drop=function(n,t,r){return o.call(n,null==t||r?1:t)},w.compact=function(n){return w.filter(n,w.identity)};var R=function(n,t,r){return A(n,function(n){w.isArray(n)?t?a.apply(r,n):R(n,t,r):r.push(n)}),r};w.flatten=function(n,t){return R(n,t,[])},w.without=function(n){return w.difference(n,o.call(arguments,1))},w.uniq=w.unique=function(n,t,r,e){w.isFunction(t)&&(e=r,r=t,t=!1);var u=r?w.map(n,r,e):n,i=[],a=[];return A(u,function(r,e){(t?e&&a[a.length-1]===r:w.contains(a,r))||(a.push(r),i.push(n[e]))}),i},w.union=function(){return w.uniq(c.apply(e,arguments))},w.intersection=function(n){var t=o.call(arguments,1);return w.filter(w.uniq(n),function(n){return w.every(t,function(t){return w.indexOf(t,n)>=0})})},w.difference=function(n){var t=c.apply(e,o.call(arguments,1));return w.filter(n,function(n){return!w.contains(t,n)})},w.zip=function(){for(var n=o.call(arguments),t=w.max(w.pluck(n,"length")),r=Array(t),e=0;t>e;e++)r[e]=w.pluck(n,""+e);return r},w.object=function(n,t){if(null==n)return{};for(var r={},e=0,u=n.length;u>e;e++)t?r[n[e]]=t[e]:r[n[e][0]]=n[e][1];return r},w.indexOf=function(n,t,r){if(null==n)return-1;var e=0,u=n.length;if(r){if("number"!=typeof r)return e=w.sortedIndex(n,t),n[e]===t?e:-1;e=0>r?Math.max(0,u+r):r}if(y&&n.indexOf===y)return n.indexOf(t,r);for(;u>e;e++)if(n[e]===t)return e;return-1},w.lastIndexOf=function(n,t,r){if(null==n)return-1;var e=null!=r;if(b&&n.lastIndexOf===b)return e?n.lastIndexOf(t,r):n.lastIndexOf(t);for(var u=e?r:n.length;u--;)if(n[u]===t)return u;return-1},w.range=function(n,t,r){1>=arguments.length&&(t=n||0,n=0),r=arguments[2]||1;for(var e=Math.max(Math.ceil((t-n)/r),0),u=0,i=Array(e);e>u;)i[u++]=n,n+=r;return i},w.bind=function(n,t){if(n.bind===j&&j)return j.apply(n,o.call(arguments,1));var r=o.call(arguments,2);return function(){return n.apply(t,r.concat(o.call(arguments)))}},w.partial=function(n){var t=o.call(arguments,1);return function(){return n.apply(this,t.concat(o.call(arguments)))}},w.bindAll=function(n){var t=o.call(arguments,1);return 0===t.length&&(t=w.functions(n)),A(t,function(t){n[t]=w.bind(n[t],n)}),n},w.memoize=function(n,t){var r={};return t||(t=w.identity),function(){var e=t.apply(this,arguments);return w.has(r,e)?r[e]:r[e]=n.apply(this,arguments)}},w.delay=function(n,t){var r=o.call(arguments,2);return setTimeout(function(){return n.apply(null,r)},t)},w.defer=function(n){return w.delay.apply(w,[n,1].concat(o.call(arguments,1)))},w.throttle=function(n,t){var r,e,u,i,a=0,o=function(){a=new Date,u=null,i=n.apply(r,e)};return function(){var c=new Date,l=t-(c-a);return r=this,e=arguments,0>=l?(clearTimeout(u),u=null,a=c,i=n.apply(r,e)):u||(u=setTimeout(o,l)),i}},w.debounce=function(n,t,r){var e,u;return function(){var i=this,a=arguments,o=function(){e=null,r||(u=n.apply(i,a))},c=r&&!e;return clearTimeout(e),e=setTimeout(o,t),c&&(u=n.apply(i,a)),u}},w.once=function(n){var t,r=!1;return function(){return r?t:(r=!0,t=n.apply(this,arguments),n=null,t)}},w.wrap=function(n,t){return function(){var r=[n];return a.apply(r,arguments),t.apply(this,r)}},w.compose=function(){var n=arguments;return function(){for(var t=arguments,r=n.length-1;r>=0;r--)t=[n[r].apply(this,t)];return t[0]}},w.after=function(n,t){return 0>=n?t():function(){return 1>--n?t.apply(this,arguments):void 0}},w.keys=_||function(n){if(n!==Object(n))throw new TypeError("Invalid object");var t=[];for(var r in n)w.has(n,r)&&(t[t.length]=r);return t},w.values=function(n){var t=[];for(var r in n)w.has(n,r)&&t.push(n[r]);return t},w.pairs=function(n){var t=[];for(var r in n)w.has(n,r)&&t.push([r,n[r]]);return t},w.invert=function(n){var t={};for(var r in n)w.has(n,r)&&(t[n[r]]=r);return t},w.functions=w.methods=function(n){var t=[];for(var r in n)w.isFunction(n[r])&&t.push(r);return t.sort()},w.extend=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)n[r]=t[r]}),n},w.pick=function(n){var t={},r=c.apply(e,o.call(arguments,1));return A(r,function(r){r in n&&(t[r]=n[r])}),t},w.omit=function(n){var t={},r=c.apply(e,o.call(arguments,1));for(var u in n)w.contains(r,u)||(t[u]=n[u]);return t},w.defaults=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)null==n[r]&&(n[r]=t[r])}),n},w.clone=function(n){return w.isObject(n)?w.isArray(n)?n.slice():w.extend({},n):n},w.tap=function(n,t){return t(n),n};var I=function(n,t,r,e){if(n===t)return 0!==n||1/n==1/t;if(null==n||null==t)return n===t;n instanceof w&&(n=n._wrapped),t instanceof w&&(t=t._wrapped);var u=l.call(n);if(u!=l.call(t))return!1;switch(u){case"[object String]":return n==t+"";case"[object Number]":return n!=+n?t!=+t:0==n?1/n==1/t:n==+t;case"[object Date]":case"[object Boolean]":return+n==+t;case"[object RegExp]":return n.source==t.source&&n.global==t.global&&n.multiline==t.multiline&&n.ignoreCase==t.ignoreCase}if("object"!=typeof n||"object"!=typeof t)return!1;for(var i=r.length;i--;)if(r[i]==n)return e[i]==t;r.push(n),e.push(t);var a=0,o=!0;if("[object Array]"==u){if(a=n.length,o=a==t.length)for(;a--&&(o=I(n[a],t[a],r,e)););}else{var c=n.constructor,f=t.constructor;if(c!==f&&!(w.isFunction(c)&&c instanceof c&&w.isFunction(f)&&f instanceof f))return!1;for(var s in n)if(w.has(n,s)&&(a++,!(o=w.has(t,s)&&I(n[s],t[s],r,e))))break;if(o){for(s in t)if(w.has(t,s)&&!a--)break;o=!a}}return r.pop(),e.pop(),o};w.isEqual=function(n,t){return I(n,t,[],[])},w.isEmpty=function(n){if(null==n)return!0;if(w.isArray(n)||w.isString(n))return 0===n.length;for(var t in n)if(w.has(n,t))return!1;return!0},w.isElement=function(n){return!(!n||1!==n.nodeType)},w.isArray=x||function(n){return"[object Array]"==l.call(n)},w.isObject=function(n){return n===Object(n)},A(["Arguments","Function","String","Number","Date","RegExp"],function(n){w["is"+n]=function(t){return l.call(t)=="[object "+n+"]"}}),w.isArguments(arguments)||(w.isArguments=function(n){return!(!n||!w.has(n,"callee"))}),"function"!=typeof/./&&(w.isFunction=function(n){return"function"==typeof n}),w.isFinite=function(n){return isFinite(n)&&!isNaN(parseFloat(n))},w.isNaN=function(n){return w.isNumber(n)&&n!=+n},w.isBoolean=function(n){return n===!0||n===!1||"[object Boolean]"==l.call(n)},w.isNull=function(n){return null===n},w.isUndefined=function(n){return n===void 0},w.has=function(n,t){return f.call(n,t)},w.noConflict=function(){return n._=t,this},w.identity=function(n){return n},w.times=function(n,t,r){for(var e=Array(n),u=0;n>u;u++)e[u]=t.call(r,u);return e},w.random=function(n,t){return null==t&&(t=n,n=0),n+Math.floor(Math.random()*(t-n+1))};var M={escape:{"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","/":"&#x2F;"}};M.unescape=w.invert(M.escape);var S={escape:RegExp("["+w.keys(M.escape).join("")+"]","g"),unescape:RegExp("("+w.keys(M.unescape).join("|")+")","g")};w.each(["escape","unescape"],function(n){w[n]=function(t){return null==t?"":(""+t).replace(S[n],function(t){return M[n][t]})}}),w.result=function(n,t){if(null==n)return null;var r=n[t];return w.isFunction(r)?r.call(n):r},w.mixin=function(n){A(w.functions(n),function(t){var r=w[t]=n[t];w.prototype[t]=function(){var n=[this._wrapped];return a.apply(n,arguments),D.call(this,r.apply(w,n))}})};var N=0;w.uniqueId=function(n){var t=++N+"";return n?n+t:t},w.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var T=/(.)^/,q={"'":"'","\\":"\\","\r":"r","\n":"n","	":"t","\u2028":"u2028","\u2029":"u2029"},B=/\\|'|\r|\n|\t|\u2028|\u2029/g;w.template=function(n,t,r){var e;r=w.defaults({},r,w.templateSettings);var u=RegExp([(r.escape||T).source,(r.interpolate||T).source,(r.evaluate||T).source].join("|")+"|$","g"),i=0,a="__p+='";n.replace(u,function(t,r,e,u,o){return a+=n.slice(i,o).replace(B,function(n){return"\\"+q[n]}),r&&(a+="'+\n((__t=("+r+"))==null?'':_.escape(__t))+\n'"),e&&(a+="'+\n((__t=("+e+"))==null?'':__t)+\n'"),u&&(a+="';\n"+u+"\n__p+='"),i=o+t.length,t}),a+="';\n",r.variable||(a="with(obj||{}){\n"+a+"}\n"),a="var __t,__p='',__j=Array.prototype.join,"+"print=function(){__p+=__j.call(arguments,'');};\n"+a+"return __p;\n";try{e=Function(r.variable||"obj","_",a)}catch(o){throw o.source=a,o}if(t)return e(t,w);var c=function(n){return e.call(this,n,w)};return c.source="function("+(r.variable||"obj")+"){\n"+a+"}",c},w.chain=function(n){return w(n).chain()};var D=function(n){return this._chain?w(n).chain():n};w.mixin(w),A(["pop","push","reverse","shift","sort","splice","unshift"],function(n){var t=e[n];w.prototype[n]=function(){var r=this._wrapped;return t.apply(r,arguments),"shift"!=n&&"splice"!=n||0!==r.length||delete r[0],D.call(this,r)}}),A(["concat","join","slice"],function(n){var t=e[n];w.prototype[n]=function(){return D.call(this,t.apply(this._wrapped,arguments))}}),w.extend(w.prototype,{chain:function(){return this._chain=!0,this},value:function(){return this._wrapped}})}).call(this);

