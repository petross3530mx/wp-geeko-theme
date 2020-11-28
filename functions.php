<?php

// PeepSo Version Check
$ver_gecko = explode('.', wp_get_theme()->version);
array_pop($ver_gecko);
$ver_gecko = implode('.', $ver_gecko);

if(class_exists('PeepSo')) {

	$ver_peepso = explode('.',PeepSo::PLUGIN_VERSION);
	if(count($ver_peepso) == 4) {
		array_pop($ver_peepso);
	}

	$ver_peepso = implode('.', $ver_peepso);

	if($ver_peepso != $ver_gecko) {
		// @TODO RAISE WARNING #3343
		add_action('admin_notices', function () {
			echo '<div class="error peepso">' .
			     sprintf(__('Please make sure the first three version numbers of PeepSo plugins %s and Gecko theme %s match. It’d be best to update the plugins and theme to latest versions.', 'gecko'), PeepSo::PLUGIN_VERSION, wp_get_theme()->version)
			     . '</strong></div>';
		});
	}
}


//  ENQUEUE SCRIPTS
require_once( __DIR__ . '/core/enqueue-scripts.php');


//
//  REGISTER MENU
//
register_nav_menus( array(
	'primary-menu' => 'Header Menu',
	'mobile-menu' => 'Mobile Menu',
	'footer-menu'  => 'Footer Menu',
));


//
// LANGUAGE
//
function gecko_load_theme_textdomain() {
	load_theme_textdomain( 'gecko', get_template_directory() . '/language' );
}
add_action( 'after_setup_theme', 'gecko_load_theme_textdomain' );


// SETUP INITIAL CONFIG
/* Tell WordPress to run gecko_setup() when the 'after_switch_theme' hook is run. */
function gecko_setup() {
	$default_config = array(
		// 'opt_show_search_in_header' => '',
		// 'opt_limit_page_options' => '',
		// 'opt_sticky_sidebar' => '0',
		// 'opt_blog_sidebars' => '',
		// 'opt_blog_update' => '',
		// 'opt_blog_grid' => '',
		// 'opt_archives_grid' => '',
		// 'opt_search_grid' => '',
		// 'opt_woo_builder' => '',
		// 'opt_woo_sidebars'  => '',
		// 'opt_ld_sidebars' => '',
		'gecko_license' => '',
		// 'opt_limit_blog_post' => '0'
	);

	add_option('gecko_options', $default_config);
}
add_action( 'after_switch_theme', 'gecko_setup' );


//
//  INCLUDES
//

//  helper class
require_once( __DIR__ . '/core/helpers.php');

//  date class
require_once( __DIR__ . '/core/date.php');

//  OPTIONS class
require_once( __DIR__ . '/core/admin/options.php');

//  SETTINGS PAGE
require_once( __DIR__ . '/core/admin/settings.php');

//  PAGE BUILDERS PAGE
require_once( __DIR__ . '/core/admin/page_builders.php');

//  SETTINGS - LICENSE SUBPAGE
require_once( __DIR__ . '/core/admin/license.php');

//  CUSTOMIZER
require_once( __DIR__ . '/core/admin/customizer.php');

//  WIDGETS
require_once( __DIR__ . '/core/widgets.php');

//  PAGE OPTIONS
require_once( __DIR__ . '/core/page.php');

//  LANDING OPTIONS
require_once( __DIR__ . '/core/landing.php');

//  UTILITY FUNCTIONS
require_once( __DIR__ . '/core/utility.php');


// Save config
if(isset($_REQUEST['gecko_options']) && current_user_can('manage_options')) {

	$options = apply_filters('gecko_sanitize_option', $_REQUEST['gecko_options']);

	foreach ($options as $key => $value) {
		GeckoConfigSettings::get_instance()->set_option(
			$key,
			$value,
			TRUE
		);
	}
}


//
//  REDIRECTS
//
function is_login_page() {
	global $wp, $wpdb;
	$register_id = $wpdb->get_var('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_content LIKE "%[peepso_register]%" AND post_parent = 0');
	$recovery_id = $wpdb->get_var('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_content LIKE "%[peepso_recover]%" AND post_parent = 0');
	$reset_id = $wpdb->get_var('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_content LIKE "%[peepso_reset]%" AND post_parent = 0');

	if ( $GLOBALS['pagenow'] === 'wp-login.php' && ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'register' || is_page( array( $register_id, $recovery_id, $reset_id ) ) )
		return true;
	return false;
}

