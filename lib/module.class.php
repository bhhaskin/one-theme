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

    public $autoRegister = True;

    public function __construct() {
        if ($this->autoRegister) {
            $this->register();
        }

        $this->init();
    }
    public function init() {

    }

    public function register() {
        $masterControl = \MasterControl::getInstance();
        $masterControl->register($this);
    }

    public function delete() {
        $masterControl = \MasterControl::getInstance();
        $objName = parse_classname(get_class($this))['classname'];
        unset($masterControl->$objName );
    }
}
