<?php

class WS_Init_Actions extends WS_Action_Set {

	/**
	 * Constructor
	 */
	public function __construct() {
		show_admin_bar(false);

		parent::__construct(
			array(
				'init' 					=> 'setup',
				'after_theme_setup'		=> array( 'remove_post_formats', 11, 0 ),
				'login_head'			=> 'login_css',
				'admin_head'			=> 'admin_css',
				'admin_menu'			=> 'all_settings_link',
				));
	}

	/** POST TYPES AND OTHER INIT ACTIONS */
	public function setup() {

		//add additional featured image sizes
		//NOTE: wordpress will allow hyphens in these names, but swig or the API(i'm not sure) will not
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'person', 500, 500, false );
			add_image_size( 'news', 512, 275, true );
			add_image_size( 'story', 400, 286, true );
			add_image_size( 'testimonial', 1024, 550, true );	
			add_image_size( 'projectslideshow', 1440, 1440, false );
			add_image_size( 'category', 1680, 600, true );
			add_image_size( 'hero', 1680, 1050, false );
		}

		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}


		//register post types
		//optional - include a custom icon, list of icons available at https://developer.wordpress.org/resource/dashicons/
		register_post_type( 'projects',
			array(
				'labels' => array(
					'name' => 'Projects',
					'singular_name' =>'Project',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Project',
					'edit_item' => 'Edit Project',
					'new_item' => 'New Project',
					'all_items' => 'All Projects',
					'view_item' => 'View Project',
					'search_items' => 'Search Projects',
					'not_found' =>  'No Projects found',
					'not_found_in_trash' => 'No Projects found in Trash',
					),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'projects'),
				'show_in_rest'       => true,
				'rest_base'          => 'projects',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title', 'thumbnail'),
				'menu_icon'   => 'dashicons-building'
				));

		register_taxonomy(
			'project_categories',
			'projects',
			array(
				'hierarchical' => true,
				'label' => 'Project Categories',
				'query_var' => true,
				'rewrite' => array('slug' => 'project_categories'),
				'rest_base'          => 'project_categories',
				'rest_controller_class' => 'WP_REST_Terms_Controller',
				)
			);

		global $wp_taxonomies;
		$taxonomy_name = 'project_categories';

		if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
			$wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
			$wp_taxonomies[ $taxonomy_name ]->rest_base = $taxonomy_name;
			$wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
		}

		register_post_type( 'people',
			array(
				'labels' => array(
					'name' => 'People',
					'singular_name' =>'Person',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Person',
					'edit_item' => 'Edit Person',
					'new_item' => 'New Person',
					'all_items' => 'All People',
					'view_item' => 'View Person',
					'search_items' => 'Search People',
					'not_found' =>  'No People found',
					'not_found_in_trash' => 'No People found in Trash',
					),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'people'),
				'show_in_rest'       => true,
				'rest_base'          => 'people',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title', 'thumbnail'),
				'menu_icon'   => 'dashicons-id'
				));

		register_post_type( 'news',
			array(
				'labels' => array(
					'name' => 'News',
					'singular_name' =>'News Item',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New News Item',
					'edit_item' => 'Edit News Item',
					'new_item' => 'New News Item',
					'all_items' => 'All News Items',
					'view_item' => 'View News Item',
					'search_items' => 'Search News Items',
					'not_found' =>  'No News Items found',
					'not_found_in_trash' => 'No News Items found in Trash',
					),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'news'),
				'show_in_rest'       => true,
				'rest_base'          => 'news',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title', 'thumbnail'),
				'menu_icon'	=>	'dashicons-welcome-widgets-menus'
				));

		register_post_type( 'about',
			array(
				'labels' => array(
					'name' => 'Info Pages',
					'singular_name' => 'Info Page',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Info Page',
					'edit_item' => 'Edit Info Page',
					'new_item' => 'New Info Page',
					'all_items' => 'All Info Pages',
					'view_item' => 'View Info Page',
					'search_items' => 'Search Info Pages',
					'not_found' =>  'No Info Pages found',
					'not_found_in_trash' => 'No Info Pages found in Trash',
					),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'about'),
				'show_in_rest'       => true,
				'rest_base'          => 'about',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title', 'thumbnail'),
				'menu_icon'   => 'dashicons-admin-page'
				));

		register_post_type( 'jobs',
			array(
				'labels' => array(
					'name' => 'Jobs',
					'singular_name' => 'Job',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Job',
					'edit_item' => 'Edit Job',
					'new_item' => 'New Job',
					'all_items' => 'All Jobs',
					'view_item' => 'View Job',
					'search_items' => 'Search Jobs',
					'not_found' =>  'No Jobs found',
					'not_found_in_trash' => 'No Jobs found in Trash',
					),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'jobs'),
				'show_in_rest'       => true,
				'rest_base'          => 'jobs',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title'),
				'menu_icon'   => 'dashicons-clipboard'
				));

		//add ACF options pages
		//optional - include a custom icon, list of icons available at https://developer.wordpress.org/resource/dashicons/
		if( function_exists('acf_add_options_page') ) {
			$option_page = acf_add_options_page(array(
				'page_title' 	=> 'Home Page',
				'menu_title' 	=> 'Home Page',
				'menu_slug' 	=> 'home-page',
				'icon_url'      => 'dashicons-admin-home',
				'position'		=> '50.1',
				));
			$option_page = acf_add_options_page(array(
				'page_title' 	=> 'About Page',
				'menu_title' 	=> 'About Page',
				'menu_slug' 	=> 'about-page',
				'icon_url'      => 'dashicons-index-card',
				'position'		=> '50.3',
				));
			$option_page = acf_add_options_page(array(
				'page_title' 	=> 'Work Page',
				'menu_title' 	=> 'Work Page',
				'menu_slug' 	=> 'work-page',
				'icon_url'      => 'dashicons-screenoptions',
				'position'		=> '50.5'
				));
			$option_page = acf_add_options_page(array(
				'page_title' 	=> 'General Information',
				'menu_title' 	=> 'General Information',
				'menu_slug' 	=> 'general-information',
				'icon_url'      => 'dashicons-location',
				'position'		=> '50.7'
				));
		}


	}

	/* CUSTOM MENU LINK FOR ALL SETTINGS - WILL ONLY APPEAR FOR ADMIN */
	public function all_settings_link() {
		add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
	}

	/** ADMIN DASHBOARD ASSETS */
	public function login_css() { wp_enqueue_style( 'login_css', get_template_directory_uri() . '/assets/css/login.css' ); }
	public function admin_css() { wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/assets/css/admin.css' ); }

}

new WS_Init_Actions();
