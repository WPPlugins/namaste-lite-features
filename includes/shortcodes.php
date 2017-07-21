<?php
/**
 * Create and define the shortcodes
 *
 * @package Namaste Features
 * @since  1.0
 */
 
// Allow Shortcodes in Widgets

add_filter('widget_text', 'namaste_shortcode');

// Shortcodes function
if (!function_exists('namaste_shortcode')){
function namaste_shortcode($content){
	$shortcodes = 'section, box, container, column, align, br, clear, spacer, separator, dropcap, blockquote, highlight, button, heading, heading_2, subtext, big_heading, flexslider, map, imagebox, iconbox, icon, action_call, post, postcarousel, quote_element, quote_carousel';
	$shortcodes = explode(",",$shortcodes);
	$shortcodes = array_map("trim",$shortcodes);
	
	global $shortcode_tags;
	
    // Backup current registered shortcodes and clear them all out
    $orig_shortcode_tags = $shortcode_tags;
	$shortcode_tags = array();
	
	foreach ($shortcodes as $shortcode){
		add_shortcode('namaste_'.$shortcode, 'namaste_'.$shortcode);
	}	
	 // Do the shortcode (only the one above is registered)
    $content = do_shortcode($content);
 
    // Put the original shortcodes back
    $shortcode_tags = $orig_shortcode_tags;
 
    return $content;
}
}

add_filter('wp_nav_menu_items', 'do_shortcode');

if(!function_exists('namaste_categorized_blog')){
function namaste_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so namste_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so namste_categorized_blog should return false.
		return false;
	}
}
}

if(!function_exists('namaste_owl_navigation')){
function namaste_owl_navigation() {
	$return = "navigationText : ['<i class=";
	$return .= '"icon icon-chevron-left"';
	$return .= "></i>','<i class=";
	$return .= '"icon icon-chevron-right"';
	$return .= "></i>'],";

	return $return;
}
}

if(!function_exists('namaste_shortcode_share_buttons')){
function namaste_shortcode_share_buttons() {
	
	$postpermalink = get_permalink();
	
	if ( function_exists( 'fw_get_db_customizer_option') ) {
		$allow_social_share = fw_get_db_customizer_option( 'allow_social_share/on_off');
		$socials = fw_get_db_customizer_option( 'allow_social_share/on/socials');
		$emailto = fw_get_db_customizer_option( 'allow_social_share/on/email_text');
	}
	
	$return = '
	<div class="social-share-bar">
				<ul>';
	if(isset($socials['facebook'])){
		$return .= '<li class="facebook-share"><a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url($postpermalink) . '" target="_blank" title="' . __( 'Share on Facebook', 'namaste' ) . '"><i class="fa fa-facebook"></i></a></li>';
	}
	if(isset($socials['twitter'])){
		$return .= '<li class="twitter-share"><a href="https://twitter.com/intent/tweet?source=' . esc_url($postpermalink) . '&amp;text=' . esc_url($postpermalink) . '" target="_blank" title="' . __( 'Tweet', 'namaste' ) . '"><i class="fa fa-twitter"></i></a></li>';
	}
	if(isset($socials['google'])){
	$return .= '<li class="google-share"><a href="https://plus.google.com/share?url=' . esc_url($postpermalink) . '" target="_blank" title="' . __( 'Share on Google+', 'namaste' ) . '"><i class="fa fa-google-plus"></i></a></li>';
	}
	if(isset($socials['tumblr'])){
		$return .= '<li class="tumblr-share"><a href="http://www.tumblr.com/share?v=3&amp;u=' . esc_url($postpermalink) . '&amp;s=" target="_blank" title="' . __( 'Post to Tumblr', 'namaste' ) . '"><i class="fa fa-tumblr"></i></a></li>';
	}
	if(isset($socials['pinterest'])){
		$return .= '<li class="pinterest-share"><a href="http://pinterest.com/pin/create/button/?url=' . esc_url($postpermalink) . '" target="_blank" title="' . __( 'Pin it', 'namaste' ) . '"><i class="fa fa-pinterest"></i></a></li>';
	}
	if(isset($socials['reddit'])){
		$return .= '<li class="reddit-share"><a href="http://www.reddit.com/submit?url=' . esc_url($postpermalink) . '" target="_blank" title="' . __( 'Submit to Reddit', 'namaste' ) . '"><i class="fa fa-reddit"></i></a></li>';
	}
	if(isset($socials['linkedin'])){
		$return .= '<li class="linkedin-share"><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . esc_url($postpermalink) . '&amp;source=' . esc_url($postpermalink) . '" target="_blank" title="' . __( 'Share on LinkedIn', 'namaste' ) . '"><i class="fa fa-linkedin"></i></a></li>';
	}
	if(isset($socials['email'])){
		$return .= '<li class="email-share"><a href="mailto:' . is_email($emailto) . '?body=' . esc_url($postpermalink) . '" target="_blank" title="' . __( 'Email', 'namaste' ) . '"><i class="fa fa-envelope"></i></a></li>';
	}
	$return .= '</ul>
			</div>';
			
	return $return;
}
}

