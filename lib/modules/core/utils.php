<?php
/**
 * MasterControl Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.2
 * @author Bryan Haskin
 * @version 1.1
 */

namespace oneTheme;

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

class StyleEnqueueWrapper {
 private static $ins = null;

 private $styles = array();

 public static function instance(){
   is_null(self::$ins) && self::$ins = new self;
   return self::$ins;
 }

 public static function init($loc = 'wp_enqueue_scripts'){
   add_action($loc, array(self::instance(), 'enqueue'));
 }

 public static function register($hndl, $src, $deps=array(), $ver=null, $media='all'){
   self::instance()->styles[$hndl] = array(
       'src'       => $src,
       'deps'      => $deps,
       'ver'       => $ver,
       'media'    => $media,
   );
 }
 public function enqueue()
 {
     foreach($this->styles as $key => $value)
     {;
         wp_register_style(
             $key,
             $value['src'],
             $value['deps'],
             $value['ver'],
             $value['media']
         );
         wp_enqueue_style($key);
     }
 }
}

class ScriptEnqueueWrapper {
 private static $ins = null;

 private $scripts = array();

 public static function instance(){
   is_null(self::$ins) && self::$ins = new self;
   return self::$ins;
 }

 public static function init($loc = 'wp_enqueue_scripts'){
   add_action($loc, array(self::instance(), 'enqueue'));
 }

 public static function register($hndl, $src, $deps=array(), $ver=null, $footer=true){
   self::instance()->scripts[$hndl] = array(
       'src'       => $src,
       'deps'      => $deps,
       'ver'       => $ver,
       'footer'    => $footer,
   );
 }
 public function enqueue()
 {
     foreach($this->scripts as $key => $value)
     {
         wp_register_script(
             $key,
             $value['src'],
             $value['deps'],
             $value['ver'],
             $value['footer']
         );
         wp_enqueue_script($key);
     }
 }
}

function parse_classname ($name)
{
  return array(
    'namespace' => array_slice(explode('\\', $name), 0, -1),
    'classname' => join('', array_slice(explode('\\', $name), -1)),
  );
}

function arrayFind($array, $key, $obj) {

    if (isset($array[$key]))
                return $array[$key];
      else
        return $obj;

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

trait assetManager {
    public $assets = array();

    public function getAssets($manifestLoad=True) {

        if ($manifestLoad && file_exists($this->getDirectory() . "manifest.json")){
            $this->assets = json_decode(file_get_contents($this->getDirectory() . "manifest.json"), true);

      } else {

            if (file_exists($this->getDirectory() . 'css/')){
                $directory = $this->getDirectory() . 'css/';
                $scanned_directory = array_diff(scandir($directory), array('..', '.'));
                $this->assets['css']=array();
                foreach($scanned_directory as $key => $value) {
                    if (pathinfo($value, PATHINFO_EXTENSION) == "css")
                        array_push($this->assets['css'], array('file' => $value));
                }

            }

            if (file_exists($this->getDirectory() . 'js/')) {
                $directory = $this->getDirectory() . 'js/';
                $scanned_directory = array_diff(scandir($directory), array('..', '.'));
                $this->assets['js']=array();
                foreach($scanned_directory as $key => $value) {
                    if (pathinfo($value, PATHINFO_EXTENSION) == "js")
                        array_push($this->assets['js'], array('file' => $value));
                }
            }
        }
    }

    public function loadAssets($manifestLoad=True) {

        $this->getAssets($manifestLoad);

        foreach($this->assets as $key => $values) {
            if ($key == 'css') {
                foreach($values as $key2 => $value) {
                    StyleEnqueueWrapper::init();
                    StyleEnqueueWrapper::register( get_class($this) . "-" . basename($value['file'], ".css"),  $this->getUrl() . 'css/' . $value['file'], arrayFind($value, 'deps', array()), arrayFind($value, 'ver', null), arrayFind($value, 'media', 'all'));
                }

            }

            if ($key == 'js') {
                foreach($values as $key2 => $value) {
                    ScriptEnqueueWrapper::init();
                    ScriptEnqueueWrapper::register( get_class($this) . "-" . basename($value['file'], ".js"),  $this->getUrl() . 'js/' . $value['file'], arrayFind($value, 'ver', null), arrayFind($value,'footer', false));
                }
            }
        }
    }

}

trait signals {

    public $signals = array();

    public function connect($signal, $context, $slot) {
        $action = function() use($slot, $context){$context->$slot();};
        $this->signals[$signal][] = $action;
    }

    public function disconnect($signal, $context, $slot) {
        $action = function() use($slot, $context){$context->$slot();};
        unset($this->signals[$signal][$action]);
    }

    public function emit($signal) {
        if (array_key_exists($signal, $this->signals)) {
            foreach ($this->signals[$signal] as $function)
            {
                $function();
            }
        }
    }

}
