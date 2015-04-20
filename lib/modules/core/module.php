<?php
/**
 * Load all the settings for theme
 * @package Wordpress
 * @subpackage core-module
 * @since 1.0
 * @author Matthew Hansen
 */

add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support('nav-menus');

define( 'PARENTPATH', get_template_directory_uri() );
define( 'TEMPPATH', get_bloginfo( 'stylesheet_directory' ) );
define( 'PIMAGES', PARENTPATH . '/images' );
define( 'IMAGES' , TEMPPATH . '/images' );

require_once 'module.class.php';

//register navigation

if( function_exists( 'register_nav_menus' ) ) :

  register_nav_menus(
 		array(
 			'main' => 'Main Nav',
       'top' => 'Top Menu',
       'foot' => 'Footer Menu'
 			)
 		);

endif;

if( function_exists( 'register_sidebar' ) ) :

  register_sidebar( array (
		'name' => __('Standard Sidebar', 'standard-sidebar'),
		'id' => 'standard-sidebar',
		'description' => __('Standard Sidebar', 'dir'),
		'before_widget' => '<div class="st_sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="st_sidebar_title">',
		'after_title' => '</h3>'
	   )
   );

endif;

if( !function_exists( 'slugify' ) ) :
    function slugify($str, $replace=array(), $delimiter='-', $stripHtml = True) {
        if ($stripHtml) {
            wp_strip_all_tags($str);
        }
        if( !empty($replace) ) {
    		$str = str_replace((array)$replace, ' ', $str);
    	}

    	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    	$clean = strtolower(trim($clean, '-'));
    	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    	return $clean;
    }
endif;







include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (is_plugin_active( 'simple-fields/simple_fields.php')) {
    if (!function_exists('get_sfs')) {
        function get_sfs($connectorslug) {
            if (!simple_fields_is_connector($connectorslug)) {
                return false;
            }
            global $sf;
            $connectors = $sf->get_post_connectors();
            $connector = null;

                foreach ($connectors as $one_connector) {
                    if ( $one_connector["slug"] === $connectorslug && $one_connector["deleted"] == False)
                    { $connector = $one_connector;}
                }
            if (empty($connector)) {
                return [];
            }
            $slugArray = array();
            foreach ($connector['field_groups'] as $item) {
                $slug = $sf->get_field_group($item['id'])['slug'];
                array_push($slugArray, $slug);
            }
            return $slugArray;
        }
    }
    if (!function_exists('sfs_parts')) {
        function sfs_parts($connector) {
            if ($connector) {
        	$fieldGroups = get_sfs($connector);
            	foreach ($fieldGroups as $fieldGroup) {
            		get_template_part( 'partials/sfg',  $fieldGroup);
            	}
            }
        }
    }
}


if( !function_exists( 'get_alt' ) ) :
    function get_alt($id, $default = "Image") {
    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
    if (!empty($alt)) {
        return $alt;
    }
    return $default;
}
endif;

if( !function_exists( 'convert_number_to_words' ) ) :
    function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
            $string = $dictionary[$number];
            break;
            case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
            case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
            default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
endif;

if( !function_exists( 'lct_temporary_wpautop_disable' ) ) {

    add_filter( 'the_content', 'lct_temporary_wpautop_disable', 99 );
    function lct_temporary_wpautop_disable( $content ) {
        $new_content = '';
        $pattern_full = '{(\[raw\].*?\[/raw\])}is';
        $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
        $pieces = preg_split( $pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE );

        foreach( $pieces as $piece ) {
            if( preg_match( $pattern_contents, $piece, $matches ) ) {
                $new_content .= $matches[1];
            } else {
                $new_content .= wptexturize( wpautop( $piece ) );
            }
        }

        $new_content = str_replace( array( "[raw]", "[/raw]" ), "", $new_content );

        return $new_content;
    }
}


require_once 'loader.php'; // NEEDS TO BE AT THE END OF THE FILE
$masterControl = oneTheme\MasterControl::getInstance();
$masterControl->emit(oneTheme\MasterControl::SIGNAL_READY);
