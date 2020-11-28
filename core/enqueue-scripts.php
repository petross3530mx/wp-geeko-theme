<?php

//
// ENQUEUE SCRIPTS
//

function gecko_scripts() {
  // Load our main stylesheet.
  wp_enqueue_style( 'gecko-styles', gecko_add_cachebust_arg(get_stylesheet_uri()) );
  wp_enqueue_style( 'gecko-icons-css', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/icons.css'), array(), wp_get_theme()->version );
  wp_enqueue_style( 'gecko-css', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/gecko.css'), array(), wp_get_theme()->version );
  wp_style_add_data( 'gecko-css', 'rtl', 'replace' );

  // Prints CSS variables selected from the Gecko customizer as inline style.
  $gecko_options = Gecko_Customizer_Options::get_instance();
  $gecko_preset = Gecko_Customizer_Preset::get_instance();
  $gecko_active_preset = get_option('gecko_active_preset', 'light');
  $gecko_preview = isset( $_GET['gecko-preview'] );
  $gecko_enable_user_preset = (int) $gecko_options-> get('opt_user_preset');
  $gecko_default_preset = $gecko_active_preset;

  if ( ! $gecko_preview && $gecko_enable_user_preset ) {
    $gecko_user_preset = get_user_meta( get_current_user_id(), 'peepso_gecko_user_theme', true );
    if ( $gecko_user_preset && $gecko_preset->get($gecko_user_preset) ) {
      $gecko_active_preset = $gecko_user_preset;
    }
  }



  $presets = Gecko_Customizer_Preset::get_instance()->list();

	$themes = array();
	foreach ($presets as $preset) {
		$themes[$preset['id']] = $preset['label'];
  }

  foreach($themes as $key => $theme) {
    $fallback = $key;
    break;
  }

  // if preset is hide, then use fallback
  if (!isset($themes[$gecko_active_preset])) {
    $gecko_active_preset = $fallback;
    if (isset($themes[$gecko_default_preset])) {
      $gecko_active_preset = $gecko_default_preset;
    }
  }

  $gecko_css = '';
  $body_class = '';

  $active_preset = $gecko_preset->get($gecko_active_preset);
  if ($active_preset && isset($active_preset['css_vars'])) {
    foreach ( $active_preset['css_vars'] as $key => $value ) {
        // skip preset
        if('--presets' == $key)                     { continue; }

        // skip preset-specific config values that are not CSS vars
        if(substr($key,0,6) == 'config') { continue; }

        $gecko_css .= "\t" . $key . ': ' . $value . ';' . PHP_EOL;
    }

    if(isset($active_preset['css_vars']['config-body-class'])) {
        $body_class = $active_preset['css_vars']['config-body-class'];
    }
  }

    add_filter('body_class', function($args) use ($gecko_active_preset, $body_class) {
        $args[]="gc-preset--$gecko_active_preset $body_class";

        return $args;
    });

  wp_add_inline_style( 'gecko-css', 'body {' . PHP_EOL . $gecko_css . '}' );

  // Gecko Scripts
  wp_enqueue_script( 'gecko-macy-js', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/js/macy.js'), array(), wp_get_theme()->version, true );
  wp_enqueue_script( 'gecko-sticky-js', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/js/sticky.js'), array(), wp_get_theme()->version, true );
  wp_enqueue_script( 'gecko-js', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/js/scripts.js'), array('jquery'), wp_get_theme()->version, true );
  wp_localize_script( 'gecko-js', 'geckodata', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' )
  ) );

  if ( class_exists( 'woocommerce' ) ) {
    wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css', array(), '1.11.4' );
  }
}
add_action( 'wp_enqueue_scripts', 'gecko_scripts' );

function gecko_admin_scripts() {
  wp_enqueue_style( 'gecko-admin-css', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/admin.css'), array(), wp_get_theme()->version );
  wp_enqueue_script( 'gecko-admin', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/js/admin.js'), 'jquery' );
  wp_enqueue_style( 'gecko-icons-css', gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/icons.css'), array(), wp_get_theme()->version );
}
add_action( 'admin_footer', 'gecko_admin_scripts' );
