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





/**
 * =====================================================
 *
 * Restructure IMG HTML to allow lazy-loading
 *
 * =====================================================
 */
function callback($html) {
	$pattern = '/<img[^>]*src=(?:["])+([^> ]*)(?:["])[^>]*\/?>/';
	$new_html = preg_replace_callback($pattern,
		function($matches){
			preg_match('/alt=(?:["])+([^> ]*)(?:["])/',$matches[0],$alt);
			preg_match('/width=(?:["])+([^> ]*)(?:["])/',$matches[0],$width);
			preg_match('/height=(?:["])+([^> ]*)(?:["])/',$matches[0],$height);
			return '
			<div class="image-container">
				<noscript><img src="'.$matches[1].'" width="'.$width[1].'" height="'.$height[1].'" alt="'.$alt[1].'" /></noscript>
				<img src="'.get_bloginfo('stylesheet_directory').'/img/placeholder.gif" data-src="'.$matches[1].'" width="'.$width[1].'" height="'.$height[1].'" alt="'.$alt[1].'" />
			</div>';
		}, 
		$html);
	return $new_html;
}

function buffer_start() { ob_start("callback"); }
add_action('wp_head', 'buffer_start');

function buffer_end() { ob_end_flush(); }
add_action('wp_footer', 'buffer_end');
?>