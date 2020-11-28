<?php

//  Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

if (!class_exists('Gecko_Customizer_Options')) {
	/**
	 * Gecko_Customizer_Options class.
	 *
	 * @since 3.0.0.0
	 */
	class Gecko_Customizer_Options {
		private $settings;
		private $options_settings;
		private $options_css_vars;

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
			$this->settings = GeckoConfigSettings::get_instance();
			$this->options_settings = [];
			$this->options_css_vars = [];

			// Flatten option listing to be used internally for a faster query.
			$options = $this->list();
			foreach ($options as $cat) {
				foreach ($cat['options'] as $subcat) {
					foreach ($subcat['settings'] as $option) {
						if (isset($option['setting']) && $option['setting']) {
							$this->options_settings[$option['setting']] = $option;
						} elseif (isset($option['var']) && $option['var']) {
							$this->options_css_vars[$option['var']] = $option;
						}
					}
				}
			}
		}

		/**
		 * Get all options.
		 *
		 * @since 3.0.0.0
		 *
		 * @return array
		 */
		public function list() {
			$options = [];

			$options['Site'] = [
				'name' => __('Site', 'gecko'),
				'desc' => __('Site settings.', 'gecko'),
				'tags' => __('Logo, fonts, typography.', 'gecko'),
				'id' => 'site',
				'icon' => 'gcis gci-cog',
				'options' => [
					'Logo' => [
						'title' => __('Logo', 'gecko'),
						'icon' => 'gcis gci-image',
						'desc' => __('Select Logo.', 'gecko'),
						'settings' => [
							'site-logo' => [
								'name' => __('Site Logo', 'gecko'),
								'id' => 'site-logo',
								'type' => 'image',
								'setting' => 'opt_custom_logo',
							],
							'site-mobile-logo' => [
								'name' => __('Site Mobile Logo', 'gecko'),
								'id' => 'site-mobile-logo',
								'type' => 'image',
								'setting' => 'opt_custom_mobile_logo',
							],
						],
					],
					'Font' => [
						'title' => __('Fonts', 'gecko'),
						'icon' => 'gcis gci-font',
						'desc' => __('Choose one of the available fonts.', 'gecko'),
						'settings' => [
							'google-font' => [
								'name' => __('Google fonts', 'gecko'),
								'id' => 'google-font',
								'type' => 'select',
								'var' => '--GC-FONT-FAMILY',
								'options' => [
									'Sora' => 'Default (Sora)',
									'Maven Pro' => 'Maven Pro',
									'Mulish' => 'Mulish',
									'Quicksand' => 'Quicksand',
									'Comfortaa' => 'Comfortaa',
									'Exo' => 'Exo',
									'Manrope' => 'Manrope',
									'Varta' => 'Varta',
									'Roboto' => 'Roboto',
									'Roboto Condensed' => 'Roboto Condensed',
									'Roboto Slab' => 'Roboto Slab',
									'Open Sans' => 'Open Sans',
									'Lato' => 'Lato',
									'Baloo Tammudu 2' => 'Baloo Tammudu 2',
									'Kufam' => 'Kufam',
									'Merriweather' => 'Merriweather',
									'Ubuntu' => 'Ubuntu',
									'Epilogue' => 'Epilogue',
									'Work Sans' => 'Work Sans',
									'Mukta' => 'Mukta',
									'Rubik' => 'Rubik',
									'Nanum Gothic' => 'Nanum Gothic',
									'Oxygen' => 'Oxygen',
									'Dancing Script' => 'Dancing Script',
									'Abel' => 'Abel',
								],
							],
						],
					],
					'Typography' => [
						'title' => __('Typography', 'gecko'),
						'icon' => 'gcis gci-heading',
						'desc' => __(
							'Here you can edit font-size, line-height or text colors.',
							'gecko'
						),
						'settings' => [
							'font-size' => [
								'name' => __('Global font-size', 'gecko'),
								'id' => 'font-size',
								'type' => 'range',
								'var' => '--FONT-SIZE',
								'unit' => 'px',
								'min' => '14',
								'max' => '24',
								'step' => '1',
							],
						],
					],
				],
			];

			$options['global-colors'] = [
				'name' => __('Global colors', 'gecko'),
				'desc' => __(
					'Here you can change global colors which may affect many elements. You can find more specific settings in different categories.',
					'gecko'
				),
				'tags' => __('Base colors palette.', 'gecko'),
				'id' => 'global-colors',
				'icon' => 'gcis gci-palette',
				'options' => [
					'primary-colors' => [
						'title' => __('Primary colors', 'gecko'),
						'icon' => 'gcis gci-swatchbook',
						'desc' => __(
							'Base colors used for important elements like action buttons, alerts, progress bars or active element indicators.',
							'gecko'
						),
						'settings' => [
							'color-primary' => [
								'name' => __('Primary color', 'gecko'),
								'id' => 'gcc-color-primary',
								'type' => 'color',
								'var' => '--COLOR--PRIMARY',
							],

							'color-primary-shade' => [
								'name' => __('Primary shade color', 'gecko'),
								'id' => 'gcc-color-primary-shade',
								'type' => 'color',
								'var' => '--COLOR--PRIMARY--SHADE',
							],

							'color-primary-light' => [
								'name' => __('Primary light color', 'gecko'),
								'id' => 'gcc-color-primary-light',
								'type' => 'color',
								'var' => '--COLOR--PRIMARY--LIGHT',
							],

							'color-primary-ultralight' => [
								'name' => __('Primary ultralight color', 'gecko'),
								'id' => 'gcc-color-primary-ultralight',
								'type' => 'color',
								'var' => '--COLOR--PRIMARY--ULTRALIGHT',
							],

							'color-primary-dark' => [
								'name' => __('Primary dark color', 'gecko'),
								'id' => 'gcc-color-primary-dark',
								'type' => 'color',
								'var' => '--COLOR--PRIMARY--DARK',
							],
						],
					],

					'success-colors' => [
						'title' => __('Success colors', 'gecko'),
						'icon' => 'gcis gci-swatchbook',
						'desc' => __(
							'Success action colors used for alerts buttons etc.',
							'gecko'
						),
						'settings' => [
							'color-success' => [
								'name' => __('Success color', 'gecko'),
								'id' => 'gcc-color-success',
								'type' => 'color',
								'var' => '--COLOR--SUCCESS',
							],

							'color-success-light' => [
								'name' => __('Success light color', 'gecko'),
								'id' => 'gcc-color-success-light',
								'type' => 'color',
								'var' => '--COLOR--SUCCESS--LIGHT',
							],

							'color-success-ultralight' => [
								'name' => __('Success ultralight color', 'gecko'),
								'id' => 'gcc-color-success-ultralight',
								'type' => 'color',
								'var' => '--COLOR--SUCCESS--ULTRALIGHT',
							],

							'color-success-dark' => [
								'name' => __('Success dark color', 'gecko'),
								'id' => 'gcc-color-success-dark',
								'type' => 'color',
								'var' => '--COLOR--SUCCESS--DARK',
							],
						],
					],

					'warning-colors' => [
						'title' => __('Warning colors', 'gecko'),
						'icon' => 'gcis gci-swatchbook',
						'desc' => __(
							'Warning action colors used for alerts buttons etc.',
							'gecko'
						),
						'settings' => [
							'color-warning' => [
								'name' => __('Warning color', 'gecko'),
								'id' => 'gcc-color-warning',
								'type' => 'color',
								'var' => '--COLOR--WARNING',
							],

							'color-warning-light' => [
								'name' => __('Warning light color', 'gecko'),
								'id' => 'gcc-color-warning-light',
								'type' => 'color',
								'var' => '--COLOR--WARNING--LIGHT',
							],

							'color-warning-ultralight' => [
								'name' => __('Warning ultralight color', 'gecko'),
								'id' => 'gcc-color-warning-ultralight',
								'type' => 'color',
								'var' => '--COLOR--WARNING--ULTRALIGHT',
							],

							'color-warning-dark' => [
								'name' => __('Warning dark color', 'gecko'),
								'id' => 'gcc-color-warning-dark',
								'type' => 'color',
								'var' => '--COLOR--WARNING--DARK',
							],
						],
					],

					'abort-colors' => [
						'title' => __('Abort colors', 'gecko'),
						'icon' => 'gcis gci-swatchbook',
						'desc' => __(
							'Abort action/error colors used for alerts buttons etc.',
							'gecko'
						),
						'settings' => [
							'color-abort' => [
								'name' => __('Abort color', 'gecko'),
								'id' => 'gcc-color-abort',
								'type' => 'color',
								'var' => '--COLOR--ABORT',
							],

							'color-abort-light' => [
								'name' => __('Abort light color', 'gecko'),
								'id' => 'gcc-color-abort-light',
								'type' => 'color',
								'var' => '--COLOR--ABORT--LIGHT',
							],

							'color-abort-ultralight' => [
								'name' => __('Abort ultralight color', 'gecko'),
								'id' => 'gcc-color-abort-ultralight',
								'type' => 'color',
								'var' => '--COLOR--ABORT--ULTRALIGHT',
							],

							'color-abort-dark' => [
								'name' => __('Abort dark color', 'gecko'),
								'id' => 'gcc-color-abort-dark',
								'type' => 'color',
								'var' => '--COLOR--ABORT--DARK',
							],
						],
					],

					'link-colors' => [
						'title' => __('Links', 'gecko'),
						'icon' => 'gcis gci-link',
						'desc' => __('These colors are used for links.','gecko'),
						'settings' => [
							'link-color' => [
								'name' => __('Link color', 'gecko'),
								'id' => 'link-color',
								'type' => 'color',
								'var' => '--COLOR--LINK',
							],
							'link-color-hover' => [
								'name' => __('Link hover color', 'gecko'),
								'id' => 'link-color-hover',
								'type' => 'color',
								'var' => '--COLOR--LINK-HOVER',
							],
							'link-color-focus' => [
								'name' => __('Link focus color', 'gecko'),
								'id' => 'link-color-focus',
								'type' => 'color',
								'var' => '--COLOR--LINK-FOCUS',
							],
						],
					],

					'bg-colors' => [
						'title' => __('Backgrounds', 'gecko'),
						'icon' => 'gcis gci-adjust',
						'desc' => __(
							'These colors are used for backgrounds of components, widgets etc.',
							'gecko'
						),
						'settings' => [
							'color-body' => [
								'name' => __('Body background', 'gecko'),
								'id' => 'color-body',
								'type' => 'color',
								'var' => '--c-gc-body-bg',
							],
							'color-app' => [
								'name' => __('Base color', 'gecko'),
								'id' => 'color-app',
								'type' => 'color',
								'var' => '--COLOR--APP',
							],
							'color-app-dark' => [
								'name' => __('Base dark color', 'gecko'),
								'id' => 'color-app-dark',
								'type' => 'color',
								'var' => '--COLOR--APP--DARK',
							],
							'color-app-darker' => [
								'name' => __('Base darker color', 'gecko'),
								'id' => 'color-app-darker',
								'type' => 'color',
								'var' => '--COLOR--APP--DARKER',
							],
							'color-app-gray' => [
								'name' => __('Base gray color', 'gecko'),
								'id' => 'color-app-gray',
								'type' => 'color',
								'var' => '--COLOR--APP--GRAY',
							],
							'color-app-light-gray' => [
								'name' => __('Base light gray color', 'gecko'),
								'id' => 'color-app-light-gray',
								'type' => 'color',
								'var' => '--COLOR--APP--LIGHTGRAY',
							],
							'color-app-dark-gray' => [
								'name' => __('Base dark gray color', 'gecko'),
								'id' => 'color-app-dark-gray',
								'type' => 'color',
								'var' => '--COLOR--APP--DARKGRAY',
							],
						],
					],

					'border-colors' => [
						'title' => __('Borders & Separators', 'gecko'),
						'icon' => 'gcis gci-border-all',
						'desc' => __(
							'These colors are used for borders & separators of components, widgets etc.',
							'gecko'
						),
						'settings' => [
							'color-divider' => [
								'name' => __('Basic border', 'gecko'),
								'id' => 'color-divider',
								'type' => 'color',
								'var' => '--DIVIDER',
							],
							'color-divider-light' => [
								'name' => __('Light border', 'gecko'),
								'id' => 'color-divider-light',
								'type' => 'color',
								'var' => '--DIVIDER--LIGHT',
							],
							'color-divider-lighten' => [
								'name' => __('Lighten border', 'gecko'),
								'id' => 'color-divider-lighten',
								'type' => 'color',
								'var' => '--DIVIDER--LIGHTEN',
							],
							'color-divider-dark' => [
								'name' => __('Dark border', 'gecko'),
								'id' => 'color-divider-dark',
								'type' => 'color',
								'var' => '--DIVIDER--DARK',
							],
							'color-divider-invert' => [
								'name' => __('Invert border', 'gecko'),
								'id' => 'color-divider-invert',
								'type' => 'color',
								'var' => '--DIVIDER--R',
							],
							'color-divider-invert-light' => [
								'name' => __('Invert light border', 'gecko'),
								'id' => 'color-divider-invert-light',
								'type' => 'color',
								'var' => '--DIVIDER--R--LIGHT',
							],
						],
					],
				],
			];

			$options['Appearance'] = [
				'name' => __('Appearance', 'gecko'),
				'desc' => __('Borders, shadows etc.', 'gecko'),
				'tags' => __('Borders, shadows etc.', 'gecko'),
				'id' => 'appearance',
				'icon' => 'gcis gci-magic',
				'options' => [
					'appearance-borders' => [
						'title' => __('Borders', 'gecko'),
						'icon' => 'gcis gci-border-style',
						'settings' => [
							'border-radius' => [
								'name' => __('Global border radius', 'gecko'),
								'id' => 'border-radius',
								'type' => 'range',
								'var' => '--BORDER-RADIUS',
								'unit' => 'px',
								'min' => '0',
								'max' => '20',
								'step' => '1',
							],
						],
					],
					'appearance-shadows' => [
						'title' => __('Shadows', 'gecko'),
						'desc' => __('Global shadow settings applied to most of the theme elements.', 'gecko'),
						'icon' => 'gcis gci-clone',
						'settings' => [
							'shadow-distance' => [
								'name' => __('Global shadow distance', 'gecko'),
								'id' => 'shadow-distance',
								'type' => 'range',
								'var' => '--BOX-SHADOW-DIS',
								'unit' => 'px',
								'min' => '0',
								'max' => '50',
								'step' => '1',
							],
							'shadow-blur' => [
								'name' => __('Global shadow blur', 'gecko'),
								'id' => 'shadow-blur',
								'type' => 'range',
								'var' => '--BOX-SHADOW-BLUR',
								'unit' => 'px',
								'min' => '0',
								'max' => '50',
								'step' => '1',
							],
							'shadow-thickness' => [
								'name' => __('Global shadow thickness', 'gecko'),
								'id' => 'shadow-thickness',
								'type' => 'range',
								'var' => '--BOX-SHADOW-THICKNESS',
								'unit' => 'px',
								'min' => '0',
								'max' => '50',
								'step' => '1',
							],
							'shadow-color' => [
								'name' => __('Global shadow color', 'gecko'),
								'id' => 'shadow-color',
								'type' => 'color',
								'var' => '--BOX-SHADOW-COLOR',
							],
						],
					],
				],
			];

			$options['Theme'] = [
				'name' => __('Theme', 'gecko'),
				'desc' => __('Gecko theme settings.', 'gecko'),
				'tags' => __('Header, footer, sidebars, layout.', 'gecko'),
				'id' => 'theme',
				'icon' => 'gcis gci-table',
				'options' => [
					'General' => [
						'title' => __('General', 'gecko'),
						'icon' => 'gcis gci-cog',
						'desc' => __('Theme settings.', 'gecko'),
						'settings' => [
							'gc-admin-bar-vis' => [
							  'name' => __('Show WP admin bar.', 'gecko'),
							  'id' => 'gc-admin-bar-vis',
							  'type' => 'select',
							  'setting' => 'opt_show_adminbar',
								'options' => [
									'1' => __('Always', 'gecko'),
									'2' => __('Only to Administrators', 'gecko'),
									'3' => __('Never', 'gecko'),
								],
							],
							'gc-browser-zoom' => [
							  'name' => __('Allow zoom on Mobile.', 'gecko'),
							  'id' => 'gc-browser-zoom',
							  'type' => 'switch',
							  'setting' => 'opt_zoom_feature',
							  'on' => '1',
							  'off' => '',
							],
							'gc-limit_page_options' => [
							  'name' => __('Limit access to Gecko Page options.', 'gecko'),
								'desc' => 'Limits the ability to edit Gecko Page options to admins only. (Moderators & Editors have access to these options as default)',
							  'id' => 'gc-limit_page_options',
							  'type' => 'switch',
							  'setting' => 'opt_limit_page_options',
							  'on' => '1',
							  'off' => '',
							],
						],
					],
					'Layout' => [
						'title' => __('Layout', 'gecko'),
						'icon' => 'gcis gci-border-all',
						'desc' => __('Theme layout settings.', 'gecko'),
						'settings' => [
							'gc-layout-width' => [
								'name' => __('Layout width', 'gecko'),
								'desc' => __('You can use any unit, like px or %.', 'gecko'),
								'id' => 'gc-layout-width',
								'type' => 'default',
								'var' => '--c-gc-layout-width',
							],
							'gc-layout-gaps' => [
								'name' => __('Columns gap', 'gecko'),
								'desc' => __('This will affect the gaps between main columns (middle and sidebars).', 'gecko'),
								'id' => 'gc-layout-gaps',
								'type' => 'range',
								'var' => '--c-gc-layout-gap',
								'unit' => 'px',
								'min' => '0',
								'max' => '50',
								'step' => '5',
							],
							'middle-column-size' => [
								'name' => __('Middle column size', 'gecko'),
								'desc' => __('Have in mind this setting may not work if you set fixed width in "px" on sidebars. Middle column will fill all the available space then.', 'gecko'),
								'id' => 'middle-column-size',
								'type' => 'range',
								'var' => '--c-gc-main-column',
								'unit' => 'fr',
								'min' => '1',
								'max' => '4',
								'step' => '1',
							],
						],
					],
					'Sidebars' => [
						'title' => __('Sidebars', 'gecko'),
						'icon' => 'gcis gci-border-all',
						'desc' => __('Theme sidebars settings.', 'gecko'),
						'settings' => [
							'gc-sticky-sidebars' => [
							  'name' => __('Sticky sidebars.', 'gecko'),
							  'id' => 'gc-sticky-sidebars',
							  'type' => 'switch',
							  'setting' => 'opt_sticky_sidebar',
							  'on' => '1',
							  'off' => '',
							],
							'gc-sidebar-left-width' => [
								'name' => __('Left sidebar fixed width.', 'gecko'),
								'desc' => __('You can use any unit, like px or %. (Default is 1fr)', 'gecko'),
								'id' => 'gc-sidebar-left-width',
								'type' => 'default',
								'var' => '--c-gc-sidebar-left-width',
							],
							'gc-sidebar-right-width' => [
								'name' => __('Right sidebar fixed width.', 'gecko'),
								'desc' => __('You can use any unit, like px or %. (Default is 1fr)', 'gecko'),
								'id' => 'gc-sidebar-right-width',
								'type' => 'default',
								'var' => '--c-gc-sidebar-right-width',
							],
							'gc-sidebar-widgets-gap' => [
								'name' => __('Widgets gap', 'gecko'),
								'desc' => __('Gap between widgets in sidebars.', 'gecko'),
								'id' => 'gc-sidebar-widgets-gap',
								'type' => 'range',
								'var' => '--c-gc-sidebar-widgets-gap',
								'unit' => 'px',
								'min' => '0',
								'max' => '50',
								'step' => '5',
							],
							'gc-sidebar-left-show-mobile' => [
								'name' => __('Show left sidebar on mobile.', 'gecko'),
								'id' => 'gc-sidebar-left-show-mobile',
								'type' => 'switch',
								'setting' => 'opt_sidebar_left_mobile_vis',
								'on' => '1',
								'off' => '',
							],
							'gc-sidebar-right-show-mobile' => [
								'name' => __('Show right sidebar on mobile.', 'gecko'),
								'id' => 'gc-sidebar-right-show-mobile',
								'type' => 'switch',
								'setting' => 'opt_sidebar_right_mobile_vis',
								'on' => '1',
								'off' => '',
							],
						],
					],
                    'Body' => [
                        'title' => __('Body', 'gecko'),
                        'icon' => 'gcis gci-code',
                        'desc' => __('Theme body tag settings.', 'gecko'),
                        'settings' => [
                            'config-body-class' => [
                                'name' => __('Body class', 'gecko'),
                                'id' => 'config-body-class',
                                'type' => 'default',
                                'var' => 'config-body-class',
                            ],
                        ],
                    ],
					'Header' => [
						'title' => __('Header', 'gecko'),
						'icon' => 'gcis gci-window-maximize',
						'desc' => __('Header settings.', 'gecko'),
						'settings' => [
							// Basic header
							'gc-header-basic' => [
								'name' => __('Basic header options', 'gecko'),
								'id' => 'gc-header-basic',
								'type' => 'category',
								'var' => false,
							],
							'gc-header-height' => [
								'name' => __('Header height', 'gecko'),
								'id' => 'gc-header-height',
								'type' => 'range',
								'var' => '--c-gc-header-height',
								'unit' => 'px',
								'min' => '80',
								'max' => '120',
								'step' => '1',
							],
							'gc-header-font-size' => [
								'name' => __('Header font-size', 'gecko'),
								'id' => 'gc-header-font-size',
								'type' => 'range',
								'var' => '--c-gc-header-font-size',
								'unit' => '%',
								'min' => '70',
								'max' => '120',
								'step' => '10',
							],
							'gc-header-widget-toggle' => [
							  'name' => __('Enable toggle icon for Header widget (mobile).', 'gecko'),
							  'id' => 'gc-header-widget-toggle',
							  'type' => 'switch',
							  'setting' => 'opt_widget_icon',
							  'on' => '1',
							  'off' => '',
							],
							// Static header
							'gc-header-sticky' => [
								'name' => __('Sticky header', 'gecko'),
								'desc' => __(
									'Header will follow on scroll when this setting is enabled.',
									'gecko'
								),
								'id' => 'gc-header-sticky',
								'type' => 'category',
								'var' => false,
							],
							'gc-header-sticky-desktop' => [
								'name' => __('On desktop', 'gecko'),
								'id' => 'gc-header-sticky-desktop',
								'type' => 'switch',
								'var' => '--c-gc-header-sticky',
								'on' => 'fixed',
								'off' => 'absolute',
							],
							'gc-header-sticky-mobile' => [
								'name' => __('On mobile', 'gecko'),
								'id' => 'gc-header-sticky-mobile',
								'type' => 'switch',
								'var' => '--c-gc-header-sticky-mobile',
								'on' => 'fixed',
								'off' => 'absolute',
							],
							// Header colors
							'gc-header-colors' => [
								'name' => __('Header colors', 'gecko'),
								'id' => 'gc-header-colors',
								'type' => 'category',
								'var' => false,
							],
							'gc-header-bg' => [
								'name' => __('Header background', 'gecko'),
								'id' => 'gc-header-bg',
								'type' => 'color',
								'var' => '--c-gc-header-bg',
							],
							'gc-header-text-color' => [
								'name' => __('Header text color', 'gecko'),
								'id' => 'gc-header-text-color',
								'type' => 'color',
								'var' => '--c-gc-header-text-color',
							],
							'gc-header-link-color' => [
								'name' => __('Header links color', 'gecko'),
								'id' => 'gc-header-link-color',
								'type' => 'color',
								'var' => '--c-gc-header-link-color',
							],
							'gc-header-link-color-hover' => [
								'name' => __('Header links hover color', 'gecko'),
								'id' => 'gc-header-link-color-hover',
								'type' => 'color',
								'var' => '--c-gc-header-link-color-hover',
							],
							'gc-header-link-active-indicator' => [
								'name' => __('Header active link indicator', 'gecko'),
								'id' => 'gc-header-link-active-indicator',
								'type' => 'color',
								'var' => '--c-gc-header-link-active-indicator',
							],
							// Header menu
							'gc-header-menu' => [
								'name' => __('Header menu', 'gecko'),
								'id' => 'gc-header-menu',
								'type' => 'category',
								'var' => false,
							],
							'gc-header-menu-alignment' => [
								'name' => __('Header menu alignment', 'gecko'),
								'id' => 'gc-header-menu-alignment',
								'type' => 'select',
								'var' => '--c-gc-header-menu-alignment',
								'options' => [
									'0 auto' => 'Center',
									'0 auto 0 0' => 'Left',
									'0 0 0 auto' => 'Right',
								],
							],
							'gc-header-menu-font-size' => [
								'name' => __('Header menu font-size', 'gecko'),
								'id' => 'gc-header-menu-font-size',
								'type' => 'range',
								'var' => '--c-gc-header-menu-font-size',
								'unit' => '%',
								'min' => '70',
								'max' => '120',
								'step' => '10',
							],
							// Header search
							'gc-header-search' => [
								'name' => __('Header search', 'gecko'),
								'id' => 'gc-header-search',
								'type' => 'category',
								'var' => false,
							],
							'gc-header-search-vis' => [
								'name' => __('Show search on Header', 'gecko'),
								'id' => 'gc-header-search-vis',
								'type' => 'switch',
								'setting' => 'opt_show_search_in_header',
								'on' => '1',
								'off' => '',
							],
						],
					],

					'Footer' => [
						'title' => __('Footer', 'gecko'),
						'icon' => 'gcis gci-window-minimize',
						'desc' => __('Theme footer settings.', 'gecko'),
						'settings' => [
							'gc-footer-appearance' => [
								'name' => __('Footer appearance', 'gecko'),
								'id' => 'gc-footer-copyrights',
								'type' => 'category',
								'var' => false,
							],
							'gc-footer-bg' => [
							  'name' => __('Footer background.', 'gecko'),
							  'id' => 'gc-footer-bg',
								'type' => 'color',
								'var' => '--c-gc-footer-bg',
							],
							'gc-footer-text-color' => [
							  'name' => __('Text color.', 'gecko'),
							  'id' => 'gc-footer-text-color',
								'type' => 'color',
								'var' => '--c-gc-footer-text-color',
							],
							'gc-footer-text-color-light' => [
								'name' => __('Text light color.', 'gecko'),
								'id' => 'gc-footer-text-color-light',
								'type' => 'color',
								'var' => '--c-gc-footer-text-color-light',
							],
							'gc-footer-links-color' => [
							  'name' => __('Links color.', 'gecko'),
							  'id' => 'gc-footer-links-color',
								'type' => 'color',
								'var' => '--c-gc-footer-links-color',
							],
							'gc-footer-links-color-hover' => [
							  'name' => __('Links color on hover.', 'gecko'),
							  'id' => 'gc-footer-links-color-hover',
								'type' => 'color',
								'var' => '--c-gc-footer-links-color-hover',
							],
							'gc-footer-copyrights' => [
								'name' => __('Footer copyrights', 'gecko'),
								'id' => 'gc-footer-copyrights',
								'type' => 'category',
								'var' => false,
							],
							'gc-footer-text-line-1' => [
							    'name' => __('Footer text', 'gecko') . ' ('.sprintf(__('Line %d','gecko'), 1) .')',
							    'id' => 'gc-footer-text-line-1',
							    'type' => 'default',
							    'setting' => 'opt_footer_text_line_1',
							],
							'gc-footer-text-line-2' => [
							    'name' => __('Footer text', 'gecko') . ' ('.sprintf(__('Line %d','gecko'), 2) .')',
							    'id' => 'gc-footer-text-line-2',
							    'type' => 'default',
							    'setting' => 'opt_footer_text_line_2',
							],
							'gc-footer-widgets' => [
								'name' => __('Footer widgets', 'gecko'),
								'id' => 'gc-footer-widgets',
								'type' => 'category',
								'var' => false,
							],
							'gc-footer-col' => [
								'name' => __('Columns number.', 'gecko'),
								'id' => 'gc-footer-col',
								'type' => 'range',
								'var' => '--c-gc-footer-col',
								'unit' => '',
								'min' => '1',
								'max' => '6',
								'step' => '1',
							],
							'gc-footer-widgets-visibility' => [
								'name' => __('Display on desktop', 'gecko'),
								'id' => 'gc-footer-widgets-visibility',
								'type' => 'switch',
								'var' => '--c-gc-footer-widgets-vis',
								'on' => 'grid',
								'off' => 'none',
							],
							'gc-footer-widgets-visibility-mobile' => [
								'name' => __('Display on mobile', 'gecko'),
								'id' => 'gc-footer-widgets-visibility-mobile',
								'type' => 'switch',
								'var' => '--c-gc-footer-widgets-vis-mobile',
								'on' => 'block',
								'off' => 'none',
							],
						],
					],
				],
			];

			$options['Blog'] = [
				'name' => __('Blog', 'gecko'),
				'desc' => __('Wordpress blog settings.', 'gecko'),
				'tags' => __('Blog, search page, archives etc.', 'gecko'),
				'id' => 'blog',
				'icon' => 'gcis gci-pen-square',
				'options' => [
					'blog-general' => [
						'title' => __('General settings', 'gecko'),
						'desc' => __('All settings related to blog page.', 'gecko'),
						'icon' => 'gcis gci-cog',
						'settings' => [
							'gc-blog-sidebars-vis' => [
							  'name' => __('Show sidebars on Blog Posts.', 'gecko'),
							  'id' => 'gc-blog-post-sidebars-vis',
							  'type' => 'switch',
							  'setting' => 'opt_blog_sidebars',
							  'on' => '1',
							  'off' => '',
							],
							'gc-blog-limit-post-words' => [
								'name' => __('Limit words on Blog post.', 'gecko'),
								'id' => 'gc-blog-limit-post-words',
								'type' => 'switch',
								'setting' => 'opt_limit_blog_post',
								'on' => '1',
								'off' => '',
							],
							'gc-blog-update' => [
								'name' => __('Show `update` date on posts meta.', 'gecko'),
								'id' => 'gc-blog-update',
								'type' => 'switch',
								'setting' => 'opt_blog_update',
								'on' => '1',
								'off' => '',
							],
							'gc-blog-image-maxwidth' => [
								'name' => __('Max height of the posts featured image (Blog view).', 'gecko'),
								'desc' => __('Use pixels as unit or set 100% - for auto height. (Default is 100%)', 'gecko'),
								'id' => 'gc-blog-image-maxwidth',
								'type' => 'default',
								'var' => '--c-gc-blog-image-max-height',
							],
						],
					],
					'blog-grid' => [
						'title' => __('Grid layout', 'gecko'),
						'icon' => 'gcis gci-border-all',
						'desc' => __('Grid settings for blog related pages.', 'gecko'),
						'settings' => [
							'gc-blog-grid' => [
								'name' => __('Grid layout on Blog page.', 'gecko'),
								'id' => 'gc-blog-grid',
								'type' => 'switch',
								'setting' => 'opt_blog_grid',
								'on' => '1',
								'off' => '',
							],
							'gc-blog-archives-grid' => [
								'name' => __('Grid layout on Archives page.', 'gecko'),
								'id' => 'gc-blog-archives-grid',
								'type' => 'switch',
								'setting' => 'opt_archives_grid',
								'on' => '1',
								'off' => '',
							],
							'gc-blog-search-grid' => [
								'name' => __('Grid layout on Search page.', 'gecko'),
								'id' => 'gc-blog-search-grid',
								'type' => 'switch',
								'setting' => 'opt_search_grid',
								'on' => '1',
								'off' => '',
							],
						],
					],
					'blog-single-post' => [
						'title' => __('Single post', 'gecko'),
						'icon' => 'gcis gci-newspaper',
						'desc' => __('Settings for single post view.', 'gecko'),
						'settings' => [
							'gc-blog-single-post-image-maxwidth' => [
								'name' => __('Max height of the post featured image.', 'gecko'),
								'desc' => __('Use pixels as unit or set 100% - for auto height. (Default is 100%)', 'gecko'),
								'id' => 'gc-blog-single-post-image-maxwidth',
								'type' => 'default',
								'var' => '--c-gc-post-image-max-height',
							],
						],
					],
					'search-page' => [
						'title' => __('Search results', 'gecko'),
						'icon' => 'gcis gci-search',
						'desc' => __('Settings for search results page.', 'gecko'),
						'settings' => [
							'gc-search-notice' => [
								'name' => __('All the other search page options will be available in 3.1.0.0 release.', 'gecko'),
								'id' => 'gc-footer-copyrights',
								'type' => 'category',
								'var' => false,
							],
							'gc-search-show-sidebar-left' => [
								'name' => __('Show left sidebar on Search page.', 'gecko'),
								'id' => 'gc-search-show-sidebar-left',
								'type' => 'switch',
								'setting' => 'opt_sidebar_left_search_vis',
								'on' => '1',
								'off' => '',
							],
							'gc-search-show-sidebar-right' => [
								'name' => __('Show right sidebar on Search page.', 'gecko'),
								'id' => 'gc-search-show-sidebar-right',
								'type' => 'switch',
								'setting' => 'opt_sidebar_right_search_vis',
								'on' => '1',
								'off' => '',
							],
						],
					],
				],
			];

			$options['Widgets'] = [
				'name' => __('Widgets', 'gecko'),
				'desc' => __('General widget settings.', 'gecko'),
				'tags' => __('General widget settings.', 'gecko'),
				'id' => 'widgets',
				'icon' => 'gcis gci-th-large',
				'options' => [
					'Default-Widgets' => [
						'title' => __('Default widgets', 'gecko'),
						'icon' => 'gcis gci-window-restore',
						'desc' => __('Edit colors of default Widgets.', 'gecko'),
						'settings' => [
							'gc-widgets-default-bg' => [
								'name' => __('Default widgets Background color', 'gecko'),
								'id' => 'gc-widgets-default-bg',
								'type' => 'color',
								'var' => '--c-gc-widget-bg',
							],
							'gc-widgets-default-text-color' => [
								'name' => __('Default widgets Text color', 'gecko'),
								'id' => 'gc-widgets-default-text-color',
								'type' => 'color',
								'var' => '--c-gc-widget-text-color',
							],
							// 'gc-widgets-default-links-color' => [
							// 	'name' => __('Default widgets Links color', 'gecko'),
							// 	'id' => 'gc-widgets-default-links-color',
							// 	'type' => 'color',
							// 	'var' => '--c-gc-widget-links-color',
							// ],
							// 'gc-widgets-default-links-color-hover' => [
							// 	'name' => __('Default widgets Links hover color', 'gecko'),
							// 	'id' => 'gc-widgets-default-links-color-hover',
							// 	'type' => 'color',
							// 	'var' => '--c-gc-widget-links-color-hover',
							// ],
						],
					],
					'Gradient-Widgets' => [
						'title' => __('Gradient widgets', 'gecko'),
						'icon' => 'gcis gci-window-restore',
						'desc' => __('Edit default colors of gradient widgets.', 'gecko'),
						'settings' => [
							'gc-widgets-gradient-bg' => [
								'name' => __('Gradient widgets Background color', 'gecko'),
								'id' => 'gc-widgets-gradient-bg',
								'type' => 'color',
								'var' => '--widget--gradient-bg',
							],
							'gc-widgets-gradient-bg-2' => [
								'name' => __('Gradient widgets Background color 2', 'gecko'),
								'id' => 'gc-widgets-gradient-bg-2',
								'type' => 'color',
								'var' => '--widget--gradient-bg-2',
							],
							'gc-widgets-gradient-text-color' => [
								'name' => __('Gradient widgets Text color', 'gecko'),
								'id' => 'gc-widgets-gradient-text-color',
								'type' => 'color',
								'var' => '--widget--gradient-color',
							],
							'gc-widgets-gradient-links-color' => [
								'name' => __('Gradient widgets Links color', 'gecko'),
								'id' => 'gc-widgets-gradient-links-color',
								'type' => 'color',
								'var' => '--widget--gradient-links',
							],
							'gc-widgets-gradient-links-color-hover' => [
								'name' => __('Gradient widgets Links hover color', 'gecko'),
								'id' => 'gc-widgets-gradient-links-color-hover',
								'type' => 'color',
								'var' => '--widget--gradient-links-hover',
							],
						],
					],
					'Top-widgets' => [
						'title' => __('Top widgets', 'gecko'),
						'icon' => 'gcis gci-window-restore',
						'desc' => __('Top area widgets (above the middle theme part).', 'gecko'),
						'settings' => [
							'gc-widgets-top-col' => [
								'name' => __('Columns number', 'gecko'),
								'id' => 'gc-widgets-top-col',
								'type' => 'range',
								'var' => '--c-gc-widgets-top-col',
								'unit' => '',
								'min' => '1',
								'max' => '6',
								'step' => '1',
							],
							'gc-widgets-top-visibility' => [
								'name' => __('Display on desktop', 'gecko'),
								'id' => 'gc-widgets-top-visibility',
								'type' => 'switch',
								'var' => '--c-gc-widgets-top-vis',
								'on' => 'block',
								'off' => 'none',
							],
							'gc-widgets-top-visibility-mobile' => [
								'name' => __('Display on mobile', 'gecko'),
								'id' => 'gc-widgets-top-visibility-mobile',
								'type' => 'switch',
								'var' => '--c-gc-widgets-top-vis-mobile',
								'on' => 'block',
								'off' => 'none',
							],
						],
					],
					'Bottom-widgets' => [
						'title' => __('Bottom widgets', 'gecko'),
						'icon' => 'gcis gci-window-restore',
						'desc' => __('Bottom area widgets (under the middle theme part).', 'gecko'),
						'settings' => [
							'gc-widgets-bottom-col' => [
								'name' => __('Columns number', 'gecko'),
								'id' => 'gc-widgets-bottom-col',
								'type' => 'range',
								'var' => '--c-gc-widgets-bottom-col',
								'unit' => '',
								'min' => '1',
								'max' => '6',
								'step' => '1',
							],
							'gc-widgets-bottom-visibility' => [
								'name' => __('Display on desktop', 'gecko'),
								'id' => 'gc-widgets-bottom-visibility',
								'type' => 'switch',
								'var' => '--c-gc-widgets-bottom-vis',
								'on' => 'block',
								'off' => 'none',
							],
							'gc-widgets-bottom-visibility-mobile' => [
								'name' => __('Display on mobile', 'gecko'),
								'id' => 'gc-widgets-bottom-visibility-mobile',
								'type' => 'switch',
								'var' => '--c-gc-widgets-bottom-vis-mobile',
								'on' => 'block',
								'off' => 'none',
							],
						],
					],
				],
			];

			$options['PeepSo'] = [
				'name' => __('PeepSo', 'gecko'),
				'desc' => __('Here you can change style of the PeepSo components.', 'gecko'),
				'tags' => __('General PeepSo settings.', 'gecko'),
				'id' => 'peepso',
				'icon' => 'gcib gci-product-hunt',
				'options' => [
					'ps-profile-page' => [
						'title' => __('Profile page settings', 'gecko'),
						'icon' => 'gcis gci-user-cog',
						'settings' => [
							'ps-profile-cover' => [
							  'name' => __('Profile cover size.', 'gecko'),
							  'id' => 'ps-profile-cover',
							  'type' => 'select',
							  'setting' => 'opt_ps_profile_page_cover',
							  'options' => [
							    '1' => __('Default', 'gecko'),
							    '2' => __('Wide cover', 'gecko'),
							    '3' => __('Full width cover', 'gecko'),
							  ],
							],
							'ps-profile-cover-height' => [
								'name' => __('Profile cover height.', 'gecko'),
								'id' => 'ps-profile-cover-height',
								'type' => 'range',
								'var' => '--c-ps-profile-cover-height',
								'unit' => '%',
								'min' => '10',
								'max' => '50',
								'step' => '5',
							],
							'ps-profile-cover-centered' => [
								'name' => __('Center focus Avatar and details/actions.', 'gecko'),
								'id' => 'ps-profile-cover-centered',
								'type' => 'switch',
								'setting' => 'opt_ps_profile_page_cover_centered',
								'on' => '1',
								'off' => '',
							],
							'ps-profile-avatar-size' => [
								'name' => __('Profile avatar size.', 'gecko'),
								'id' => 'ps-profile-avatar-size',
								'type' => 'range',
								'var' => '--c-ps-profile-avatar-size',
								'unit' => 'px',
								'min' => '80',
								'max' => '240',
								'step' => '10',
							],
						],
					],

					'ps-general' => [
						'title' => __('General settings', 'gecko'),
						'icon' => 'gcis gci-cog',
						'settings' => [
							'ps-page-title' => [
							  'name' => __('Show page title on PeepSo pages.', 'gecko'),
							  'id' => 'ps-page-title',
							  'type' => 'switch',
							  'var' => '--c-gc-show-page-title',
							  'on' => 'block',
							  'off' => 'none',
							],
							'ps-avatar' => [
								'name' => __('Avatars', 'gecko'),
								'id' => 'ps-avatar',
								'type' => 'category',
								'var' => false,
							],
							'ps-avatar-corners' => [
								'name' => __('Avatar corners', 'gecko'),
								'id' => 'ps-avatar-corners',
								'type' => 'range',
								'var' => '--c-ps-avatar-style',
								'unit' => '%',
								'custom-values' => '0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50',
							],
							'ps-other' => [
								'name' => __('Other', 'gecko'),
								'id' => 'ps-other',
								'type' => 'category',
								'var' => false,
							],
							'ps-user-preset' => [
								'name' => __('Let users select preferred theme.', 'gecko') .' (BETA)',
                                'desc' => __('Users will be able to choose from custom presets','gecko'),
								'id' => 'ps-user-preset',
								'type' => 'switch',
								'setting' => 'opt_user_preset',
								'on' => '1',
								'off' => '',
							],
							'ps-side-to-side' => [
								'name' => __('Full width PeepSo pages on mobile', 'gecko'),
                                'desc' => __('Remove side paddings from PeepSo pages on mobile', 'gecko'),
								'id' => 'ps-side-to-side',
								'type' => 'switch',
								'setting' => 'opt_ps_side_to_side',
								'on' => '1',
								'off' => '',
							],
						],
					],

					'ps-navbar' => [
						'title' => __('Navbar', 'gecko'),
						'icon' => 'gcis gci-minus',
						'settings' => [
							'ps-navbar-bg' => [
								'name' => __('Navbar background.', 'gecko'),
								'id' => 'ps-navbar-bg',
								'type' => 'color',
								'var' => '--c-ps-navbar-bg',
							],
							'ps-navbar-links-color' => [
								'name' => __('Navbar links color.', 'gecko'),
								'id' => 'ps-navbar-links-color',
								'type' => 'color',
								'var' => '--c-ps-navbar-links-color',
							],
							'ps-navbar-links-color-hover' => [
								'name' => __('Navbar links color on hover.', 'gecko'),
								'id' => 'ps-navbar-links-color-hover',
								'type' => 'color',
								'var' => '--c-ps-navbar-links-color-hover',
							],
							'ps-navbar-font-size' => [
								'name' => __('Navbar font-size.', 'gecko'),
								'id' => 'ps-navbar-font-size',
								'type' => 'range',
								'var' => '--c-ps-navbar-font-size',
								'unit' => 'px',
								'min' => '14',
								'max' => '24',
								'step' => '1',
							],
							'ps-navbar-icon-size' => [
								'name' => __('Navbar icons size.', 'gecko'),
								'id' => 'ps-navbar-icon-size',
								'type' => 'range',
								'var' => '--c-ps-navbar-icons-size',
								'unit' => 'px',
								'min' => '14',
								'max' => '24',
								'step' => '1',
							],
						],
					],

					'ps-stream' => [
						'title' => __('Activity stream', 'gecko'),
						'icon' => 'gcis gci-file-invoice',
						'settings' => [
							// POST
							'ps-post' => [
								'name' => __('Post', 'gecko'),
								'id' => 'ps-post',
								'type' => 'category',
								'var' => false,
							],
							'ps-post-bg' => [
								'name' => __('Post background', 'gecko'),
								'id' => 'ps-post-bg',
								'type' => 'color',
								'var' => '--c-ps-post-bg',
							],
							'ps-post-text-color' => [
								'name' => __('Post text color', 'gecko'),
								'id' => 'ps-post-text-color',
								'type' => 'color',
								'var' => '--c-ps-post-text-color',
							],
							'ps-post-text-color-light' => [
								'name' => __('Post text light color', 'gecko'),
								'id' => 'ps-post-text-color-light',
								'type' => 'color',
								'var' => '--c-ps-post-text-color-light',
							],
							'ps-post-font-size' => [
								'name' => __('Post font-size', 'gecko'),
								'id' => 'ps-post-font-size',
								'type' => 'range',
								'var' => '--c-ps-post-font-size',
								'unit' => 'px',
								'min' => '14',
								'max' => '24',
								'step' => '1',
							],
							'ps-post-pinned' => [
								'name' => __('Pinned post', 'gecko'),
								'id' => 'ps-post-pinned',
								'type' => 'category',
								'var' => false,
							],
							'ps-post-pinned-border-color' => [
								'name' => __('Pinned post border color', 'gecko'),
								'id' => 'ps-post-pinned-border-color',
								'type' => 'color',
								'var' => '--c-ps-post-pinned-border-color',
							],
							'ps-post-pinned-border-size' => [
								'name' => __('Pinned post border size', 'gecko'),
								'id' => 'ps-post-pinned-border-size',
								'type' => 'range',
								'var' => '--c-ps-post-pinned-border-size',
								'unit' => 'px',
								'min' => '0',
								'max' => '10',
								'step' => '1',
							],
							// POST PHOTOS
							'ps-post-photos' => [
								'name' => __('Photos in post', 'gecko'),
								'id' => 'ps-post-photos',
								'type' => 'category',
								'var' => false,
							],
							'ps-post-photo-width' => [
								'name' => __('Force single photo to fill 100% width.', 'gecko'),
								'desc' => __('Change "Single photo height" to "auto" for best results.', 'gecko'),
								'id' => 'ps-post-photo-width',
								'type' => 'switch',
								'var' => '--c-ps-post-photo-width',
								'on' => '100%',
								'off' => 'auto',
							],
							'ps-post-photo-limit-width' => [
								'name' => __('Single photo limit width', 'gecko'),
								'desc' => __('If the photo has "auto" width (setting above), you can limit the photo with the maximum width.', 'gecko'),
								'id' => 'ps-post-photo-limit-width',
								'type' => 'default',
								'var' => '--c-ps-post-photo-limit-width',
							],
							'ps-post-photo-height' => [
								'name' => __('Single photo height', 'gecko'),
								'desc' => __('You can use any unit, like px, % or auto.', 'gecko'),
								'id' => 'ps-post-photo-height',
								'type' => 'default',
								'var' => '--c-ps-post-photo-height',
							],
							'ps-post-gallery-width' => [
								'name' => __('Post gallery width', 'gecko'),
								'desc' => __('You can use any unit, like px or %.', 'gecko'),
								'id' => 'ps-post-gallery-width',
								'type' => 'default',
								'var' => '--c-ps-post-gallery-width',
							],
							// POSTBOX
							'ps-postbox' => [
								'name' => __('Postbox', 'gecko'),
								'id' => 'ps-postbox',
								'type' => 'category',
								'var' => false,
							],
							'ps-postbox-bg' => [
								'name' => __('Postbox background', 'gecko'),
								'id' => 'ps-postbox-bg',
								'type' => 'color',
								'var' => '--c-ps-postbox-bg',
							],
							'ps-postbox-text-color' => [
								'name' => __('Postbox text color', 'gecko'),
								'id' => 'ps-postbox-text-color',
								'type' => 'color',
								'var' => '--c-ps-postbox-text-color',
							],
							'ps-postbox-text-color-light' => [
								'name' => __('Postbox text color light', 'gecko'),
								'id' => 'ps-postbox-text-color-light',
								'type' => 'color',
								'var' => '--c-ps-postbox-text-color-light',
							],
							'ps-postbox-icons-color' => [
								'name' => __('Postbox icons color', 'gecko'),
								'id' => 'ps-postbox-icons-color',
								'type' => 'color',
								'var' => '--c-ps-postbox-icons-color',
							],
							'ps-postbox-separator-color' => [
								'name' => __('Postbox separator color', 'gecko'),
								'id' => 'ps-postbox-separator-color',
								'type' => 'color',
								'var' => '--c-ps-postbox-separator-color',
							],
						],
					],

					'ps-chat' => [
						'title' => __('Chat settings', 'gecko'),
						'icon' => 'gcir gci-comments',
						'settings' => [
							'ps-chat-window-notif-bg' => [
								'name' => __('Chat window Notification background color.', 'gecko'),
								'id' => 'ps-chat-window-notif-bg',
								'type' => 'color',
								'var' => '--c-ps-chat-window-notif-bg',
							],
							'ps-chat-message-bg' => [
								'name' => __('Chat message background (participants).', 'gecko'),
								'id' => 'ps-chat-message-bg',
								'type' => 'color',
								'var' => '--c-ps-chat-message-bg',
							],
							'ps-chat-message-text-color' => [
								'name' => __('Chat message text color (participants).', 'gecko'),
								'id' => 'ps-chat-message-text-color',
								'type' => 'color',
								'var' => '--c-ps-chat-message-text-color',
							],
							'ps-chat-message-bg-me' => [
								'name' => __('Chat message background (you).', 'gecko'),
								'id' => 'ps-chat-message-bg-me',
								'type' => 'color',
								'var' => '--c-ps-chat-message-bg-me',
							],
							'ps-chat-message-text-color-me' => [
								'name' => __('Chat message text color (you).', 'gecko'),
								'id' => 'ps-chat-message-text-color-me',
								'type' => 'color',
								'var' => '--c-ps-chat-message-text-color-me',
							],
						],
					],
				],
			];

			if ( class_exists( 'woocommerce' ) ) {

			$options['WooCommerce'] = [
				'name' => __('WooCommerce', 'gecko'),
				'desc' => __('Settings related to WooCommerce plugin.', 'gecko'),
				'tags' => __('General WooCommerce settings.', 'gecko'),
				'id' => 'woocommerce',
				'icon' => 'gcis gci-th-large',
				'options' => [
					'woo-general' => [
						'title' => __('General settings', 'gecko'),
						'icon' => 'gcis gci-minus',
						'settings' => [
							'woo-builder-friendly' => [
							  'name' => __('Builder friendly products.', 'gecko'),
								'desc' => __('Makes every single product view builder friendly.', 'gecko'),
							  'id' => 'woo-builder-friendly',
							  'type' => 'switch',
							  'setting' => 'opt_woo_builder',
								'on' => '1',
								'off' => '',
							],
							'woo-sidebars-vis' => [
							  'name' => __('Show sidebars on Product pages.', 'gecko'),
							  'id' => 'woo-sidebars-vis',
							  'type' => 'switch',
							  'setting' => 'opt_woo_sidebars',
								'on' => '1',
								'off' => '0',
							],
						],
					],
				],
			];

			} // end of WooCommerce

			if ( class_exists( 'SFWD_LMS' ) ) {

			$options['LearnDash'] = [
				'name' => __('LearnDash', 'gecko'),
				'desc' => __('Settings related to LearnDash plugin.', 'gecko'),
				'tags' => __('General LearnDash settings.', 'gecko'),
				'id' => 'learndash',
				'icon' => 'gcis gci-th-large',
				'options' => [
					'woo-general' => [
						'title' => __('General settings', 'gecko'),
						'icon' => 'gcis gci-minus',
						'settings' => [
							'ld-courses-sidebars' => [
							  'name' => __('Show sidebars on all Courses.', 'gecko'),
							  'id' => 'ld-courses-sidebars',
							  'type' => 'switch',
							  'setting' => 'opt_ld_sidebars',
								'on' => '1',
								'off' => '',
							],
						],
					],
				],
			];

		} // end of LearnDash

			return $options;
		}

		/**
		 * Get an option value.
		 *
		 * @since 3.0.0.0
		 *
		 * @param string $id
		 * @return string
		 */
		public function get($id) {
			$value = '';

			if (isset($this->options_settings[$id])) {
				$option_value = $this->settings->get_option($id);
				if ($option_value) {
					$value = $option_value;
				}
			} elseif (isset($this->options_css_vars[$id])) {
				$css_vars = get_option('gecko_css_vars', []);
				if (isset($css_vars[$id])) {
					$value = $css_vars[$id];
				}
			}

			return $value;
		}

		/**
		 * Update an option value.
		 *
		 * @since 3.0.0.0
		 *
		 * @param string $id
		 * @param string $value
		 * @return boolean
		 */
		public function update($id, $value) {
			if (isset($this->options_settings[$id])) {
				// Sanitize numeric value.
				if (is_string($value) && is_numeric($value)) {
					$value = (int) $value;
				}

				$this->settings->set_option($id, $value);
				return true;
			} elseif (isset($this->options_css_vars[$id])) {
				$css_vars = get_option('gecko_css_vars', []);
				$css_vars[$id] = $value;
				update_option('gecko_css_vars', $css_vars);
				return true;
			}

			return false;
		}

		/**
		 * Delete all option values.
		 *
		 * @since 3.0.0.0
		 */
		public function clear() {
			// Delete backend settings.
			$options = array_keys($this->options_settings);
			$this->settings->remove_option($options);

			// Delete CSS variables.
			delete_option('gecko_css_vars');
		}
	}
}