/* Section */

if (!function_exists('namaste_section')){
function namaste_section($atts,$content = NULL){
	extract(shortcode_atts(array(
		'padding'			=>  '30',
		'background_color'	=>  '',
		'color'				=>  '',
		'parallax'			=>  '',
		'image'				=>  '',
		'image_position'	=>  '',
		'layer'				=>	'0',
		'layer_color'		=>	'',
		'border_top'		=>  '',
		'border_bottom'		=>  '',
		'customcss'			=>	'',
	), $atts));

	$general_color_1 = '#e3ae3a';
	$general_color_2 = '#222222';
	$general_color_3 = '#7C2E00';
	$general_color_5 = '#ffffff';	
	
	$background_color = trim($background_color);
	if ( $background_color =='' ) $background_color = $general_color_2 ;	
	
	$color = trim($color);
	if ( $color =='' ) $color = $general_color_5 ;
	
	$layer_color = trim($layer_color);
	if ( $layer_color =='' ) $layer_color = $general_color_3 ;
	
	$border_top = trim($border_top);
	if ( $border_top =='' ) $border_top = '2px solid ' . $general_color_1 ;
	
	$border_bottom = trim($border_bottom);
	if ( $border_bottom =='' ) $border_bottom = '2px solid ' . $general_color_1 ;
	
	
	$random_section_number = rand(1000,9999);
	

	$return = '
		<div id="namaste-section-' . intval($random_section_number) . '" class="shortcode-section';
		
	if ( $parallax == 'yes' ) {
		$return .= ' parallax-section';
	}
	
	$return .= '" style="background-color: ' . esc_attr($background_color) . '; color: ' . esc_attr($color) . '; border-top: ' . esc_attr($border_top) . '; border-bottom: ' . esc_attr($border_bottom) . ';';
	
	if ( $image_position != 'left' && $image_position != 'right' ) {
	$return .= ' background-image: url(' . esc_url($image) . ');';
		if ( $image_position == 'pattern' ) {
			$return .= ' background-size: inherit;';
		}
	}
	
	$return .= ' ' . wp_strip_all_tags($customcss) . '">
			<div class="section-layer-holder">
				<div class="section-layer-' . intval($random_section_number) . ' section-layer" style="background: ' . esc_attr($layer_color) . '; opacity: .' . intval($layer) . ';"></div>';
				
	if ( $image_position == 'right' ) {
	$return .= '<div class="section-image-right" style="background-image: url(' . esc_url($image) . ');"></div>';
	} elseif ( $image_position == 'left' ) {
	$return .= '<div class="section-image-left" style="background-image: url(' . esc_url($image) . ');"></div>';
	}
				
	$return .= '<div class="section-content';
	
	if ( $image_position == 'right' ) {
	$return .= ' section-content-left';
	} elseif ( $image_position == 'left' ) {
	$return .= ' section-content-right';
	}
	
	$return .= '" style="padding: ' . intval($padding) . 'px 0;"><div class="container">'.do_shortcode($content).'</div><div class="clearfix"></div></div>
			</div>
		</div>';

	return $return;	
}
}


/* Box */

if (!function_exists('namaste_box')){
function namaste_box($atts,$content = NULL){
	extract(shortcode_atts(array(
		'padding'	=>  '10',
		'style'		=>  '1',
	), $atts));	
	
	$return = '
		<div class="namaste-box-holder-' . intval($style) . '">
			<div class="namaste-box-shortcode-' . intval($style) . '" style="padding:' . intval($padding) . 'px;">'.do_shortcode($content).'
			<div class="clearfix"></div>
			</div>
		</div>';

	return $return;	
}
}


/* Container */

if (!function_exists('namaste_container')){
function namaste_container($atts,$content = NULL){

	$return = '<div class="container">'.do_shortcode($content).'</div>';

	return $return;	
}
}


/* Columns */

