<?php
/**
 * Extendable Bootstrap Menu class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

class Bootstrap_Navigation {

  public $nav_left = false;
  public $nav_right = false;
  public $inverse = false;
  public $container = false;
  public $sticky_top = false;
  public $menu_location = 'main';
  public $pos_right = false;


  function __construct($data = array()) {
    if(count($data) > 0) {
      foreach($data as $name => $value) {
        $this->$name = $value;
      }
    }
  }


  function menu_output() {
    if($this->inverse == true) {
      $iv = ' navbar-inverse';
    }else{
      $iv = '';
    }

    if($this->sticky_top == true) {
      $stick = ' navbar-fixed-top';
    }else{
      $stick = '';
    }

    if($this->pos_right == true) {
        $nr = ' navbar-right';
    }else{
        $nr = '';
    }


    $hurl = home_url('/');
    $blog_title = get_bloginfo('name');

    ?>
      <div class="navbar navbar-default<?= $nr ?><?= $iv ?><?= $stick ?>" role="navigation">
        <?php if($this->container == true){
          echo '<div class="container">';
        } ?>


        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php
          if($this->nav_left == true){
          //echo '<a class="navbar-brand" href="'.$hurl.'">'.$blog_title.'</a>';
          do_action('navbar_brand');
        } ?>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php wp_nav_menu( array(
                              'menu'       => $this->menu_location,
                              'theme_location' => $this->menu_location,
                              'depth'      => 2,
                              'container'  => false,
                              'menu_class' => 'nav navbar-nav',
                              'fallback_cb' => 'wp_page_menu',
                              'walker' => new wp_bootstrap_navwalker()
                            )
                      ); ?>

        <?php if($this->nav_right == true){
          do_action('navbar_right'); ?>
        <?php  } ?>
        </div>
        <?php if($this->container == true){
          echo '</div>';
        } ?>
      </div>
    <?php
  }

}