function guest_redirect() {

    // Do not redirect users who are logged in
    if(is_user_logged_in())     {   return false;   }

    // Get settings
    $settings = GeckoConfigSettings::get_instance();
    $redirect_page_id = $settings->get_option( 'opt_redirect_guest', 0 );

    // Exit if option is disabled
    if ($redirect_page_id < 1)   {   return false;   }

    // Do not interfere with login, registration, password reset and privacy policy
    if(is_login_page())         {   return false;   }
    if(is_privacy_policy())     {   return false;   }

    // Check exceptions
    $exception_ids = $settings->get_option( 'opt_redirect_guest_exceptions', '' );

    // Is it comma separated?
    if(strlen($exception_ids && stristr($exception_ids, ','))) {
        $exception_ids = explode( ',', $exception_ids );
    }

    // Or maybe it's just one number?
    if(!is_array($exception_ids)) {
        $exception_ids = [ $exception_ids ];
    }

    // If there is at least one condition
    global $wp;

    if(is_array($exception_ids) && count($exception_ids)) {
        foreach($exception_ids as $id) {
            $id = strtolower(trim($id));
            if( is_numeric($id) && is_page($id) ) {
                return false;
            }

            if('blog' == $id && is_home()) {
                return false;
            }
//            if(stristr($id, 'singular_')) {
//                $singular = explode('_', $id);
//                if( isset($singular[1]) && strlen($singular[1]) && is_singular([$singular[1]]) ) {
//                    return false;
//                }
//            }

            if('frontpage' == $id && is_front_page()) {
                return false;
            }
        }
    }

    // $redirect_page_id is the page id of the target landing page
    // Do not redirect if we are already there
    if( !is_page($redirect_page_id) ){
        $redirect = trim(get_permalink($redirect_page_id), '/');
        if ($redirect != home_url( $wp->request )) {
            wp_redirect( $redirect );
            exit;
        }
    }
}
add_action( 'template_redirect', 'guest_redirect' );

/**
 * Log errors.
 *
 * @param string $error_message
 */
function gecko_log_error($error_message) {
	if ( class_exists('PeepSoError') ) {
		new PeepSoError($error_message);
	}
}

// HTML Classes Array
function gecko_get_html_class( $class = '' ) {
	$classes = array();

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	$classes = apply_filters( 'gecko_html_class', $classes, $class );

	return array_unique( $classes );
}

function gecko_html_class( $class = '' ) {
	// Separates class names with a single space, collates class names for body element
	echo 'class="' . join( ' ', gecko_get_html_class( $class ) ) . '"';
}

//  HEADER FUNCTIONS
require_once( __DIR__ . '/core/header.php');

//  WooCommerce loop columns
/**
 * Change number or products per row to 3
 */
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

add_filter( 'woocommerce_widget_cart_is_hidden', 'always_show_cart', 40, 0 );
function always_show_cart() {
	return false;
}

add_action( 'admin_bar_menu', function() {
	if ( ! current_user_can( 'customize' ) ) {
		return;
	}

	global $wp_admin_bar;
	$nodes = $wp_admin_bar->get_nodes();
	if (isset($nodes['customize'])) {
		$nodes['customize']->href = admin_url() . 'admin.php?page=gecko-customizer';
		$wp_admin_bar->add_menu($nodes['customize']);
	}
}, 999 );

// Check for custom logo set by Gecko Customizer.
add_filter( 'theme_mod_custom_logo', 'gecko_theme_mod_custom_logo', 10, 1 );
function gecko_theme_mod_custom_logo( $attachment_id ) {
	$settings = GeckoConfigSettings::get_instance();
	$custom_logo_id = $settings->get_option( 'opt_custom_logo' );
	if ( $custom_logo_id ) {
		$attachment_id = $custom_logo_id;
	}

	return $attachment_id;
}

// Main layout class
function layout_main_class($id) {

	if(isset($_GLOBALS['layout_main_class'])) {
		return $_GLOBALS['layout_main_class'];
	}

	$main_class = '';
	$hide_sidebars = get_post_meta($id, 'gecko-page-sidebars', true);

	$has_left_sidebar = TRUE;
	$has_right_sidebar = TRUE;

	$has_left_widgets = is_active_sidebar( 'sidebar-left' );
	$has_right_widgets = is_active_sidebar( 'sidebar-right' );

	/** LEFT SIDEBAR **/

	// is left sidebar hidden in page meta?
	if(in_array($hide_sidebars, ['both','left'])) {
		$has_left_sidebar = FALSE;
	}

	// are there widgets in left sidebar?
	if($has_left_sidebar && !$has_left_widgets) {
		$has_left_sidebar = FALSE;
	}

	// is there content in left sidebar?
	if($has_left_sidebar && !isset($_GLOBALS['gecko_sidebar_left_content'])) {
		gecko_log_error('Initializing GLOBALS LEFT');
		ob_start();
		dynamic_sidebar( 'sidebar-left' );
		$_GLOBALS['gecko_sidebar_left_content'] = ob_get_clean();
	}

	if($has_left_sidebar && !strlen(trim($_GLOBALS['gecko_sidebar_left_content']))) {
		$has_left_sidebar = FALSE;
	}

	/** RIGHT SIDEBAR **/

	// is right sidebar hidden in page meta?
	if(in_array($hide_sidebars, ['both','right'])) {
		$has_right_sidebar = FALSE;
	}

	// are there widgets in right sidebar?
	if($has_right_sidebar && !$has_right_widgets) {
		$has_right_sidebar = FALSE;
	}

	// is there content in right sidebar?
	if($has_right_sidebar && !isset($_GLOBALS['gecko_sidebar_right_content'])) {
		gecko_log_error('Initializing GLOBALS RIGHT');
		ob_start();
		dynamic_sidebar( 'sidebar-right' );
		$_GLOBALS['gecko_sidebar_right_content'] = ob_get_clean();
	}

	if($has_right_sidebar && !strlen(trim($_GLOBALS['gecko_sidebar_right_content']))) {
		$has_right_sidebar = FALSE;
	}


	if($has_left_sidebar && !$has_right_sidebar) {
		$main_class ='main--left';
	}

	if($has_right_sidebar && !$has_left_sidebar) {
		$main_class ='main--right';
	}

	if($has_right_sidebar && $has_left_sidebar) {
		$main_class = 'main--both';
	}

	gecko_log_error("Gecko main class: $main_class");

	$_GLOBALS['layout_main_class'] = $main_class;

	return $main_class;
}

