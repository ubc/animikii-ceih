<?php


Class Animikii_Frontpage {

  /**
   * init function.
   *
   * @access public
   * @return void
   */
  function init() {
    remove_action( 'template_redirect', array( 'UBC_Collab_Frontpage', 'start' ), 999 );
    add_action( 'template_redirect', array( __CLASS__, 'frontpage_start'), 999 );

    wp_register_script( 'animikii-ceih-frontpage-masonry', 'https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js', array( 'jquery' ), '2.1', true );
    wp_register_script( 'animikii-ceih-frontpage-imagesloaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js', array( 'jquery' ), '2.1', true );
    wp_register_script( 'animikii-ceih-frontpage-imagefill', plugins_url('/js/jquery-imagefill.js', __FILE__), array( 'jquery' ), '2.1', true );
    wp_register_script( 'animikii-ceih-frontpage-settings', plugins_url('/js/aki-frontpage.js', __FILE__), array( 'jquery' ), '2.1', true );
    wp_enqueue_script('animikii-ceih-frontpage-masonry');
    wp_enqueue_script('animikii-ceih-frontpage-imagesloaded');
    wp_enqueue_script('animikii-ceih-frontpage-imagefill');
    wp_enqueue_script('animikii-ceih-frontpage-settings');
  }

  /**
   * start function.
   * Set up in the theme redirect part
   * @access public
   * @return bool
   */
  static function frontpage_start( $template ) {

    if( is_front_page() ) :
      $layout = UBC_Collab_Theme_Options::get('frontpage-layout');

      if($layout == 'default')
        return true;

      load_template( plugin_dir_path( __FILE__ ).'/frontpage/'.UBC_Collab_Theme_Options::get('frontpage-layout').'.php' );
      die();
    endif;

    return false;
  }

  static function get_sections() {
    $items = wp_get_nav_menu_items( 'TOP-NAV' );

    if(is_array($items)) {
      $sections = [];
      foreach ($items as $item) {
        if($item->title === 'Home') continue;

        $sections[$item->ID] = array(
          "id" => $item->object_id,
          "menu_id" => $item->ID,
          "menu_parent_id" => $item->menu_item_parent,
          "title" => $item->title,
          "slug" => $item->post_name,
          "has_children" => false
        );
      }

      foreach($sections as $section) {
        if( isset( $sections[$section["menu_parent_id"]] ) ) {
          $sections[$section["menu_parent_id"]]["has_children"] = true;
        }
      }

      return $sections;
    }
  }

   /**
   * show function.
   *
   * @access public
   * @param bool $echo (default: true)
   * @return string,void
   */
  static function show( $echo = true ) {
    $html = self::shell();

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
    $html = '<div class="section-container">';
    $html .= self::section_items();
    $html .= '</div>';
    return $html;
  }

  /**
   * section_items function.
   *
   * @access public
   * @return string
   */
  static function section_items() {
    $html = '';

    if ( apply_filters( 'has_nav_menu', true, 'primary' ) ) {
      $sections = self::get_sections();
      $html .= self::render_sections($sections, 0);
    }

    return $html;
  }

   /**
   * createTreeView function.
   *
   * @access public
   * @param array $array
   * @param mixed $current_parent_id
   * @param int $current_lvl (default: 0)
   * @param int $prev_lvl (default: -1)
   * @return string
   */
  static function render_sections($array, $current_parent_id, $current_lvl = 0, $prev_lvl = -1) {
    global $post;
    $html = '';

    foreach ($array as $menu_id => $item) {
      if ($current_parent_id == $item['menu_parent_id']) {
        $post_id = $item["id"];

        // Assign your post details
        $post = get_post($post_id);
        setup_postdata( $post );

        $description = rwmb_meta( 'akii_frontpage_description', null, $post_id );
        $class = '';

        // start
        if($current_lvl === 0) {
          if($item['has_children'])
            $class .= 'has-children';
          $html .= '<section class="section ' . $class . ' " id="section-' . $post_id . '">'; // section start
        } else if($current_lvl === 1) {
          $html .= '<div class="sub-grid-item">';
            $html .= '<div class="section-sub">';
              $html .= self::get_frontpage_image( $post_id, array( 'width'  => 500, 'height' => 200 ) );
        }

        // begin section
        if($current_lvl === 0) {
          $html .= '<div class="section-body">';
            $html .= '<h1>' . $item['title'] . '</h1>';
            $html .= '<p>' . $description . '</p>';
          $html .= '</div>';

          $html .= '<div class="section-header-image">';
            $html .= self::get_frontpage_image( $post_id );
          $html .= '</div>';

          if($item['has_children'])
            $html .= '<div class="row-fluid expand section-sub-grid">';
            $html .= '<div class="gutter-sizer"></div>';
            $html .= '<div class="grid-sizer"></div>';

        } else if($current_lvl === 1) {
          $html .= '<div class="section-sub-overlay">';
            $html .= '<a class="sub-link" href="' . get_permalink() . '">';
              $html .= $item['title'];
            $html .= '</a>';

            // if($item['has_children']) {
            //   $html .= '<a class="sub-toggle collapsed" data-toggle="collapse" href="#sub-section-' . $post_id . '">';
            //     $html .= '<span class="icon icon-plus"></span>';
            //   $html .= '</a>';
            // }
          $html .= '</div>';
          // $html .= '<div id="sub-section-' . $post_id . '" class="accordion-body sub-section collapse">';
          //   $html .= '<ul>';
        // } else if($current_lvl === 2) {
        //   $html .= '<li><a href="' . get_permalink() . '">' . $item['title'] . '</a></li>';
        }

        if ($current_lvl > $prev_lvl) {
          $prev_lvl = $current_lvl;
        }

        $current_lvl++;

        $html .= self::render_sections($array, $menu_id, $current_lvl, $prev_lvl);

        $current_lvl--;

        // end
        if($current_lvl === 0) {
          if($item['has_children'])
            $html .= '</div>';
          $html .= '</section>'; // section end
        } else if($current_lvl === 1) {
              //   $html .= '</ul>';
              // $html .= '</div>';
            $html .= '</div>';
          $html .= '</div>';
        }
      }
    }

    return $html;
  }

    /**
   * wave_resize_akii_frontpage_image function.
   * returns the url of the resized image
   *
   * @access public
   * @param string $url
   * @param mixed $width
   * @param mixed $height (default: null)
   * @param int $zc (default: 1)
   * @return string
   */
  static function wave_resize_akii_frontpage_image($url, $width, $height=null, $zc=1) {
    $args = array(
      'width' => $width,
      'height' => $height,
      'zc' => $zc
    );
    return  wave_resize_image_url( $url, $args );
  }

  /**
   * get_feature_image function
   * returns the image string for a front image
   *
   * @access public
   * @param int $id (default: null)
   * @param array $size (default: null)
   * @return string
   */
  static function get_frontpage_image( $id = null, $dimensions = null) {
    $size = 'large';
    if( !$dimensions ) {
      $dimensions = array(
        'width'  => 1200,
        'height' => 450
      );
      $size = 'full_image';
    }

    $html = '';

    if( !$id ) {
      $images = rwmb_meta( 'akii_frontpage_image', array( 'size' => $size, 'limit' => 1 ) );
    } else {
      $images = rwmb_meta( 'akii_frontpage_image', array( 'size' => $size, 'limit' => 1 ), $id );
    }

    if ( is_array($images) && !empty($images) ) {
      $image = reset($images);
      $html .= '<img src="' . self::wave_resize_akii_frontpage_image($image['url'], $dimensions['width'], $dimensions['height']) . '" alt="">';
    } else {
      $html .= '<img src="https://placehold.it/' . $dimensions['width'] . 'x' . $dimensions['height'] . '" alt="Image Placeholder">';
    }

    return $html;
  }

}

Animikii_Frontpage::init();
