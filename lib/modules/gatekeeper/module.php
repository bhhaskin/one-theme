<?php
namespace oneTheme;

require_once get_template_directory() . '/lib/modules/core/module.class.php';
require_once 'GateKeeper.php';

class GateKeeper extends Module{

  public function init() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if (is_plugin_active('contact-form-7/wp-contact-form-7.php')){
      include_once( ABSPATH . 'wp-content/plugins/contact-form-7/includes/shortcodes.php' );
      include_once( ABSPATH . 'wp-content/plugins/contact-form-7/modules/captcha.php' );

      add_action( 'wpcf7_init', array( $this, 'wpcf7_add_shortcode_captcha' ));

      remove_filter( 'wpcf7_validate_captchar', 'wpcf7_captcha_validation_filter' );
      add_filter( 'wpcf7_validate_captchar', array( $this, 'wpcf7_captcha_validation_filter'), 10, 2 );

      remove_filter( 'wpcf7_ajax_onload', 'wpcf7_captcha_ajax_refill' );
      remove_filter( 'wpcf7_ajax_json_echo', 'wpcf7_captcha_ajax_refill' );

      add_filter( 'wpcf7_ajax_onload', array( $this, 'wpcf7_captcha_ajax_refill') );
      add_filter( 'wpcf7_ajax_json_echo', array( $this, 'wpcf7_captcha_ajax_refill') );

      add_action( 'admin_init', array( $this, 'wpcf7_add_tag_generator_captcha'), 45 );

    }

  }

  public function wpcf7_add_shortcode_captcha() {
    wpcf7_add_shortcode( array( 'captchac', 'captchar' ),
		array( $this, 'wpcf7_captcha_shortcode_handler' ), true );
  }

  public function wpcf7_captcha_shortcode_handler($tag) {
    $tag = new \WPCF7_Shortcode( $tag );

    if ( empty( $tag->name ) )
		return '';

    $validation_error = wpcf7_get_validation_error( $tag->name );

    $class = wpcf7_form_controls_class( $tag->type );

    if ( 'captchac' == $tag->type ) { // CAPTCHA-Challenge (image)
      $class .= ' wpcf7-captcha-' . $tag->name;
      $atts = array();
      $atts['class'] = $tag->get_class_option( $class );
	    $atts['id'] = $tag->get_id_option();
      $atts['alt'] = 'captcha';
      $atts['src'] = $this->getUrl() . '/00000.png';
      $atts = wpcf7_format_atts( $atts );
      $html = sprintf(
			'<input type="hidden" name="_wpcf7_captcha_challenge_%1$s" value="%2$s" /><img %3$s />',
			$tag->name, '00000.png', $atts );

      return $html;
    } elseif ( 'captchar' == $tag->type ) { // CAPTCHA-Response (input)
      if ( $validation_error )
			$class .= ' wpcf7-not-valid';
      $atts = array();
  		$atts['size'] = $tag->get_size_option( '40' );
  		$atts['maxlength'] = $tag->get_maxlength_option();
  		$atts['class'] = $tag->get_class_option( $class );
  		$atts['id'] = $tag->get_id_option();
  		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

  		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

  		$value = (string) reset( $tag->values );

      if ( wpcf7_is_posted() )
			$value = '';

		if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
			$atts['placeholder'] = $value;
			$value = '';
		}

		$atts['value'] = $value;
		$atts['type'] = 'text';
		$atts['name'] = $tag->name;

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf(
			'<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>',
			sanitize_html_class( $tag->name ), $atts, $validation_error );
		return $html;

    }

  }

  public function wpcf7_captcha_validation_filter( $result, $tag ) {
    $tag = new \WPCF7_Shortcode( $tag );

	  $type = $tag->type;
	  $name = $tag->name;
    $response = isset( $_POST[$name] ) ? (string) $_POST[$name] : '';
    session_start();
    if (isset($_SESSION['gatekeeper']) && $response == $_SESSION['gatekeeper']) {
      null;
    } else {
      $result['valid'] = false;
      $result['reason'][$name] = wpcf7_get_message( 'captcha_not_match' );
    }

    if ( isset( $result['reason'][$name] ) && $id = $tag->get_id_option() ) {
		    $result['idref'][$name] = $id;
      }
    return $result;
  }

  public function wpcf7_captcha_ajax_refill($items) {
    if ( ! is_array( $items ) )
		  return $items;

    $fes = wpcf7_scan_shortcode( array( 'type' => 'captchac' ) );

  	if ( empty( $fes ) )
	    return $items;

	  $refill = array();
    $invalidCheck = false;

    if (array_key_exists('invalids', $items)){
      foreach ($items['invalids'] as $item) {
         $invalidCheck = preg_match('/captcha/', $item['into']);
      }
    }

    foreach ( $fes as $fe ) {
      $name = $fe['name'];
	    $options = $fe['options'];

      if ( empty( $name ) )
			   continue;

     if ($invalidCheck){
       $refill[$name] = $this->getModuleUrl() . '/00000.png';
     }
    }
    if ( ! empty( $refill ) )
		  $items['captcha'] = $refill;

	  return $items;
  }

