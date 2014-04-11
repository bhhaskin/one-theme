<?php
/**
 * OneCore Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 */

abstract class OneModule {
  public $onecore;
  public function __construct($onecore) {
    $this->onecore = $onecore;
  }
  public function __destruct() {
  }
}

 abstract class OneCore
 {

  public $moduleList= array();
  public $moduleClassList = array();

   public function __construct() {
       $this->loadModules();
      $this->Test->hello();
  }

  protected function get_php_classes($php_code) {
    $classes = array();
    $tokens = token_get_all($php_code);
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

  protected function file_get_php_classes($filepath) {
    $php_code = file_get_contents($filepath);
    $classes = $this->get_php_classes($php_code);
    return $classes;
  }


  public function loadModules() {
      $phpversion = phpversion();
      if ( version_compare( $phpversion, '5.2.11', '>=' ) ) :
        $this->moduleSearch();
      else :
        $this->moduleSearchFallback();
      endif;
    foreach ($this->moduleList as $module){
      require_once $module;
      $temp = $this->file_get_php_classes($module);
      if (sizeof($temp) > 0){
        array_push($this->moduleClassList, $temp[0]);
      }
    }
    foreach ($this->moduleClassList as $module) {

      $this->$module = new $module($this);
    }
  }

  protected function moduleSearch() {
    // Include all modules from the theme (NOT the child themes)
    $modules_path = new RecursiveDirectoryIterator( get_template_directory() . '/lib/modules/' );
    $recIterator  = new RecursiveIteratorIterator( $modules_path );
    $regex        = new RegexIterator( $recIterator, '/\/module.php$/i' );
    foreach( $regex as $item ) {
      array_push($this->moduleList, $item->getPathname());
    }

  }

  protected function moduleSearchFallback() {
    // Include all modules from the theme (NOT the child themes)
    foreach( glob( get_template_directory() . '/lib/modules/*/module.php' ) as $module ) {
      array_push($this->moduleList, $module);
    }
  }

   public function __destruct() {
   }
 }

class TheOne extends OneCore{
  
}
