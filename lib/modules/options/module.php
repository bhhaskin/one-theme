<?php
namespace oneTheme;

class Options extends Module{

  public function init() {
      /**
      * Required: set 'ot_theme_mode' filter to true.
      */
      add_filter( 'ot_theme_mode', '__return_true' );

      /**
      * Required: include OptionTree.
      */
      require( trailingslashit( get_template_directory() ) . 'option-tree/ot-loader.php' );
      require_once(get_template_directory() . "/option-tree/includes/ot-functions.php"); //sadly needed due to the loading order....
      add_action('admin_head', array($this, 'admin_css'));
      
      if (!WP_DEBUG) {
        add_filter( 'ot_show_pages', '__return_false' );
      }
  }

  public  function admin_css(){
    ?>
    <style>
        #option-tree-header #option-tree-logo a {
            visibility: hidden;
        }

        #option-tree-version span {
            visibility: hidden;
        }
    </style>
    <?php
}


}

new Options();
