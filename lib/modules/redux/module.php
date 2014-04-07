<?php

/**
 * Load Redux Framework
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

   if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/ReduxCore/framework.php' ) ) {
     require_once( dirname( __FILE__ ) . '/ReduxCore/framework.php' );
   }

   if( !isset( $redux ) && file_exists( dirname( __FILE__ ) . '/ot-config.php' ) ) {
     require_once(dirname(__FILE__).'/ot-config.php');
   }