function gecko_is_shop() {
	return function_exists('is_shop') ? is_shop() : FALSE;
}

add_filter('body_class', function( $classes ) {
	global $post;

	$settings = GeckoConfigSettings::get_instance();
	$class = $settings->get_option( 'opt_ps_side_to_side', 0 );

	// PeepSo/PeepSo#4456 add special <body> class to PeepSo Pages

	if (! is_404() || gecko_is_shop()) {
		if ( class_exists('PeepSo') ) {
			$shortcodes = PeepSo::get_instance()->all_shortcodes();
			foreach ($shortcodes as $sc => $method) {
				if (stristr($post->post_content, "[$sc")) {
					if($class == 1) {
						$classes[] = 'peepso-page peepso-sts';
					} else {
						$classes[] = 'peepso-page';
					}
					break;
				}
			}
		}
	}

	if ( is_user_logged_in() ) {
		if ( get_user_option( 'gecko_darkmode', get_current_user_id() ) ) {
			$classes[] = 'gc-theme--dark';
		}
	}

	return $classes;
});

// Add additional user preference for PeepSo.
add_filter( 'peepso_profile_preferences', 'gecko_peepso_profile_preferences', 10, 1 );
function gecko_peepso_profile_preferences( $pref ) {
	require_once __DIR__ . '/core/admin/customizer-preset.php';
	require_once __DIR__ . '/core/admin/customizer-options.php';

	$gecko_options = Gecko_Customizer_Options::get_instance();
	$gecko_enable_user_preset = (int) $gecko_options-> get('opt_user_preset');
	if (!$gecko_enable_user_preset) {
		return $pref;
	}

	$presets = Gecko_Customizer_Preset::get_instance()->list(FALSE);

	$themes = array();
	foreach ($presets as $preset) {
		$themes[$preset['id']] = $preset['label'];
	}

	// Some color schemes might be removed with a filter, make sure the  fallback is to the first one available
	$themes = apply_filters('peepso_filter_gecko_user_color_schemes', $themes);

	if(count($themes)) {
        foreach ($themes as $key => $theme) {
            $fallback = $key;
            break;
        }

        $user_theme = get_user_meta(get_current_user_id(), 'peepso_gecko_user_theme', true);
        if (!($user_theme && isset($themes[$user_theme]))) {
            $user_theme = get_option('gecko_active_preset', $fallback);
        }

        $fields['peepso_gecko_user_theme'] = array(
            'label' => __('Preferred color theme', 'gecko'),
            'type' => 'select',
            'options' => $themes,
            'value' => $user_theme,
            'validation' => array(),
            'loading' => TRUE,
        );

        $pref['gecko'] = array(
            'title' => __('Theme', 'gecko'),
            'items' => $fields,
        );
    }

	return $pref;
}

// Add PeepSo theme override message.
add_filter( 'peepso_theme_override', 'gecko_peepso_theme_override', 10, 1 );
function gecko_peepso_theme_override( $override_message ) {

    $override_message = sprintf(
        'This feature is controlled by %s.',
        '<a href="' . admin_url() . 'admin.php?page=gecko-customizer">Gecko ' . __('theme'). ' <i class="fa fa-external-link"></i></a>'
    );

	return $override_message;
}

/**
 * Add cache buster for resource URL.
 *
 * @since 3.0.0.3
 *
 * @param string $url
 * @return string
 */
function gecko_add_cachebust_arg($url) {
	$enabled = defined('GECKO_DEV_MODE_CACHE_BUSTING') && GECKO_DEV_MODE_CACHE_BUSTING;

	// Respect PeepSo setting if present.
	if (!$enabled && class_exists('PeepSo')) {
		$enabled = PeepSo::get_option_new('cache_busting');
	}

	if ($enabled) {
		$base_url = get_stylesheet_directory_uri();
		$base_path = get_stylesheet_directory();
		$file = str_replace($base_url, $base_path, $url);
		if (file_exists($file)) {
			$url = add_query_arg('mt', filemtime($file), $url);
		}
	}

	return $url;
}
