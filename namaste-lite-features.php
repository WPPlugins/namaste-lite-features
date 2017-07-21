<?php
/**
 * Plugin Name: Namaste Lite Features
 * Plugin URI:  http://webzakt.com/product/namaste-buddhist-wordpress-theme/
 * Description: Shortcodes plugin for Namaste theme from <a href="http://webzakt.com" target="_blank">Webzakt</a>. Get <a href="http://webzakt.com/product/namaste-buddhist-wordpress-theme/" target="_blank">Pro Version</a> with more Shortcodes, event and portfolio modules.
 * Version: 1.1.0
 * Author: Webzakt
 * Author URI: http://www.webzakt.com
 */

class Namaste_Lite_Features {

    function __construct()
    {
    	define( 'NAMASTE_VERSION', '1.1' );

    	// Plugin folder path
    	if ( ! defined( 'NAMASTE_PLUGIN_DIR' ) ) {
    		define( 'NAMASTE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    	}

    	// Plugin folder URL
    	if ( ! defined( 'NAMASTE_PLUGIN_URL' ) ) {
    		define( 'NAMASTE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    	}

    	require_once( NAMASTE_PLUGIN_DIR .'includes/shortcodes.php' );
		
        add_action( 'init', array(&$this, 'namaste_init') );
        add_action( 'admin_init', array(&$this, 'namaste_admin_init') );
	}

	/**
	 * Enqueue front end scripts and styles
	 *
	 * @return	void
	 */
	function namaste_init()
	{
		if( ! is_admin() )
		{
			wp_enqueue_style( 'namaste-shortcodes', NAMASTE_PLUGIN_URL . 'assets/css/shortcodes.css' );			
			wp_enqueue_style( 'namaste-font-fontello', NAMASTE_PLUGIN_URL . 'assets/css/fontello.css' );
			wp_enqueue_style( 'font-awesome', NAMASTE_PLUGIN_URL . 'assets/css/font-awesome.css' );
			wp_enqueue_style( 'owl-carousel', NAMASTE_PLUGIN_URL . 'assets/css/owl-carousel.css' );
			
			wp_enqueue_script( 'owl-carousel', NAMASTE_PLUGIN_URL . 'assets/js/owl.carousel.min.js', true, '1.3.2', true );
			wp_enqueue_script( 'bootstrap-js', NAMASTE_PLUGIN_URL . 'assets/js/bootstrap.min.js', true, '3.3.5', true );	
		}
	}

	function namaste_admin_init()
	{
		include_once( NAMASTE_PLUGIN_DIR . 'includes/class-namaste-admin-insert.php' );

		// css
		wp_enqueue_style( 'namaste-popup', NAMASTE_PLUGIN_URL . 'assets/css/admin.css', false, '1.0', 'all' );

	}
}
new Namaste_Lite_Features();