if (!function_exists('namaste_column')){
function namaste_column($atts,$content = NULL){
	extract(shortcode_atts(array(
		'size'		=>  '12',
		'left'		=>  '15',
		'right'		=>  '15',
		'customcss'	=>  '',
		'last'		=>	'false',
	), $atts));
	
	if($last!='true')
	$return = '<div class="col-'.intval($size).'" style="padding-left: '.esc_attr($left).'px; padding-right: '.esc_attr($right).'px; '.wp_strip_all_tags($customcss).'">'.do_shortcode($content).'</div>';
	else
	$return = '<div class="col-'.intval($size).'" style="padding-left: '.esc_attr($left).'px; padding-right: '.esc_attr($right).'px; '.wp_strip_all_tags($customcss).'">'.do_shortcode($content).'</div><div class="clearfix"></div>';
	return $return;	
}
}


/* Align */

if (!function_exists('namaste_align')){
function namaste_align($atts,$content = NULL){
	extract(shortcode_atts(array(
		'align'		=>  '',
	), $atts));
	
	$align = trim($align);
	if ( $align== '' ) { $align = 'left'; }
	
	$return = '
		<div class="align-'.esc_attr($align).'">
		' . do_shortcode($content) . '
		</div>';	
	return $return;	
}
}


/*  Br  */

if (!function_exists('namaste_br')){
function namaste_br($atts, $content = null ){
	return '<br>';
}
}


/*  Clear  */

if (!function_exists('namaste_clear')){
function namaste_clear($atts, $content = null ){
	return '<div class="clearfix"></div>';
}
}


/*  Spacer  */

if (!function_exists('namaste_spacer')){
function namaste_spacer($atts, $content = null ){
	extract(shortcode_atts(array(
		'height'		=>	'30',
	), $atts));
	
	
	return '
		<div class="clearfix"></div>
		<div class="spacer" style="margin-top:'.intval($height).'px"></div>
		<div class="clearfix"></div>';
}
}


/*  Separator  */

if (!function_exists('namaste_separator')){
function namaste_separator($atts, $content = NULL ){
	extract(shortcode_atts(array(
		'size'				=>  'normal',
		'color'				=>	'',
		'margin'			=>	'10',
		'icon'				=>	'fontello-big-buddha',
	), $atts));
		
	$general_color_2 = '#222222';	
	
	$color = trim($color);
	if ( $color =='' ) $color = $general_color_2 ;
	
	if ( $icon =='' ) $icon = 'fontello-big-buddha' ;
	
	if ( $size != 'small' ) $size = 'normal' ;
	
	return '
	<div class="clearfix"></div>
	<div class="separator-'.esc_attr($size).'" style="color: '.esc_attr($color).'; margin: '.esc_attr($margin).'px 0;">
		<div class="separator-icon-holder">
			<i class="separator-icon icon-' . esc_attr($icon) . '"></i>
		</div>
	</div>
	<div class="clearfix"></div>';
}
}


/*  Dropcap  */

if (!function_exists('namaste_dropcap')){
function namaste_dropcap($atts, $content = NULL ){	
	$return = '<span class="dropcap">' . trim($content). '</span>';
	return $return;
}
}


/*  Blockquote  */
    
if (!function_exists('namaste_blockquote')){
function namaste_blockquote($atts,$content = NULL){
	extract(shortcode_atts( array(
		'style' => '',
		'author' => '',                            
	), $atts));


	$style = trim($style);
	$author = trim($author);
		
	if ( $style != '2' ) {
	return '
	<div class="blockquote-1">
		<div class="blockquote-1-author">' . esc_html($author) . '</div>
		<div class="blockquote-1-content">' . do_shortcode($content) . '</div>
		<div class="clearfix"></div>
	</div>';
	
	} else {
		
	return '
	<div class="blockquote-2-holder">
		<div class="blockquote-2">
			<div class="blockquote-2-content">' . do_shortcode($content) . '</div>
			<div class="blockquote-2-author">' . esc_html($author) . '</div>
		</div>
	</div>';
	}
}
}


/*  Highlight */

if (!function_exists('namaste_highlight')){
function namaste_highlight($atts,$content = NULL){
	extract(shortcode_atts(array(
		'style'		=>	1,
	), $atts));
	$return = '<span class="highlight highlight-style-'.esc_attr($style).'">'.do_shortcode($content).'</span>';
	return $return;	
}
}


/*  Button  */

