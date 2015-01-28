<?php
namespace library\config;

class Init {
		
	public function __construct() {
		add_action( 'init', array( $this, 'registerPostTypes' ) );
		add_action( 'init', array( $this, 'registerTaxonomies' ) );
		register_activation_hook( __FILE__, 'my_rewrite_flush' );
		add_action( 'init', array( $this, 'appendPageTypeData' ) );
		add_action( 'init', array( $this, 'deregisterJS' ) );
		add_action( 'init', array( $this, 'registerJS' ) );
		add_action( 'init', array( $this, 'deregisterCSS') );
		add_action( 'init', array( $this, 'registerCSS' ) );
		add_action( 'admin_menu', array( $this, 'scaffoldMenu' ) );
		add_action( 'admin_menu', array( $this, 'hideAdminDashboardWidgets' ) );
		
		$this->getShowAdminBar();
		$this->getEnableFeaturedImages();
		$this->getMultiFeaturedImages();
		return true;
	}
		
	protected function getShowAdminBar() {	
		$ns_theme = ns_theme();
		$showAdminBar = $ns_theme::getShowAdminBar();
		
		if ( !isset($showAdminBar) || !is_bool($showAdminBar) )	 { $showAdminBar = False; }
		
		show_admin_bar($showAdminBar);
	}
	
	public function hideAdminDashboardWidgets() {
		$ns_theme = ns_theme();
		$hideAdminDashboardWidgets = $ns_theme::getHideAdminDashboardWidgets();

		foreach ( $hideAdminDashboardWidgets as $value ) {
				remove_meta_box( $value, 'dashboard', 'core' );
		}
	}
		
	protected function getEnableFeaturedImages() {
		$ns_theme = ns_theme();
		$registerPostTypes = $ns_theme::getRegisterPostTypes();

		$posttypes = array();
		
		foreach ( $registerPostTypes as $key => $value ) {
			if ( isset( $value['supports'] ) ) {
				foreach ( $value['supports'] as $support ) {
					if ( $support == 'thumbnail' ) {
						$posttypes[] = $key;
						
						if ( isset( $value['image-sizes'] ) ) {
							foreach ( $value['image-sizes'] as $size ) {
									call_user_func_array('add_image_size', $size);
							}
						}
					}
				}
			}
		}
			
		add_theme_support( 'post-thumbnails', $posttypes );
	}
		
	protected function getMultiFeaturedImages() {
		$ns_theme = ns_theme();
		$multiFeaturedImages = $ns_theme::getMultiFeaturedImages();
		
		if ( class_exists( 'kdMultipleFeaturedImages' ) && is_array( $multiFeaturedImages ) ) {
			foreach ( $multiFeaturedImages as $args ) {
					new kdMultipleFeaturedImages( $args );
			}
		}
	}
		
	/* Deregister JS scripts to prevent them loading. */
	public function deregisterJS() {
		$ns_theme = ns_theme();
		$deregisterJS = $ns_theme::getDeregisterJS();
		foreach ( $deregisterJS as $key => $value ) {
			if ( $key == "admin" && is_admin() ) {
					wp_deregister_script( $value );
			}
			
			if ( $key == "site" && !is_admin() ) {
					wp_deregister_script( $value );
			}
		}
	}
		
	/* Register JS scripts for loading. */
	public function registerJS() {
		$ns_theme = ns_theme();
		$registerJS = $ns_theme::getRegisterJS();
	
		foreach ( $registerJS as $section => $scripts ) {
			foreach ( $scripts as $id => $script ) {
				@$script[2] = trim(esc_html($script[2]));
				
				/* If file version isn't set then assign Theme Version value instead. */
				if ( !isset($script[2]) || empty($script[2]) || !$script[2] ) {
						$script[2] = trim(esc_html(THEME_VERSION));
				}
				
				if ( $section == "admin" && is_admin() ) {
						wp_register_script( $id, THEME_URI . '/js/' . $script[0], @$script[1], $script[2], @$script[3] );
						wp_enqueue_script( $id );
				}
				
				if ( $section == "site" && !is_admin() ) {
						wp_register_script( $id, THEME_URI . '/js/' . $script[0], @$script[1], $script[2], @$script[3] );
						wp_enqueue_script( $id );
				}
			}
		}
	}
		
	/* Deregister CSS scripts to prevent them loading. */
	public function deregisterCSS() {
		$ns_theme = ns_theme();
		$deregisterCSS = $ns_theme::getDeregisterCSS();

		foreach ( $deregisterCSS as $key => $value ) {
			if ( $key == "admin" && is_admin() ) {
					wp_deregister_style( $value );
			}
			if ( $key == "site" && !is_admin() ) {
					wp_deregister_style( $value );
			}
		}
	}
		
