<?php
/**
 * Module Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 * @version 1.4
 */
namespace oneTheme;

require_once('masterControl.class.php');

abstract class Module {
    public $autoRegister = True;

    public function __construct($data = array()) {
        if(count($data) > 0) {
          foreach($data as $name => $value) {
            $this->$name = $value;
          }
        }
        if ($this->autoRegister) {
            $this->registerModule();
        }

        $this->init();
        $masterControl = MasterControl::getInstance();
        $masterControl->connect(MasterControl::SIGNAL_READY, $this, 'whenReady');
    }

    public function init() {

    }

    public function whenReady() {

    }

    public function getDirectory() {
      $reflection = new \ReflectionClass($this);
      $directory = dirname($reflection->getFileName()) . '/';

      return $directory;
    }

    public function getUrl() {
        $masterControl = MasterControl::getInstance();
        if ($this->isParent()) {
            return get_template_directory_uri() . '/lib/modules/' . strtolower(parse_classname(get_class($this))['classname']) . '/';
        } else {
            return get_stylesheet_directory_uri() . '/lib/modules/' . strtolower(parse_classname(get_class($this))['classname']) . '/';
          }
    }

    public function isParent() {
       if(dirname(dirname($this->getDirectory())) == (get_template_directory() . "/lib") ): // Check to see if parent or child theme
         return true;
       else:
         return false;
       endif;
     }

    public function registerModule() {
        $masterControl = MasterControl::getInstance();
        $masterControl->register($this);
    }

    public function delete() {
        $masterControl = MasterControl::getInstance();
        $objName = parse_classname(get_class($this))['classname'];
        unset($masterControl->$objName );
    }
}
