<?php

//  Exit if accessed directly
if (!defined('ABSPATH')) {
	exit();
}

require_once __DIR__ . '/customizer-options.php'; // Gecko_Customizer_Options
require_once __DIR__ . '/customizer-preset.php'; // Gecko_Customizer_Preset

if (!class_exists('Gecko_Customizer')) {
	/**
	 * Gecko_Customizer class.
	 *
	 * @since 3.0.0.0
	 */
	class Gecko_Customizer {
		private $options;
		private $preset;

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
			$this->options = Gecko_Customizer_Options::get_instance();
			$this->preset = Gecko_Customizer_Preset::get_instance();

			// Check if the URL needs to be redirected.
			$this->maybe_preview_redirect();

			// Register ajax endpoints.
			add_action('wp_ajax_gecko_customizer_apply', [$this, 'ajax_apply']);
			add_action('wp_ajax_gecko_customizer_apply_temp', [$this, 'ajax_apply_temp']);
			add_action('wp_ajax_gecko_customizer_clear_temp', [$this, 'ajax_clear_temp']);
			add_action('wp_ajax_gecko_customizer_save_preset', [$this, 'ajax_save_preset']);
			add_action('wp_ajax_gecko_customizer_rename_preset', [$this, 'ajax_rename_preset']);
			add_action('wp_ajax_gecko_customizer_delete_preset', [$this, 'ajax_delete_preset']);

			if (is_admin() && !wp_doing_ajax()) {
				// Clear temporary configurations on page load.
				delete_transient('gecko_options');

				// Add customizer submenu page.
				add_action('admin_menu', [$this, 'register_sub_menu']);

				if (isset($_GET['page']) && 'gecko-customizer' === $_GET['page']) {

				    // Reset RC8 once
                    if('RC8' == Gecko_Theme_License::THEME_RELEASE && !strlen(get_option('gecko_did_reset_rc8',''))) {
                        update_option('gecko_did_reset_rc8','YES');
                        $this->reset();
                        wp_redirect($_SERVER['REQUEST_URI'], 302);
                        exit();
                    }

					// Reset all customizer configurations if the parameter is set.
					$reset_arg = 'gecko-customizer-reset';
					if (isset($_GET[$reset_arg]) && current_user_can('administrator')) {
						$this->reset();
						$url = remove_query_arg($reset_arg, $_SERVER['REQUEST_URI']);
						wp_redirect($url, '302');
						exit();
					}

					// Only load assets on the customizer page.
					add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
				}
			}
		}

		/**
		 * Add submenu page.
		 *
		 * @since 3.0.0.0
		 */
		public function register_sub_menu() {
			add_submenu_page(
				'gecko-settings',
				__('Gecko Customizer', 'gecko'),
				__('Gecko Customizer', 'gecko'),
				'manage_options',
				'gecko-customizer',
				[$this, 'create_admin_page'],
				1
			);
		}

		/**
		 * Settings page output.
		 *
		 * @since 3.0.0.0
		 */
		public function create_admin_page() {
			$active_preset = get_option('gecko_active_preset', 'light');
			$css_vars = array_merge($this->preset->get($active_preset)['css_vars'], get_option('gecko_css_vars', []));
			$settings_default = $this->preset->get($active_preset)['settings'];
			$settings = GeckoConfigSettings::get_instance();
			$template_dir = get_stylesheet_directory_uri();

			?>
			<div class="gca-customizer gc-js-customizer">
				<div class="gca-customizer__side">
					<div class="gca-customizer__side-inner">
						<a href="<?php echo admin_url(); ?>" class="gca-customizer__back">
							<i class="gcis gci-arrow-left"></i><?php _e('Back to WordPress', 'gecko'); ?>
						</a>
						<div class="gca-customizer__actions">
							<button type="button" class="gca-customizer__action gca-customizer__action--primary gc-js-btn-main gca-tip gca-tip--bottom"
									disabled="disabled"
									aria-label="<?php _e('Save changes to the current preset.', 'gecko'); ?>"
									data-publish="1">
								<i class="gc-js-loading" data-class-loading="gcis gci-circle-notch gci-spin"></i>
								<span>&nbsp; &nbsp; &nbsp;</span>
							</button>
							<div class="gc-dropdown">
								<div class="gc-dropdown__toggle">
									<button class="gca-customizer__action gc-js-btn-main-more">
										<i class="gcis gci-chevron-down"></i>
									</button>
								</div>
								<div class="gc-dropdown__box">
									<button type="button" class="gca-customizer__presets-action gca-tip gca-tip--bottom gc-js-btn-save"
										disabled="disabled"
										aria-label="<?php _e('Save the preset without setting it as a default.', 'gecko') ?>">
										<i class="gcis gci-save" data-class-loading="gcis gci-circle-notch gci-spin"></i>
										<?php _e('Save', 'gecko'); ?>
									</button>
									<button type="button" class="gca-customizer__presets-action gca-tip gca-tip--bottom gc-js-btn-publish"
										disabled="disabled"
										aria-label="<?php _e('Save the preset and set it as a default.', 'gecko') ?>">
										<i class="gcis gci-save" data-class-loading="gcis gci-circle-notch gci-spin"></i>
										<?php _e('Publish', 'gecko'); ?>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="gca-customizer__header">
						<img src="<?php echo $template_dir; ?>/assets/images/gecko.png" alt="Gecko Customizer">
					</div>
					<div class="gca-customizer__presets">
						<div class="gca-customizer__presets-label gc-js-options-label">
							<span class="gc-customizer__preset-togg gc-js-options-less"><?php _e('Preset', 'gecko'); ?>: <strong></strong></span>
							<span class="gc-customizer__preset-togg gc-js-options-more" style="display:none"><?php _e('Presets', 'gecko'); ?></span>
							<i class="gcis gci-expand-alt"
								data-class-expand="gcis gci-compress-alt"
								data-class-collapse="gcis gci-expand-alt"></i>
						</div>
						<div class="gca-customizer__presets-options gc-js-options-more" style="display:none">
							<select class="gc-js-preset">
								<?php

								$presets = $this->preset->list();
								foreach ($presets as $id => $preset) {
									$selected = $id === $active_preset;
									echo '<option value="' . $id . '"' . ($selected ? ' selected' : '') . '>'
										. $preset['label'] . ($selected ? ' (Default)' : '') . '</option>';
								}

								?>
							</select>
						</div>
					</div>
					<div class="gca-customizer__manage gc-js-actions gc-js-options-more" style="display:none">
						<div class="gca-customizer__manage-actions">
							<div class="gca-customizer__manage-action">
								<button type="button" class="gca-customizer__action gca-tip gca-tip--left gc-js-btn-reset"
									disabled="disabled"
									aria-label="<?php _e('Discard changes for the current preset.', 'gecko'); ?>">
									<i class="gcis gci-undo"></i>
									<?php _e('Reset', 'gecko'); ?>
								</button>
							</div>

							<div class="gca-customizer__manage-action">
								<button type="button" class="gca-customizer__action gca-customizer__action--primary gca-tip gc-js-btn-save-as"
									disabled="disabled"
									aria-label="<?php _e('New custom preset based on the current.', 'gecko'); ?>">
									<i class="gcir gci-copy"></i>
									<?php _e('Duplicate', 'gecko'); ?>
								</button>
							</div>

							<div class="gca-customizer__manage-action">
								<div class="gc-dropdown">
									<div class="gc-dropdown__toggle">
										<button type="button" class="gca-customizer__action gca-customizer__action--primary gc-js-btn-more"
											aria-label="<?php _e('More actions.', 'gecko'); ?>">
											<i class="gcis gci-ellipsis-v"></i>
										</button>
									</div>
									<div class="gc-dropdown__box">
										<button type="button" class="gca-customizer__presets-action gca-tip gc-js-btn-rename"
											disabled="disabled"
											aria-label="<?php _e('Rename selected preset.', 'gecko'); ?>">
											<i class="gcis gci-edit"></i>
											<?php _e('Rename', 'gecko'); ?>
										</button>
										<button type="button" class="gca-customizer__presets-action gca-tip gc-js-btn-delete"
											disabled="disabled"
											aria-label="<?php _e('Remove selected preset.', 'gecko'); ?>">
											<i class="gcis gci-trash-alt"></i>
											<?php _e('Delete', 'gecko'); ?>
										</button>
									</div>
								</div>
							</div>
						</div>

						<div class="gca-customizer__presets-add gc-js-save-as-form" style="display:none">
							<div class="gca-customizer__presets-add-title"><?php _e('Custom preset', 'gecko'); ?></div>
							<div class="gca-customizer__presets-add-name">
								<input class="gca-input" type="text" name="" value="" />
							</div>
							<div class="gca-customizer__presets-add-actions">
								<button type="button" class="gca-customizer__presets-add-action gca-customizer__presets-add-action--cancel gc-js-cancel">
									<?php _e('Cancel', 'gecko'); ?>
								</button>
								<button type="button" class="gca-customizer__presets-add-action gca-customizer__presets-add-action--save gc-js-save">
									<span class="gc-js-loading" style="display:none">
										<i class="gcis gci-circle-notch gci-spin"></i>
									</span>
									<?php _e('Save', 'gecko'); ?>
								</button>
							</div>
						</div>

						<div class="gca-customizer__presets-add gc-js-rename-form" style="display:none">
							<div class="gca-customizer__presets-add-title"><?php _e('Rename preset', 'gecko'); ?></div>
							<div class="gca-customizer__presets-add-name">
								<input class="gca-input" type="text" value=""
									placeholder="<?php _e('New preset name', 'gecko'); ?>" />
							</div>
							<div class="gca-customizer__presets-add-actions">
								<button type="button" class="gca-customizer__presets-add-action gca-customizer__presets-add-action--cancel gc-js-cancel">
									<?php _e('Cancel', 'gecko'); ?>
								</button>
								<button type="button" class="gca-customizer__presets-add-action gca-customizer__presets-add-action--save gc-js-save">
									<span class="gc-js-loading" style="display:none">
										<i class="gcis gci-circle-notch gci-spin"></i>
									</span>
									<?php _e('Update', 'gecko'); ?>
								</button>
							</div>
						</div>
					</div>
					<button type="button" class="gca-customizer__tabs-back gc-js-btn-back" disabled="disabled">
						<i class="gcis gci-angle-left"></i><span><?php _e('Back to Categories', 'gecko'); ?></span>
					</button>
					<div class="gca-customizer__menu">
						<div class="gca-customizer__tabs gc-js-customizer-tabs">
							<?php
       						$options = $this->options->list();
       						foreach ($options as $menu_item) : ?>
								<a href="#" class="gca-customizer__tab gc-js-customizer-tab" data-tab="#<?php echo $menu_item['id']; ?>">
									<i class="<?php echo $menu_item['icon']; ?>"></i><span><?php echo $menu_item['name']; ?><span><?php echo $menu_item['tags']; ?></span></span>
									<i class="gca-customizer__tab-arrow gcis gci-chevron-right"></i>
								</a>
							<?php endforeach ?>
						</div>

						<?php foreach ($options as $tab) : ?>
							<div class="gca-customizer__settings gc-js-settings" id="<?php echo $tab['id']; ?>">
								<div class="gca-customizer__settings-category">
									<?php if (isset($tab['name'])) : ?>
										<h2><?php echo $tab['name']; ?><i class="<?php echo $tab['icon']; ?>"></i></h2>
									<?php endif; ?>
									<?php if (isset($tab['desc'])) : ?>
										<p><?php echo $tab['desc']; ?></p>
									<?php endif; ?>
								</div>

								<?php foreach ($tab['options'] as $option): ?>
									<div class="gca-customizer__options gc-js-optgroup">
										<div class="gca-customizer__options-title gc-js-optgroup-title">
											<i class="<?php echo $option['icon']; ?>"></i><span><?php echo $option['title']; ?></span>
										</div>
										<?php if (isset($option['desc'])) : ?>
										<div class="gca-customizer__options-desc gc-js-optgroup-desc">
											<?php echo $option['desc']; ?>
										</div>
										<?php endif; ?>
										<div class="gca-customizer__options-list gc-js-optgroup-list">
										<?php foreach ($option['settings'] as $setting) {
											$value = '';
											$css = true;

											$setting['name'] = trim($setting['name'], '.');

											// Normalize value with default value.
											if (isset($setting['var']) && isset($css_vars[$setting['var']])) {
												$value = $css_vars[$setting['var']];
											} elseif (isset($setting['setting'])) {
												$value_default = isset($settings_default[$setting['setting']]) ? $settings_default[$setting['setting']] : null;
												$value = $settings->get_option($setting['setting'], $value_default, true);
												$css = false;
											}

											// Apply unit value if set.
											if (isset($setting['unit'])) {
												$value = str_replace($setting['unit'], '', (string) $value);
											}

											switch ($setting['type']) {
												case 'category':
													$this->render_option_category($setting, $value, $css);
													break;
												case 'color':
													$this->render_option_color($setting, $value, $css);
													break;
												case 'range':
													$this->render_option_range($setting, $value, $css);
													break;
												case 'switch':
													$this->render_option_switch($setting, $value, $css);
													break;
												case 'select':
													$this->render_option_select($setting, $value, $css);
													break;
												case 'textarea':
													$this->render_option_textarea($setting, $value, $css);
													break;
												case 'image':
													$this->render_option_image($setting, $value, $css);
													break;
												default:
													$this->render_option_default($setting, $value, $css);
													break;
											}
										} ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>

						<!-- built preset disabler -->
						<div class="gca-customizer__presets-alert gc-js-action-warning-desc" style="display:none">
							<div class="gca-customizer__presets-alert-overlay"></div>
							<div class="gca-customizer__presets-alert-inner">
								<i class="gcis gci-exclamation-triangle"></i>
								<?php echo __('Only custom presets can be modified - select a custom preset to edit. To create a custom preset, <u>duplicate a Gecko preset</u>.', 'gecko'); ?>
							</div>
						</div>
					</div>

					<div class="gca-customizer__viewport" arialabel="Viewport">
						<button class="gca-customizer__viewport-item gc-js-btn-viewport" data-width="375" data-height="667" aria-label="Mobile"><i class="gcis gci-mobile-alt"></i></button>
						<button class="gca-customizer__viewport-item gc-js-btn-viewport" data-width="768" data-height="100%" aria-label="Tablet"><i class="gcis gci-tablet-alt"></i></button>
						<button class="gca-customizer__viewport-item active gc-js-btn-viewport" data-width="100%" data-height="100%" aria-label="Desktop"><i class="gcis gci-desktop"></i></button>
					</div>
				</div>

				<div class="gca-customizer__preview gc-js-preview">
					<div class="gca-customizer__view">
						<div class="gca-customizer__view-loading">
							<p><?php _e('Loading', 'gecko'); ?>...</p>
							<i class="gcis gci-cog gci-spin"></i>
						</div>
						<iframe name="gecko-customizer-preview"
							src="<?php echo add_query_arg('gecko-preview', '1', get_home_url()); ?>"
							width="100%" height="100%"></iframe>
					</div>
				</div>

			</div>
		<?php
		}

		/**
		 * Render category-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_category($setting, $value, $css = true) {
			?>
			<div class="gca-customizer__category">
				<h3 id="<?php echo $setting['id']; ?>">
					<?php echo $setting['name']; ?>
				</h3>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
				<?php if (isset($setting['desc'])): ?>
				<p><?php echo $setting['desc']; ?></p>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Render color-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_color($setting, $value, $css = true) {
			?>
			<div class="gca-customizer__option gca-customizer__option--color" data-option-name="<?php echo $setting['id']; ?>">
				<input type="text" class="gca-customizer__option-input" autocomplete="off"
					name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>"
					data-option-type="color"
					<?php if (isset($setting['var'])): ?>
					data-var="<?php echo $setting['var']; ?>"
					<?php elseif (isset($setting['setting'])): ?>
					data-setting="<?php echo $setting['setting']; ?>"
					<?php endif; ?>
					value="<?php echo esc_attr($value); ?>" />
				<button type="button"></button>
				<label class="gca-customizer__option-label" for="<?php echo $setting['id']; ?>">
					<?php echo $setting['name']; ?>
				</label>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
				<?php if (isset($setting['desc'])): ?>
				<p><?php echo $setting['desc']; ?></p>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Render range-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_range($setting, $value, $css = true) {
			?>
			<div class="gca-customizer__option gca-customizer__option--range" data-option-name="<?php echo $setting['id']; ?>">
				<label class="gca-customizer__option-label" for="<?php echo $setting['id']; ?>">
					<?php echo $setting['name']; ?>
				</label>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
				<?php if (isset($setting['desc'])): ?>
				<p><?php echo $setting['desc']; ?></p>
				<?php endif; ?>
				<input type="text" class="gca-customizer__option-input gc-js-range-slider"
					name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>"
					data-option-type="range"
					<?php if (isset($setting['var'])): ?>
					data-var="<?php echo $setting['var']; ?>"
					<?php elseif (isset($setting['setting'])): ?>
					data-setting="<?php echo $setting['setting']; ?>"
					<?php endif; ?>
					data-unit="<?php echo isset($setting['unit']) ? $setting['unit'] : ''; ?>"
					data-min="<?php echo isset($setting['min']) ? $setting['min'] : ''; ?>"
					data-max="<?php echo isset($setting['max']) ? $setting['max'] : ''; ?>"
					data-step="<?php echo isset($setting['step']) ? $setting['step'] : ''; ?>"
					data-postfix="<?php echo isset($setting['unit']) ? $setting['unit'] : ''; ?>"
					data-values="<?php echo isset($setting['custom-values']) ? $setting['custom-values'] : ''; ?>"
					value="<?php echo esc_attr($value); ?>" />
				<div class="gca-customizer__option-controls js-option-controls">
					<button type="button" class="gca-customizer__option-control gca-customizer__option-control--minus" data-dir="-1">
						<i class="gcis gci-minus"></i>
					</button>
					<button type="button" class="gca-customizer__option-control gca-customizer__option-control--plus" data-dir="+1">
						<i class="gcis gci-plus"></i>
					</button>
				</div>
			</div>
			<?php
		}

		/**
		 * Render switch-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_switch($setting, $value, $css = true) {
			?>
			<div class="gca-customizer__option gca-customizer__option--switch">
				<div class="gca-checkbox">
					<input type="checkbox" class="gca-customizer__option-input"
						name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>"
						<?php if (isset($setting['var'])): ?>
						data-var="<?php echo $setting['var']; ?>"
						data-var-on="<?php echo $setting['on']; ?>"
						data-var-off="<?php echo $setting['off']; ?>"
						<?php elseif (isset($setting['setting'])): ?>
						data-setting="<?php echo $setting['setting']; ?>"
						data-setting-on="<?php echo $setting['on']; ?>"
						data-setting-off="<?php echo $setting['off']; ?>"
						<?php endif; ?>
						<?php echo $value == $setting['on'] ? 'checked' : ''; ?> />
					<label class="gca-customizer__option-label" for="<?php echo $setting['id']; ?>">
						<?php echo $setting['name']; ?>
					</label>
				</div>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
				<?php if (isset($setting['desc'])): ?>
				<p><?php echo $setting['desc']; ?></p>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Render select-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_select($setting, $value, $css = true) {
			?>
			<div class="gca-customizer__option gca-customizer__option--select">
				<label class="gca-customizer__option-label" for="<?php echo $setting['id']; ?>">
					<?php echo $setting['name']; ?>
				</label>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
				<?php if (isset($setting['desc'])): ?>
				<p><?php echo $setting['desc']; ?></p>
				<?php endif; ?>
				<select class="gca-input" name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>"
					<?php if (isset($setting['var'])): ?>
					data-var="<?php echo $setting['var']; ?>"
					<?php elseif (isset($setting['setting'])): ?>
					data-setting="<?php echo $setting['setting']; ?>"
					<?php endif; ?>
					data-unit="<?php echo isset($setting['unit']) ? $setting['unit'] : ''; ?>"
				>
					<?php foreach ($setting['options'] as $key => $label): ?>
					<option value="<?php echo esc_attr($key); ?>"<?php echo $key == $value ? ' selected' : ''; ?>>
						<?php echo $label; ?>
					</option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php
		}

		/**
		 * Render image-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_image($setting, $value, $css = true) {
			wp_enqueue_media();

			// Get the attachment URL.
			$src = wp_get_attachment_url($value);
			?>
			<div class="gca-customizer__option gca-customizer__option--image">
				<input type="hidden" name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>"
					data-option-type="image"
					<?php if (isset($setting['var'])): ?>
					data-var="<?php echo $setting['var']; ?>"
					<?php elseif (isset($setting['setting'])): ?>
					data-setting="<?php echo $setting['setting']; ?>"
					<?php endif; ?>
					data-desc="<?php echo esc_attr($setting['name']); ?>"
					value="<?php echo esc_attr($value); ?>" />

				<img <?php echo $src ? 'src="' . $src . '"' : ''; ?> style="max-width:100%" />
				<button type="button" data-action="upload" <?php echo $src ? 'style="display:none"' : ''; ?>>
					<i class="gcis gci-upload"></i><?php echo $setting['name']; ?>
				</button>
				<button type="button" data-action="delete" <?php echo $src ? '' : 'style="display:none"'; ?>>
					<i class="gcis gci-times"></i><?php echo __('Remove', 'gecko'); ?>
				</button>
				<button type="button" data-action="change" <?php echo $src ? '' : 'style="display:none"'; ?>>
					<i class="gcis gci-cog"></i><?php echo __('Change', 'gecko'); ?>
				</button>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Render textarea-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_textarea($setting, $value, $css = true) {
			?>
			<div class="gca-customizer__option gca-customizer__option--textarea">
				<label class="gca-customizer__option-label" for="<?php echo $setting['id']; ?>">
					<?php echo $setting['name']; ?>
				</label>
				<textarea type="text" class="gca-input" name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>"
					<?php if (isset($setting['var'])): ?>
					data-var="<?php echo $setting['var']; ?>"
					<?php elseif (isset($setting['setting'])): ?>
					data-setting="<?php echo $setting['setting']; ?>"
					<?php endif; ?>>
					<?php echo esc_attr($value); ?>
				</textarea>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
				<?php if (isset($setting['desc'])): ?>
				<p><?php echo $setting['desc']; ?></p>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Render default-type option.
		 *
		 * @since 3.0.0.0
		 *
		 * @param array $setting
		 * @param string $value
		 * @param bool $css
		 */
		public function render_option_default($setting, $value, $css = true) {
			?>
			<div class="gca-customizer__option gca-customizer__option--text">
				<label class="gca-customizer__option-label" for="<?php echo $setting['id']; ?>">
					<?php echo $setting['name']; ?>
				</label>
				<input type="text" class="gca-input" name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>"
					<?php if (isset($setting['var'])): ?>
					data-var="<?php echo $setting['var']; ?>"
					<?php elseif (isset($setting['setting'])): ?>
					data-setting="<?php echo $setting['setting']; ?>"
					<?php endif; ?>
					data-unit="<?php echo isset($setting['unit']) ? $setting['unit'] : ''; ?>"
					value="<?php echo esc_attr($value); ?>"/>
				<?php if (!$css) : ?>
				<p><?php echo $this->noncss_marker(); ?></p>
				<?php endif; ?>
				<?php if (isset($setting['desc'])): ?>
				<p><?php echo $setting['desc']; ?></p>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Return non-css marker.
		 *
		 * @return string
		 */
		private function noncss_marker() {
			$html = '<span><i class="gcis gci-globe"></i> '
				. __('<b>Global setting</b> - applies to all presets', 'gecko') .'</span>';

			return $html;
		}

		/**
		 * Maintain the 'gecko-preview' query param for the preview URL.
		 *
		 * @return boolean
		 */
		public function maybe_preview_redirect() {
			$url = $_SERVER['REQUEST_URI'];

			// Skip checking for admin page and built-in ajax requests.
			if (is_admin() || wp_doing_ajax()) {
				return false;
			}

			// Also, skip checking for normal ajax requests.
			if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
				if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
					return false;
				}
			}

			// Also, skip checking if query param is already present.
			if (isset($_GET['gecko-preview'])) {
				return false;
			}

			// Redirect only if the referer has query param and requested page has not.
			$referer = wp_get_referer();
			if ($referer && false !== strpos($referer, 'gecko-preview=')) {
				$url = add_query_arg('gecko-preview', '1', $url);
				wp_redirect($url, '302');
				exit();
			}
		}

		/**
		 * Reset all customizer confugirations.
		 *
		 * @since 3.0.0.0
		 */
		protected function reset() {
			$this->options->clear();
			$this->preset->clear();

			delete_option('gecko_active_preset');

			// Delete temporary configuration for preview.
			delete_transient('gecko_options');

		}

		/**
		 * Return non-css marker.
		 *
		 * @since 3.0.0.3
		 *
		 * @param string|array $inpt
		 * @return string|array
		 */
		private function sanitize_input($input) {
			if ( is_array( $input ) ) {
				foreach ($input as $key => $value) {
					$key = sanitize_key($key);
					$input[$key] = $this->sanitize_input($value);
				}
			} else {
				$input = is_string($input) ? sanitize_text_field( stripslashes($input) ) : '';
				if (is_numeric($input)) {
					$input = (int) $input;
				}
			}

			return $input;
		}

		/**
		 * Apply configurations.
		 *
		 * @since 3.0.0.0
		 */
		public function ajax_apply() {
			$this->options->clear();

			// Save selected preset.
			if (isset($_POST['id'])) {
				$preset = $_POST['id'];
				update_option('gecko_active_preset', $preset);
			}

			// Save selected settings.
			if (isset($_POST['settings'])) {
				$gecko_options = $_POST['settings'];
				foreach ($gecko_options as $key => $value) {
					$this->options->update($key, $value);
				}
			}

			// Save selected CSS variables.
			if (isset($_POST['css_vars'])) {
				$gecko_css_vars = $_POST['css_vars'];
				foreach ($gecko_css_vars as $key => $value) {
					$this->options->update($key, $value);
				}
			}

			// Delete temporary configurations upon save.
			delete_transient('gecko_options');

			$result = ['success' => true];
			wp_die(json_encode($result));
		}

		/**
		 * Apply configurations temporarily for preview.
		 *
		 * @since 3.0.0.0
		 */
		public function ajax_apply_temp() {
			// Save selected settings in transient.
			if (isset($_POST['settings'])) {
				$gecko_options = $this->sanitize_input($_POST['settings']);
				// Transient will expire in an hour.
				set_transient('gecko_options', $gecko_options, HOUR_IN_SECONDS);
			}

			$result = ['success' => true];
			wp_die(json_encode($result));
		}

		/**
		 * Clear temporarily saved configurations for preview.
		 *
		 * @since 3.0.0.0
		 */
		public function ajax_clear_temp() {
			delete_transient('gecko_options');

			$result = ['success' => true];
			wp_die(json_encode($result));
		}

		/**
		 * Save a preset.
		 *
		 * @since 3.0.0.0
		 */
		public function ajax_save_preset() {
			$configs = array();
			$configs['css_vars'] = isset( $_POST['css_vars'] ) ? $this->sanitize_input($_POST['css_vars']) : (object) array();
			$configs['settings'] = isset( $_POST['settings'] ) ? $this->sanitize_input($_POST['settings']) : (object) array();

			if (isset($_POST['name'])) {
				$preset = $this->preset->add($_POST['name'], $configs);
			} elseif (isset($_POST['id'])) {
				$preset = $this->preset->update($_POST['id'], $configs);

				// Apply preset if necessary.
				$publish = isset($_POST['publish']) ? (int) $_POST['publish'] : 0;
				if ($publish) {
					update_option('gecko_active_preset', $_POST['id']);
					foreach ($configs['settings'] as $key => $value) {
						$this->options->update($key, $value);
					}
				}
			}

			$result = [
				'success' => true,
				'data' => ['preset' => $preset]
			];

			wp_die(json_encode($result));
		}

		/**
		 * Rename a preset.
		 *
		 * @since 3.0.0.0
		 */
		public function ajax_rename_preset() {
			$result = ['success' => false];

			if (isset($_POST['id']) && isset($_POST['name'])) {
				$preset = $this->preset->rename($_POST['id'], $_POST['name']);
				$result = [
					'success' => true,
					'data' => ['preset' => $preset]
				];
			}

			wp_die(json_encode($result));
		}

		/**
		 * Delete a preset.
		 *
		 * @since 3.0.0.0
		 */
		public function ajax_delete_preset() {
			$result = ['success' => false];

			if (isset($_POST['id'])) {
				$preset = $this->preset->delete($_POST['id']);
				$result = [
					'success' => true,
					'data' => ['preset' => $preset]
				];
			}

			wp_die(json_encode($result));
		}

		/**
		 * Load required assets for the customizer page.
		 *
		 * @since 3.0.0.0
		 */
		public function enqueue_assets() {
			wp_enqueue_style('wp-color-picker');

			wp_enqueue_style(
				'gecko-font-awesome',
				gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/icons.css'),
				[],
				wp_get_theme()->version
			);

			wp_enqueue_style(
				'gecko-google-font',
				'https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap',
				[],
				wp_get_theme()->version
			);

			wp_enqueue_style(
				'gecko-customizer-rangeslider-css',
				gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/ion.rangeSlider.css'),
				[],
				wp_get_theme()->version
			);

			wp_enqueue_style(
				'gecko-customizer-colorpicker-css',
				gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/pickr.classic.min.css'),
				[],
				wp_get_theme()->version
			);

			wp_enqueue_style(
				'gecko-customizer-css',
				gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/css/customizer.css'),
				[],
				wp_get_theme()->version
			);

			wp_enqueue_script(
				'gecko-customizer-rangeslider-js',
				gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/js/ion.rangeSlider.min.js'),
				['jquery'],
				wp_get_theme()->version,
				true
			);

			wp_enqueue_script(
				'gecko-customizer-colorpicker-js',
				gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/js/pickr.es5.min.js'),
				['jquery'],
				wp_get_theme()->version,
				true
			);

			wp_enqueue_script(
				'gecko-customizer',
				gecko_add_cachebust_arg(get_template_directory_uri() . '/assets/js/customizer.js'),
				['jquery', 'wp-color-picker'],
				wp_get_theme()->version,
				true
			);

			wp_localize_script('gecko-customizer', 'gecko_customizer', [
				'presets' => $this->preset->list(),
				'text_save' => __('Save', 'gecko'),
				'text_saved' => __('Saved', 'gecko'),
				'text_publish' => __('Publish', 'gecko'),
				'text_published' => __('Published', 'gecko'),
				'text_default_preset' => __('Default preset', 'gecko'),
				'text_set_as_default_preset' => __('Set as default preset', 'gecko'),
				'text_notice_publish' => __('Are you sure want to save and set the current preset as default?', 'gecko'),
				'text_notice_unsaved' => __('Switching preset will discard unsaved changes to the current preset. Are you sure?', 'gecko'),
				'text_notice_reload' => __('Leaving the page will discard unsaved changes to the current preset. Are you sure?', 'gecko'),
				'text_notice_reset' => __('Are you sure want to discard any changes to the current preset?', 'gecko'),
				'text_notice_delete' => __('Are you sure want to delete the current preset?', 'gecko'),
			]);
		}
	}
}

Gecko_Customizer::get_instance();
