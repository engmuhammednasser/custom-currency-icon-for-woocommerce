<?php
/**
 * Settings Page Template
 *
 * @package CCFW
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current settings
$settings = ccfw_get_option();
?>

<div class="wrap ccfw-settings-page">
	<h1><?php esc_html_e( 'Custom Currency Icon for WooCommerce', CCFW_TEXT_DOMAIN ); ?></h1>

	<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Settings saved successfully!', CCFW_TEXT_DOMAIN ); ?></p>
		</div>
	<?php endif; ?>

	<form method="post" id="ccfw-settings-form">
		<?php wp_nonce_field( 'ccfw_save_settings', 'ccfw_settings_nonce' ); ?>

		<div class="ccfw-settings-container">
			<!-- Main Settings Column -->
			<div class="ccfw-settings-main">
				<!-- Enable/Disable Plugin -->
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="ccfw_enabled">
								<?php esc_html_e( 'Enable Currency Icon', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</th>
						<td>
							<input type="checkbox" name="ccfw_enabled" id="ccfw_enabled" value="1" 
								<?php checked( $settings['enabled'] ?? 1 ); ?> />
							<p class="description">
								<?php esc_html_e( 'Check to enable custom currency icons', CCFW_TEXT_DOMAIN ); ?>
							</p>
						</td>
					</tr>
				</table>

				<hr />

				<!-- Currency Image Upload -->
				<h2><?php esc_html_e( 'Currency Image', CCFW_TEXT_DOMAIN ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Select Currency & Upload Image', CCFW_TEXT_DOMAIN ); ?></label>
						</th>
						<td>
							<div id="ccfw-currency-upload-container">
								<select id="ccfw-currency-select" class="widefat">
									<option value=""><?php esc_html_e( 'Select a currency...', CCFW_TEXT_DOMAIN ); ?></option>
									<?php
									$currencies = \CCFW\Settings::get_woocommerce_currencies();
									foreach ( $currencies as $code => $name ) {
										printf(
											'<option value="%s">%s (%s)</option>',
											esc_attr( $code ),
											esc_html( $name ),
											esc_html( $code )
										);
									}
									?>
								</select>

								<div style="margin-top: 15px;">
									<button type="button" id="ccfw-upload-image-btn" class="button button-primary">
										<?php esc_html_e( 'Upload Image', CCFW_TEXT_DOMAIN ); ?>
									</button>
									<button type="button" id="ccfw-remove-image-btn" class="button button-secondary" style="display: none;">
										<?php esc_html_e( 'Remove Image', CCFW_TEXT_DOMAIN ); ?>
									</button>
								</div>

								<div id="ccfw-image-preview" style="margin-top: 15px;"></div>
								<input type="hidden" id="ccfw-image-id" value="" />
							</div>
							<p class="description">
								<?php esc_html_e( 'Supported formats: PNG, JPG, WebP. Max size: 2MB', CCFW_TEXT_DOMAIN ); ?>
							</p>
						</td>
					</tr>
				</table>

				<hr />

				<!-- Display Method -->
				<h2><?php esc_html_e( 'Display Method', CCFW_TEXT_DOMAIN ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="ccfw_display_method">
								<?php esc_html_e( 'How to display the currency icon', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</th>
						<td>
							<select name="ccfw_display_method" id="ccfw_display_method" class="widefat">
								<option value="image_before_price" <?php selected( $settings['display_method'] ?? 'image_before_price', 'image_before_price' ); ?>>
									<?php esc_html_e( 'Image Before Price', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="image_after_price" <?php selected( $settings['display_method'] ?? 'image_before_price', 'image_after_price' ); ?>>
									<?php esc_html_e( 'Image After Price', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="image_only" <?php selected( $settings['display_method'] ?? 'image_before_price', 'image_only' ); ?>>
									<?php esc_html_e( 'Image Only', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="symbol_only" <?php selected( $settings['display_method'] ?? 'image_before_price', 'symbol_only' ); ?>>
									<?php esc_html_e( 'Symbol Only', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="image_with_symbol" <?php selected( $settings['display_method'] ?? 'image_before_price', 'image_with_symbol' ); ?>>
									<?php esc_html_e( 'Image + Symbol', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="image_with_name" <?php selected( $settings['display_method'] ?? 'image_before_price', 'image_with_name' ); ?>>
									<?php esc_html_e( 'Image + Currency Name', CCFW_TEXT_DOMAIN ); ?>
								</option>
							</select>
						</td>
					</tr>
				</table>

				<hr />

				<!-- Image Size & Style -->
				<h2><?php esc_html_e( 'Image Size & Style', CCFW_TEXT_DOMAIN ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="ccfw_width">
								<?php esc_html_e( 'Width (px)', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</th>
						<td>
							<input type="number" name="ccfw_width" id="ccfw_width" value="<?php echo esc_attr( $settings['width'] ?? 24 ); ?>" min="10" max="200" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ccfw_height">
								<?php esc_html_e( 'Height (px)', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</th>
						<td>
							<input type="number" name="ccfw_height" id="ccfw_height" value="<?php echo esc_attr( $settings['height'] ?? 24 ); ?>" min="10" max="200" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ccfw_margin">
								<?php esc_html_e( 'Margin (px)', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</th>
						<td>
							<input type="number" name="ccfw_margin" id="ccfw_margin" value="<?php echo esc_attr( $settings['margin'] ?? 4 ); ?>" min="0" max="50" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ccfw_alignment">
								<?php esc_html_e( 'Vertical Alignment', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</th>
						<td>
							<select name="ccfw_alignment" id="ccfw_alignment">
								<option value="baseline" <?php selected( $settings['alignment'] ?? 'middle', 'baseline' ); ?>>
									<?php esc_html_e( 'Baseline', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="top" <?php selected( $settings['alignment'] ?? 'middle', 'top' ); ?>>
									<?php esc_html_e( 'Top', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="middle" <?php selected( $settings['alignment'] ?? 'middle', 'middle' ); ?>>
									<?php esc_html_e( 'Middle', CCFW_TEXT_DOMAIN ); ?>
								</option>
								<option value="bottom" <?php selected( $settings['alignment'] ?? 'middle', 'bottom' ); ?>>
									<?php esc_html_e( 'Bottom', CCFW_TEXT_DOMAIN ); ?>
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="ccfw_custom_class">
								<?php esc_html_e( 'Custom CSS Class', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="ccfw_custom_class" id="ccfw_custom_class" value="<?php echo esc_attr( $settings['custom_class'] ?? '' ); ?>" placeholder="e.g., my-custom-class" class="widefat" />
						</td>
					</tr>
				</table>

				<hr />

				<!-- Display Locations -->
				<h2><?php esc_html_e( 'Display Locations', CCFW_TEXT_DOMAIN ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Show Icon In', CCFW_TEXT_DOMAIN ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="ccfw_show_in_product" <?php checked( $settings['show_in_product'] ?? true ); ?> />
								<?php esc_html_e( 'Product Page', CCFW_TEXT_DOMAIN ); ?>
							</label><br />
							<label>
								<input type="checkbox" name="ccfw_show_in_shop" <?php checked( $settings['show_in_shop'] ?? true ); ?> />
								<?php esc_html_e( 'Shop/Archive Pages', CCFW_TEXT_DOMAIN ); ?>
							</label><br />
							<label>
								<input type="checkbox" name="ccfw_show_in_cart" <?php checked( $settings['show_in_cart'] ?? true ); ?> />
								<?php esc_html_e( 'Shopping Cart', CCFW_TEXT_DOMAIN ); ?>
							</label><br />
							<label>
								<input type="checkbox" name="ccfw_show_in_checkout" <?php checked( $settings['show_in_checkout'] ?? true ); ?> />
								<?php esc_html_e( 'Checkout Page', CCFW_TEXT_DOMAIN ); ?>
							</label><br />
							<label>
								<input type="checkbox" name="ccfw_show_in_order" <?php checked( $settings['show_in_order'] ?? true ); ?> />
								<?php esc_html_e( 'Order Details', CCFW_TEXT_DOMAIN ); ?>
							</label><br />
							<label>
								<input type="checkbox" name="ccfw_show_in_emails" <?php checked( $settings['show_in_emails'] ?? true ); ?> />
								<?php esc_html_e( 'Order Emails', CCFW_TEXT_DOMAIN ); ?>
							</label>
						</td>
					</tr>
				</table>

				<hr />

				<?php submit_button(); ?>
			</div>

			<!-- Preview Column -->
			<div class="ccfw-settings-preview">
				<div class="ccfw-preview-box">
					<h3><?php esc_html_e( 'Live Preview', CCFW_TEXT_DOMAIN ); ?></h3>
					<div id="ccfw-preview-content" style="padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; font-size: 18px;">
						<p style="color: #999;">
							<?php esc_html_e( 'Select settings to see preview...', CCFW_TEXT_DOMAIN ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<style>
	.ccfw-settings-container {
		display: grid;
		grid-template-columns: 2fr 1fr;
		gap: 30px;
		margin-top: 20px;
	}

	.ccfw-preview-box {
		background: white;
		padding: 20px;
		border: 1px solid #ddd;
		border-radius: 4px;
		position: sticky;
		top: 32px;
	}

	.ccfw-preview-box h3 {
		margin-top: 0;
		margin-bottom: 20px;
	}

	@media (max-width: 768px) {
		.ccfw-settings-container {
			grid-template-columns: 1fr;
		}

		.ccfw-preview-box {
			position: static;
		}
	}
</style>
