<?php
/**
 * Define the shortcode parameters
 */


// Post titles

$post_title = array();
$args=array('order'=>'ASC','posts_per_page'	=>	1000,);
$posts = get_posts( $args );
if ( $posts ) {
foreach ( $posts as $post ) {
   $post_title[$post->post_title] = $post->post_title;
}
}

// Post categories

$post_categories = array();
$category_terms = get_categories( $args );
foreach ( $category_terms as $category_term ) {
$post_categories[$category_term->slug] = $category_term->slug;}
$category_tags_tmp = array_unshift($post_categories, "All");


// Icons

$namaste_shortcode_icons = array(
				'' => '',
				'fontello-bamboo' => 'bamboo',
				'fontello-big-buddha' => 'big-buddha', 
				'fontello-buddha' => 'buddha', 
				'fontello-buddha-light' => 'buddha-light', 
				'fontello-buddhism' => 'buddhism',
				'fontello-candle' => 'candle',
				'fontello-candles' => 'candles', 
				'fontello-fish' => 'fish',
				'fontello-flower' => 'flower',
				'fontello-flowers' => 'flowers',
				'fontello-hand' => 'hand',
				'fontello-hand2' => 'hand2',
				'fontello-hand3' => 'hand3',
				'fontello-incense' => 'incense',
				'fontello-leaves' => 'leaves',
				'fontello-lotus' => 'lotus',
				'fontello-lotus-light' => 'lotus-light',				
				'fontello-om' => 'om', 
				'fontello-om-light' => 'om-light',
				'fontello-oriental' => 'oriental', 
				'fontello-pagoda' => 'pagoda',
				'fontello-shinto' => 'shinto', 
				'fontello-statue' => 'statue',
				'fontello-stones' => 'stones',
				'fontello-sushi' => 'sushi',
				'fontello-tea' => 'tea',
				'fontello-water' => 'water',
				'fontello-wheel' => 'wheel',
				'fontello-yoga' => 'yoga',
				'fontello-yoga2' => 'yoga2',
				'glass' => 'glass',
				'music' => 'music',
				'search' => 'search',
				'envelope-o' => 'envelope-o',
				'heart' => 'heart',
				'star' => 'star',
				'star-o' => 'star-o',
				'user' => 'user',
				'film' => 'film',
				'th-large' => 'th-large',
				'th' => 'th',
				'th-list' => 'th-list',
				'check' => 'check',
				'times' => 'times',
				'search-plus' => 'search-plus',
				'search-minus' => 'search-minus',
				'power-off' => 'power-off',
				'signal' => 'signal',
				'cog' => 'cog',
				'trash-o' => 'trash-o',
				'home' => 'home',
				'file-o' => 'file-o',
				'clock-o' => 'clock-o',
				'road' => 'road',
				'download' => 'download',
				'arrow-circle-o-down' => 'arrow-circle-o-down',
				'arrow-circle-o-up' => 'arrow-circle-o-up',
				'inbox' => 'inbox',
				'play-circle-o' => 'play-circle-o',
				'repeat' => 'repeat',
				'refresh' => 'refresh',
				'list-alt' => 'list-alt',
				'lock' => 'lock',
				'flag' => 'flag',
				'headphones' => 'headphones',
				'volume-off' => 'volume-off',
				'volume-down' => 'volume-down',
				'volume-up' => 'volume-up',
				'qrcode' => 'qrcode',
				'barcode' => 'barcode',
				'tag' => 'tag',
				'tags' => 'tags',
				'book' => 'book',
				'bookmark' => 'bookmark',
				'print' => 'print',
				'camera' => 'camera',
				'font' => 'font',
				'bold' => 'bold',
				'italic' => 'italic',
				'text-height' => 'text-height',
				'text-width' => 'text-width',
				'align-left' => 'align-left',
				'align-center' => 'align-center',
				'align-right' => 'align-right',
				'align-justify' => 'align-justify',
				'list' => 'list',
				'outdent' => 'outdent',
				'indent' => 'indent',
				'video-camera' => 'video-camera',
				'picture-o' => 'picture-o',
				'pencil' => 'pencil',
				'map-marker' => 'map-marker',
				'adjust' => 'adjust',
				'tint' => 'tint',
				'pencil-square-o' => 'pencil-square-o',
				'share-square-o' => 'share-square-o',
				'check-square-o' => 'check-square-o',
				'arrows' => 'arrows',
				'step-backward' => 'step-backward',
				'fast-backward' => 'fast-backward',
				'backward' => 'backward',
				'play' => 'play',
				'pause' => 'pause',
				'stop' => 'stop',
				'forward' => 'forward',
				'fast-forward' => 'fast-forward',
				'step-forward' => 'step-forward',
				'eject' => 'eject',
				'chevron-left' => 'chevron-left',
				'chevron-right' => 'chevron-right',
				'plus-circle' => 'plus-circle',
				'minus-circle' => 'minus-circle',
				'times-circle' => 'times-circle',
				'check-circle' => 'check-circle',
				'question-circle' => 'question-circle',
				'info-circle' => 'info-circle',
				'crosshairs' => 'crosshairs',
				'times-circle-o' => 'times-circle-o',
				'check-circle-o' => 'check-circle-o',
				'ban' => 'ban',
				'arrow-left' => 'arrow-left',
				'arrow-right' => 'arrow-right',
				'arrow-up' => 'arrow-up',
				'arrow-down' => 'arrow-down',
				'share' => 'share',
				'expand' => 'expand',
				'compress' => 'compress',
				'plus' => 'plus',
				'minus' => 'minus',
				'asterisk' => 'asterisk',
				'exclamation-circle' => 'exclamation-circle',
				'gift' => 'gift',
				'leaf' => 'leaf',
				'fire' => 'fire',
				'eye' => 'eye',
				'eye-slash' => 'eye-slash',
				'exclamation-triangle' => 'exclamation-triangle',
				'plane' => 'plane',
				'calendar' => 'calendar',
				'random' => 'random',
				'comment' => 'comment',
				'magnet' => 'magnet',
				'chevron-up' => 'chevron-up',
				'chevron-down' => 'chevron-down',
				'retweet' => 'retweet',
				'shopping-cart' => 'shopping-cart',
				'folder' => 'folder',
				'folder-open' => 'folder-open',
				'arrows-v' => 'arrows-v',
				'arrows-h' => 'arrows-h',
				'bar-chart' => 'bar-chart',
				'twitter-square' => 'twitter-square',
				'facebook-square' => 'facebook-square',
				'camera-retro' => 'camera-retro',
				'key' => 'key',
				'cogs' => 'cogs',
				'comments' => 'comments',
				'thumbs-o-up' => 'thumbs-o-up',
				'thumbs-o-down' => 'thumbs-o-down',
				'star-half' => 'star-half',
				'heart-o' => 'heart-o',
				'sign-out' => 'sign-out',
				'linkedin-square' => 'linkedin-square',
				'thumb-tack' => 'thumb-tack',
				'external-link' => 'external-link',
				'sign-in' => 'sign-in',
				'trophy' => 'trophy',
				'github-square' => 'github-square',
				'upload' => 'upload',
				'lemon-o' => 'lemon-o',
				'phone' => 'phone',
				'square-o' => 'square-o',
				'bookmark-o' => 'bookmark-o',
				'phone-square' => 'phone-square',
				'twitter' => 'twitter',
				'facebook' => 'facebook',
				'github' => 'github',
				'unlock' => 'unlock',
				'credit-card' => 'credit-card',
				'rss' => 'rss',
				'hdd-o' => 'hdd-o',
				'bullhorn' => 'bullhorn',
				'bell' => 'bell',
				'certificate' => 'certificate',
				'hand-o-right' => 'hand-o-right',
				'hand-o-left' => 'hand-o-left',
				'hand-o-up' => 'hand-o-up',
				'hand-o-down' => 'hand-o-down',
				'arrow-circle-left' => 'arrow-circle-left',
				'arrow-circle-right' => 'arrow-circle-right',
				'arrow-circle-up' => 'arrow-circle-up',
				'arrow-circle-down' => 'arrow-circle-down',
				'globe' => 'globe',
				'wrench' => 'wrench',
				'tasks' => 'tasks',
				'filter' => 'filter',
				'briefcase' => 'briefcase',
				'arrows-alt' => 'arrows-alt',
				'users' => 'users',
				'link' => 'link',
				'cloud' => 'cloud',
				'flask' => 'flask',
				'scissors' => 'scissors',
				'files-o' => 'files-o',
				'paperclip' => 'paperclip',
				'floppy-o' => 'floppy-o',
				'square' => 'square',
				'bars' => 'bars',
				'list-ul' => 'list-ul',
				'list-ol' => 'list-ol',
				'strikethrough' => 'strikethrough',
				'underline' => 'underline',
				'table' => 'table',
				'magic' => 'magic',
				'truck' => 'truck',
				'pinterest' => 'pinterest',
				'pinterest-square' => 'pinterest-square',
				'google-plus-square' => 'google-plus-square',
				'google-plus' => 'google-plus',
				'money' => 'money',
				'caret-down' => 'caret-down',
				'caret-up' => 'caret-up',
				'caret-left' => 'caret-left',
				'caret-right' => 'caret-right',
				'columns' => 'columns',
				'sort' => 'sort',
				'sort-desc' => 'sort-desc',
				'sort-asc' => 'sort-asc',
				'envelope' => 'envelope',
				'linkedin' => 'linkedin',
				'undo' => 'undo',
				'gavel' => 'gavel',
				'tachometer' => 'tachometer',
				'comment-o' => 'comment-o',
				'comments-o' => 'comments-o',
				'bolt' => 'bolt',
				'sitemap' => 'sitemap',
				'umbrella' => 'umbrella',
				'clipboard' => 'clipboard',
				'lightbulb-o' => 'lightbulb-o',
				'exchange' => 'exchange',
				'cloud-download' => 'cloud-download',
				'cloud-upload' => 'cloud-upload',
				'user-md' => 'user-md',
				'stethoscope' => 'stethoscope',
				'suitcase' => 'suitcase',
				'bell-o' => 'bell-o',
				'coffee' => 'coffee',
				'cutlery' => 'cutlery',
				'file-text-o' => 'file-text-o',
				'building-o' => 'building-o',
				'hospital-o' => 'hospital-o',
				'ambulance' => 'ambulance',
				'medkit' => 'medkit',
				'fighter-jet' => 'fighter-jet',
				'beer' => 'beer',
				'h-square' => 'h-square',
				'plus-square' => 'plus-square',
				'angle-double-left' => 'angle-double-left',
				'angle-double-right' => 'angle-double-right',
				'angle-double-up' => 'angle-double-up',
				'angle-double-down' => 'angle-double-down',
				'angle-left' => 'angle-left',
				'angle-right' => 'angle-right',
				'angle-up' => 'angle-up',
				'angle-down' => 'angle-down',
				'desktop' => 'desktop',
				'laptop' => 'laptop',
				'tablet' => 'tablet',
				'mobile' => 'mobile',
				'circle-o' => 'circle-o',
				'quote-left' => 'quote-left',
				'quote-right' => 'quote-right',
				'spinner' => 'spinner',
				'circle' => 'circle',
				'reply' => 'reply',
				'github-alt' => 'github-alt',
				'folder-o' => 'folder-o',
				'folder-open-o' => 'folder-open-o',
				'smile-o' => 'smile-o',
				'frown-o' => 'frown-o',
				'meh-o' => 'meh-o',
				'gamepad' => 'gamepad',
				'keyboard-o' => 'keyboard-o',
				'flag-o' => 'flag-o',
				'flag-checkered' => 'flag-checkered',
				'terminal' => 'terminal',
				'code' => 'code',
				'reply-all' => 'reply-all',
				'star-half-o' => 'star-half-o',
				'location-arrow' => 'location-arrow',
				'crop' => 'crop',
				'code-fork' => 'code-fork',
				'chain-broken' => 'chain-broken',
				'question' => 'question',
				'info' => 'info',
				'exclamation' => 'exclamation',
				'superscript' => 'superscript',
				'subscript' => 'subscript',
				'eraser' => 'eraser',
				'puzzle-piece' => 'puzzle-piece',
				'microphone' => 'microphone',
				'microphone-slash' => 'microphone-slash',
				'shield' => 'shield',
				'calendar-o' => 'calendar-o',
				'fire-extinguisher' => 'fire-extinguisher',
				'rocket' => 'rocket',
				'maxcdn' => 'maxcdn',
				'chevron-circle-left' => 'chevron-circle-left',
				'chevron-circle-right' => 'chevron-circle-right',
				'chevron-circle-up' => 'chevron-circle-up',
				'chevron-circle-down' => 'chevron-circle-down',
				'html5' => 'html5',
				'css3' => 'css3',
				'anchor' => 'anchor',
				'unlock-alt' => 'unlock-alt',
				'bullseye' => 'bullseye',
				'ellipsis-h' => 'ellipsis-h',
				'ellipsis-v' => 'ellipsis-v',
				'rss-square' => 'rss-square',
				'play-circle' => 'play-circle',
				'ticket' => 'ticket',
				'minus-square' => 'minus-square',
				'minus-square-o' => 'minus-square-o',
				'level-up' => 'level-up',
				'level-down' => 'level-down',
				'check-square' => 'check-square',
				'pencil-square' => 'pencil-square',
				'external-link-square' => 'external-link-square',
				'share-square' => 'share-square',
				'compass' => 'compass',
				'caret-square-o-down' => 'caret-square-o-down',
				'caret-square-o-up' => 'caret-square-o-up',
				'caret-square-o-right' => 'caret-square-o-right',
				'eur' => 'eur',
				'gbp' => 'gbp',
				'usd' => 'usd',
				'inr' => 'inr',
				'jpy' => 'jpy',
				'rub' => 'rub',
				'krw' => 'krw',
				'btc' => 'btc',
				'file' => 'file',
				'file-text' => 'file-text',
				'sort-alpha-asc' => 'sort-alpha-asc',
				'sort-alpha-desc' => 'sort-alpha-desc',
				'sort-amount-asc' => 'sort-amount-asc',
				'sort-amount-desc' => 'sort-amount-desc',
				'sort-numeric-asc' => 'sort-numeric-asc',
				'sort-numeric-desc' => 'sort-numeric-desc',
				'thumbs-up' => 'thumbs-up',
				'thumbs-down' => 'thumbs-down',
				'youtube-square' => 'youtube-square',
				'youtube' => 'youtube',
				'xing' => 'xing',
				'xing-square' => 'xing-square',
				'youtube-play' => 'youtube-play',
				'dropbox' => 'dropbox',
				'stack-overflow' => 'stack-overflow',
				'instagram' => 'instagram',
				'flickr' => 'flickr',
				'adn' => 'adn',
				'bitbucket' => 'bitbucket',
				'bitbucket-square' => 'bitbucket-square',
				'tumblr' => 'tumblr',
				'tumblr-square' => 'tumblr-square',
				'long-arrow-down' => 'long-arrow-down',
				'long-arrow-up' => 'long-arrow-up',
				'long-arrow-left' => 'long-arrow-left',
				'long-arrow-right' => 'long-arrow-right',
				'apple' => 'apple',
				'windows' => 'windows',
				'android' => 'android',
				'linux' => 'linux',
				'dribbble' => 'dribbble',
				'skype' => 'skype',
				'foursquare' => 'foursquare',
				'trello' => 'trello',
				'female' => 'female',
				'male' => 'male',
				'gratipay' => 'gratipay',
				'sun-o' => 'sun-o',
				'moon-o' => 'moon-o',
				'archive' => 'archive',
				'bug' => 'bug',
				'vk' => 'vk',
				'weibo' => 'weibo',
				'renren' => 'renren',
				'pagelines' => 'pagelines',
				'stack-exchange' => 'stack-exchange',
				'arrow-circle-o-right' => 'arrow-circle-o-right',
				'arrow-circle-o-left' => 'arrow-circle-o-left',
				'caret-square-o-left' => 'caret-square-o-left',
				'dot-circle-o' => 'dot-circle-o',
				'wheelchair' => 'wheelchair',
				'vimeo-square' => 'vimeo-square',
				'try' => 'try',
				'plus-square-o' => 'plus-square-o',
				'space-shuttle' => 'space-shuttle',
				'slack' => 'slack',
				'envelope-square' => 'envelope-square',
				'wordpress' => 'wordpress',
				'openid' => 'openid',
				'university' => 'university',
				'graduation-cap' => 'graduation-cap',
				'yahoo' => 'yahoo',
				'google' => 'google',
				'reddit' => 'reddit',
				'reddit-square' => 'reddit-square',
				'stumbleupon-circle' => 'stumbleupon-circle',
				'stumbleupon' => 'stumbleupon',
				'delicious' => 'delicious',
				'digg' => 'digg',
				'pied-piper' => 'pied-piper',
				'pied-piper-alt' => 'pied-piper-alt',
				'drupal' => 'drupal',
				'joomla' => 'joomla',
				'language' => 'language',
				'fax' => 'fax',
				'building' => 'building',
				'child' => 'child',
				'paw' => 'paw',
				'spoon' => 'spoon',
				'cube' => 'cube',
				'cubes' => 'cubes',
				'behance' => 'behance',
				'behance-square' => 'behance-square',
				'steam' => 'steam',
				'steam-square' => 'steam-square',
				'recycle' => 'recycle',
				'car' => 'car',
				'taxi' => 'taxi',
				'tree' => 'tree',
				'spotify' => 'spotify',
				'deviantart' => 'deviantart',
				'soundcloud' => 'soundcloud',
				'database' => 'database',
				'file-pdf-o' => 'file-pdf-o',
				'file-word-o' => 'file-word-o',
				'file-excel-o' => 'file-excel-o',
				'file-powerpoint-o' => 'file-powerpoint-o',
				'file-image-o' => 'file-image-o',
				'file-archive-o' => 'file-archive-o',
				'file-audio-o' => 'file-audio-o',
				'file-video-o' => 'file-video-o',
				'file-code-o' => 'file-code-o',
				'vine' => 'vine',
				'codepen' => 'codepen',
				'jsfiddle' => 'jsfiddle',
				'life-ring' => 'life-ring',
				'circle-o-notch' => 'circle-o-notch',
				'rebel' => 'rebel',
				'empire' => 'empire',
				'git-square' => 'git-square',
				'git' => 'git',
				'hacker-news' => 'hacker-news',
				'tencent-weibo' => 'tencent-weibo',
				'qq' => 'qq',
				'weixin' => 'weixin',
				'paper-plane' => 'paper-plane',
				'paper-plane-o' => 'paper-plane-o',
				'history' => 'history',
				'circle-thin' => 'circle-thin',
				'header' => 'header',
				'paragraph' => 'paragraph',
				'sliders' => 'sliders',
				'share-alt' => 'share-alt',
				'share-alt-square' => 'share-alt-square',
				'bomb' => 'bomb',
				'futbol-o' => 'futbol-o',
				'tty' => 'tty',
				'binoculars' => 'binoculars',
				'plug' => 'plug',
				'slideshare' => 'slideshare',
				'twitch' => 'twitch',
				'yelp' => 'yelp',
				'newspaper-o' => 'newspaper-o',
				'wifi' => 'wifi',
				'calculator' => 'calculator',
				'paypal' => 'paypal',
				'google-wallet' => 'google-wallet',
				'cc-visa' => 'cc-visa',
				'cc-mastercard' => 'cc-mastercard',
				'cc-discover' => 'cc-discover',
				'cc-amex' => 'cc-amex',
				'cc-paypal' => 'cc-paypal',
				'cc-stripe' => 'cc-stripe',
				'bell-slash' => 'bell-slash',
				'bell-slash-o' => 'bell-slash-o',
				'trash' => 'trash',
				'copyright' => 'copyright',
				'at' => 'at',
				'eyedropper' => 'eyedropper',
				'paint-brush' => 'paint-brush',
				'birthday-cake' => 'birthday-cake',
				'area-chart' => 'area-chart',
				'pie-chart' => 'pie-chart',
				'line-chart' => 'line-chart',
				'lastfm' => 'lastfm',
				'lastfm-square' => 'lastfm-square',
				'toggle-off' => 'toggle-off',
				'toggle-on' => 'toggle-on',
				'bicycle' => 'bicycle',
				'bus' => 'bus',
				'ioxhost' => 'ioxhost',
				'angellist' => 'angellist',
				'cc' => 'cc',
				'ils' => 'ils',
				'meanpath' => 'meanpath',
				'buysellads' => 'buysellads',
				'connectdevelop' => 'connectdevelop',
				'dashcube' => 'dashcube',
				'forumbee' => 'forumbee',
				'leanpub' => 'leanpub',
				'sellsy' => 'sellsy',
				'shirtsinbulk' => 'shirtsinbulk',
				'simplybuilt' => 'simplybuilt',
				'skyatlas' => 'skyatlas',
				'cart-plus' => 'cart-plus',
				'cart-arrow-down' => 'cart-arrow-down',
				'diamond' => 'diamond',
				'ship' => 'ship',
				'user-secret' => 'user-secret',
				'motorcycle' => 'motorcycle',
				'street-view' => 'street-view',
				'heartbeat' => 'heartbeat',
				'venus' => 'venus',
				'mars' => 'mars',
				'mercury' => 'mercury',
				'transgender' => 'transgender',
				'transgender-alt' => 'transgender-alt',
				'venus-double' => 'venus-double',
				'mars-double' => 'mars-double',
				'venus-mars' => 'venus-mars',
				'mars-stroke' => 'mars-stroke',
				'mars-stroke-v' => 'mars-stroke-v',
				'mars-stroke-h' => 'mars-stroke-h',
				'neuter' => 'neuter',
				'facebook-official' => 'facebook-official',
				'pinterest-p' => 'pinterest-p',
				'whatsapp' => 'whatsapp',
				'server' => 'server',
				'user-plus' => 'user-plus',
				'user-times' => 'user-times',
				'bed' => 'bed',
				'viacoin' => 'viacoin',
				'train' => 'train',
				'subway' => 'subway'
			);

