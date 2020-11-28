<?php

//  Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

if (!class_exists('Gecko_Customizer_Preset')) {
	/**
	 * Gecko_Customizer_Preset class.
	 *
	 * @since 3.0.0.0
	 */
	class Gecko_Customizer_Preset {
		private static $instance = null;

		public static function get_instance() {
			if (null === self::$instance) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Default constructor.
		 *
		 * @since 3.0.0.0
		 */
		private function __construct() {
		}

		/**
		 * Get default preset settings.
		 *
		 * @since 3.0.0.0
		 *
		 * @return array
		 */
		protected function get_default() {
			// Default backend settings.
			$settings = [
				'opt_custom_logo' => '',
				'opt_custom_mobile_logo' => '',
				'opt_user_preset' => '',
				'opt_show_search_in_header' => '1',
				'opt_zoom_feature' => '',
				'opt_show_adminbar' => '2',
				'opt_limit_page_options' => '',
				'opt_sticky_sidebar' => '',
				'opt_sidebar_left_mobile_vis' => '1',
				'opt_sidebar_right_mobile_vis' => '1',
				'opt_blog_sidebars' => '1',
				'opt_limit_blog_post' => '',
				'opt_blog_update' => '1',
				'opt_blog_grid' => '',
				'opt_archives_grid' => '',
				'opt_search_grid' => '',
				'opt_sidebar_left_search_vis' => '1',
				'opt_sidebar_right_search_vis' => '1',
        'opt_footer_text_line_1' => get_bloginfo('name'),
        'opt_footer_text_line_2' => 'All rights reserved',
				'opt_widget_icon' => '1',
				// PeepSo
				'opt_ps_side_to_side' => '',
				'opt_ps_profile_layout' => '1',
				'opt_ps_profile_page_cover_centered' => '',
				// WooCommerce
				'opt_woo_builder' => '',
				'opt_woo_sidebars' => '1',
				// LearnDash
				'opt_ld_sidebars' => '1',
			];

			// Default CSS variables.
			$css_vars = [
				'--COLOR--PRIMARY' => '#2979FF',
				'--COLOR--PRIMARY--SHADE' => '#BBDEFB',
				'--COLOR--PRIMARY--LIGHT' => '#64B5F6',
				'--COLOR--PRIMARY--ULTRALIGHT' => '#E3F2FD',
				'--COLOR--PRIMARY--DARK' => '#2962FF',

				'--COLOR--SUCCESS' => '#66BB6A',
				'--COLOR--SUCCESS--LIGHT' => '#C8E6C9',
				'--COLOR--SUCCESS--ULTRALIGHT' => '#E8F5E9',
				'--COLOR--SUCCESS--DARK' => '#4CAF50',

				'--COLOR--WARNING' => '#FFA726',
				'--COLOR--WARNING--LIGHT' => '#FFE0B2',
				'--COLOR--WARNING--ULTRALIGHT' => '#FFF3E0',
				'--COLOR--WARNING--DARK' => '#F57C00',

				'--COLOR--ABORT' => '#E53935',
				'--COLOR--ABORT--LIGHT' => '#FFCDD2',
				'--COLOR--ABORT--ULTRALIGHT' => '#FFEBEE',
				'--COLOR--ABORT--DARK' => '#D32F2F',

				'--FONT-SIZE' => '18px',

				// TEMPORARY LIGHT PRESET
				'--BORDER-RADIUS' => '4px',
				'--COLOR--APP' => '#fff',
				'--COLOR--APP--GRAY' => '#f7f7f7',
				'--COLOR--APP--LIGHTGRAY' => '#fbfbfb',
				'--COLOR--APP--DARKGRAY' => '#eee',
				'--COLOR--APP--DARK' => '#46494f',
				'--COLOR--APP--DARKER' => '#202124',
				'--COLOR--TEXT' => '#494954',
				'--COLOR--TEXT--LIGHT' => '#91919d',
				'--COLOR--HEADING' => '#333',
				'--COLOR--LINK' => 'var(--COLOR--PRIMARY)',
				'--COLOR--LINK-HOVER' => 'var(--COLOR--PRIMARY--DARK)',
				'--COLOR--LINK-FOCUS' => 'var(--COLOR--PRIMARY--DARK)',
				'--DIVIDER' => 'rgba(70, 77, 87, 0.15)',
				'--DIVIDER--LIGHT' => 'rgba(70, 77, 87, 0.1)',
				'--DIVIDER--LIGHTEN' => 'rgba(70, 77, 87, 0.05)',
				'--DIVIDER--DARK' => 'rgba(70, 77, 87, 0.25)',
				'--DIVIDER--R' => 'rgba(255, 255, 255, 0.1)',
				'--DIVIDER--R--LIGHT' => 'rgba(255, 255, 255, 0.05)',
				'--BOX-SHADOW-DIS' => '0',
				'--BOX-SHADOW-BLUR' => '0',
				'--BOX-SHADOW-THICKNESS' => '1px',
				'--BOX-SHADOW-COLOR' => 'rgba(70, 77, 87, 0.05)',
				'--BOX-SHADOW--HARD' => '0 var(--BOX-SHADOW-DIS) var(--BOX-SHADOW-BLUR) var(--BOX-SHADOW-THICKNESS) var(--BOX-SHADOW-COLOR)',

				// THEME
				'--c-gc-layout-width' => '1280px',
				'--c-gc-main-column' => '2fr',
				'--c-gc-layout-gap' => '30px',
				'--c-gc-header-height' => '80px',
				'--c-gc-header-bg' => 'var(--COLOR--APP)',
				'--c-gc-header-text-color' => 'var(--COLOR--TEXT--LIGHT)',
				'--c-gc-header-link-color' => 'var(--COLOR--TEXT--LIGHT)',
				'--c-gc-header-link-color-hover' => 'var(--COLOR--TEXT)',
				'--c-gc-header-link-active-indicator' => 'var(--COLOR--PRIMARY--LIGHT)',
				'--c-gc-header-font-size' => '100%',
				'--c-gc-header-sticky' => 'fixed',
				'--c-gc-header-sticky-mobile' => 'fixed',
				'--c-gc-header-menu-alignment' => '0 0 0 auto',
				'--c-gc-header-menu-font-size' => '90%',
				'--c-gc-footer-col' => '4',
				'--c-gc-footer-bg' => 'var(--COLOR--APP)',
				'--c-gc-footer-text-color' => 'var(--COLOR--TEXT)',
				'--c-gc-footer-text-color-light' => 'var(--COLOR--TEXT--LIGHT)',
				'--c-gc-footer-links-color' => 'var(--COLOR--LINK)',
				'--c-gc-footer-links-color-hover' => 'var(--COLOR--LINK-HOVER)',
				'--c-gc-footer-widgets-vis' => 'grid',
				'--c-gc-footer-widgets-vis-mobile' => 'block',
				'--c-gc-sidebar-left-width' => '1fr',
				'--c-gc-sidebar-right-width' => '1fr',
				'--c-gc-sidebar-widgets-gap' => '30px',
				'--c-gc-post-image-max-height' => '100%',
				'--c-gc-blog-image-max-height' => '100%',

				// WIDGETS
				'--c-gc-widgets-top-col' => '4',
				'--c-gc-widgets-bottom-col' => '4',
				'--c-gc-widgets-top-vis' => 'block',
				'--c-gc-widgets-top-vis-mobile' => 'block',
				'--c-gc-widgets-bottom-vis' => 'block',
				'--c-gc-widgets-bottom-vis-mobile' => 'block',

				'--c-gc-widget-bg' => 'var(--COLOR--APP)',
				'--c-gc-widget-text-color' => 'var(--COLOR--TEXT)',
				// '--c-gc-widget-links-color' => 'var(--COLOR--LINK)',
				// '--c-gc-widget-links-color-hover' => 'var(--COLOR--LINK--HOVER)',

				'--widget--gradient-bg' => '#8EC5FC',
				'--widget--gradient-bg-2' => '#E0C3FC',
				'--widget--gradient-color' => '#fff',
				'--widget--gradient-links' => 'rgba(255, 255, 255, 0.8)',
				'--widget--gradient-links-hover' => '#fff',

				// Temporary settings - BETA 3
				'--c-gc-show-page-title' => 'block',
				'--c-ps-avatar-style' => '100%',
				'--c-gc-body-bg' => '#f5f5f5',
				'--GC-FONT-FAMILY' => 'Sora',

				// PeepSo - Post
				'--c-ps-post-bg' => 'var(--COLOR--APP)',
				'--c-ps-post-text-color' => 'var(--COLOR--TEXT)',
				'--c-ps-post-text-color-light' => 'var(--COLOR--TEXT--LIGHT)',
				'--c-ps-post-font-size' => '16px',
				'--c-ps-post-pinned-border-color' => 'var(--DIVIDER)',
				'--c-ps-post-pinned-border-size' => '3px',

				'--c-ps-post-photo-width' => 'auto',
				'--c-ps-post-photo-limit-width' => '100%',
				'--c-ps-post-photo-height' => '500px',
				'--c-ps-post-gallery-width' => '100%',

				'--c-ps-navbar-bg' => 'var(--COLOR--APP--DARK)',
				'--c-ps-navbar-links-color' => 'rgba(255,255,255, .65)',
				'--c-ps-navbar-links-color-hover' => '#fff',
				'--c-ps-navbar-font-size' => '14px',
				'--c-ps-navbar-icons-size' => '16px',

				'--c-ps-postbox-bg' => 'var(--COLOR--APP)',
				'--c-ps-postbox-text-color' => 'var(--COLOR--TEXT)',
				'--c-ps-postbox-text-color-light' => 'var(--COLOR--TEXT--LIGHT)',
				'--c-ps-postbox-icons-color' => 'var(--c-ps-postbox-text-color-light)',
				'--c-ps-postbox-separator-color' => 'var(--DIVIDER--LIGHT)',
				'--c-ps-checkbox-border' => 'rgba(0,0,0, .1)', // add as option

				'--c-ps-profile-cover-height' => '40%',
				'--c-ps-profile-avatar-size' => '160px',

				'--c-ps-notification-unread-bg' => 'var(--PS-COLOR--PRIMARY--ULTRALIGHT)', // add as option

				'--c-ps-chat-window-notif-bg' => 'var(--COLOR--WARNING)',
				'--c-ps-chat-message-bg' => 'var(--COLOR--APP--DARKGRAY)',
				'--c-ps-chat-message-text-color' => 'var(--COLOR--TEXT)',
				'--c-ps-chat-message-bg-me' => 'var(--COLOR--PRIMARY)',
				'--c-ps-chat-message-text-color-me' => '#fff',
			];

			return [
				'settings' => $settings,
				'css_vars' => $css_vars,
			];
		}

		/**
		 * Get all presets.
		 *
		 * @since 3.0.0.0
		 *
		 * @return array
		 */
		public function list($defaults = TRUE) {
			$presets = [];

			if($defaults) {
                $defaults = $this->get_default();
                $default_settings = $defaults['settings'];
                $default_css_vars = $defaults['css_vars'];

                $presets['light'] = [
                    'id' => 'light',
                    'label' => __('Gecko','gecko') . ' ' . __('Light', 'gecko'),
                    'readonly' => true,
                    'settings' => array_merge($default_settings, []),
                    'css_vars' => array_merge($default_css_vars, []),
                ];

                $presets['dark'] = [
                    'id' => 'dark',
                    'label' => __('Gecko','gecko') . ' ' . __('Dark', 'gecko'),
                    'readonly' => true,
                    'settings' => array_merge($default_settings, []),
                    'css_vars' => array_merge($default_css_vars, [
                        '--COLOR--APP' => '#191919',
                        '--COLOR--APP--GRAY' => '#222',
                        '--COLOR--APP--LIGHTGRAY' => '#121212',
                        '--COLOR--APP--DARKGRAY' => '#1a1a1a',
                        '--COLOR--APP--DARK' => '#46494f',
                        '--COLOR--APP--DARKER' => '#202124',
                        '--COLOR--TEXT' => '#f9f9f9',
                        '--COLOR--TEXT--LIGHT' => '#8c8c8c',
                        '--COLOR--HEADING' => '#fff',
                        '--c-gc-body-bg' => '#111',
                        '--c-ps-checkbox-border' => 'rgba(255,255,255, .1)', // add as option
                        '--c-ps-notification-unread-bg' => 'var(--PS-COLOR--APP--DARK)', // add as option
                    ]),
                ];
            }

			$custom_presets = get_option('gecko_custom_presets', []);

			if(is_array($custom_presets) && count($custom_presets)) {
				foreach ( $custom_presets as $id => $preset ) {

					if(!is_array($preset)) {
						continue;
					}

					$id             = $preset['id'];
					$presets[ $id ] = [
						'id'       => $id,
						'label'    => $preset['label'],
						'settings' => $preset['settings'],
						'css_vars' => $preset['css_vars'],
					];
				}
			}
			return $presets;
		}

		/**
		 * Get a single preset.
		 *
		 * @since 3.0.0.0
		 *
		 * @param string $id
		 * @return array|boolean
		 */
		public function get($id) {
			$presets = $this->list();
			if (isset($presets[$id])) {
				return $presets[$id];
			}

			return false;
		}

		/**
		 * Add a new custom preset.
		 *
		 * @since 3.0.0.0
		 *
		 * @param string $label
		 * @param array $configs
		 * @return array
		 */
		public function add($label, $configs = []) {
			$label = sanitize_text_field($label);

			$id = preg_replace('#\s+#', '_', $label);
			$id = sanitize_title($id);

			// Check for existing preset IDs to prevent overwriting.
			$presets = $this->list();
			while (isset($presets[$id])) {
				$counter = 1;
				if (preg_match('#_(\d+)$#', $id, $matches)) {
					$counter += (int) $matches[1];
				}
				$id = preg_replace('#_(\d+)$#', '', $id);
				$id = $id . '_' . $counter;
			}

			$custom_presets = get_option('gecko_custom_presets', []);
			$custom_presets[$id] = [
				'id' => $id,
				'label' => $label,
				'settings' => isset($configs['settings']) ? $configs['settings'] : (object) [],
				'css_vars' => isset($configs['css_vars']) ? $configs['css_vars'] : (object) [],
			];
			update_option('gecko_custom_presets', $custom_presets);

			return $custom_presets[$id];
		}

		/**
		 * Update a custom preset.
		 *
		 * @since 3.0.0.0
		 *
		 * @param string $id
		 * @param array $configs
		 * @return array|boolean
		 */
		public function update($id, $configs = []) {
			$custom_presets = get_option('gecko_custom_presets', []);
			if (isset($custom_presets[$id])) {
				$preset = $custom_presets[$id];
				$custom_presets[$id] = [
					'id' => $id,
					'label' => $preset['label'],
					'settings' => isset($configs['settings']) ? $configs['settings'] : (object) [],
					'css_vars' => isset($configs['css_vars']) ? $configs['css_vars'] : (object) [],
				];
				update_option('gecko_custom_presets', $custom_presets);
				return $custom_presets[$id];
			}

			return false;
		}

		/**
		 * Rename a custom preset.
		 *
		 * @since 3.0.0.0
		 *
		 * @param string $id
		 * @param string $label
		 * @return array|boolean
		 */
		public function rename($id, $label) {
			$label = sanitize_text_field($label);
			if (strlen($label) === 0) {
				return false;
			}

			$custom_presets = get_option('gecko_custom_presets', []);
			if (isset($custom_presets[$id])) {
				$custom_presets[$id] = array_merge($custom_presets[$id], [ 'label' => $label ]);
				update_option('gecko_custom_presets', $custom_presets);
				return $custom_presets[$id];
			}

			return false;
		}

		/**
		 * Delete a custom preset.
		 *
		 * @since 3.0.0.0
		 *
		 * @param string $id
		 * @return array|boolean
		 */
		public function delete($id) {
			$custom_presets = get_option('gecko_custom_presets', []);
			if (isset($custom_presets[$id])) {
				$preset = $custom_presets[$id];
				unset($custom_presets[$id]);
				update_option('gecko_custom_presets', $custom_presets);
				return $preset;
			}

			return false;
		}

		/**
		 * Delete all custom presets.
		 *
		 * @since 3.0.0.0
		 */
		public function clear() {
			update_option('gecko_custom_presets', []);
		}
	}
}
