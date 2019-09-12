<?php
/**
 * Plugin Name: Animikii - Centre for Excellence in Indigenous Health
 * Plugin URI: http://www.anmimikii.com
 * Description: Animikii Design implementaion for Centre for Excellence in Indigenous Health - CLF 7.0 as according to UBC CLF requirements. Once activated, options available in <a href="/wp-admin/themes.php?page=theme_options">Theme Options</a>
 * Version: 0.2
 * Author: Dakota J Lightning (dakotalightning)
 * Author URI: http://www.anmimikii.com
 *
 */

Class Animikii_CEIH {

  static $prefix;

  public static function init() {

    // wp-hybrid-clf
    self::$prefix = hybrid_get_prefix();

    require_once( plugin_dir_path( __FILE__ ) . 'lib/meta-box/meta-box.php');

    require_once( plugin_dir_path( __FILE__ ) . 'class.quick-links.php');
    require_once( plugin_dir_path( __FILE__ ) . 'class.frontpage.php');
    require_once( plugin_dir_path( __FILE__ ) . 'class.side-menu.php');

    wp_register_style( 'animikii-ceih-style', plugins_url('/css/animikii.ceih.css', __FILE__) );
    wp_enqueue_style('animikii-ceih-style');

    add_filter( 'rwmb_meta_boxes', array( __CLASS__, 'akii_frontpage_meta_box' ) );
    add_filter( 'template_include', array( __CLASS__, 'new_default_template' ), 99 );

    add_action( self::$prefix."_before_container", array(__CLASS__, 'feature_image') , 1 );
  }

  function feature_image() { ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <?php self::display_the_feature_image(); ?>

      <?php endwhile; ?>

    <?php else: ?>

      <!-- Apologies, but no results were found. -->

    <?php endif; ?>

    <?php
  }

  /**
   * get_the_feature_image_url function.
   *
   *
   * @access public
   * @param mixed $post_id
   * @return string
   */
  function get_the_feature_image_url($post_id = null) {
    $args = array(
      'width' => 1200,
      'height' => 450,
      'zc' => 1
    );
    $feature_image_url = get_the_post_thumbnail_url( $post_id, array( 1200, 450 ) );
    if ( ! $feature_image_url ) {
      return false;
    }
    return $feature_image_url;
  }

  /**
   * display_the_feature_image function.
   *
   *
   * @access public
   * @return void
   */
  function display_the_feature_image() {
    $args = array(
      'width' => 1200,
      'height' => 450,
      'zc' => 1
    );

    $feature_image_url = self::get_the_feature_image_url( get_the_ID() );
    if ( ! $feature_image_url ) {
      return false;
    }

    echo '<div class="post-header-image">';
      echo '<img src="' . wave_resize_image_url( $feature_image_url, $args ) . '" alt="">';
    echo '</div>';
  }

  function new_default_template( $template ) {

    $file = dirname(__FILE__) . '/default-template.php';

    if ( file_exists( $file ) ) {
      return $file;
    }

    return $template;
  }

  /**
   * register_side_menu_sidebars function.
   *
   *
   * @access public
   * @return void
   */
  function register_side_menu_sidebars() {

    register_sidebar( array(
      'name'          => 'Side Menu Top',
      'description'   => 'This will be displayed at the top of the Side Menu',
      'id'            => 'side_menu_top',
      'before_widget' => '<div class="menu-top-widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
      'name'          => 'Side Menu Bottom',
      'description'   => 'This will be displayed at the bottom of the Side Menu',
      'id'            => 'side_menu_bottom',
      'before_widget' => '<div class="menu-bottom-widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>',
    ) );

  }


  /**
   * akii_frontpage_meta_box function.
   * register the metaboxes for the front page content
   *
   * @access public
   * @return void
   */
  function akii_frontpage_meta_box( $meta_boxes ) {
    $prefix = 'akii_';

    $meta_boxes[] = array(
      'id' => 'akii_frontpage_box',
      'title' => esc_html__( 'Frontpage Settings', 'akii-ceih' ),
      'post_types' => array( 'page', 'post' ),
      'context' => 'normal',
      'priority' => 'high',
      'autosave' => false,
      'fields' => array(
        array(
          'id' => $prefix . 'frontpage_description',
          'type' => 'textarea',
          'name' => esc_html__( 'Description', 'akii-ceih' ),
          'rows' => 10,
        ),
        array(
          'id' => $prefix . 'frontpage_image',
          'type' => 'image_advanced',
          'name' => esc_html__( 'Image', 'akii-ceih' ),
          'max_file_uploads' => '1',
          'max_status' => false,
        ),
      ),
    );

    return $meta_boxes;
  }


}

add_action( 'init', array( 'Animikii_CEIH', 'init' ) );
add_action( 'widgets_init', array( 'Animikii_CEIH', 'register_side_menu_sidebars') );