/* Section --- */

$namaste_shortcodes['section'] = array(
	'title' => __('Section', 'namaste'),
	'id' => 'namaste-section-shortcode',
	'template' => '[namaste_section {{attributes}}]{{content}}[/namaste_section]',
	'params' => array(
		'padding' => array(
			'std' => '30',
			'type' => 'text',
			'label' => __('Padding', 'namaste'),
			'desc' => __('Add the size of the padding (px).', 'namaste'),
		),
		'background_color' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Background color', 'namaste'),
			'desc' => __('Add a color code.', 'namaste'),
		),
		'color' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Font color', 'namaste'),
			'desc' => __('Add a color code.', 'namaste'),
		),
		'image' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Background image', 'namaste'),
			'desc' => __('Add the link of Your image.', 'namaste'),
		),
		'image_position' => array(
			'std' => 'background',
			'type' => 'select',
			'label' => __('Image position', 'namaste'),
			'desc' => __('The position of the image.', 'namaste'),
			'options' => array(
				'background' => __('Background', 'namaste'),
				'pattern' => __('Pattern', 'namaste'),
				'left' => __('Left', 'namaste'),
				'right' => __('Right', 'namaste')
			)
		),
		'parallax' => array(
			'std' => 'no',
			'type' => 'select',
			'label' => __('Parallax', 'namaste'),
			'desc' => __('Set if You want parallax image.', 'namaste'),
			'options' => array(
				'no' => __('no', 'namaste'),
				'yes' => __('yes', 'namaste')
			)
		),
		'layer' => array(
			'std' => '0',
			'type' => 'text',
			'label' => __('Layer opacity', 'namaste'),
			'desc' => __('Add a number for the layer opacity (0-99).', 'namaste'),
		),
		'layer_color' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Layer color', 'namaste'),
			'desc' => __('Add a color code.', 'namaste'),
		),
		'border_top' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Border top', 'namaste'),
			'desc' => __('Add custom css for border.', 'namaste'),
		),
		'border_bottom' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Border bottom', 'namaste'),
			'desc' => __('Add custom css for border.', 'namaste'),
		),
		'customcss' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Custom CSS', 'namaste'),
			'desc' => __('Add custom css.', 'namaste'),
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Box --- */

