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

  require_once dirname( __FILE__ ) . '/class.bootstrap_navwalker.inc';
  require_once dirname( __FILE__ ) . '/class.navigation.inc';


  //enqueue bootstap css and js files to load with the template

	if ( !function_exists( 'ot_load_bootstrap_js' ) ) :

    function ot_load_bootstrap_js() {
  		wp_register_script( 'bootstrap_js', get_template_directory_uri() . '/lib/modules/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '3.3.4', true );
  		wp_enqueue_script( 'bootstrap_js' );
  	}

    add_action( 'init', 'ot_load_bootstrap_js');

	endif;


	if ( !function_exists( 'ot_load_bootstrap_css' ) ) :

	function ot_load_bootstrap_css() {
		wp_register_style( 'bootstrap_css', get_template_directory_uri() . '/lib/modules/bootstrap/css/bootstrap.min.css', array(), '3.3.4', 'all' );
		wp_enqueue_style( 'bootstrap_css' );
	}
	add_action( 'wp_enqueue_scripts', 'ot_load_bootstrap_css', 1 );


	endif;

endif;


function bsCenter($content, $total, $count, $lgSize = null, $mdSize = 1, $smSize = null, $xsSize = null) {
    $arr = array($lgSize, $mdSize, $smSize, $xsSize);
    if (isset($lgSize)) {
        $lgSize = 'col-lg-' . $lgSize;
    } else {
        $lgSize = '';
    }
    if (isset($mdSize)) {
        $mdSize = 'col-md-' . $mdSize;
    } else {
        $mdSize = '';
    }
    if (isset($smSize)) {
        $smSize = 'col-sm-' . $smSize;
    } else {
        $smSize = '';
    }
    if (isset($xsSize)) {
        $xsSize = 'col-xs-' . $xsSize;
    } else {
        $xsSize = '';
    }
    $smallest = min(array_filter($arr,'strlen'));
    $rows = 12/$smallest;
    $offset = $total % $rows;
    if ($count % $rows == 0) {
        //echo '<div class="row">';
    }
    echo '<div class="' . $lgSize . ' ' . $mdSize . ' ' . $smSize . ' ' . $xsSize . '">';
    echo $content;
    echo '</div>';
     if ((($count + 1) % $row) == 0) {
        //echo '</div>';
    }
}