if (!function_exists('namaste_button')){
function namaste_button($atts,$content = NULL){
	extract(shortcode_atts(array(
		'link'			=>  '',
		'target'		=>	'_self',
		'icon'			=>	'',
		'icon_position'	=>	'right',
		'color'			=>	'basic',
		'custom_color'	=>	'',
		'size'			=>	'button-normal',
	), $atts));
		
	$button_class = '';	
	
	if ( $color!= 'inverse' && $color!= 'custom' && $color!= 'bw' ) $color = 'basic';
	
	$size = trim($size);
	if ( $size == '' ) $size = 'normal';	
	
		/* icon */
	$icon_position = trim($icon_position);
	if ( $icon_position!='left' ) $icon_position = 'right';		
	$icon = trim($icon);
	if ( $icon != '') {
		$ic = '<i class="icon-'.esc_attr($icon).' '.esc_attr($icon_position).'-icon"></i>';
		$button_class .= ' has_icon';
	} else $ic = '';
	
	
		/* link */
	$link = trim($link);
	if ( $link ) {
		if ( trim($target) == '_blank' ) $target = '_blank'; else $target = '_self';
		$target = ' target="'.$target.'"';
		$href = ' href='.esc_url($link).'';
	} else {
		$target = '';
		$href = '';
	}
	
	
		/* content */
	if ( !trim($content) ) $button_class .= ' no-content';
	
	$return = '<div class="button button-' . esc_attr($color) . '"'; 
	if ( $color == 'custom' ) {
		$return .= ' style="border-color: '.esc_attr($custom_color).';"';
	}
	$return .= '>
		<a class="btn'.esc_attr($button_class).' button-'.esc_attr($size).'"' . esc_attr($href) . $target . ' '; 
	if ( $color == 'custom' ) {
		$return .= 'style="background-color: '.esc_attr($custom_color).';">';
	} else {
		$return .= '>';
	}
	
	if ( $icon_position == 'left') $return .= $ic . '<span>' . trim($content) . '</span>';
	else $return .= '<span>' . trim($content) . '</span>' . $ic ;
	
	$return .= '</a></div>';
	
	return $return;
}
}


/* Heading */

if (!function_exists('namaste_heading')){
function namaste_heading($atts,$content = NULL){
	extract(shortcode_atts(array(
		'h'				=>	'',
		'icon'			=>	'',
		'align'			=>	'center',
	), $atts));
	
	$h = strtolower( trim ($h) );
	if(!in_array($h,array('h1','h2','h3','h4','h5','h6'))) $h = 'h2';
	
	$return = '
		<div class="heading" style="text-align: ' . esc_attr($align) . ';">
			<div class="heading-holder heading-holder-' . esc_attr($h) . '">
				<' . esc_attr($h) . ' class="h"><i class="heading-icon heading-icon-left icon-' . esc_attr($icon) . '"></i>' . trim($content) . '<i class="heading-icon heading-icon-right icon-' . esc_attr($icon) . '"></i></' . esc_attr($h) . '>
			</div>
		</div>';

	return $return;	
}
}


/* Heading 2 */

if (!function_exists('namaste_heading_2')){
function namaste_heading_2($atts,$content = NULL){
	extract(shortcode_atts(array(
		'h'				=>	'',
		'align'			=>	'center',
		'icon'			=>	'',
		'color'			=>	'',
	), $atts));
	
	$h = strtolower( trim ($h) );
	if(!in_array($h,array('h1','h2','h3','h4','h5','h6'))) $h = 'h2';
	
	$return = '<div class="heading-2" style="text-align: ' . esc_attr($align) . ';';
	
	if ($color != '') { $return .= ' color:' . esc_attr($color) . ';'; }
	$return .= '"><' . $h . ' class="h"><i class="heading-icon heading-icon-left icon-' . esc_attr($icon) . '"></i>' . trim($content) . '<i class="heading-icon heading-icon-right icon-' . esc_attr($icon) . '"></i></' . esc_attr($h) . '>';
	$return .= '</div>';

	return $return;	
}
}


/* Subtext */

if (!function_exists('namaste_subtext')){
function namaste_subtext($atts,$content = NULL){
	extract(shortcode_atts(array(
		'align'			=>	'center',
		'color'			=>	'',
	), $atts));
		
	$return = '<div class="subtext" style="text-align: ' . esc_attr($align) . ';';
	
	if ($color != '') { $return .= ' color:' . esc_attr($color) . ';'; }
	$return .= '"><p>' . trim($content) . '</p>';
	$return .= '</div>';

	return $return;	
}
}


/* Big Heading */

if (!function_exists('namaste_big_heading')){
function namaste_big_heading($atts,$content = NULL){
	extract(shortcode_atts(array(
		'bigtext'			=>	'Namaste',
		'width'				=>	'500',
	), $atts));
	
	
	$return = '
	<div class="bigtitle" style="max-width: '.intval($width).'px;">
		<div class="big-letters">'.esc_html($bigtext).'</div>
		<div class="small-letters">'.esc_html($content).'</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>';

	return $return;	
}
}


/*  FlexSlider  */

