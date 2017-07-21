<?php
/**
 * Creates the admin interface to add shortcodes to the editor
 *
 * @package  Namaste Features
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * NAMASTE_Admin_Insert class
 */
class NAMASTE_Admin_Insert {

	/**
	 * __construct function
	 *
	 * @access public
	 * @return  void
	 */
	public function __construct() {
		add_action( 'media_buttons', array( $this, 'media_buttons' ), 20 );
		add_action( 'admin_footer', array( $this, 'namaste_popup_html' ) );
	}

	/**
	 * media_buttons function
	 *
	 * @access public
	 * @return void
	 */
	public function media_buttons( $editor_id = 'content' ) {
		global $pagenow;

		// Only run on add/edit screens
		if ( in_array( $pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php') ) ) {
			$output = '<a href="#TB_inline?width=4000&amp;inlineId=namaste-choose-shortcode" class="thickbox button namaste-thicbox" title="' . __( 'Insert Shortcode', 'namaste' ) . '"><span class="wp-menu-image dashicons-before dashicons-menu shortcode-dashicon"></span> ' . __( 'Insert Shortcode', 'namaste' ) . '</a>';
		echo $output;
		}
		
	}

	/**
	 * Build out the input fields for shortcode content
	 * @param  string $key
	 * @param  array $param the parameters of the input
	 * @return void
	 */
	public function namaste_build_fields($key, $param) {
		$html = '<tr>';
		$html .= '<td class="label">' . $param['label'] . ':</td>';
		switch( $param['type'] )
		{
			case 'text' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<input type="text" class="namaste-form-text namaste-input" name="' . $key . '" id="' . $key . '" value="' . $param['std'] . '" />' . "\n";
				$output .= '<span class="namaste-form-desc">' . $param['desc'] . '</span></td>' . "\n";

				// append
				$html .= $output;

				break;

			case 'textarea' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<textarea rows="10" cols="30" name="' . $key . '" id="' . $key . '" class="namaste-form-textarea namaste-input">' . $param['std'] . '</textarea>' . "\n";
				$output .= '<span class="namaste-form-desc">' . $param['desc'] . '</span></td>' . "\n";

				// append
				$html .= $output;

				break;

			case 'select' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<select name="' . $key . '" id="' . $key . '" class="namaste-form-select namaste-input">' . "\n";

				foreach( $param['options'] as $value => $option )
				{
					$output .= '<option value="' . $value . '">' . $option . '</option>' . "\n";
				}

				$output .= '</select>' . "\n";
				$output .= '<span class="namaste-form-desc">' . $param['desc'] . '</span></td>' . "\n";

				// append
				$html .= $output;

				break;

			case 'checkbox' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<input type="checkbox" name="' . $key . '" id="' . $key . '" class="namaste-form-checkbox namaste-input"' . ( $param['default'] ? 'checked' : '' ) . '>' . "\n";
				$output .= '<span class="namaste-form-desc">' . $param['desc'] . '</span></td>';

				$html .= $output;

				break;

			default :
				break;
		}
		$html .= '</tr>';

		return $html;
	}

	/**
	 * Popup window
	 *
	 * Print the footer code needed for the Insert Shortcode Popup
	 *
	 * @since 1.0
	 * @global $pagenow
	 * @return void Prints HTML
	 */
	function namaste_popup_html() {
		global $pagenow;
		include(NAMASTE_PLUGIN_DIR . 'includes/config.php');

		// Only run in add/edit screens
		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) { ?>

			<script type="text/javascript">
				function namasteInsertShortcode() {
					// Grab input content, build the shortcodes, and insert them
					// into the content editor
					var select = jQuery('#select-namaste-shortcode').val(),
						type = select.replace('namaste-', '').replace('-shortcode', ''),
						template = jQuery('#' + select).data('shortcode-template'),
						childTemplate = jQuery('#' + select).data('shortcode-child-template'),
						tables = jQuery('#' + select).find('table').not('.namaste-clone-template'),
						attributes = '',
						content = '',
						contentToEditor = '';

					// go over each table, build the shortcode content
					for (var i = 0; i < tables.length; i++) {
						var elems = jQuery(tables[i]).find('input, select, textarea');

						// Build an attributes string by mapping over the input
						// fields in a given table.
						attributes = jQuery.map(elems, function(el, index) {
							var $el = jQuery(el);

							console.log(el);

							if( $el.attr('id') === 'content' ) {
								content = $el.val();
								return '';
							} else if( $el.attr('id') === 'last' ) {
								if( $el.is(':checked') ) {
									return $el.attr('id') + '="true"';
								} else {
									return '';
								}
							} else {
								return $el.attr('id') + '="' + $el.val() + '"';
							}
						});
						attributes = attributes.join(' ').trim();

						// Place the attributes and content within the provided
						// shortcode template
						if( childTemplate ) {
							// Run the replace on attributes for columns because the
							// attributes are really the shortcodes
							contentToEditor += childTemplate.replace('{{attributes}}', attributes).replace('{{attributes}}', attributes).replace('{{content}}', content);
						} else {
							// Run the replace on attributes for columns because the
							// attributes are really the shortcodes
							contentToEditor += template.replace('{{attributes}}', attributes).replace('{{attributes}}', attributes).replace('{{content}}', content);
						}
					};

					// Insert built content into the parent template
					if( childTemplate ) {
						contentToEditor = template.replace('{{child_shortcode}}', contentToEditor);
					}

					// Send the shortcode to the content editor and reset the fields
					window.send_to_editor( contentToEditor );
					namasteResetFields();
				}

				// Set the inputs to empty state
				function namasteResetFields() {
					jQuery('#namaste-shortcode-title').text('');
					jQuery('#namaste-shortcode-wrap').find('input[type=text], select').val('');
					jQuery('#namaste-shortcode-wrap').find('textarea').text('');
					jQuery('.namaste-was-cloned').remove();
					jQuery('.namaste-shortcode-type').hide();
				}

				// Function to redraw the thickbox for new content
				function namasteResizeTB() {
					var	ajaxCont = jQuery('#TB_ajaxContent'),
						tbWindow = jQuery('#TB_window'),
						namastePopup = jQuery('#namaste-shortcode-wrap');

					ajaxCont.css({
						height: (tbWindow.outerHeight()-47),
						overflow: 'auto', // IMPORTANT
						width: (tbWindow.outerWidth() - 30)
					});
				}

				// Simple function to clone an included template
				function namasteCloneContent(el) {
					var clone = jQuery(el).find('.namaste-clone-template').clone().removeClass('hidden namaste-clone-template').removeAttr('id').addClass('namaste-was-cloned');

					jQuery(el).append(clone);
				}

				jQuery(document).ready(function($) {
					var $shortcodes = $('.namaste-shortcode-type').hide(),
						$title = $('#namaste-shortcode-title');

					// Show the selected shortcode input fields
	                $('#select-namaste-shortcode').change(function () {
	                	var text = $(this).find('option:selected').text();

	                	$shortcodes.hide();
	                	$title.text(text);
	                    $('#' + $(this).val()).show();
	                    namasteResizeTB();
	                });

	                // Clone a set of input fields
	                $('.clone-content').on('click', function() {
						var el = $(this).siblings('.namaste-sortable');

						namasteCloneContent(el);
						namasteResizeTB();
						$('.namaste-sortable').sortable('refresh');
					});

	                // Remove a set of input fields
					$('.namaste-shortcode-type').on('click', '.namaste-remove' ,function() {
						$(this).closest('table').remove();
					});

					// Make content sortable using the jQuery UI Sortable method
					$('.namaste-sortable').sortable({
						items: 'table:not(".hidden")',
						placeholder: 'namaste-sortable-placeholder'
					});
	            });
			</script>

			<div id="namaste-choose-shortcode" style="display: none;">
				<div id="namaste-shortcode-wrap" class="wrap namaste-shortcode-wrap">
					<div class="namaste-shortcode-select">    
						<label for="namaste-shortcode"><?php _e('Select the shortcode type', 'namaste'); ?></label>
						<select name="namaste-shortcode" id="select-namaste-shortcode">
							<option>Select Shortcode</option>
                            <optgroup label="Organizing">
                            <option data-title="Section" value="namaste-section-shortcode">Section</option>
                            <option data-title="Box" value="namaste-box-shortcode">Box</option>
                            <option data-title="Container" value="namaste-container-shortcode">Container</option>
                            <option data-title="Columns" value="namaste-column-shortcode">Columns</option>
                            <option data-title="Align" value="namaste-align-shortcode">Align</option>
                            <option data-title="Break" value="namaste-br-shortcode">Break</option>
                            <option data-title="Clear" value="namaste-clear-shortcode">Clear</option>
                            <option data-title="Spacer" value="namaste-spacer-shortcode">Spacer</option>
                            <option data-title="Separator" value="namaste-separator-shortcode">Separator</option>
                            </optgroup>
                            <optgroup label="Decorations">
                            <option data-title="Dropcap" value="namaste-dropcap-shortcode">Dropcap</option>
                            <option data-title="Highlight" value="namaste-highlight-shortcode">Highlight</option>
                            <option data-title="Blockquote" value="namaste-blockquote-shortcode">Blockquote</option>
                            <option data-title="Quote Carousel" value="namaste-quote-carousel-shortcode">Quote Carousel</option>
                            <option data-title="Button" value="namaste-button-shortcode">Button</option>
                            <option data-title="Heading" value="namaste-heading-shortcode">Heading</option>
                            <option data-title="Heading 2" value="namaste-heading-2-shortcode">Heading 2</option>
                            <option data-title="Subtext" value="namaste-subtext-shortcode">Subtext</option>
                            <option data-title="Big Heading" value="namaste-big-heading-shortcode">Big Heading</option>
                            </optgroup>
                            <optgroup label="Content">
                            <option data-title="FlexSlider" value="namaste-flexslider-shortcode">FlexSlider</option>
                            <option data-title="Map" value="namaste-map-shortcode">Map</option>
                            <option data-title="Imagebox" value="namaste-imagebox-shortcode">Imagebox</option>
                            <option data-title="Content Slider" value="namaste-content-slider-shortcode">Content Slider</option>
                            <option data-title="Iconbox" value="namaste-iconbox-shortcode">Iconbox</option>
                            <option data-title="Icon" value="namaste-icon-shortcode">Icon</option>
                            <option data-title="Action Call" value="namaste-action-call-shortcode">Action Call</option>
                            </optgroup>
                            <optgroup label="Post">
                            <option data-title="Post" value="namaste-post-shortcode">Post</option>
                            <option data-title="Post Carousel" value="namaste-postcarousel-shortcode">Post Carousel</option>
                            </optgroup>
						</select>
					</div>

					<h3 id="namaste-shortcode-title"></h3>

				<?php

				$html = '';
				$clone_button = array( 'show' => false );

				// Loop through each shortcode building content
				foreach( $namaste_shortcodes as $key => $shortcode ) {

					// Add shortcode templates to be used when building with JS
					$shortcode_template = ' data-shortcode-template="' . $shortcode['template'] . '"';
					if( array_key_exists('child_shortcode', $shortcode ) ) {
						$shortcode_template .= ' data-shortcode-child-template="' . $shortcode['child_shortcode']['template'] . '"';
					}

					// Individual shortcode 'block'
					$html .= '<div id="' . $shortcode['id'] . '" class="namaste-shortcode-type" ' . $shortcode_template . '>';

					// If shortcode has children, it can be cloned and is sortable.
					// Add a hidden clone template, and set clone button to be displayed.
					if( array_key_exists('child_shortcode', $shortcode ) ) {
						$html .= (isset($shortcode['child_shortcode']['shortcode']) ? $shortcode['child_shortcode']['shortcode'] : null);
						$shortcode['params'] = $shortcode['child_shortcode']['params'];
						$clone_button['show'] = true;
						$clone_button['text'] = $shortcode['child_shortcode']['clone_button'];
						$html .= '<div class="namaste-sortable">';
						$html .= '<table id="clone-' . $shortcode['id'] . '" class="hidden namaste-clone-template"><tbody>';
						foreach( $shortcode['params'] as $key => $param ) {
							$html .= $this->namaste_build_fields($key, $param);
						}
						if( $clone_button['show'] ) {
							$html .= '<tr><td colspan="2"><a href="#" class="namaste-remove">' . __('Remove', 'namaste') . '</a></td></tr>';
						}
						$html .= '</tbody></table>';
					}

					// Build the actual shortcode input fields
					$html .= '<table><tbody>';
					foreach( $shortcode['params'] as $key => $param ) {
						$html .= $this->namaste_build_fields($key, $param);
					}

					// Add a link to remove a content block
					if( $clone_button['show'] ) {
						$html .= '<tr><td colspan="2"><a href="#" class="namaste-remove">' . __('Remove', 'namaste') . '</a></td></tr>';
					}
					$html .= '</tbody></table>';

					// Close out the sortable div and display the clone button as needed
					if( $clone_button['show'] ) {
						$html .= '</div>';
						$html .= '<a id="add-' . $shortcode['id'] . '" href="#" class="button-secondary clone-content">' . $clone_button['text'] . '</a>';
						$clone_button['show'] = false;
					}

					// Display notes if provided
					if( array_key_exists('notes', $shortcode) ) {
						$html .= '<p class="namaste-notes">' . $shortcode['notes'] . '</p>';
					}
					$html .= '</div>';
				}

				echo $html;
				?>

				<p class="submit">
					<input type="button" id="namaste-insert-shortcode" class="button-primary" value="<?php _e('Insert Shortcode', 'namaste'); ?>" onclick="namasteInsertShortcode();" />
					<a href="#" id="namaste-cancel-shortcode-insert" class="button-secondary namaste-cancel-shortcode-insert" onclick="tb_remove();"><?php _e('Cancel', 'namaste'); ?></a>
				</p>
				</div>
			</div>

		<?php
		}
	}
}

new NAMASTE_Admin_Insert();