$namaste_shortcodes['box'] = array(
	'title' => __('Box', 'namaste'),
	'id' => 'namaste-box-shortcode',
	'template' => '[namaste_box {{attributes}}]{{content}}[/namaste_box]',
	'params' => array(
		'padding' => array(
			'std' => '10',
			'type' => 'text',
			'label' => __('Padding', 'namaste'),
			'desc' => __('Add the size of the padding (px).', 'namaste'),
		),
		'style' => array(
			'std' => '1',
			'type' => 'select',
			'label' => __('Style', 'namaste'),
			'desc' => __('Select one from the styles.', 'namaste'),
			'options' => array(
				'1' => __('1', 'namaste'),
				'2' => __('2', 'namaste')
			)
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Container --- */

$namaste_shortcodes['container'] = array(
	'title' => __('Container', 'namaste'),
	'id' => 'namaste-container-shortcode',
	'template' => '[namaste_container]{{content}}[/namaste_container]',
	'params' => array(
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Columns --- */

$namaste_shortcodes['column'] = array(
	'title' => __('Columns', 'namaste'),
	'id' => 'namaste-column-shortcode',
	'template' => '[namaste_column {{attributes}}]{{content}}[/namaste_column]',
	'params' => array(
		'size' => array(
			'std' => '4',
			'type' => 'select',
			'label' => __('Size', 'namaste'),
			'desc' => __('Set the size of the column (1 is the smallest, 12 is fullwidth).', 'namaste'),
			'options' => array(
				'1' => __('1', 'namaste'),
				'2' => __('2', 'namaste'),
				'3' => __('3', 'namaste'),
				'4' => __('4', 'namaste'),
				'5' => __('5', 'namaste'),
				'6' => __('6', 'namaste'),
				'7' => __('7', 'namaste'),
				'8' => __('8', 'namaste'),
				'9' => __('9', 'namaste'),
				'10' => __('10', 'namaste'),
				'11' => __('11', 'namaste'),
				'12' => __('12', 'namaste'),
			)
		),
		'left' => array(
			'std' => '15',
			'type' => 'text',
			'label' => __('Padding left', 'namaste'),
			'desc' => __('Padding left (px).', 'namaste'),
		),
		'right' => array(
			'std' => '15',
			'type' => 'text',
			'label' => __('Padding right', 'namaste'),
			'desc' => __('Padding right (px).', 'namaste'),
		),
		'customcss' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Custom CSS', 'namaste'),
			'desc' => __('Add custom css.', 'namaste'),
		),
		'last' => array(
			'type' => 'checkbox',
			'label' => __('Last column', 'namaste'),
			'desc' => __('Set whether this is the last column.', 'namaste'),
			'default' => false
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		),
	)
);

/* Align --- */

$namaste_shortcodes['align'] = array(
	'title' => __('Align', 'namaste'),
	'id' => 'namaste-align-shortcode',
	'template' => '[namaste_align {{attributes}}]{{content}}[/namaste_align]',
	'params' => array(
		'align' => array(
			'std' => 'left',
			'type' => 'select',
			'label' => __('Align', 'namaste'),
			'desc' => __('Set the align.', 'namaste'),
			'options' => array(
				'left' => __('left', 'namaste'),
				'right' => __('right', 'namaste'),
				'center' => __('center', 'namaste'),
				'justify' => __('justify', 'namaste'),
			)
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		),
	)
);

/* Br --- */

$namaste_shortcodes['br'] = array(
	'title' => __('Break', 'namaste'),
	'id' => 'namaste-br-shortcode',
	'template' => '[namaste_br]',
	'params' => array(

	)
);

/* Clear --- */

$namaste_shortcodes['clear'] = array(
	'title' => __('Clear', 'namaste'),
	'id' => 'namaste-clear-shortcode',
	'template' => '[namaste_clear]',
	'params' => array(

	)
);

/* Spacer --- */

$namaste_shortcodes['spacer'] = array(
	'title' => __('Spacer', 'namaste'),
	'id' => 'namaste-spacer-shortcode',
	'template' => '[namaste_spacer {{attributes}}]',
	'params' => array(
		'height' => array(
			'std' => '30',
			'type' => 'text',
			'label' => __('Height', 'namaste'),
			'desc' => __('Spacer`s height (px).', 'namaste'),
		),
	)
);

/* Separator --- */

$namaste_shortcodes['separator'] = array(
	'title' => __('Separator', 'namaste'),
	'id' => 'namaste-separator-shortcode',
	'template' => '[namaste_separator {{attributes}}]',
	'params' => array(
		'size' => array(
			'std' => 'normal',
			'type' => 'select',
			'label' => __('Size', 'namaste'),
			'desc' => __('Select the size of the separator.', 'namaste'),
			'options' => array(
				'normal' => __('Normal', 'namaste'),
				'small' => __('Small', 'namaste')
			)
		),
		'color' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Color', 'namaste'),
			'desc' => __('Add a color code.', 'namaste'),
		),
		'margin' => array(
			'std' => '10',
			'type' => 'text',
			'label' => __('Margin', 'namaste'),
			'desc' => __('Add the size of the margin (px).', 'namaste'),
		),
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select the icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
	)
);

/* Dropcap --- */

$namaste_shortcodes['dropcap'] = array(
	'title' => __('Dropcap', 'namaste'),
	'id' => 'namaste-dropcap-shortcode',
	'template' => '[namaste_dropcap {{attributes}}]{{content}}[/namaste_dropcap]',
	'params' => array(
		'content' => array(
			'std' => 'Content',
			'type' => 'text',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Blockquote --- */

$namaste_shortcodes['blockquote'] = array(
	'title' => __('Blockquote', 'namaste'),
	'id' => 'namaste-blockquote-shortcode',
	'template' => '[namaste_blockquote {{attributes}}]{{content}}[/namaste_blockquote]',
	'params' => array(
		'author' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Author', 'namaste'),
			'desc' => __('Add the author.', 'namaste'),
		),
		'style' => array(
			'std' => '1',
			'type' => 'select',
			'label' => __('Style', 'namaste'),
			'desc' => __('Select one from the styles.', 'namaste'),
			'options' => array(
				'1' => __('1', 'namaste'),
				'2' => __('2', 'namaste'),
			)
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Highlight --- */

$namaste_shortcodes['highlight'] = array(
	'title' => __('Highlight', 'namaste'),
	'id' => 'namaste-highlight-shortcode',
	'template' => '[namaste_highlight {{attributes}}]{{content}}[/namaste_highlight]',
	'params' => array(
		'style' => array(
			'std' => 'left',
			'type' => 'select',
			'label' => __('Style', 'namaste'),
			'desc' => __('Select one from the styles.', 'namaste'),
			'options' => array(
				'1' => __('1', 'namaste'),
				'2' => __('2', 'namaste'),
			)
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		),
	)
);

/* Button --- */

$namaste_shortcodes['button'] = array(
	'title' => __('Button', 'namaste'),
	'id' => 'namaste-button-shortcode',
	'template' => '[namaste_button {{attributes}}]{{content}}[/namaste_button]',
	'params' => array(
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link', 'namaste'),
			'desc' => __('Add the link.', 'namaste'),
		),
		'color' => array(
			'std' => '',
			'type' => 'select',
			'label' => __('Color type', 'namaste'),
			'desc' => __('Select the color type.', 'namaste'),
			'options' => array(
				'basic' => __('basic', 'namaste'),
				'inverse' => __('inverse', 'namaste'),
				'bw' => __('black & white', 'namaste'),
				'custom' => __('custom', 'namaste'),
			)
		),
		'custom_color' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Custom color', 'namaste'),
			'desc' => __('Add a color code for custom button.', 'namaste'),
		),
		'size' => array(
			'std' => '',
			'type' => 'select',
			'label' => __('Size', 'namaste'),
			'desc' => __('Select the size.', 'namaste'),
			'options' => array(
				'' => __('', 'namaste'),
				'small' => __('small', 'namaste'),
				'normal' => __('normal', 'namaste'),
				'big' => __('big', 'namaste'),
				'bigger' => __('bigger', 'namaste'),
			)
		),
		'target' => array(
			'std' => '',
			'type' => 'select',
			'label' => __('Target', 'namaste'),
			'desc' => __('Select a target.', 'namaste'),
			'options' => array(
				'_self' => __('self', 'namaste'),
				'_blank' => __('blank', 'namaste'),
			)
		),
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select the icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
		'icon_position' => array(
			'std' => 'right',
			'type' => 'select',
			'label' => __('Icon position', 'namaste'),
			'desc' => __('Left or right.', 'namaste'),
			'options' => array(
				'right' => __('right', 'namaste'),
				'left' => __('left', 'namaste'),
			)
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Text on button', 'namaste'),
		)
	)
);

/* Heading --- */

$namaste_shortcodes['heading'] = array(
	'title' => __('Heading', 'namaste'),
	'id' => 'namaste-heading-shortcode',
	'template' => '[namaste_heading {{attributes}}]{{content}}[/namaste_heading]',
	'params' => array(
		'h' => array(
			'std' => 'h2',
			'type' => 'select',
			'label' => __('Size', 'namaste'),
			'desc' => __('Select the font size.', 'namaste'),
			'options' => array(
				'h1' => __('h1', 'namaste'),
				'h2' => __('h2', 'namaste'),
				'h3' => __('h3', 'namaste'),
				'h4' => __('h4', 'namaste'),
				'h5' => __('h5', 'namaste'),
				'h6' => __('h6', 'namaste')
			)
		),
		'align' => array(
			'std' => 'left',
			'type' => 'select',
			'label' => __('Align', 'namaste'),
			'desc' => __('Set the align.', 'namaste'),
			'options' => array(
				'left' => __('left', 'namaste'),
				'center' => __('center', 'namaste'),
				'right' => __('right', 'namaste'),
			)
		),
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select the icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
		'content' => array(
			'std' => 'Title',
			'type' => 'text',
			'label' => __('Title', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Heading 2 --- */

$namaste_shortcodes['heading_2'] = array(
	'title' => __('Heading 2', 'namaste'),
	'id' => 'namaste-heading-2-shortcode',
	'template' => '[namaste_heading_2 {{attributes}}]{{content}}[/namaste_heading_2]',
	'params' => array(
		'h' => array(
			'std' => 'h2',
			'type' => 'select',
			'label' => __('Size', 'namaste'),
			'desc' => __('Select the font size.', 'namaste'),
			'options' => array(
				'h1' => __('h1', 'namaste'),
				'h2' => __('h2', 'namaste'),
				'h3' => __('h3', 'namaste'),
				'h4' => __('h4', 'namaste'),
				'h5' => __('h5', 'namaste'),
				'h6' => __('h6', 'namaste')
			)
		),
		'align' => array(
			'std' => 'left',
			'type' => 'select',
			'label' => __('Align', 'namaste'),
			'desc' => __('Set the align.', 'namaste'),
			'options' => array(
				'left' => __('left', 'namaste'),
				'center' => __('center', 'namaste'),
				'right' => __('right', 'namaste'),
			)
		),
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select the icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
		'color' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Color', 'namaste'),
			'desc' => __('You can add specific color.', 'namaste'),
		),
		'content' => array(
			'std' => 'Title',
			'type' => 'text',
			'label' => __('Title', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Subtext --- */

$namaste_shortcodes['subtext'] = array(
	'title' => __('Subtext', 'namaste'),
	'id' => 'namaste-subtext-shortcode',
	'template' => '[namaste_subtext {{attributes}}]{{content}}[/namaste_subtext]',
	'params' => array(
		'align' => array(
			'std' => 'left',
			'type' => 'select',
			'label' => __('Align', 'namaste'),
			'desc' => __('Set the align.', 'namaste'),
			'options' => array(
				'left' => __('left', 'namaste'),
				'center' => __('center', 'namaste'),
				'right' => __('right', 'namaste'),
			)
		),
		'color' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Color', 'namaste'),
			'desc' => __('You can add specific color.', 'namaste'),
		),
		'content' => array(
			'std' => 'Text',
			'type' => 'text',
			'label' => __('Text', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Big Heading --- */

$namaste_shortcodes['big_heading'] = array(
	'title' => __('Big Heading', 'namaste'),
	'id' => 'namaste-big-heading-shortcode',
	'template' => '[namaste_big_heading {{attributes}}]{{content}}[/namaste_big_heading]',
	'params' => array(
		'bigtext' => array(
			'std' => 'Namaste',
			'type' => 'text',
			'label' => __('Big text', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		),
		'width' => array(
			'std' => '500',
			'type' => 'text',
			'label' => __('Max width', 'namaste'),
			'desc' => __('Add the width of the area (px).', 'namaste'),
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('The content of the smalltext area.', 'namaste'),
		)
	)
);

/* FlexSlider --- */

$namaste_shortcodes['flexslider'] = array(
	'title' => __('FlexSlider', 'namaste'),
	'id' => 'namaste-flexslider-shortcode',
	'template' => '[namaste_flexslider]{{child_shortcode}}[/namaste_flexslider]',
	'notes' => __('Click \'Add Slide\' to add a new slide.', 'namaste'),
	'params' => array(),
	'child_shortcode' => array(
		'params' => array(
			'content' => array(
				'std' => '',
				'type' => 'text',
				'label' => __('Image', 'namaste'),
				'desc' => __('Add the link of the image.', 'namaste'),
			),
		),
		'template' => '[slide]{{content}}[/slide] ',
		'clone_button' => __('Add Slide', 'namaste')
	)
);

/* Map --- */

$namaste_shortcodes['map'] = array(
	'title' => __('Map', 'namaste'),
	'id' => 'namaste-map-shortcode',
	'template' => '[namaste_map {{attributes}}]{{content}}[/namaste_map]',
	'params' => array(
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __('Code', 'namaste'),
			'desc' => __('Add the iframe code from Google Map.', 'namaste'),
		)
	)
);

/* Imagebox --- */

$namaste_shortcodes['imagebox'] = array(
	'title' => __('Imagebox', 'namaste'),
	'id' => 'namaste-imagebox-shortcode',
	'template' => '[namaste_imagebox {{attributes}}]{{content}}[/namaste_imagebox]',
	'params' => array(
		'image' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Image', 'namaste'),
			'desc' => __('Add the link of the image.', 'namaste'),
		),
		'link_type' => array(
			'std' => '',
			'type' => 'select',
			'label' => __('Link type', 'namaste'),
			'desc' => __('Select the link type.', 'namaste'),
			'options' => array(
				'normal' => 'Normal',
				'lightbox' => 'Lightbox',
			),
		),
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link', 'namaste'),
			'desc' => __('Link for the image.', 'namaste'),
		),
		'name' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Title', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Description', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		),
	)
);

/* Content Slider --- */

$namaste_shortcodes['content_slider'] = array(
	'title' => __('Content Slider', 'namaste'),
	'id' => 'namaste-content-slider-shortcode',
	'template' => '[namaste_content_slider]{{child_shortcode}}[/namaste_content_slider]',
	'notes' => __('Click \'Add Slide\' to add a new slide.', 'namaste'),
	'params' => array(),
	'child_shortcode' => array(
		'params' => array(
			'type' => array(
				'std' => 'image',
				'type' => 'select',
				'label' => __('Type', 'namaste'),
				'desc' => __('Select the type of the slide.', 'namaste'),
				'options' => array(
					'image' => __('Image', 'namaste'),
					'video' => __('Video', 'namaste'),
					'content' => __('Content', 'namaste'),
				)
			),
			'image_or_video' => array(
				'std' => '',
				'type' => 'text',
				'label' => __('Link', 'namaste'),
				'desc' => __('Link of the image or the Youtube ID of the video.', 'namaste'),
			),
			'content' => array(
				'std' => 'Content',
				'type' => 'textarea',
				'label' => __('Content', 'namaste'),
				'desc' => __('Add the content.', 'namaste'),
			)
		),
		'template' => '[namaste_content_slide {{attributes}}]{{content}}[/namaste_content_slide] ',
		'clone_button' => __('Add Slide', 'namaste')
	)
);

/* Iconbox --- */

$namaste_shortcodes['iconbox'] = array(
	'title' => __('Iconbox', 'namaste'),
	'id' => 'namaste-iconbox-shortcode',
	'template' => '[namaste_iconbox {{attributes}}] {{content}} [/namaste_iconbox]',
	'params' => array(
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Title', 'namaste'),
			'desc' => __('Add the title.', 'namaste')
		),
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select the icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link', 'namaste'),
			'desc' => __('Add a link or leave it blank.', 'namaste')
		),
		'content' => array(
			'std' => 'Content',
			'type' => 'textarea',
			'label' => __('Content', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		)
	)
);

/* Icon --- */

$namaste_shortcodes['icon'] = array(
	'title' => __('Icon', 'namaste'),
	'id' => 'namaste-icon-shortcode',
	'template' => '[namaste_icon {{attributes}}]',
	'params' => array(
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select the icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
	)
);

/* Action Call --- */

$namaste_shortcodes['action_call'] = array(
	'title' => __('Action Call', 'namaste'),
	'id' => 'namaste-action-call-shortcode',
	'template' => '[namaste_action_call {{attributes}}]',
	'params' => array(
		'title' => array(
			'std' => 'om mani padme hum',
			'type' => 'text',
			'label' => __('<strong>Main Title</strong>', 'namaste'),
			'desc' => __('Add the content.', 'namaste'),
		),
		'name_1' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('<strong>1. column</strong><br>Title', 'namaste'),
			'desc' => __('Add a title.', 'namaste'),
		),
		'icon_1' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select an icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
		'link_1' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link', 'namaste'),
			'desc' => __('Add a link for the area.', 'namaste'),
		),
		'head_1' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside header', 'namaste'),
			'desc' => __('Add a title.', 'namaste'),
		),
		'desc_1' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside description', 'namaste'),
			'desc' => __('Add a description.', 'namaste'),
		),
		'img_1' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside image', 'namaste'),
			'desc' => __('Add a link for the inside image.', 'namaste'),
		),
		'name_2' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('<strong>2. column</strong><br>Title', 'namaste'),
			'desc' => __('Add a title.', 'namaste'),
		),
		'icon_2' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select an icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
		'link_2' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link', 'namaste'),
			'desc' => __('Add a link for the area.', 'namaste'),
		),
		'head_2' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside header', 'namaste'),
			'desc' => __('Add a title.', 'namaste'),
		),
		'desc_2' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside description', 'namaste'),
			'desc' => __('Add a description.', 'namaste'),
		),
		'img_2' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside image', 'namaste'),
			'desc' => __('Add a link for the inside image.', 'namaste'),
		),
		'name_3' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('<strong>3. column</strong><br>Title', 'namaste'),
			'desc' => __('Add a title.', 'namaste'),
		),
		'icon_3' => array(
			'type' => 'select',
			'label' => __('Icon', 'namaste'),
			'desc' => __('Select an icon.', 'namaste'),
			'options' => $namaste_shortcode_icons
		),
		'link_3' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link', 'namaste'),
			'desc' => __('Add a link for the area.', 'namaste'),
		),
		'head_3' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside header', 'namaste'),
			'desc' => __('Add a title.', 'namaste'),
		),
		'desc_3' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside description', 'namaste'),
			'desc' => __('Add a description.', 'namaste'),
		),
		'img_3' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Inside image', 'namaste'),
			'desc' => __('Add a link for the inside image.', 'namaste'),
		)

	)
);

