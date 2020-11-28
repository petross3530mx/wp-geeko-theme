<?php
$navbar_class = "ps-navbar--gecko";

if (class_exists('PeepSo')) {

if(0==PeepSo::get_option('disable_navbar', 0)) {
    PeepSoTemplate::exec_template('general', 'js-unavailable');
    $PeepSoGeneral = PeepSoGeneral::get_instance();
    ?>

    <?php if (is_user_logged_in()) { ?>
        <!-- PeepSo Navbar -->
        <div class="ps-navbar <?php echo $navbar_class; ?> js-toolbar">
          <div class="ps-navbar__inner">
            <div class="ps-navbar__menu"><?php echo $PeepSoGeneral->render_navigation('primary'); ?></div>

            <div class="ps-navbar__menu ps-navbar__menu--mobile"><?php echo $PeepSoGeneral->render_navigation('mobile-secondary'); ?></div>

            <div class="ps-navbar__notifications"><?php echo $PeepSoGeneral->render_navigation('secondary'); ?></div>

            <div class="ps-navbar__toggle">
              <span class="ps-navbar__menu-item">
                  <a href="#" class="ps-navbar__menu-link ps-js-navbar-toggle" onclick="return false;">
                      <i class="gcis gci-bars"></i>
                  </a>
              </span>
            </div>
          </div>

          <div id="ps-mobile-navbar" class="ps-navbar__submenu"><?php echo $PeepSoGeneral->render_navigation('mobile-primary'); ?></div>
        </div>
        <!-- end: PeepSo Navbar -->
    <?php }
}

do_action('peepso_action_render_navbar_after');

}
?>
