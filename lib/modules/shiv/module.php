<?php

/**
 * Load respond and shiv if using an IE browser
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */


if( !is_admin() ) :

	if ( !function_exists( 'ot_load_shives_js' ) ) :

	function ot_load_shives_js() {
		wp_register_script('respond_js', get_template_directory_uri().'/lib/modules/shiv/js/respond.min.js', array("jquery"), '1.3.0', false);
		wp_register_script('respondmatchmedia_js', get_template_directory_uri().'/lib/modules/shiv/js/respond.matchmedia.addListener.min.js', array("jquery"), '1.3.0', false);
		wp_register_script('modernizer_js', get_template_directory_uri().'/lib/modules/shiv/js/modernizer.min.js', array("jquery"), '1.3.0', false);
		wp_register_script('shiv_js', get_template_directory_uri().'/lib/modules/shiv/js/html5shiv.js', array(), '1.3.0', false);
	}

	add_action( 'init', 'ot_load_shives_js');

	endif;

	if ( !function_exists( 'ot_load_shiv_conditon' ) ) :

		function ot_load_shiv_conditon() {
		global $is_IE;
		if($is_IE) {
			wp_enqueue_script('shiv_js');
			wp_enqueue_script('respondmatchmedia_js');
			wp_enqueue_script('respond_js');

			wp_enqueue_script('modernizer_js');
		}
	}
	add_action('wp_print_scripts', 'ot_load_shiv_conditon');

	endif;

endif;
