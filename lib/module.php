<?php
/**
 * OneModule Class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Bryan Haskin
 * @version 1.1
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
   $this->init();
  }
public function init() {

}

public function getModuleDirectory() {
  $reflection = new ReflectionClass($this);
  $directory = dirname($reflection->getFileName()) . '/';

  return $directory;
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
       if ($this->oneCore->isParent()) {
       $temp[$key][] = get_template_directory_uri() . "/lib/modules/" . lcfirst(get_class($this)) . "/" . $key . "/" .$item; } else {
           $temp[$key][] = get_stylesheet_directory_uri() . "/lib/modules/" . lcfirst(get_class($this)) . "/" . $key . "/" .$item;
       }
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
         if ($this->oneCore->isParent()){
           array_push($assetList, get_template_directory_uri() . "/lib/modules/" . lcfirst(get_class($this)) . "/" . $directory  . $item->getfileName());
         } else {
           array_push($assetList, get_stylesheet_directory_uri() . "/lib/modules/" . lcfirst(get_class($this)) . "/" . $directory  . $item->getfileName());
         }
       }
     return $assetList;
   }
 }

}
