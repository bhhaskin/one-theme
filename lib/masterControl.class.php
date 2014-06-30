<?php
/**
 * MasterControl Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 * @version 1.3
 */

require_once('utils.php');

class MasterControl extends Singleton
{

    use registerObj;

    public function isParent() {
       if($this->getDirectory() . '/lib' == (get_template_directory() . "/lib") ): // Check to see if parent or child theme
         return true;
       else:
         return false;
       endif;
     }

     public function getDirectory() {
       $reflection = new ReflectionClass($this);
       $directory = dirname($reflection->getFileName()) . '/';

       return $directory;
     }

}