if (!function_exists('namaste_flexslider')){
function namaste_flexslider($atts,$content = NULL){


	if (!preg_match_all("/(.?)\[(slide)\b(.*?)(?:(\/))?\](?:(.+?)\[\/slide\])?(.?)/s", $content, $matches)) :
		return do_shortcode($content);		
	else :
		
		$return = '
			<div class="namaste-flexslider">
				<div class="flexslider"><ul class="slides">';
		
		for($i = 0; $i < count($matches[0]); $i++):
			
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
			$image = trim($matches[5][$i]);
			
			if ( $image ) {
				$img = '<img src="' . esc_url($image) . '" alt="'.basename($image).'" />';
			} else {
				$img = '';
			}				
			
			$return .= '<li class="slide">' . $img . '</li>';

		endfor;
		
		$return .= '</ul></div></div>'; // flexslider	
			
		return $return;
		
	endif;
}
}


/* Map */

if (!function_exists('namaste_map')){
function namaste_map($atts,$content = NULL){
        
	return '<div class="google-map">'.do_shortcode($content).'</div>';
}
}


/*  Imagebox   */

if (!function_exists('namaste_imagebox')){
function namaste_imagebox($atts,$content = NULL){
	extract(shortcode_atts(array(
		'image'		=>  '',
		'name'		=>	'',
		'link_type'	=>	'',
		'link'		=>	'',
	), $atts));
	
	if ( $link_type !='lightbox' ) $link_type = 'normal';
			
	$return = '<div class="imagebox">';
	
	if ( $image ) {
		$return .= '<div class="image">';
		
		if ( $link_type =='lightbox' ) { $return .= '<a href="' . esc_url($image) . '" data-gal="prettyPhoto[0]">';} 
		elseif ($link != '') { $return .= '<a href="' . esc_url($link) . '">'; }
				
		$return .= '<img src="' . esc_url($image) . '" alt="' . esc_html($name) . '"/>';
				
		if ($link != '' || $link_type =='lightbox') { $return .= '</a>'; }
		
		$return .= '</div>';
	}
	
	$return .= '<div class="text">';
	if ($link != '') {
	$return .= '<a href="' . esc_url($link) . '">';
	}
	if ($name != '') {
	$return .= '<h4 class="name">' . esc_html($name) . '</h4>';
	}
	if ($link != '') {
	$return .= '</a>';
	}
	$return .= '<div class="desc">' . do_shortcode($content) . '</div>';
	$return .= '</div>'; // text
	$return .= '</div>'; // imagebox
	
	return $return;	
}
}


/*   Iconbox  */

if (!function_exists('namaste_iconbox')){
function namaste_iconbox($atts,$content = NULL){
	extract(shortcode_atts(array(
		'icon'			=>  'fontello-hand',
		'name'			=>	'',
		'title'			=>	'',
		'link'			=>	'',
	), $atts));
	
	$plugin_dir = NAMASTE_PLUGIN_URL ;
	
		/* name */
	$name = trim($name);
	if (!$name) $name = trim($title);
		
		/* icon */
	$icon = trim($icon);
	if ( strpos($icon,'//') !== false ) {
		$ic = '<img src="' . esc_url($icon) . '" />';
		$ic_class = 'ic-image';
	}	else {
		$ic = '<i class="icon-'.esc_attr($icon).'"></i>';
		$ic_class = 'ic-icon';
	}
	
	$return = '<div class="iconbox">';
	
	$return .= '
		<div class="icon '.esc_attr($ic_class).'">';		
	if ( $link !='') { $return .= '<a href="' . esc_url($link) . '">'; }	
	$return .= '
			<img class="iconbox-image" src="' . esc_url($plugin_dir) . 'assets/images/mandala-frame.png"  width="150" height="150" alt="mandala">
			<div class="iconbox-icon-holder">' . $ic . '</div>';
	if ( $link !='') { $return .= '</a>'; }		
	$return .= '
		</div>';
		
	if ( $link !='') { $return .= '<a href="' . esc_url($link) . '">'; }
	$return .= '<p class="name">' . esc_html($name) . '</p>';
	if ( $link !='') { $return .= '</a>'; }
	$return .= '<div class="desc"><p>' . do_shortcode( trim($content) ) . '</p></div>';
	
	$return .= '</div>'; 
	return $return;
}
}


/*  Icon */

if (!function_exists('namaste_icon')){
function namaste_icon($atts, $content = null ){
	extract(shortcode_atts(array(
		'icon'		=>	'',
	), $atts));	
		
		/* icon */
	$icon = trim($icon);
	
	$return = '<i class="icon-shortcode icon-'.esc_attr($icon).'"></i>';

	return $return;
			
}
}


