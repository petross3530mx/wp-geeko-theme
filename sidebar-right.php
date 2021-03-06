<?php

// Get search visibility option from admin settings
$settings = GeckoConfigSettings::get_instance();

$pageid = get_proper_ID();

$hide_sidebars = get_post_meta($pageid, 'gecko-page-sidebars', true);
$hide_sidebars_mobile = get_post_meta($pageid, 'gecko-page-sidebars-mobile', true);
$sticky_sidebar = GeckoConfigSettings::get_instance()->get_option( 'opt_sticky_sidebar', 0 );

if (is_search()) {
  $hide_sidebars = 'both';

  if($settings->get_option( 'opt_sidebar_right_search_vis', 1 ) == 1) {
    $hide_sidebars = 'left';
  }

  if($settings->get_option( 'opt_sidebar_left_search_vis', 1 ) == 1 && $settings->get_option( 'opt_sidebar_right_search_vis', 1 ) == 1) {
    $hide_sidebars = 'left';
  }
}

if ( ! is_active_sidebar( 'sidebar-right' ) || $hide_sidebars == 'both' || $hide_sidebars == 'right' ) {
	// do nothing
} else {
	ob_start();
	dynamic_sidebar( 'sidebar-right' );
	$content = ob_get_clean();


	if(strlen(trim($content))) { ?>
        <div id="sidebar-right" class="sidebar <?php echo $sticky_sidebar ? 'sidebar--sticky' : '' ?> sidebar--right <?php if ($hide_sidebars_mobile == 1) { echo 'sidebar--hidden-mobile'; } ?>"><div class="sidebar__inner"><?php echo $content; ?></div></div>
	<?php }
}


?>
