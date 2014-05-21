<?php
/**
 * OneCore Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 * @version 1.1
 */


abstract class OneCore {
 public $moduleList = array();
 public $moduleClassList = array();
 //module levels
 public $systemModules = array();
 public $coreModules = array();
 public $normalModules = array();
 // theme Assets
 public $cssAssets = array();
 public $jsAssets = array();

 public function __construct() {
 }
 public function assets(){
   $this->loadModules();
   $this->getAssets();
   $this->enqueueAssets();
 }

 // Loads modules
 public function loadModules() {
   $this->moduleSearch();
   // load module.php and build moduleClassList
   foreach ($this->moduleList as $module){
     require_once $module;
     $temp = $this->getphpCode($module);
     if (sizeof($temp) > 0){
       array_push($this->moduleClassList, $temp[0]);
     }
   }
   // sort Modules by level & priority
   foreach ($this->moduleClassList as $module) {
     $this->$module = new $module($this);
     if ($this->$module->level == "core"){
       array_push($this->coreModules, $module);
     } else if ($this->$module->level == "system") {
       array_push($this->systemModules, $module);
     } else {
     array_push($this->normalModules, $module);
     }
   }
   usort($this->systemModules,array($this, "prioritySort"));
   usort($this->coreModules,array($this, "prioritySort"));
   usort($this->normalModules,array($this, "prioritySort"));

 }

 public function getAssets() {
   foreach ($this->systemModules as $module) {
     $temp = $this->$module->getAssets();
     if (!empty($temp['css'])){
       foreach ($temp['css'] as $item){
         array_push($this->cssAssets, array($item, $module));
       }
     }
     if (!empty($temp['js'])){
       foreach ($temp['js'] as $item){
         array_push($this->jsAssets, array($item, $module));
       }
     }
   }
   foreach ($this->coreModules as $module) {
     $temp = $this->$module->getAssets();
     if (!empty($temp['css'])){
       foreach ($temp['css'] as $item){
         array_push($this->cssAssets, array($item, $module));
       }
     }
     if (!empty($temp['js'])){
       foreach ($temp['js'] as $item){
         array_push($this->jsAssets, array($item, $module));
       }
     }
   }
   foreach ($this->normalModules as $module) {
     $temp = $this->$module->getAssets();
     if (!empty($temp['css'])){
       foreach ($temp['css'] as $item){
         array_push($this->cssAssets, array($item, $module));
       }
     }
     if (!empty($temp['js'])){
       foreach ($temp['js'] as $item){
         array_push($this->jsAssets, array($item, $module));
       }
     }
   }
 }

 public function enqueueAssets(){
   foreach ($this->cssAssets as $item){
     StyleEnqueueWrapper::init();
     StyleEnqueueWrapper::register( $item[1] . "-" . basename($item[0], ".css"), $item[0]);
   }
   foreach ($this->jsAssets as $item){
     ScriptEnqueueWrapper::init();
     ScriptEnqueueWrapper::register( $item[1] . "-" . basename($item[0], ".css"), $item[0]);
   }

 }

 // Extracts module classes from php code
 protected function extractModuleClasses($phpCode){
   $classes = array();
   $tokens = token_get_all($phpCode);
   $count = count($tokens);
   for ($i = 2; $i < $count; $i++) {
     if (   $tokens[$i - 2][0] == T_CLASS
       && $tokens[$i - 1][0] == T_WHITESPACE
       && $tokens[$i][0] == T_STRING) {
       $class_name = $tokens[$i][1];
       $classes[] = $class_name;
     }
   }
   return $classes;
 }
 // Get php code from module.php
 protected function getphpCode($filepath) {
   $phpCode = file_get_contents($filepath);
   $classes = $this->extractModuleClasses($phpCode);
   return $classes;
 }

 // Locate modules
 protected function moduleSearch() {
	$phpversion = phpversion();
	if ( version_compare( $phpversion, '5.2.11', '>=' ) ) { //falls back if older php version
		// Include all modules from the theme (NOT the child themes)
		$modules_path = new RecursiveDirectoryIterator( $this->getDirectory() . '/lib/modules/' );
		$recIterator  = new RecursiveIteratorIterator( $modules_path );
		$regex        = new RegexIterator( $recIterator, '/\/module.php$/i' );
		foreach( $regex as $item ) {
		  array_push($this->moduleList, $item->getPathname());
		}
	} else {
		foreach( glob( $this->getDirectory() . '/lib/modules/*/module.php' ) as $module ) {
			array_push($this->moduleList, $module);
		}
	}

  }


public function getDirectory() {
  $reflection = new ReflectionClass($this);
  $directory = dirname($reflection->getFileName()) . '/';
  return dirname($directory).'';
}

 public function isParent() {
   if($this->getDirectory() . '/lib' == (get_template_directory() . "/lib") ): // Check to see if parent or child theme
     return true;
   else:
     return false;
   endif;
 }

 // Priority function for usort
 public function prioritySort($a, $b){
     if ($this->$a->priority == $this->$b->priority) return 0;
   return ($this->$a->priority < $this->$b->priority) ? -1 : 1;
   }
}

 /**
* Core Class for parent themes.
*
* @author Bryan Haskin
*/

class TheOne extends OneCore{

}

class StyleEnqueueWrapper {
 private static $ins = null;

 private $styles = array();

 public static function instance(){
   is_null(self::$ins) && self::$ins = new self;
   return self::$ins;
 }

 public static function init(){
   add_action('wp_enqueue_scripts', array(self::instance(), 'enqueue'));
 }

 public static function register($hndl, $src, $deps=array(), $ver=null, $footer=false){
   self::instance()->styles[$hndl] = array(
       'src'       => $src,
       'deps'      => $deps,
       'ver'       => $ver,
       'footer'    => $footer,
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
             $value['footer']
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

 public static function init(){
   add_action('wp_enqueue_scripts', array(self::instance(), 'enqueue'));
 }

 public static function register($hndl, $src, $deps=array(), $ver=null, $footer=false){
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
