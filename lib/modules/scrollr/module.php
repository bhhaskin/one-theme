<?php

/**
 * Load scroler js file
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

if( !is_admin() ) :

	if( !function_exists( 'ot_load_scrollr_js' ) ) :

		function ot_load_scrollr_js() {
			wp_register_script('scrollr_js', get_template_directory_uri().'/lib/modules/scrollr/js/skrollr.js', array("jquery"), '0.6.22', true);
			wp_enqueue_script('scrollr_js');
		}

		add_action( 'init', 'ot_load_scrollr_js');

	endif;

endif;
