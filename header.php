<?php

// Get search visibility option from admin settings
$settings = GeckoConfigSettings::get_instance();

// Header settings
$header_vis = get_post_meta(get_proper_ID(), 'gecko-page-hide-header', true);
$header_menu_vis = get_post_meta(get_proper_ID(), 'gecko-page-hide-header-menu', true);
$header_blend = get_post_meta(get_proper_ID(), 'gecko-page-transparent-header', true);
$header_full = get_post_meta(get_proper_ID(), 'gecko-page-full-width-header', true);

if ($header_blend == 1) {
  add_filter( 'gecko_header_class', function( $classes ) {
      return array_merge( $classes, array( 'gc-header--transparent' ) );
  } );

  add_filter( 'gecko_html_class', function( $classes ) {
      return array_merge( $classes, array( 'header-is-transparent' ) );
  } );
}

if ($header_full == 1) {
  add_filter( 'gecko_header_class', function( $classes ) {
      return array_merge( $classes, array( 'gc-header--full' ) );
  } );
}

if ($header_vis == 1) {
  add_filter( 'gecko_html_class', function( $classes ) {
      return array_merge( $classes, array( 'header-is-hidden' ) );
  } );
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php gecko_html_class('no-js gecko'); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <!--<meta name="viewport" content="width=device-width">-->

    <?php if (0 == $settings->get_option( 'opt_zoom_feature', 0 ) ) : ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php else : ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php endif; ?>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <!--[if lt IE 9]>
    <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
    <![endif]-->

    <?php wp_head(); ?>

    <style>
      html {
        margin-top: 0 !important;
      }
    </style>
  </head>
  <body id="body" <?php body_class(); ?>>
    <!-- HEADER -->
    <?php do_action('gecko_before_header'); ?>

    <?php if (!$header_vis) : ?>
    <div <?php gecko_header_class('gc-header'); ?>>
      <div class="gc-header__inner">
        <!-- Logo -->
        <?php $logo_url = esc_url( home_url( '/' ) ); ?>

        <?php if ($settings->get_option( 'opt_custom_mobile_logo', 0 ) ) : ?>
          <div class="gc-header__logo gc-header__logo--mobile">
            <a class="gc-logo__link" href="<?php echo $logo_url; ?>"><img class="gc-logo__image" src="<?php echo wp_get_attachment_url($settings->get_option( 'opt_custom_mobile_logo')); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
          </div>
        <?php endif; ?>
        <div class="gc-header__logo">
          <?php if (has_custom_logo()) : ?>
            <div class="gc-logo__image"><?php echo the_custom_logo(); ?></div>
          <?php else : ?>
            <a class="gc-logo__link" href="<?php echo $logo_url; ?>"><h1><?php bloginfo( 'name' ); ?></h1></a>
          <?php endif; ?>
        </div>

        <?php if (!$header_menu_vis) : ?>
        <!-- Header Menu -->
        <div class="gc-header__menu">
          <ul>
            <?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'items_wrap' => '%3$s', 'fallback_cb' => false,'container' => false ) ); ?>
            <?php if (1 == $settings->get_option( 'opt_show_search_in_header', 1 ) ) : ?>
            <!-- Header Search -->
            <div class="gc-header__search">
              <a href="javascript:" class="gc-header__search-toggle"><i class="gcis gci-search"></i></a>

              <div class="gc-header__search-box">
                <div class="gc-header__search-box-inner">
                  <div class="gc-header__search-input-wrapper">
                    <i class="gcis gci-search"></i>
                    <?php if (is_active_sidebar( 'header-search' )) : ?>
                      <?php dynamic_sidebar( 'header-search' ); ?>
                    <?php else : ?>
                      <form action="<?php echo home_url( '/' ); ?>" method="get" class="gc-header__search-form">
                          <input type="text" class="gc-header__search-input" name="s" placeholder="<?php esc_html_e( 'Type to search', 'gecko' ); ?>.." id="search" value="<?php the_search_query(); ?>" />
                      </form>
                    <?php endif; ?>
                  </div>
                  <a href="javascript:" class="gc-header__search-toggle"><i class="gcis gci-times"></i></a>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Header Addons -->
        <?php if (1 == $settings->get_option( 'opt_widget_icon', 1 ) && is_user_logged_in() ) : ?>
          <?php if (is_active_sidebar( 'header-widgets' )) : ?>
            <div class="gc-header__addons">
              <!-- Header Widget -->
              <div class="gc-header__widget">
                <div class="gc-header__widget-inner">
                  <?php dynamic_sidebar( 'header-widgets' ); ?>
                </div>

                <a class="gc-header__widget-toggle js-header-widget-toggle" href="javascript:">
                  <i class="gcis gci-user"></i>
                </a>
              </div>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <?php if (is_active_sidebar( 'header-widgets' )) : ?>
            <div class="gc-header__addons">
              <!-- Header Widget -->
              <div class="gc-header__widget">
                <?php dynamic_sidebar( 'header-widgets' ); ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (is_active_sidebar( 'header-cart' )) : ?>
        <div class="gc-header__cart-wrapper">
          <?php dynamic_sidebar( 'header-cart' ); ?>
          <a href="javascript:" class="gc-header__cart-toggle js-header-cart-toggle empty">
            <i class="gcis gci-shopping-basket"></i>
          </a>
        </div>
        <?php endif; ?>

        <a href="#" class="gc-header__menu-toggle gc-js-header-menu-open">
          <i class="gcis gci-bars"></i>
        </a>
      </div>
    </div>

    <div id="menu" class="gc-modal gc-modal--menu">
      <div class="gc-modal__inner">
        <div class="gc-modal__content">
          <ul class="gc-modal__menu">
            <?php if (has_nav_menu ('mobile-menu')) : ?>
              <?php wp_nav_menu( array( 'theme_location' => 'mobile-menu', 'items_wrap' => '%3$s', 'container' => false, 'fallback_cb' => false ) ); ?>
            <?php else : ?>
              <?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'items_wrap' => '%3$s', 'container' => false, 'fallback_cb' => false ) ); ?>
            <?php endif; ?>
            <?php if ( is_user_logged_in()) : ?>
            <li><a href="<?php echo wp_logout_url( home_url() ); ?>"><i class="gc-icon-sign-out-alt"></i> <?php esc_html_e( 'Logout', 'gecko' ); ?></a></li>
            <?php endif; ?>
          </ul>
        </div>

        <a href="#" class="gc-modal__close gc-js-header-menu-close">
          <i class="gcis gci-chevron-right"></i>
        </a>
      </div>
    </div>
    <?php endif; ?>

    <?php do_action('gecko_after_header'); ?>
    <!-- end: HEADER -->

    <?php if (! is_page_template( 'page-tpl-landing.php' ) ) : ?>
    <!-- TOP WIDGETS -->
      <?php get_template_part( 'template-parts/widgets/top' ); ?>
    <!-- end: TOP WIDGETS -->
    <?php endif; ?>
