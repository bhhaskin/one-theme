<?php

/**
 * Load Font Awesome main file
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

if( !is_admin() ) :

	if ( !function_exists( 'ot_load_fontawesome_css' ) ) :

	function ot_load_fontawesome_css() {
		wp_register_style('fontawesome_css', get_template_directory_uri().'/lib/modules/font-awesome/css/font-awesome.min.css', array(), '4.0.3', 'all' );
		wp_enqueue_style('fontawesome_css');
	}
	add_action( 'wp_enqueue_scripts', 'ot_load_fontawesome_css', 1 );


	endif;
else:
	function ot_load_fontawesome_css() {
		wp_register_style('fontawesome_css', get_template_directory_uri().'/lib/modules/font-awesome/css/font-awesome.min.css', array(), '4.0.3', 'all' );
		wp_enqueue_style('fontawesome_css');
	}
	add_action( 'admin_enqueue_scripts', 'ot_load_fontawesome_css', 1 );
endif;