/* Action Call */

if (!function_exists('namaste_action_call')){
function namaste_action_call($atts,$content = NULL){
	
	extract(shortcode_atts(array(
		'title' 			=> 'om mani padme hum',
		'name_1'			=>  '',
		'icon_1'			=>  '', 
		'link_1'			=>  '',
		'head_1'			=>  '',
		'desc_1'			=>  '',
		'img_1'			    =>  '',
		'name_2'			=>  '',
		'icon_2'			=>  '', 
		'link_2'			=>  '',
		'head_2'			=>  '',
		'desc_2'			=>  '',
		'img_2'			    =>  '',
		'name_3'			=>  '',
		'icon_3'			=>  '', 
		'link_3'			=>  '',
		'head_3'			=>  '',
		'desc_3'			=>  '',
		'img_3'			    =>  '',
	), $atts));
    
	$return = '
	<section class="namaste-action-call">
		<div class="container action-call-container">
			
			<div class="title"><p class="main-title">'.wp_kses_post($title).'</p></div>
			
			<div class="action-call-items">
			<div class="action-call-item square colored action-call-effect top_to_bottom">
				<a href="'.esc_url($link_1).'">
				<div class="text-holder">
					<div class="text">
						<div class="call-ribbon">
							<div class="call-ribbon-2">
								<i class="icon-'.esc_attr($icon_1).'"></i>
								<p>'.esc_html($name_1).'</p>
							</div>
						</div>
					</div>
				</div>
				<div class="info">
					<div class="info-back" style="background-image: url('.esc_url($img_1).');">
						<div class="info-content">
							<h3>'.esc_html($head_1).'</h3>
							<p>'.esc_html($desc_1).'</p>
						</div>
					</div>
				</div>
				</a>
			</div>

			<div class="action-call-item square colored action-call-effect top_to_bottom">
				<a href="'.esc_url($link_2).'">
				<div class="text-holder">
					<div class="text">
						<div class="call-ribbon">
							<div class="call-ribbon-2">
								<i class="icon-'.esc_attr($icon_2).'"></i>
								<p>'.esc_html($name_2).'</p>
							</div>
						</div>
					</div>
				</div>
				<div class="info">
					<div class="info-back" style="background-image: url('.esc_url($img_2).');">
						<div class="info-content">
							<h3>'.esc_html($head_2).'</h3>
							<p>'.esc_html($desc_2).'</p>
						</div>
					</div>
				</div>
				</a>
			</div>

			<div class="action-call-item square colored action-call-effect top_to_bottom">
				<a href="'.esc_url($link_3).'">
				<div class="text-holder">
					<div class="text">
						<div class="call-ribbon">
							<div class="call-ribbon-2">
								<i class="icon-'.esc_attr($icon_3).'"></i>
								<p>'.esc_html($name_3).'</p>
							</div>
						</div>
					</div>
				</div>
				<div class="info">
					<div class="info-back" style="background-image: url('.esc_url($img_3).');">
						<div class="info-content">
							<h3>'.esc_html($head_3).'</h3>
							<p>'.esc_html($desc_3).'</p>
						</div>
					</div>
				</div>
				</a>
			</div>
			<div class="clearfix"></div>
			</div>

		</div>
		<div class="clearfix"></div>
	</section>' ;
		
			return $return;
	
}
}


/*  Post  */

