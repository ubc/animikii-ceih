<?php
Class Quick_Links {

  static $counter; // this is used to that the shortcodes can be placed into myltipe places at the same time
  static $slider_attr;
  static $add_script;
  static $slider_options;

  /**
   * init function.
   *
   * @access public
   * @return void
   */
  static function init() {
    // register the spotlight functions
    if( !is_admin() ):
      wp_register_style( 'animikii-quik-links', plugins_url( '/css/quick-links.css', __FILE__ ) );
      wp_enqueue_style( 'animikii-quik-links' );
      add_action( 'wp_footer', array( __CLASS__ ,'display_js'), 999 );
    endif;

    remove_action( 'init', array( 'UBC_Collab_Spotlight', 'register_scripts' ), 999);
    remove_action( 'wp_footer', array( 'UBC_Collab_Spotlight', 'print_script' ), 999);

    remove_action( 'init', array( 'UBC_Collab_Frontpage', 'register_script'), 999 );
		remove_action( 'wp_footer', array( 'UBC_Collab_Frontpage', 'print_script'), 999 );

  }

  function display_js() {
		$html = '<script type="text/javascript">';
		$html .= '(function() {';
    $html .= 'jQuery(".js-quick-links").removeClass("is-initial")';
    $html .= '})();';
    $html .= '</script>';
    echo $html;
	}

  /**
   * show function.
   *
   * @access public
   * @param array $atts (default: array())
   * @param bool $echo (default: true)
   * @return mixed
   */
  static function show( $atts = array(), $echo = true ) {
    self::$add_script = true;
    self::$slider_attr = shortcode_atts( UBC_Collab_Spotlight::defaults(), $atts );

    $html = self::shell();

    self::$counter++;

    if( $echo )
      echo $html;
    else
      return $html;
  }

  /**
   * shell function.
   *
   * @access public
   * @return string
   */
  static function shell() {
    $html = '<section class="c-quick-links s-dark-background js-quick-links is-initial '.UBC_Collab_Spotlight::get_slider_class().' ">';
    $html .= '<nav class="c-quick-links__inner">';
    $html .= self::slider_items();
    $html .= '</nav>';
    $html .= '</section>';
    return $html;
  }

  /**
   * slider_items function.
   *
   * @access public
   * @return string
   */
  static function slider_items() {

    $sections = Animikii_Frontpage::get_sections();

    $html = '<ul class="c-quick-links__list">';

    $count = 0;
    foreach ($sections as $menu_id => $item) {
      if ($count == 4) break;
      if ($item['menu_parent_id'] == 0) {
        $post_id = $item["id"];

        $description = rwmb_meta( 'akii_frontpage_description', null, $post_id );

        $html .= '<li class="c-quick-links__item" style="background-image: url( ' . self::get_quicklink_image_src( $post_id, array( 'width'=>1000, 'height'=>900 ) ) . ')">';
          $html .= '<div class="c-quick-links__item__background"></div>';

          $html .= '<h3 class="c-quick-links__item__header">';
            $html .= '<a href="#section-' . $post_id . '">' . $item['title'] . '</a>';
          $html .= '</h3>';
          $html .= '<div class="c-quick-links__item__body">';
            $html .= '<p>' . $description . '</p>';
            $html .= '<p>';
              $html .= '<a class="c-button" href="#section-' . $post_id. '">Learn more</a>';
            $html .= '</p>';
          $html .= '</div>';
        $html .= '</li>';

        $count++;
      }
    }

    $html .= '</ul>';

    return $html;
  }

  static function get_quicklink_image_src($id, $size) {
    return self::get_slider_image_src( Animikii_Frontpage::get_frontpage_image( $id, $size ) );
  }


  /**
   * get_slider_image_src function.
   *
   * @access public
   * @return string
   */
  static function get_slider_image_src( $img ) {

    return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';

    return $html;

  }

}

Quick_Links::init();
