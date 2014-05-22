<?php

/**
 * Theme Functions
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

require_once dirname( __FILE__ ) . '/lib/modules/loadModules.php';
require_once get_template_directory() . '/lib/core.php';

global $theOne;
$theOne = new TheOne;
$theOne->loadModules();


if( !is_admin() ) :
  $theOne->assets();

	//Load main.js / this is the parent themes custom js file.

	if( !function_exists( 'load_main_js' ) ) :

		function load_main_js() {
			wp_register_script( 'main_js', get_template_directory_uri().'/main.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'main_js' );
		}

		add_action( 'init', 'load_main_js' );

	endif;

endif;