if (!function_exists('namaste_post')){
function namaste_post($atts, $content = null ){ 
	extract(shortcode_atts(array(
		'title'		=>	'',
	), $atts));

	$title = trim($title);

	if ( function_exists( 'fw_get_db_customizer_option') ) {
    $read_more_text = fw_get_db_customizer_option( 'read_more_text');
	if ( $read_more_text =='') {  $read_more_text = __( 'Read more', 'namaste' ) ; }
  	}	
	else {  $read_more_text = __( 'Read more', 'namaste' ) ;}
        
	$postmiracle = array(
        'posts_per_page' => 1,
        'name' => '' . esc_html($title) . '');

	$postmiracle = new WP_Query( $postmiracle ); 
	while ($postmiracle->have_posts()) : $postmiracle->the_post(); 

	$permalink = get_permalink();
	$title = get_the_title() ;
	$author = get_the_author();
	$excerpt = substr(get_the_excerpt(), 0,210);
	$taglist = get_the_tag_list( '<span class="tag-list">', '</span><span class="tag-list"> ', '</span>') ;
	
	$return =  '
		<div class="post-shortcode">
			<div class="entry-elements">' ;

    $category_list = get_the_category_list( __( ', ', 'namaste' ) );
	$return .=  '<span class="category-list">' . wp_kses_post($category_list) . '</span>';                   
         
	$return .=  '
			</div>
        	<h3 class="entry-title"><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></h3>
        	<p class="entry-meta"><span class="post-author">' . esc_html($author) . '</span></p>' ;

    if (has_post_thumbnail( $postmiracle->ID ) ){  
    $image = wp_get_attachment_url( get_post_thumbnail_id($postmiracle->ID), 'medium' ) ; 
        
	$return .=  '
			<div class="small-thumb-holder">
				<div class="small-thumb-overlay">
					<a href="' . esc_url($permalink) . '">    
						<div class="small-thumb" style="background-image: url(' . esc_url($image) . ')"></div>
					</a>
             	</div>
			</div>' ;
         }        

	$return .=  '
			<div class="entry-content">
				<p class="post-excerpt">' . wp_kses_post($excerpt) . '... </p>        
             	<div class="clearfix"></div>
				<div class="button post-2-button">
					<a class="btn" href="' . esc_url($permalink) . '" target="_blank"><span>'. esc_html($read_more_text) . '</span></a>
				</div>
				<div class="clearfix"></div>' ; 
				  
	$tag = get_the_tags(); 
	if (! $tag) { } else { 
		$return .=  '
				<div class="home-tags">' . wp_kses_post($taglist) . '</div>';
	} 
	
	$return .=  '
			</div>
		</div>' ;

	endwhile;        
	wp_reset_query();
	return $return;   
}
}


/*   Post Carousel  */

if (!function_exists('namaste_postcarousel')){
function namaste_postcarousel($atts, $content = null ){ 
	extract(shortcode_atts(array(
		'category'          => '',
		'columns'           => '3',
		'delay'             => 'false',
		'pagination'	    =>  'true',
		'navigation'	    =>  'dark',
	), $atts));

	$columns = trim($columns); 
	if ( $columns!=1 && $columns!=2 && $columns!=4 ) $columns = 3;
	
	$lesscolumns = $columns;
	
	if ( $columns==4 ) $lesscolumns = 3;
		
	$category = trim($category);       

	if ( function_exists( 'fw_get_db_customizer_option') ) {
    $read_more_text = fw_get_db_customizer_option( 'read_more_text');
	if ( $read_more_text =='') {  $read_more_text = __( 'Read more', 'namaste' ) ; }
  	}	
	else {  $read_more_text = __( 'Read more', 'namaste' ) ;}
		
	$random_carousel_1_number = rand(1000,9999);
	
    $return = '<div id="owl-news-namaste" class="owl-carousel carousel owl row owl-news-namaste-'.esc_attr($random_carousel_1_number).' owl-pagination-'.esc_attr($pagination).' owl-navigation-'.esc_attr($navigation).'">'; 

    $slider = array(
    'category_name' => '' . esc_attr($category) . '',
    'order'    => 'DESC',
	'posts_per_page'	=>	1000,);
    $slider = new WP_Query( $slider ); 

    while ($slider->have_posts()) : $slider->the_post(); 
    
	$permalink = get_permalink();
	$title = get_the_title() ;
	$author = get_the_author();
	$excerpt = substr(get_the_excerpt(), 0,210);
	$taglist = get_the_tag_list( '<span class="tag-list">', '</span><span class="tag-list"> ', '</span>') ;
	
	$return .=  '
		<div class="post-shortcode">
			<div class="entry-elements">' ;

    $category_list = get_the_category_list( __( ', ', 'namaste' ) );
	$return .=  '<span class="category-list">' . wp_kses_post($category_list) . '</span>';                   
        
	$return .=  '
			</div>
        	<h3 class="entry-title"><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></h3>
        	<p class="entry-meta"><span class="post-author">' . esc_html($author) . '</span></p>' ;

    if (has_post_thumbnail( $slider->ID ) ){  
    $image = wp_get_attachment_url( get_post_thumbnail_id($slider->ID), 'medium' ) ; 
        
	$return .=  '
			<div class="small-thumb-holder">
				<div class="small-thumb-overlay">
					<a href="' . esc_url($permalink) . '">    
						<div class="small-thumb" style="background-image: url(' . esc_url($image) . ')"></div>
					</a>
             	</div>
			</div>' ;
         }        

	$return .=  '
			<div class="entry-content">
				<p class="carousel-excerpt">' . wp_kses_post($excerpt) . '... </p>       
             	<div class="clearfix"></div>
				<div class="button post-2-button">
					<a class="btn" href="' . esc_url($permalink) . '" target="_blank"><span>'. esc_html($read_more_text) . '</span></a>
				</div>
				<div class="clearfix"></div>' ; 
				  
	$tag = get_the_tags(); 
	if (! $tag) { } else { 
		$return .=  '
				<div class="home-tags">' . wp_kses_post($taglist) . '</div>';
	} 
	
	$return .=  '
			</div>
		</div>' ;
		                             
	endwhile;  
     
	$return .= '</div>' ;
	
	$return .= '
		 <script type="text/javascript"> 
		 jQuery(document).ready(function($){
			$(document).ready(function() {
		
			  var owl = $(".owl-news-namaste-'.esc_attr($random_carousel_1_number).'");
		
			  owl.owlCarousel({
				  
			  navigation : true, // Show next and prev buttons
			  '.namaste_owl_navigation().'
			  pagination : true,
		
			  items : '.esc_attr($columns).',
			  itemsDesktop : [5000,'.esc_attr($columns).'],
			  itemsDesktop : [1170,'.esc_attr($lesscolumns).'],
			  itemsDesktopSmall : [900,1], 
			  itemsTablet: [600,1], 
			  itemsMobile : [400,1],';
	if ( $delay != 'false' && $delay != '') {  			  
		$return .= '
			  autoPlay : '.esc_attr($delay).'000,';
	} else {
		$return .= '
		      autoPlay : false,';
	}
	$return .= '		
			  });		
			});
		 }); // jQuery
		 </script>';
		         
	wp_reset_query();
	return $return;
}
}


