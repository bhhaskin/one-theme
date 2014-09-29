<?php

/**
 * Theme Functions
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen & Bryan Haskin
 */

function otParentFunctions() {
	require_once dirname( __FILE__ ) . '/lib/modules/loader.php'; // Loader
	$masterControl = oneTheme\MasterControl::getInstance();
}

add_action( 'after_setup_theme', 'otParentFunctions', 9 );
