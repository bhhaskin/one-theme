<?php
/**
 * Module Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 * @version 1.3
 */
namespace oneTheme;
require_once('masterControl.class.php');

abstract class Module {

    public function __construct() {
        $masterControl = \MasterControl::getInstance();
        $masterControl->register($this);
        $this->init();
    }
    public function init() {
    }
}

class Test extends Module {
    public function hello() {
        echo "Hello World!";
    }
}

new Test();