/* Tag generator */



  function wpcf7_add_tag_generator_captcha() {
  	if ( ! function_exists( 'wpcf7_add_tag_generator' ) )
  		return;

  	wpcf7_add_tag_generator( 'captcha', __( 'CAPTCHA', 'contact-form-7' ),
  		'wpcf7-tg-pane-captcha', array($this,'wpcf7_tg_pane_captcha') );
  }

  public function wpcf7_tg_pane_captcha( &$contact_form ) {
    ?>
    <div id="wpcf7-tg-pane-captcha" class="hidden">
    <form action="">
    <table>

    <tr><td><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>
    </table>

    <table class="scope captchac">
    <caption><?php echo esc_html( __( "Image settings", 'contact-form-7' ) ); ?></caption>

    <tr>
    <td><code>id</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="text" name="id" class="idvalue oneline option" /></td>

    <td><code>class</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="text" name="class" class="classvalue oneline option" /></td>
    </tr>

    <tr>
    <td><?php echo esc_html( __( "Foreground color", 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="text" name="fg" class="color oneline option" /></td>

    <td><?php echo esc_html( __( "Background color", 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="text" name="bg" class="color oneline option" /></td>
    </tr>

    <tr><td colspan="2"><?php echo esc_html( __( "Image size", 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="checkbox" name="size:s" class="exclusive option" />&nbsp;<?php echo esc_html( __( "Small", 'contact-form-7' ) ); ?>&emsp;
    <input type="checkbox" name="size:m" class="exclusive option" />&nbsp;<?php echo esc_html( __( "Medium", 'contact-form-7' ) ); ?>&emsp;
    <input type="checkbox" name="size:l" class="exclusive option" />&nbsp;<?php echo esc_html( __( "Large", 'contact-form-7' ) ); ?>
    </td></tr>
    </table>

    <table class="scope captchar">
    <caption><?php echo esc_html( __( "Input field settings", 'contact-form-7' ) ); ?></caption>

    <tr>
    <td><code>id</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="text" name="id" class="idvalue oneline option" /></td>

    <td><code>class</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="text" name="class" class="classvalue oneline option" /></td>
    </tr>

    <tr>
    <td><code>size</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="number" name="size" class="numeric oneline option" min="1" /></td>

    <td><code>maxlength</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="number" name="maxlength" class="numeric oneline option" min="1" /></td>
    </tr>
    </table>

    <div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'contact-form-7' ) ); ?>
    <br />1) <?php echo esc_html( __( "For image", 'contact-form-7' ) ); ?>
    <input type="text" name="captchac" class="tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" />
    <br />2) <?php echo esc_html( __( "For input field", 'contact-form-7' ) ); ?>
    <input type="text" name="captchar" class="tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" />
    </div>
    </form>
    </div>
    <?php
    }


}

new GateKeeper();
