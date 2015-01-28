<?php

namespace development;

class config {

	private static 
		$showAdminBar, 
		$hideAdminDashboardWidgets,
		$deregisterJS, 
		$registerJS, 
		$deregisterCSS, 
		$registerCSS,
		$registerPageTypes,
		$registerPostTypes,
		$injectPageTypeData,
		$registerTaxonomies,
		$multiFeaturedImages,
		$registerPostTypesLabels,
		$other;
	
	public static function init() {
	
		/* Display Wordpress Admin Bar */
		self::$showAdminBar = False;
		
		/* Hide admin dashboard widgets by widget name. */
		self::$hideAdminDashboardWidgets = array('dashboard_incoming_links', 'dashboard_plugins', 'dashboard_primary', 'dashboard_secondary');
		
		/* Deregister JS Scripts. Note this runs before registerJS. */
		self::$deregisterJS = array(
			'site' => array('jquery', 'backbone', 'underscore'),
			'admin' => array()
		);
		
		/* Register JS Scripts. Note this runs after deregisterJS. */
		self::$registerJS = array(
			'site' => array(
				'modernizer' => array('src/vendor/modernizr.min.js', false, false, false),
				'main' => array('dist/main.min.js', false, false, true),
			)
		);
		
		/* Deregister CSS Scripts. Note this runs before registerCSS. */
		self::$deregisterCSS = array(
			'site' => array(),
			'admin' => array()
		);
		
		/* Register CSS Scripts. Note this runs after deregisterCSS. */
		self::$registerCSS = array(
			'site' => array(
				'main' => array('styles.min.css'),
			),
			'admin' => array(
				'admin' => array('admin.min.css'),
			)
		);
		
		/*
		 * List of allowed "page types" for this theme. 
		 * All other non-registered page types will be 404'd.
		 */
		
		self::$registerPageTypes = array( 'home', 'page', 'single', 'preview', 'archive', 'author', 'attachment', 'search', '404' );
			
		self::$registerPostTypesLabels = array(
			
			'example_label'	 => array(
				'name'								=> __( 'Example Labels'),
				'singular_name'			 => __( 'Example Label'),
				'menu_name'					 => __( 'Example Labels'),
				'name_admin_bar'		 => __( 'Example Label'),
				'add_new'						 => __( 'Add New', 'Example Label'),
				'add_new_item'			 => __( 'Add New Example Label'),
				'new_item'					 => __( 'New Example Label'),
				'edit_item'					 => __( 'Edit Example Label'),
				'view_item'					 => __( 'View Example Label'),
				'all_items'					 => __( 'All Example Labels'),
				'search_items'			 => __( 'Search Example Labels'),
				'parent_item_colon'	 => __( 'Parent Example Labels:'),
				'not_found'					 => __( 'No Example Labels found.'),
				'not_found_in_trash' => __( 'No Example Labels found in Trash.')
			),
		);
		
		/* List of allow "post types".
		 * 
		 */
		self::$registerPostTypes = array(
			'post' => array(
				'disable' => false,
				'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
			),
			'page' => array(
				'disable' => false,
				'supports' => array('title', 'editor', 'thumbnail', 'author'),
				'meta-boxes' => array(
						array('id' => 'default_subtitle', 'priority' => 'high'),						 
				),
			),
			'author' => array(
				'disable' => true,
				'public' => false,
				'has_archive' => true,
				'hierarchical' => false,
				'show_ui' => false,
				'show_in_nav_menus' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'author', 'with_front' => true ),
				'rules' => array(),
			),
			'custom_post_type' => array(
				'labels'	=> self::$registerPostTypesLabels['example_label'],
				'disable' => false,
				'public' => true,
				'has_archive' => true,
				'hierarchical' => false,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'custom_post_type', 'with_front' => true ),
				'rules' => array(),
			),
		);
			
		self::$injectPageTypeData = array(
			'default' => array(
					'widgets' => array(),
			),
			// Custom loop is overriden (causing issues with paging, etc) if placed in another archive type page. So we create some placeholder pages instead. 
		);
		
		self::$registerTaxonomies = array();
		
		/* Add multiple featured images. Requires "Multiple Featured Images" plugin to work. */
		self::$multiFeaturedImages = array(
			array(
				'id' => 'test-id',
				'post_type' => 'test-post-type',
				'labels' => array(
						'name'			=> 'Image',
						'set'				=> 'Set image',
						'remove'		=> 'Remove image',
						'use'				=> 'Use as image',
				)
			)
		);
			
		/* Other non-wordpress or custom options that don't belong anywhere else! */
		self::$other = array();
	}
	
	public static function getShowAdminBar() {
		self::init();
		return self::$showAdminBar;
	}
	
	public static function getHideAdminDashboardWidgets() {
		self::init();
		return self::$hideAdminDashboardWidgets;
	}
	
	public static function getDeregisterJS() {
		self::init();
		return self::$deregisterJS;
	}
	
	public static function getRegisterJS() {
		self::init();
		return self::$registerJS;
	}
	
	public static function getDeregisterCSS() {
		self::init();
		return self::$deregisterCSS;
	}
	
	public static function getRegisterCSS() {
		self::init();
		return self::$registerCSS;
	}
	
	public static function getRegisterPageTypes() {
		self::init();
		return self::$registerPageTypes;
	}
	
	public static function getRegisterPostTypes() {
		self::init();
		return self::$registerPostTypes;
	}
	
	public static function getInjectPageTypeData() {
		self::init();
		return self::$injectPageTypeData;
	}

	public static function getRegisterTaxonomies() {
		self::init();
		return self::$registerTaxonomies;
	}
	
	public static function getMultiFeaturedImages() {
		self::init();
		return self::$multiFeaturedImages;
	}
	
	public static function getOther() {
		self::init();
		return self::$other;
	}
}

?>