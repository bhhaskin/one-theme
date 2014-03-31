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

    $hurl = home_url('/');
    $blog_title = get_bloginfo('name');

    ?>
      <div class="navbar navbar-default<?php echo $iv; ?>" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php
          if($this->brand == true){
          echo '<a class="navbar-brand" href="'.$hurl.'">'.$blog_title.'</a>';
        } ?>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php wp_nav_menu( array(
                              'menu'       => 'main',
                              'theme_location' => 'main',
                              'depth'      => 2,
                              'container'  => false,
                              'menu_class' => 'nav navbar-nav',
                              'fallback_cb' => 'wp_page_menu',
                              'walker' => new wp_bootstrap_navwalker()
                            )
                      ); ?>

        <?php if($this->search == true){ ?>
          <form class="navbar-form navbar-right" role="search" action="<?php bloginfo('url'); ?>" method="get">
            <div class="form-group">
              <input type="text" class="form-control" name="s" id="search" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        <?php  } ?>
        </div>
      </div>
    <?php
  }

  function __toString() {
    return $this->menu_output(); 
  }
}
