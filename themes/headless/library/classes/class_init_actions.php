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
				'admin_init'            => 'admin_setup'
				));
	}

	/** POST TYPES AND OTHER INIT ACTIONS */
	public function setup() {

		//add additional featured image sizes
		//NOTE: avoid hyphens in names as they could create errors in frontend
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'xs', 300, 300, false );
			add_image_size( 'sm', 600, 600, false );
			add_image_size( 'md', 900, 900, false );
			add_image_size( 'lg', 1200, 1200, false );
			add_image_size( 'xl', 1920, 1920, false );
			add_image_size( 'facebook_small', 600, 315, true );
			add_image_size( 'facebook', 1200, 630, true );
			add_image_size( 'person', 500, 500, true );
			add_image_size( 'hero_small', 560, 179, true );
			add_image_size( 'hero', 1680, 550, true );

		}

		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}


		//register post types
		//optional - include a custom icon, list of icons available at https://developer.wordpress.org/resource/dashicons/

		//people
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

		register_taxonomy(
			'people_categories',
			'people',
			array(
				'hierarchical' => true,
				'label' => 'People Categories',
				'query_var' => true,
				'rewrite' => array('slug' => 'people_categories'),
				'rest_base'          => 'people_categories',
				'rest_controller_class' => 'WP_REST_Terms_Controller',
				)
			);
		global $wp_taxonomies;
		$taxonomy_name = 'people_categories';
		if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
			$wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
			$wp_taxonomies[ $taxonomy_name ]->rest_base = $taxonomy_name;
			$wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
		}

		//news
		register_post_type( 'news',
			array(
				'labels' => array(
					'name' => 'News',
					'singular_name' =>'News Story',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New News Story',
					'edit_item' => 'Edit News Story',
					'new_item' => 'New News Story',
					'all_items' => 'All News Stories',
					'view_item' => 'View News Stories',
					'search_items' => 'Search News Stories',
					'not_found' =>  'No News Stories found',
					'not_found_in_trash' => 'No News Stories found in Trash',
					),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'news'),
				'show_in_rest'       => true,
				'rest_base'          => 'news',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'supports' => array( 'title', 'thumbnail', 'editor'),
				'menu_icon'	=>	'dashicons-welcome-widgets-menus'
				));

		register_taxonomy(
			'news_categories',
			'news',
			array(
				'hierarchical' => true,
				'label' => 'News Categories',
				'query_var' => true,
				'rewrite' => array('slug' => 'news_categories'),
				'rest_base'          => 'news_categories',
				'rest_controller_class' => 'WP_REST_Terms_Controller',
				)
			);
		global $wp_taxonomies;
		$taxonomy_name = 'news_categories';
		if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
			$wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;
			$wp_taxonomies[ $taxonomy_name ]->rest_base = $taxonomy_name;
			$wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
		}

		//jobs
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
				'supports' => array( 'title', 'thumbnail', 'editor'),
				'menu_icon'   => 'dashicons-clipboard'
				));

		// //form pages
		// register_post_type( 'form_pages',
		// 	array(
		// 		'labels' => array(
		// 			'name' => 'Form Pages',
		// 			'singular_name' => 'Form Page',
		// 			'add_new' => 'Add New',
		// 			'add_new_item' => 'Add New Form Page',
		// 			'edit_item' => 'Edit Form Page',
		// 			'new_item' => 'New Form Page',
		// 			'all_items' => 'All Form Pages',
		// 			'view_item' => 'View Form Page',
		// 			'search_items' => 'Search Form Pages',
		// 			'not_found' =>  'No Form Pages found',
		// 			'not_found_in_trash' => 'No Form Pages found in Trash',
		// 			),
		// 		'public' => true,
		// 		'has_archive' => true,
		// 		'rewrite' => array('slug' => 'formpages'),
		// 		'show_in_rest'       => true,
		// 		'rest_base'          => 'formpages',
		// 		'rest_controller_class' => 'WP_REST_Posts_Controller',
		// 		'supports' => array( 'title'),
		// 		'menu_icon'   => 'dashicons-editor-table'
		// 		));	

		// //newsletter
		// register_post_type( 'newsletters',
		// 	array(
		// 		'labels' => array(
		// 			'name' => 'Newsletters',
		// 			'singular_name' => 'Newsletter',
		// 			'add_new' => 'Add New',
		// 			'add_new_item' => 'Add New Newsletter',
		// 			'edit_item' => 'Edit Newsletter',
		// 			'new_item' => 'New Newsletter',
		// 			'all_items' => 'All Newsletters',
		// 			'view_item' => 'View Newsletter',
		// 			'search_items' => 'Search Newsletters',
		// 			'not_found' =>  'No Newsletters found',
		// 			'not_found_in_trash' => 'No Newsletters found in Trash',
		// 			),
		// 		'public' => true,
		// 		'has_archive' => true,
		// 		'rewrite' => array('slug' => 'newsletters'),
		// 		'show_in_rest'       => true,
		// 		'rest_base'          => 'newsletters',
		// 		'rest_controller_class' => 'WP_REST_Posts_Controller',
		// 		'supports' => array( 'title'),
		// 		'menu_icon'   => 'dashicons-images-alt2'
		// 		));						

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
				'page_title' 	=> 'Site Options',
				'menu_title' 	=> 'Site Options',
				'menu_slug' 	=> 'site-options',
				'icon_url'      => 'dashicons-location',
				'position'		=> '50.3'
				));
		}

	}

	/* CUSTOM MENU LINK FOR ALL SETTINGS - WILL ONLY APPEAR FOR ADMIN */
	public function all_settings_link() {
		add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
	}

	/** ADMIN DASHBOARD ASSETS */
	public function login_css() {
		wp_enqueue_style( 'login_css', get_template_directory_uri() . '/assets/css/login.css' ); }

		public function admin_css() {
			if( current_user_can( 'update_core' ) ){
				wp_enqueue_style( 'admin_hide_toolbar', get_template_directory_uri() . '/assets/css/admin-hide-toolbar.css' );

			//GET RID OF THIS LATER!!!!!
				wp_enqueue_style( 'admin_hide_update_nag', get_template_directory_uri() . '/assets/css/admin-hide-update-nag.css' );
			} else{
				wp_enqueue_style( 'admin_hide_update_nag', get_template_directory_uri() . '/assets/css/admin-hide-update-nag.css' );
			}
			wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/assets/css/admin.css' );
		}

    /**
     * Admin setup registers additional settings on the global options page for us.
     *
     * TODO: Need to update the `register_setting` function to take an array in the third parameter – once we're able to update to 4.7.3
     * That API is not available in 4.6.3
     */
    public function admin_setup() {
    	register_setting(
    		'general',
    		'cdn_url'
    		);

    	add_settings_field(
    		'cdn_url',
    		'CDN Address (URL)',
    		array( $this, 'render_settings_field' ),
    		'general',
    		'default',
    		array( 'cdn_url', get_option('cdn_url') )
    		);
    }

    /**
     * Callback function to render the CDN URL field in the options.
     *
     * @param $args array the array of value arguments
     *
     */
    public function render_settings_field( $args ) {
    	echo "<input aria-describedby='cdn-description' name='cdn_url' class='regular-text code' type='text' id='" . $args[0] . "' value='" . $args[1] . "'/>";
    	echo "<p id='cdn-description' class='description'>Input the url of the CDN to use with this site or leave this field blank to bypass the CDN.";
    }




}

new WS_Init_Actions();
