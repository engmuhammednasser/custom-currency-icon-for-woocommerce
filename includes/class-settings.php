<?php
/**
 * Class Settings
 * إدارة إعدادات البلاجن
 *
 * @package CCFW
 */

namespace CCFW;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {

	/**
	 * Initialize settings
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'save_settings' ) );
	}

	/**
	 * حفظ الإعدادات
	 */
	public function save_settings() {
		// Only process on settings page
		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'ccfw_settings' ) {
			return;
		}

		// Only process POST requests
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			return;
		}

		// Verify nonce
		if ( empty( $_POST['ccfw_settings_nonce'] ) || ! wp_verify_nonce( $_POST['ccfw_settings_nonce'], 'ccfw_save_settings' ) ) {
			wp_die( esc_html__( 'Security check failed', CCFW_TEXT_DOMAIN ) );
		}

		// Verify user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access', CCFW_TEXT_DOMAIN ) );
		}

		// Get current settings
		$settings = ccfw_get_option();

		// Sanitize and validate inputs
		$settings['enabled'] = isset( $_POST['ccfw_enabled'] ) ? 1 : 0;

		// Display method
		if ( ! empty( $_POST['ccfw_display_method'] ) ) {
			$display_methods = array(
				'image_only',
				'image_before_price',
				'image_after_price',
				'symbol_only',
				'image_with_symbol',
				'image_with_name',
			);

			$method = sanitize_text_field( $_POST['ccfw_display_method'] );
			$settings['display_method'] = in_array( $method, $display_methods, true ) ? $method : 'image_before_price';
		}

		// Width and height
		$settings['width']  = ! empty( $_POST['ccfw_width'] ) ? (int) $_POST['ccfw_width'] : 24;
		$settings['height'] = ! empty( $_POST['ccfw_height'] ) ? (int) $_POST['ccfw_height'] : 24;

		// Margin
		$settings['margin'] = ! empty( $_POST['ccfw_margin'] ) ? (int) $_POST['ccfw_margin'] : 4;

		// Alignment
		if ( ! empty( $_POST['ccfw_alignment'] ) ) {
			$alignments = array( 'baseline', 'top', 'middle', 'bottom' );
			$align      = sanitize_text_field( $_POST['ccfw_alignment'] );
			$settings['alignment'] = in_array( $align, $alignments, true ) ? $align : 'middle';
		}

		// Custom CSS class
		if ( ! empty( $_POST['ccfw_custom_class'] ) ) {
			$settings['custom_class'] = sanitize_html_class( $_POST['ccfw_custom_class'] );
		} else {
			$settings['custom_class'] = '';
		}

		// Display locations
		$settings['show_in_product']   = isset( $_POST['ccfw_show_in_product'] ) ? 1 : 0;
		$settings['show_in_shop']      = isset( $_POST['ccfw_show_in_shop'] ) ? 1 : 0;
		$settings['show_in_cart']      = isset( $_POST['ccfw_show_in_cart'] ) ? 1 : 0;
		$settings['show_in_checkout']  = isset( $_POST['ccfw_show_in_checkout'] ) ? 1 : 0;
		$settings['show_in_order']     = isset( $_POST['ccfw_show_in_order'] ) ? 1 : 0;
		$settings['show_in_emails']    = isset( $_POST['ccfw_show_in_emails'] ) ? 1 : 0;

		// Save settings
		update_option( 'ccfw_settings', $settings );

		// Redirect to prevent form resubmission
		wp_safe_redirect( add_query_arg( array( 'page' => 'ccfw_settings', 'settings-updated' => 'true' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * الحصول على قائمة العملات المدعومة
	 *
	 * @return array
	 */
	public static function get_woocommerce_currencies() {
		if ( function_exists( 'get_woocommerce_currencies' ) ) {
			return get_woocommerce_currencies();
		}

		return array();
	}
}