/* Post --- */

$namaste_shortcodes['post'] = array(
	'title' => __('Post', 'namaste'),
	'id' => 'namaste-post-shortcode',
	'template' => '[namaste_post {{attributes}}]',
	'params' => array(
		'title' => array(
			'std' => '',
			'type' => 'select',
			'label' => __('Title', 'namaste'),
			'desc' => __('Select from the posts.', 'namaste'),
			'options' => $post_title
		),
	)
);

/* Post Carousel --- */

$namaste_shortcodes['postcarousel'] = array(
	'title' => __('Post Carousel', 'namaste'),
	'id' => 'namaste-postcarousel-shortcode',
	'template' => '[namaste_postcarousel {{attributes}}]',
	'params' => array(
		'category' => array(
			'std' => '',
			'type' => 'select',
			'label' => __('Category', 'namaste'),
			'desc' => __('Select a category or leave it blank for all posts.', 'namaste'),
			'options' => $post_categories
		),
		'columns' => array(
			'std' => '1',
			'type' => 'select',
			'label' => __('Columns', 'namaste'),
			'desc' => __('Number of columns on big screens.', 'namaste'),
			'options' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		),
		'delay' => array(
			'std' => '5',
			'type' => 'text',
			'label' => __('Delay', 'namaste'),
			'desc' => __('The delay for autoplay in sec. (default: false)', 'namaste'),
		),
		'pagination' => array(
			'std' => 'true',
			'type' => 'select',
			'label' => __('Pagination', 'namaste'),
			'desc' => __('', 'namaste'),
			'options' => array(
				'true' => 'true',
				'false' => 'false',
			),
		),
		'navigation' => array(
			'std' => 'dark',
			'type' => 'select',
			'label' => __('Navigation', 'namaste'),
			'desc' => __('', 'namaste'),
			'options' => array(
				'dark' => 'dark',
				'light' => 'light',
				'burgundy' => 'burgundy',
				'big' => 'big',
				'false' => 'false',
			),
		),
	)
);

