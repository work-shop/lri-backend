<?php
/*
Plugin Name: Grey Admin Color Schemes
Plugin URI: http://toolstack.com/grey-admin-color-schemes
Description: Grey color schemes for WordPress 3.8's admin area.
Version: 1.7
Author: Greg Ross
Author URI: http://toolstack.com/
Text Domain: grey-admin-color-schemes
Domain Path: /languages/

Compatible with WordPress 3.8+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2013-2015 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details
*/

/*
	Register color schemes.
*/
function GACSAddColorSchemes() 
	{
	$rtl = is_rtl() ? '-rtl' : '';

	wp_admin_css_color(	'classicgrey', __('Classic Grey', 'grey-admin-color-schemes'), plugins_url("classic/colors$rtl.css", __FILE__ ), array( '#464646', '#6D6D6D', '#F1F1F1', '#21759B' ) );
	wp_admin_css_color(	'moderngrey', __('Modern Grey', 'grey-admin-color-schemes'), plugins_url("modern/colors$rtl.css", __FILE__ ), array( '#464646', '#6D6D6D', '#D6D6D6', '#21759B' ) );
	wp_admin_css_color( 'atouchofblue', __('A Touch of Blue', 'grey-admin-color-schemes'), plugins_url("atouchofblue/colors$rtl.css", __FILE__ ), array( '#464646', '#6D6D6D', '#D6D6D6', '#0074A2' ) );
	wp_admin_css_color( 'postmodern', __('Post Modern Grey', 'grey-admin-color-schemes'), plugins_url("postmodern/colors$rtl.css", __FILE__ ), array( '#464646', '#6D6D6D', '#D6D6D6', '#F1F1F1' ) );
	}

add_action( 'admin_init' , 'GACSAddColorSchemes' );

// Load the internationalization code.
function GACSInit() {
	load_plugin_textdomain('grey-admin-color-schemes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	__('Grey Admin Color Schemes', 'grey-admin-color-schemes');
	__('Grey color schemes for WordPress 3.8\'s admin area.', 'grey-admin-color-schemes');
}

// Add actions
add_action('init', 'GACSInit');
