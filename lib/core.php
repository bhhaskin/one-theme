<?php
/**
 * OneCore Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 * @version 1.0
 */

 /**
 * Abstract Class that modules are extended from.
 *
 * This is the abstract class that modules are extend from. It provieds all the basic class functionally.
 *
 * @author Bryan Haskin
 */
 abstract class OneModule {
	public $oneCore;

	 public final function __construct($oneCore) {
		$this->oneCore = $oneCore; // gives access to parent class

		// required class properties
		if(!isset($this->version)){
			throw new LogicException(get_class($this) . ' must have a $version');
		}
		if(!isset($this->level) && (($this->level != "system") || ($this->level != "core") || ($this->level != "normal"))){
			throw new LogicException(get_class($this) . ' must have a $level and $level must be set to system, core or normal.');
		}
		if(!isset($this->priority)){
			throw new LogicException(get_class($this) . ' must have a $priority');
		}
	 }

   public function getModuleDirectory() {
		return (realpath(dirname(__FILE__)) . "/modules/" . lcfirst(get_class($this)) . "/" );
	}

	public function getAssets() {
		if(file_exists($this->getModuleDirectory() . "manifest.json")){
			return $this->assetManifest();
		} else {
			$temp = array();
			$temp['css'] = $this->assetSearch('css/', 'css');
			$temp['js'] = $this->assetSearch('js/', 'js');
			return $temp;
		}
	}
  protected function assetManifest() {
    $json = json_decode(file_get_contents($this->getModuleDirectory() . "manifest.json"), true);
    $temp = array();

    foreach($json as $key => $value) {
      foreach($value as $item) {
        $temp[$key][] = get_template_directory_uri() . "/lib/modules/" . lcfirst(get_class($this)) . "/" . $key . "/" .$item;
      }

    }
    return $temp;

  }
	protected function assetSearch($directory, $fileExtension) {
    $assetList = array();
		$phpversion = phpversion();
    if (file_exists($this->getModuleDirectory() . $directory)) {
  			// Include all modules from the theme (NOT the child themes)
  			$modules_path = new RecursiveDirectoryIterator( $this->getModuleDirectory() . $directory );
  			$recIterator  = new RecursiveIteratorIterator( $modules_path );
  			$regex        = new RegexIterator( $recIterator, '/\/*.'. $fileExtension .'$/i' );
  			foreach( $regex as $item ) {
  				array_push($assetList, get_template_directory_uri() . "/lib/modules/" . lcfirst(get_class($this)) . "/" . $directory  . $item->getfileName());
  			}
      return $assetList;
    }
	}

 }

 /**
 * Abstract Class that parent & child themes cores are extended from.
 *
 * This is the abstract class that both parent & child themes main core are extended from.
 *
 * @author Bryan Haskin
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
		} else { // fall back
			foreach( glob( $this->getDirectory() . '/lib/modules/*/module.php' ) as $module ) {
				array_push($this->moduleList, $module);
			}
		}
	}

	public function getDirectory() {
		if ($this->isParent()):
			return get_template_directory();
		else:
			return get_stylesheet_directory();
		endif;
	}

	public function isParent() {
		if(realpath(dirname(__FILE__)) == (get_template_directory() . "/lib") ): // Check to see if parent or child theme
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
      {;
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
