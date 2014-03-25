<?php
/**
 * Load full width jquery slider js file
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

if( !function_exists( 'ot_load_unslider_js' ) ) :

  function ot_load_unslider_js() {
    wp_register_script( 'unslider_js', get_template_directory_uri().'/lib/modules/unslider/js/unslider.min.js', array( 'jquery' ), '1.3.0', true );
    wp_enqueue_script( 'unslider_js' );
  }
  add_action( 'init', 'ot_load_unslider_js' );

endif;
