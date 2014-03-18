<?php
/**
 * Load Fancybox main and supporting files
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

if( function_exists( 'ot_load_fancybox_js' ) ) :

  function ot_load_fancybox_js() {
    wp_register_script('fancybox_js', get_template_directory_uri().'/lib/modules/fancybox/jquery.fancybox.pack.js', array("jquery"), '2.1.5', true);
    wp_enqueue_script('fancybox_js');
  }
  add_action( 'init', 'ot_load_fancybox_js');

endif;

if( function_exists( 'ot_load_fancybox_css' ) ) :

  function ot_load_fancybox_css() {
    wp_register_style('fancybox_css', get_template_directory_uri().'/lib/modules/fancybox/jquery.fancybox.css', array(), '2.1.5', 'all' );
    wp_enqueue_style('fancybox_css');
  }
  add_action( 'wp_enqueue_scripts', 'ot_load_fancybox_css', 1 );

endif;