	/* Register CSS scripts for loading. */
	public function registerCSS() {
		$ns_theme = ns_theme();
		$registerCSS = $ns_theme::getRegisterCSS();

		foreach ( $registerCSS as $section => $scripts ) {
			foreach ( $scripts as $id => $script ) {
				$script[2] = trim(esc_html(@$script[2]));

				/* If file version isn't set then assign Theme Version value instead. */
				if ( !isset($script[2]) || empty($script[2]) || !$script[2] ) {
						$script[2] = trim(esc_html(THEME_VERSION));
				}
				
				if ( $section == "admin" && is_admin() ) {
						wp_register_style( $id, THEME_URI . '/css/' . $script[0], @$script[1], $script[2] );
						wp_enqueue_style( $id );
				}
				
				if ( $section == "site" && !is_admin() ) {
						wp_register_style( $id, THEME_URI . '/css/' . $script[0], @$script[1], $script[2] );
						wp_enqueue_style( $id );
				}
			}
		}
	}
		
	public function registerPostTypes() {
		$ns_theme = ns_theme();
		$registerPostTypes = $ns_theme::getRegisterPostTypes();
		$registerTaxonomies = $ns_theme::getRegisterTaxonomies();
		
		if ( is_array( $registerPostTypes ) ) {
			foreach ( $registerPostTypes as $key => $value ) {
				if ( isset( $value['disable'] ) ) {
					if ( $value['disable'] === false && $key != 'page' ) {
						register_post_type( $key, $value );
					}
					
					$rules = array();
					if ( $value['disable'] === false && isset( $value['rewrite']['slug'] ) && trim( $value['rewrite']['slug'] ) != '' ) {
						$slug = $value['rewrite']['slug'] . '/';
						if ( $slug == 'search/' ) { $slug = ""; }
						
						$rules = array(
							array( $slug . 'search/page/?([0-9]{1,})/?', 'index.php?post_type=' . $key . '&paged=$matches[1]', 'top' ),
							array( $slug . 'search/?$', 'index.php?post_type=' . $key . '&type=search', 'top' ),
						);
						
						if ( isset( $registerTaxonomies ) && is_array( $registerTaxonomies ) ) {
							foreach ( $registerTaxonomies as $tax ) {
								if ( $tax[1] === $key ) {
									if ( isset( $tax[2]['rewrite']['slug'] ) && is_string( $tax[2]['rewrite']['slug'] ) ) {
										$rules[] = array( $tax[2]['rewrite']['slug'] . '/(.+?)/page/?([0-9]{1,})/?', 'index.php?post_type=' . $key . '&mycat=' . $tax[0] . '&category_name=$matches[1]&paged=$matches[2]', 'top' );
										$rules[] = array( $tax[2]['rewrite']['slug'] . '/(.+?)/?$', 'index.php?post_type=' . $key . '&mycat=' . $tax[0] . '&category_name=$matches[1]', 'top' );
									}
								}
							}
						}
						
						if ( isset( $value['rules'] ) && is_array( $value['rules'] ) ) {
							$rules = array_unique(array_merge($rules, $value['rules']), SORT_REGULAR);
						}
						
						foreach ( $rules as $rule ) {
								if ( is_array( $rule ) && count( $rule ) == 3 ) {
										call_user_func_array( 'add_rewrite_rule', $rule );
								}
						}
					}
					
					if ( $value['disable'] === true ) {
						if ( $key == 'post' ) {
								add_action( 'admin_menu', array( $this, 'remove_menu_posts' ) );
								//do_action( 'admin_menu', 'edit.php' ); // Can't get do_action to work here.
						}
						
						if ( $key == 'page' ) {
								add_action( 'admin_menu', array( $this, 'remove_menu_pages' ) );
								//do_action( 'admin_menu', 'edit.php?post_type=page' ); // Can't get do_action to work here.
						}
					}
				}
			}
		}
	}
		
	public function registerTaxonomies() {
		$ns_theme = ns_theme();
		$registerTaxonomies = $ns_theme::getRegisterTaxonomies();

		if ( is_array( $registerTaxonomies ) ) {
			foreach ( $registerTaxonomies as $taxonomy ) {
				if ( is_string( $taxonomy[0] ) && is_string( $taxonomy[1] ) && is_array( $taxonomy[2] ) ) {
					register_taxonomy( $taxonomy[0], $taxonomy[1], $taxonomy[2] );
				}
			}
		}
	}
		
	function appendPageTypeData() {
		global $wp_post_types;
		$ns_theme = ns_theme();
		$injectPageTypeData = $ns_theme::getInjectPageTypeData();
		$wp_post_types['page']->pages = $injectPageTypeData;
	}
		
	public function my_rewrite_flush() {
		registerPostTypes();
		flush_rewrite_rules();
	}
	
	function remove_menu_posts() {
		remove_menu_page('edit.php');
	}
	
	function remove_menu_pages() {
		remove_menu_page('edit.php?post_type=page');
	}
	
	function scaffoldMenu() {
		add_menu_page( 'Theme Settings', 'Theme Settings', 'delete_pages', 'scaffold','settings_page',get_bloginfo('stylesheet_directory').'/img/icons/barrel.png');
	}
}
?>