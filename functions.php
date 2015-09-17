<?php

/**
 * Theme Functions
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.4
 * @author Matthew Hansen & Bryan Haskin
 */



function otParentFunctions() {
	require_once dirname( __FILE__ ) . '/lib/modules/core/module.php'; // Loader
	add_filter('navbar_brand', 'otParent_Brand', 1);
}

add_action( 'after_setup_theme', 'otParentFunctions', 9 );

function otc_load_main_js() {
        wp_register_script( 'main_js', get_template_directory_uri().'/main.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'main_js' );
}

add_action( 'wp_enqueue_scripts', 'otc_load_main_js', 12 );

function otParent_Brand() {
	$hurl = home_url('/');
    $blog_title = get_bloginfo('name');
    return '<a id="brand" class="navbar-brand" href="'.$hurl.'">'. $blog_title .'</a>';
}

function new_excerpt_more( $more ) { return '...'; } add_filter('excerpt_more', 'new_excerpt_more');
