<?php
/**
 * Sidebar template
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */
?>

<div id="sidebar">

<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'standard-sidebar' ) ) : endif; ?>

</div><!-- sidebar -->
