<?php

/**
 * Theme Functions
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen & Bryan Haskin
 */

function otParentFunctions() {
	require_once dirname( __FILE__ ) . '/lib/modules/core/module.php'; // Loader
	$masterControl = oneTheme\MasterControl::getInstance();
}

add_action( 'after_setup_theme', 'otParentFunctions', 9 );

function otc_load_main_js() {
        wp_register_script( 'main_js', get_template_directory_uri().'/main.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'main_js' );
}

add_action( 'init', 'otc_load_main_js', 12 );
