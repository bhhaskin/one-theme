<?php
/**
 * Module Loader
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

/*
 * Use 'RecursiveDirectoryIterator' if PHP Version >= 5.2.11
 */
function ot_include_modules($basepath=null) {
    $basepath = empty($basepath) ? get_stylesheet_directory() : $basepath;
    if (!file_exists($basepath)) {
        return false;
    }

    // Include all modules from the theme (NOT the child themes)
    $modules_path = new RecursiveDirectoryIterator( $basepath . '/lib/modules/' );
    $recIterator  = new RecursiveIteratorIterator( $modules_path );
    $regex        = new RegexIterator( $recIterator, '/\/module.php$/i' );

    foreach( $regex as $item ) {
        require_once $item->getPathname();
    }

}


/*
 * Fallback to 'glob' if PHP Version < 5.2.11
 */
function ot_include_modules_fallback($basepath=null) {
    $basepath = empty($basepath) ? get_stylesheet_directory() : $basepath;
    if (!file_exists($basepath)) {
        return false;
    }

  // Include all modules from the theme (NOT the child themes)
  foreach( glob($basepath . '/lib/modules/*/module.php' ) as $module ) {
    require_once $module;
  }



}


// PHP version control
$phpversion = phpversion();
if ( version_compare( $phpversion, '5.2.11', '>' ) ) {
  ot_include_modules(get_template_directory());
  if (is_child_theme()) {
      ot_include_modules();
  }

} else {
  ot_include_modules_fallback(get_template_directory());
  if (is_child_theme()) {
      ot_include_modules_fallback();
  }
}
