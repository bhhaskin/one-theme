<?php
/**
 * test module
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 */

require_once get_template_directory() . '/lib/core.php';

class Test extends OneModule{

	// required class properties
	public $version = "1.0";
	public $level = "system";
	public $priority = "1";

}
