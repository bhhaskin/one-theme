<?php
/**
 * Load all the settings for theme
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support('nav-menus');

define( 'PARENTPATH', get_template_directory_uri() );
define( 'TEMPPATH', get_bloginfo( 'stylesheet_directory' ) );
define( 'PIMAGES', PARENTPATH . '/images' );
define( 'IMAGES' , TEMPPATH . '/images' );

//register navigation

if( function_exists( 'register_nav_menus' ) ) :

  register_nav_menus(
 		array(
 			'main' => 'Main Nav',
       'top' => 'Top Menu',
       'foot' => 'Footer Menu'
 			)
 		);

endif;

if( function_exists( 'register_sidebar' ) ) :

  register_sidebar( array (
		'name' => __('Standard Sidebar', 'standard-sidebar'),
		'id' => 'standard-sidebar',
		'description' => __('Standard Sidebar', 'dir'),
		'before_widget' => '<div class="st_sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="st_sidebar_title">',
		'after_title' => '</h3>'
	   )
   );

endif;