/* Quote Carousel */

if (!function_exists('namaste_quote_element')){
function namaste_quote_element($atts,$content = NULL){
	extract(shortcode_atts(array(
		'author'		   =>  '',
		'author_position'  =>  '',
		'image'		       =>  '',
	), $atts));
	
	
	$return = '<div class="quote-item">
			    <div class="quote-content">' . do_shortcode($content) . '</div>';
	if ( $image != '' ) {
		$return .= '
				<div class="quote-image"><img class="quote-author-image" width="75" height="75" alt="' . esc_html($author) . '" src="' . esc_url($image) . '"></div>';
	}
	if ( $author != '' ) {
	$return .= '
				<div class="quote-author">
					<p>' . esc_html($author) . '</p>
					<p class="quote-author-position">' . esc_html($author_position) . '</p>
				</div>';
	}
	$return .= '
				</div>';

	return $return;	
}
}
 
if (!function_exists('namaste_quote_carousel')){
function namaste_quote_carousel($atts,$content = NULL){
	extract(shortcode_atts(array(
		'column'		  =>  '',
		'delay'			  =>  'false',
		'pagination'	  =>  'true',
		'navigation'	  =>  'light',
	), $atts));
	
	$column = trim($column);
	if ( $column =='' ) $column = '1';
	
	$return = '<div id="owl-quote-namaste" class="owl-carousel carousel owl row owl-pagination-'.esc_attr($pagination).' owl-navigation-'.esc_attr($navigation).'">' . do_shortcode($content) . '</div>';
	
	$return .= '	 
	 <script type="text/javascript"> 
		 jQuery(document).ready(function($){
			$(document).ready(function() {
		
			  var owl = $("#owl-quote-namaste");
		
			  owl.owlCarousel({
				  
			  navigation : true, // Show next and prev buttons
			  '.namaste_owl_navigation().'
			  pagination : true,
		 
			  slideSpeed : 1500,
			  paginationSpeed : 1500,
			  
			  items : '. esc_attr($column) .', 
			  itemsDesktop : [5000,'. esc_attr($column) .'], 
			  itemsDesktop : [1599,'. esc_attr($column) .'], 
			  itemsDesktopSmall : [900,1], 
			  itemsTablet: [600,1], 
			  itemsMobile : [400,1],';
	if ( $delay != 'false' && $delay != '') {  			  
		$return .= '
			  autoPlay : '.esc_attr($delay).'000,';
	} else {
		$return .= '
		      autoPlay : false,';
	}
	$return .= '		
			  });
			});
		 }); // jQuery
 	</script>';         

	return $return;	
}
}


	$shortcodes = 'section, box, container, column, align, br, clear, spacer, separator, dropcap, blockquote, highlight, button, heading, heading_2, subtext, big_heading, flexslider, map, imagebox, iconbox, icon, action_call, post, postcarousel, quote_element, quote_carousel';

	$shortcodes = explode(",",$shortcodes);
	$shortcodes = array_map("trim",$shortcodes);

	foreach ($shortcodes as $shortcode){
	add_shortcode('namaste_'.$shortcode, 'namaste_'.$shortcode);
	add_shortcode('namaste_section_2', 'namaste_section');
	add_shortcode('namaste_column_2', 'namaste_column');
}