/* Quote Carousel --- */

$namaste_shortcodes['quote_carousel'] = array(
	'title' => __('Quote Carousel', 'namaste'),
	'id' => 'namaste-quote-carousel-shortcode',
	'template' => '[namaste_quote_carousel]{{child_shortcode}}[/namaste_quote_carousel]',
	'notes' => __('Click \'Add Quote\' to add a new quote.', 'namaste'),
	'params' => array(),
	'child_shortcode' => array(
		'params' => array(
			'author' => array(
				'std' => '',
				'type' => 'text',
				'label' => __('Author', 'namaste'),
				'desc' => __('Add the author.', 'namaste'),
			),
			'author_position' => array(
				'std' => '',
				'type' => 'text',
				'label' => __('Author Role', 'namaste'),
				'desc' => __('Add some text.', 'namaste'),
			),
			'image' => array(
				'std' => '',
				'type' => 'text',
				'label' => __('Author image', 'namaste'),
				'desc' => __('Add an image link.', 'namaste'),
			),
			'content' => array(
				'std' => 'Content',
				'type' => 'textarea',
				'label' => __('Content', 'namaste'),
				'desc' => __('Add the content.', 'namaste'),
			),
		),
		'template' => '[namaste_quote_element {{attributes}}]{{content}}[/namaste_quote_element] ',
		'clone_button' => __('Add Quote', 'namaste')
	)
);