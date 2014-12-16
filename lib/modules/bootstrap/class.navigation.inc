<?php
/**
 * Extendable Bootstrap Menu class
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

class Bootstrap_Navigation {

  public $brand = false;
  public $search = false;
  public $inverse = false;
  public $container = false;
  public $sticky = false;
  public $menuLocation = 'main';
  public $nav_right = false;


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

    if($this->sticky == true) {
      $stick = ' navbar-fixed-top';
    }else{
      $stick = '';
    }

    if($this->nav_right == true) {
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
          if($this->brand == true){
          //echo '<a class="navbar-brand" href="'.$hurl.'">'.$blog_title.'</a>';
          do_action('navbar_brand');
        } ?>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php wp_nav_menu( array(
                              'menu'       => $this->menuLocation,
                              'theme_location' => $this->menuLocation,
                              'depth'      => 2,
                              'container'  => false,
                              'menu_class' => 'nav navbar-nav',
                              'fallback_cb' => 'wp_page_menu',
                              'walker' => new wp_bootstrap_navwalker()
                            )
                      ); ?>

        <?php if($this->search == true){
          do_action('navbar_right'); ?>
          <!--<form class="navbar-form navbar-right" role="search" action="<?php bloginfo('url'); ?>" method="get">
            <div class="form-group">
              <input type="text" class="form-control" name="s" id="search" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>-->
        <?php  } ?>
        </div>
        <?php if($this->container == true){
          echo '</div>';
        } ?>
      </div>
    <?php
  }

}
