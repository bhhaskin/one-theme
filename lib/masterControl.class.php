<?php
/**
 * MasterControl Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 * @version 1.3
 */
namespace oneTheme;

require_once('utils.php');

class MasterControl extends Singleton
{

    use registerObj;
    
    public function getRegister() {
        $array = Array();
        foreach($this as $key => $value) {
            if (is_object($value) && is_subclass_of($value, 'oneTheme\Module')){
                array_push($array, $key);
            }
        }
        return $array;
    }

}
