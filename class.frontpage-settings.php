<?php

Class Animikii_Frontpage_Settings {

  /**
   * init function.
   *
   * @access public
   * @return void
   */
  static function init() {
    // admin side
    /* Use the admin_menu action to define the custom boxes */
    add_action( 'admin_menu', array( __CLASS__, 'init_frontpage_settings' ) );

    /* Use the save_post action to do something with the data entered */
    add_action('save_post',  array( __CLASS__, 'save_meta_data' ) );

    add_action( 'admin_print_styles-post-new.php', array( __CLASS__, 'script_and_style') );
    add_action( 'admin_print_styles-post.php', array( __CLASS__, 'script_and_style') );
  }

  // ADMIN SIDE
  /* Adds a custom section to the "advanced" Post and Page edit screens */
  function script_and_style(){
    global $post;

    if( !in_array( $post->post_type, array('post', 'page') ) )
      return;
    // add javascript
    wp_enqueue_style( 'frontpage_settings_style', plugins_url( '/css/frontpage-settings.css', __FILE__ ) );
  }

  /**
   * init_frontpage_settings function.
   *
   * @access public
   * @return void
   */
  function init_frontpage_settings() {

    // on posts
    add_meta_box(
      'animikii_ceih_frontpage_settings', // $id
      __( 'Settings for landing', 'animikii-ceih-frontpage-settings' ), // $title
      array( __CLASS__, 'display_meta_box' ), // $callback
      'post', // $page
      'normal', // $context
      'high' // $priority
    );

    // on pages
    add_meta_box(
      'animikii_ceih_frontpage_settings', // $id
      __( 'Settings for Frontpage', 'animikii-ceih-frontpage-settings' ), // $title
      array( __CLASS__, 'display_meta_box' ), // $callback
      'page', // $page
      'normal', // $context
      'high' // $priority
    );
  }

  /**
   * meta_box_display function.
   *
   * @access public
   * @return void
   */
  function display_meta_box( $post ) {

    $post_id = $post->ID;

    $text_settings = get_post_meta( $post_id, '_animikii_ceih_frontpage_settings', true );

       // Use nonce for verification
       echo '<input type="hidden" name="animikii_ceih_frontpage_settings_noncename" id="animikii_ceih_frontpage_settings_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    echo __( 'This is the text that will show up on the home page if this page is in the menu', 'animikii-ceih-frontpage-settings' );
    // The actual fields for data entry
    ?>
    <textarea class="animikii-ceih-frontpage-settings" name="animikii_ceih_frontpage_settings" id="animikii-ceih-frontpage-settings"><?php echo esc_textarea( $text_settings ); ?></textarea>
    <?php

  }

  /**
   * save_meta_data function.
   *
   * @access public
   * @param mixed $post_id
   * @return void
   */
  function save_meta_data( $post_id ) {
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
      return $post_id;

    if (isset($_POST['animikii_ceih_frontpage_settings_noncename']) && !wp_verify_nonce( $_POST['animikii_ceih_frontpage_settings_noncename'], plugin_basename(__FILE__) ))
        return $post_id;

    // only update the data if it is a string
    if(isset($_POST['animikii_ceih_frontpage_settings']) && is_string( $_POST['animikii_ceih_frontpage_settings'] ) )
      add_post_meta( $post_id, '_animikii_ceih_frontpage_settings', $_POST['animikii_ceih_frontpage_settings'], true) or update_post_meta( $post_id, '_animikii_ceih_frontpage_settings', $_POST['animikii_ceih_frontpage_settings'] );

    return $post_id;

  }

}
// new Animikii_Frontpage_Settings;
Animikii_Frontpage_Settings::init();
