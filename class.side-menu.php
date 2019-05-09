<?php

Class Side_Menu {

  static $hybrid_prefix;

  /**
   * init function.
   *
   * @access public
   * @return void
   */
  static function init() {
    require_once( plugin_dir_path( __FILE__ ) . 'class.side-menu-walker.php');

    self::$hybrid_prefix = hybrid_get_prefix();

    // if( class_exists( 'UBC_Collab_Theme_Options' ) )
    // 	if( UBC_Collab_Theme_Options::get( 'navigation-header-display' ) )

    // remove the header and header menu
    remove_action( self::$hybrid_prefix.'_header', array( 'UBC_Collab_Navigation', 'header_menu'), 12 );
    remove_action( self::$hybrid_prefix.'_header', array( 'UBC_Collab_CLF', 'header' ) );

    // replace the header and header menu with our own
    add_action( self::$hybrid_prefix.'_header', array( __CLASS__, 'header_menu'), 12 );
    add_action( self::$hybrid_prefix.'_header', array( __CLASS__, 'header') );

    // add scripts to init the menu
    wp_register_script( 'animikii-ceih-side-menu-script', plugins_url('/js/aki-main-menu.js', __FILE__), array( 'jquery' ), '2.1', true );
    wp_enqueue_script('animikii-ceih-side-menu-script');
  }


  /**
   * header_menu function.
   *
   * @access public
   * @return void
   */
  static function header_menu() {
    ?>
      <!-- Push Menu -->
      <div id="main-menu-container">
        <div class="menu-container">
          <?php if ( is_active_sidebar( 'side_menu_top' ) ) : ?>
            <div class="menu-top">
              <?php if ( ! dynamic_sidebar( 'side_menu_top' ) ) : ?>

              <?php endif ?>
            </div>
          <?php endif ?>

          <!-- main-menu -->
          <?php wp_nav_menu(
            array(
              'theme_location'  => 'primary',
              'walker'          => new Side_Menu_Walker(),
              'fallback_cb'     => false,
              'container'       => false,
              'menu_class'      => false,
              'menu_id'         => false
            )
          ); ?>

          <?php if ( is_active_sidebar( 'side_menu_bottom' ) ) : ?>
            <div class="menu-bottom">
              <div class="menu-bottom-widget widget_search">
                <form method="get" class="search-form" id="search-formhybrid-search" action="http://localhost:8080/">
                  <div>
                    <input class="search-text search-query" placeholder="Search Learning Circle" type="text" name="s" id="search-texthybrid-search" value=""
                      onfocus="if(this.value==this.defaultValue)this.value='';"
                      onblur="if(this.value=='')this.value=this.defaultValue;">
                  </div>
                </form>
              </div>

              <?php if ( ! dynamic_sidebar( 'side_menu_bottom' ) ) : ?>

              <?php endif ?>
            </div>
          <?php endif ?>
          <!-- /mp-menu -->
        </div>
      </div>
      <!-- End Push Menu -->
    <?php
  }

  /**
  * replacement header
  *
  * @access public
  * @return void
  */
  static function header() {
    UBC_Collab_CLF::global_utility_menu();

    UBC_Collab_CLF::clf_header();

    Side_Menu::unit_name_bar();
  }


  /**
  * Unit Name Bar
  */
  static function unit_name_bar() {
    $faculty_name = UBC_Collab_CLF::get_faculty_name(UBC_Collab_Theme_Options::get("clf-unit-faculty"));
    $website_name = UBC_Collab_Theme_Options::get("clf-unit-unit-website");
    $unit_url = UBC_Collab_Theme_Options::get("clf-unit-url");

    $single_treatment = (empty($faculty_name)) ? 'class="ubc7-single-element"':"";
    ?>
      <div class="main-side-menu">
        <a href="#" id="main-side-menu-toggle" class="menu-trigger">
          <span class="sr-only">Toggle navigation</span>
          <span class="menu-trigger-bar first"></span>
          <span class="menu-trigger-bar middle"></span>
          <span class="menu-trigger-bar last"></span>
        </a>
      </div>

      <!-- UBC Unit Identifier -->
      <div id="ubc7-unit" class="row-fluid expand">
        <?php if (UBC_Collab_CLF::is_full_width()) { echo '<div class="container">'; } ?>
          <div class="span12">
              <!-- Unit Name -->
              <div id="ubc7-unit-name" <?php echo $single_treatment; ?>>
                  <a href="<?php echo $unit_url; ?>" title="<?php echo $website_name; ?>"><span id="ubc7-unit-faculty"><?php echo $faculty_name; ?></span><span id="ubc7-unit-identifier"><?php echo $website_name; ?></span></a>
              </div>
          </div>
          <?php if (UBC_Collab_CLF::is_full_width()) { echo '</div>'; } ?>
      </div>
      <!-- End of UBC Unit Identifier -->
    <?php
  }

}

Side_Menu::init();
