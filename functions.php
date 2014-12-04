<?php

/**
 * =====================================================
 *
 * Constants
 *
 * =====================================================
 */

define( 'ENV', 'development' );

/** 
 * Theme version
 * Appended to all enqueued scripts and stylesheets
 *	
 */	
define( 'THEME_VERSION', '0.2' );

/** 
 * DB version
 *	
 */	
define( 'THEME_DB_VERSION', '0.1' );

/** 
 * Theme directory
 *	
 */	
define( 'THEME_DIR', get_template_directory() );

/** 
 * Theme URI
 *	
 */	
define( 'THEME_URI', get_template_directory_uri() );

/** 
 * Site Url
 *	
 */	
define( 'SITE_URL', get_bloginfo('wpurl') );

/** 
 * Others..
 *	
 */
define( 'WP_ADMIN_DIR', ABSPATH . 'wp-admin' );
define( 'ATTACHMENTS_SETTINGS_SCREEN', false );
define( 'ATTACHMENTS_DEFAULT_INSTANCE', false );




/**
 * =====================================================
 *
 * Build Theme
 *
 * =====================================================
 */

require_once( THEME_DIR . "/library/config/init.class.php" );
require_once( THEME_DIR . "/config/". ENV .".config.php" );

$init = new library\config\Init();

/**
 *
 * Referenced extensively in library/config/init.class.php
 * Returns config sepcific options
 * 
 *
 */
function ns_theme() {
   return ENV . '\\config';
}





/**
 * =====================================================
 *
 * Register settings page class
 *
 * =====================================================
 */

/**
 *
 * Creates the options page in admin
 * Registered with add_menu_page in libray\config\init();
 * 
 *
 */
function settings_page(){
	
	require_once( THEME_DIR . "/library/helpers/options.helper.class.php" );
	
	/**
	 *
	 * $arg defined in library/helpers/options.helper.class.php
	 *
	 */
	new library\options_group('Settings',$arg);

}





/**
 * =====================================================
 *
 * Include ACF
 *
 * =====================================================
 */

include_once("acf/acf.php");
include_once('acf/add-ons/acf-repeater/acf-repeater.php');
include_once("library/helpers/acf.helper.class.php");
	
if(function_exists("register_field_group")){
 
  /**
	 *
	 * $arg defined in library/helpers/acf.helper.class.php
	 *
	 */
	#new library\helpers\field_group('Custom Page',$args);

}



/**
 * =====================================================
 *
 * 404 to homepage
 * Active hook if needed
 *
 * =====================================================
 */

#add_action('wp', 'redirect_404');
   
function redirect_404(){
	if ( is_404() ) {
		header ('HTTP/1.1 301 Moved Permanently');
		header ("Location: " . get_bloginfo('url') );
	 	exit(0);
	}
}




/**
 * =====================================================
 *
 * Admin functions
 *
 * =====================================================
 */

if ( is_admin() ) {
	include_once("library/functions/admin/admin-functions.php");
}




/**
 * =====================================================
 *
 * Database loops query helper
 *
 * =====================================================
 */

include_once("library/helpers/loops.helper.class.php");





/**
 * =====================================================
 *
 * Theme functions
 *
 * =====================================================
 */

include_once("library/functions/theme/app.php");


?>