<?php

/**
 * Load Bootstrap main files and supporting files
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

//only load if not in admin
if( !is_admin() ) :

  //require navwalker code to make bootstrap menu work with wordpress

  require_once dirname( __FILE__ ) . '/wp_bootstrap_navwalker.php';
  require_once dirname( __FILE__ ) . '/navigation.php';


  //enqueue bootstap css and js files to load with the template

	if ( !function_exists( 'ot_load_bootstrap_js' ) ) :

    function ot_load_bootstrap_js() {
  		wp_register_script( 'bootstrap_js', get_template_directory_uri() . '/lib/modules/bootstrap/js/bootstrap.js', array( 'jquery' ), '3.1.1', true );
  		wp_enqueue_script( 'bootstrap_js' );
  	}

    add_action( 'init', 'ot_load_bootstrap_js');

	endif;


	if ( !function_exists( 'ot_load_bootstrap_css' ) ) :

	function ot_load_bootstrap_css() {
		wp_register_style( 'bootstrap_css', get_template_directory_uri() . '/lib/modules/bootstrap/css/bootstrap.css', array(), '3.1.1', 'all' );
		wp_enqueue_style( 'bootstrap_css' );
	}
	add_action( 'wp_enqueue_scripts', 'ot_load_bootstrap_css', 1 );


	endif;

endif;
