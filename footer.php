  <?php if (! is_page_template( 'page-tpl-landing.php' ) ) : ?>
    <?php get_template_part( 'template-parts/widgets/bottom' ); ?>
  <?php endif; ?>

  <?php
  $hide_widgets = get_post_meta(get_proper_ID(), 'gecko-page-footer-mobile', true);
  $hide_sidebar_left = get_post_meta(get_proper_ID(), 'gecko-page-left-sidebar-mobile', true);
  $hide_sidebar_right = get_post_meta(get_proper_ID(), 'gecko-page-right-sidebar-mobile', true);
  $hide_footer = get_post_meta(get_proper_ID(), 'gecko-page-hide-footer', true);

  // Get search visibility option from admin settings
  $settings = GeckoConfigSettings::get_instance();

  if($settings->get_option('opt_sidebar_left_mobile_vis', '1') == 0) {
    $hide_sidebar_left = TRUE;
  }

  if($settings->get_option('opt_sidebar_right_mobile_vis', '1') == 0) {
    $hide_sidebar_right = TRUE;
  }

  if ($hide_widgets == 1) :
  ?>
  <style>
  @media screen and (max-width: 980px) {
    .footer__wrapper {
      display: none;
    }
  }
  </style>
  <?php endif; ?>

  <?php if ($hide_sidebar_left == 1) : ?>
    <style>
    @media screen and (max-width: 980px) {
      .sidebar--left {
        display: none;
      }
    }
    </style>
  <?php endif; ?>

  <?php if ($hide_sidebar_right == 1) : ?>
    <style>
    @media screen and (max-width: 980px) {
      .sidebar--right {
        display: none;
      }
    }
    </style>
  <?php endif; ?>

  <?php if(get_theme_mod('general_scroll_to_top', '1') == "1") : ?>
  <a href="#body" class="gc-scroll__to-top js-scroll-top"><i class="gcis gci-angle-up"></i></a>
  <?php endif; ?>

  <?php if (! is_page_template( 'page-tpl-landing.php' ) ) : ?>
    <?php if(! $hide_footer) : ?>
    <footer class="gc-footer">
      <?php if (! is_page_template( 'page-tpl-landing.php' ) ) : ?>
        <?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
        <div class="gc-footer__grid">
          <!-- Include widgets -->
          <?php dynamic_sidebar( 'footer-widgets' ); ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>
      <div class="gc-footer__bottom">
        <div class="gc-footer__bottom-inner">
          <div class="gc-footer__copyrights">
            <?php
            $line_1 = $settings->get_option( 'opt_footer_text_line_1', FALSE);
            if(FALSE === $line_1) {
                $line_1 = get_bloginfo('name');
            }
            if (strlen($line_1) ) : ?>
              <?php echo $line_1; ?>
            <?php endif; ?>
            <?php
            $line_2 = $settings->get_option( 'opt_footer_text_line_2', FALSE);
            if(FALSE === $line_2) {
                $line_2 = 'All rights reserved';
            }
            if (strlen($line_2)) : ?>
            <div class="gc-footer__rights">
              <?php echo $line_2; ?>
            </div>
            <?php endif; ?>
          </div>

          <ul class="gc-footer__menu"><?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'items_wrap' => '%3$s', 'container' => false, 'fallback_cb' => false ) ); ?></ul>

          <?php if ( is_active_sidebar( 'footer-social' ) ) : ?>
          <div class="gc-footer__social">
            <!-- <a href="javascript:" class="gc-footer__social-item gc-footer__social-item--facebook">
              <i class="gcib gci-facebook-f"></i>
            </a>
            <a href="javascript:" class="gc-footer__social-item gc-footer__social-item--twitter">
              <i class="gcib gci-twitter"></i>
            </a>
            <a href="javascript:" class="gc-footer__social-item gc-footer__social-item--instagram">
              <i class="gcib gci-instagram"></i>
            </a> -->
            <?php dynamic_sidebar( 'footer-social' ); ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </footer>
    <?php endif; ?>
  <?php endif; ?>

  <?php wp_footer(); ?>
  </body>
</html>
