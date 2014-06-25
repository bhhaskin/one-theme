<?php
/**
 * MasterControl Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.2
 * @author Bryan Haskin
 * @version 1.0
 */


class Singleton
{
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar Singleton $instance The *Singleton* instances of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}

function parse_classname ($name)
{
  return array(
    'namespace' => array_slice(explode('\\', $name), 0, -1),
    'classname' => join('', array_slice(explode('\\', $name), -1)),
  );
}

trait registerObj {
    public function register($obj) {
        $objName = parse_classname(get_class($obj))['classname'];
        $this->$objName = $obj;
    }

    public function unregister($obj) {
        $objName = parse_classname(get_class($obj))['classname'];
        unset($this->$objName);
    }

    public function getRegister() {
        $array = Array();
        foreach($this as $key => $value) {
            if (is_object($value)){
                array_push($array, $key);
            }
        }
        return $array;
    }
}
