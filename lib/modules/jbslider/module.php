<?php
/**
 * Load jcobb basic jquery sliders js file
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

if( !function_exists( 'ot_load_jbslider_js' ) ) :

  function ot_load_jbslider_js() {
    wp_register_script( 'jbslider_js', get_template_directory_uri().'/lib/modules/jbslider/js/bjqs-1.3.min.js', array( 'jquery' ), '1.3.0', true );
    wp_enqueue_script( 'jbslider_js' );
  }
  add_action( 'init', 'ot_load_jbslider_js' );

endif;
