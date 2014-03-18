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

define( 'TEMPPATH', get_bloginfo( 'stylesheet_directory' ) );
define( 'IMAGES' , TEMPPATH . '/images' );

//register navigation

if( function_exists('register_nav_menus') ) :

  register_nav_menus(
 		array(
 			'main' => 'Main Nav',
       'top' => 'Top Menu',
       'foot' => 'Footer Menu'
 			)
 		);

endif;
