<?php

/**
 * General Header File
 * @package Wordpress
 * @subpackage one-theme
 * @since 1.0
 * @author Matthew Hansen
 */
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>         <html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title( '&laquo;', true, 'right' ); ?> <?php bloginfo( 'name' ); ?></title>
    <meta name="viewport" content="width=device-width">

    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />

</head>

<body <?php body_class(); ?>>
<?php othead(); ?>

<!--[if lt IE 7]>
    <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->

<header>

</header>
<?php $ot_navigation = new Bootstrap_Navigation();
  $ot_navigation->menu_output();
 ?>
