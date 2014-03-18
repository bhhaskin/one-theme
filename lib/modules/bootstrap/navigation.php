<?php

/**
 * bootstrap menu function
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */

if( !function_exists( 'ot_bootstrap_menu' ) ) :

  function ot_bootstrap_menu() {
    ?>
      <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
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
        </div>
      </nav>
    <?php
  }

endif;
