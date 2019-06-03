<?php
/**
* Frontpage Option 4
*
* Slider
* Content
*
**/


get_header(); // Loads the header.php template.

//Since this is the first frontpage layout option, the span will be 8 and 4
 ?>
</div>

<?php Quick_Links::show(); ?>

<!-- ================= sections ==================================== -->
<div class="section-container">

  <?php Animikii_Frontpage::show(); ?>

</div>
<!-- ================= sections ==================================== -->

<section class="section section-registration">
  <div class="registration">
    <h1>Registration</h1>
    <h3>To register for any of our online Learning Circle events, please visit our <a href="https://learningcircle.ubc.ca/">Learning Circle registration page.</a></h3>
  </div>
</section>


<section class="section section-circle">
  <div class="circle">
    <h1>Stay in the Circle</h1>
    <h3>Sign up for the UBC Learning Circle mailing list!</h3>

    <div class="textwidget"><!-- Begin Mailchimp Signup Form -->
      <link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet" type="text/css" />
      <style type="text/css">
        #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
        /* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
          We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
      </style>
      <div id="mc_embed_signup">
      <form action="https://ubc.us6.list-manage.com/subscribe/post?u=8f90692f19eaeaf98686d5357&amp;id=fa4406f4ca" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
        <div id="mc_embed_signup_scroll">
          <input type="email" class="signup-input" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required="" />
          <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
          <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_8f90692f19eaeaf98686d5357_fa4406f4ca" tabindex="-1" value=""></div>
          <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn"></div>
        </div>
      </form>
    </div>
  </div>
</section>

<div class="row-fluid expand">
  <div id="content" class="hfeed span12">

    <?php do_atomic( 'before_content' ); // hybrid_before_content ?>

    <?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

        <?php //do_atomic( 'before_entry' ); // hybrid_before_entry -- Frontpage should not have a title unless the user specifies it. ?>
        <div class="entry-content">
          <?php the_content( sprintf( __( 'Continue reading %1$s', 'hybrid' ), the_title( ' "', '"', false ) ) ); ?>
          <?php wp_link_pages( array( 'before' => '<p class="page-links pages">' . __( 'Pages:', 'hybrid' ), 'after' => '</p>' ) ); ?>
        </div><!-- .entry-content -->

        <?php do_atomic( 'after_entry' ); // hybrid_after_entry ?>

      </div><!-- .hentry -->

      <?php if ( is_singular() ) { ?>

        <?php do_atomic( 'after_singular' ); // hybrid_after_singular ?>

        <?php comments_template( '/comments.php', true ); // Loads the comments.php template ?>

      <?php } ?>

      <?php endwhile; ?>

    <?php else: ?>

      <?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

    <?php endif; ?>

    <?php do_atomic( 'after_content' ); // hybrid_after_content ?>

  </div><!-- .content .hfeed -->

<?php get_footer(); // Loads the footer.php template. ?>

