<?php
/**
 *  Create A Simple Theme Options Panel
 *
 */

//  Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//  Start Class
if ( ! class_exists( 'Gecko_Theme_Settings' ) ) {

	class Gecko_Theme_Settings {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'Gecko_Theme_Settings', 'add_admin_menu' ) );
				add_filter( 'gecko_sanitize_option', array('Gecko_Theme_Settings', 'sanitize'));
			}

		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $_GET['page'] == 'gecko-settings' ) {
				$options['opt_redirect_guest'] = (int) $options['opt_redirect_guest'];
			}

			// Return sanitized options
			return $options;

		}


		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			add_menu_page(
				esc_html__( 'Gecko', 'gecko' ),
				esc_html__( 'Gecko', 'gecko' ),
				'manage_options',
				'gecko-settings',
				array( 'Gecko_Theme_Settings', 'create_admin_page' ),
				get_template_directory_uri() . '/assets/images/logo.png'
			);
		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { ?>
			<form method="post" action="">
				<div class="gc-admin">
					<div class="gc-admin__actions">
						<input type="hidden" name="gecko-config-nonce" value="<?php echo wp_create_nonce('gecko-config-nonce') ?>"/>
						<?php submit_button(); ?>
					</div>
					<div class="gc-admin__wrapper">
						<div class="gc-admin__side">
							<h1><img src="<?php echo get_template_directory_uri(); ?>/assets/images/gecko-white.png" alt="Gecko" /></h1>
							<div class="gc-admin__menu">
								<a class="active" href="admin.php?page=gecko-settings"><?php esc_html_e( 'Settings', 'gecko' ); ?></a>
								<a class="new" href="admin.php?page=gecko-customizer"><?php esc_html_e( 'Gecko Customizer', 'gecko' ); ?></a>
								<?php if(!isset($_SERVER['HTTP_HOST']) || 'demo.peepso.com' != $_SERVER['HTTP_HOST'] ) { ?>
								<a href="admin.php?page=gecko-license"><?php esc_html_e( 'License', 'gecko' ); ?></a>
								<?php } ?>
								<a href="admin.php?page=gecko-page-builders"><?php esc_html_e( 'Page Builders', 'gecko' ); ?></a>
							</div>
						</div>

						<div class="gc-admin__options">
							<div class="gc-admin__version">
								<?php esc_html_e( 'Version', 'gecko' ); ?>: <?php echo wp_get_theme()->version ?>
							</div>
							<div class="gc-admin__header">
								<h3><?php esc_html_e( 'Settings', 'gecko' ); ?></h3>
							</div>

							<div class="gc-admin__tabs">
								<a class="active" href="javascript:" id="gc-tab-redirect"><?php esc_html_e( 'Redirects', 'gecko' ); ?></a>
							</div>

							<div class="gc-admin__fields gc-admin__tab gc-tab-redirect" style="display: block;">
								<div class="gc-form__group">
																		<div class="gc-form__row">
																				<div class="gc-form__row-label"><?php esc_html_e( 'Redirect guests to:', 'gecko' ); ?></div>

																				<div class="gc-form__controls">
																						<label class="gc-form__controls-label">
												<?php
												$settings = GeckoConfigSettings::get_instance();
												$value = $settings->get_option( 'opt_redirect_guest', 0 );

												$dropdown_args = array(
													'post_type'        => 'page',
													'selected'         => $value,
													'name'             => 'gecko_options[opt_redirect_guest]',
													'sort_column'      => 'menu_order, post_title',
													'echo'             => 1,
													'show_option_no_change' => 'Disabled',
												);

												wp_dropdown_pages( $dropdown_args );
												?>
																						</label>
																				</div>



																				<div class="gc-form__row-desc">
											<?php esc_html_e( 'Redirect all guests to specified page except from Registration and Privacy Policy pages', 'gecko' ); ?>
																				</div>
																		</div>

																		<div class="gc-form__row">
																				<div class="gc-form__row-label"><?php esc_html_e( 'Redirect exceptions:', 'gecko' ); ?></div>

																				<div class="gc-form__controls">
																						<label class="gc-form__controls-label">
												<?php
												$settings = GeckoConfigSettings::get_instance();
												$value = $settings->get_option( 'opt_redirect_guest_exceptions', '' );

												$dropdown_args = array(
													'post_type'        => 'page',
													'selected'         => $value,
													'name'             => 'gecko_options[opt_redirect_guest_exceptions]',
													'sort_column'      => 'menu_order, post_title',
													'echo'             => 1,
													'show_option_no_change' => 'Disabled',
												);

												//wp_dropdown_pages( $dropdown_args );
												?>
																								<input name="gecko_options[opt_redirect_guest_exceptions]" type="text" value="<?php echo $value?>" size="64" />
																						</label>
																				</div>



																				<div class="gc-form__row-desc">


                                                                                    <?php esc_html_e( 'Comma-separated list of page IDs that should be visible to visitors', 'gecko' ); ?>.

                                                                                    <br/><br/>

                                                                                    <?php esc_html_e( 'Use "blog" and "frontpage" to exclude your Blog Page and Frontpage', 'gecko' ); ?>.

                                                                                    <br/><br/>

                                                                                    <?php esc_html_e('For example','gecko');?>: <code>123,456,blog,789,frontpage</code>
																				</div>
																		</div>


								</div>
							</div>
						</div>
					</div><!-- .gc-admin__wrapper -->
					<div class="gc-admin__actions">
						<input type="hidden" name="gecko-config-nonce" value="<?php echo wp_create_nonce('gecko-config-nonce') ?>"/>
						<?php submit_button(); ?>
					</div>
				</div>
			</form>
		<?php }
	}
}
new Gecko_Theme_Settings();
