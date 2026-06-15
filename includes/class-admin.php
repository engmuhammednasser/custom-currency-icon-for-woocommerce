<?php
/**
 * Class Admin
 * إدارة لوحة تحكم WordPress والإعدادات
 *
 * @package CCFW
 */

namespace CCFW;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {

	/**
	 * Initialize admin functionality
	 */
	public function init() {
		// Add menu pages
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );

		// Handle AJAX requests
		add_action( 'wp_ajax_ccfw_upload_image', array( $this, 'handle_image_upload' ) );
		add_action( 'wp_ajax_ccfw_remove_image', array( $this, 'handle_image_remove' ) );
		add_action( 'wp_ajax_ccfw_update_preview', array( $this, 'handle_preview_update' ) );
		add_action( 'wp_ajax_ccfw_get_currency_image', array( $this, 'handle_get_currency_image' ) );
	}

	/**
	 * إضافة صفحات القائمة
	 */
	public function add_menu_pages() {
		// Add settings page under WooCommerce menu
		add_submenu_page(
			'woocommerce',
			__( 'Currency Icon Settings', CCFW_TEXT_DOMAIN ),
			__( 'Currency Icon', CCFW_TEXT_DOMAIN ),
			'manage_options',
			'ccfw_settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * عرض صفحة الإعدادات
	 */
	public function render_settings_page() {
		// Verify user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access', CCFW_TEXT_DOMAIN ) );
		}

		include CCFW_PLUGIN_DIR . 'includes/templates/settings-page.php';
	}

	/**
	 * معالجة رفع الصور
	 *
	 * @return void
	 */
	public function handle_image_upload() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccfw_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Verify user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Check if files are uploaded or image_id provided
		$image_id = ! empty( $_POST['image_id'] ) ? (int) $_POST['image_id'] : 0;
		$currency = ! empty( $_POST['currency'] ) ? strtoupper( sanitize_text_field( $_POST['currency'] ) ) : '';

		// If image_id provided (from media library), just save it
		if ( $image_id > 0 && $currency ) {
			ccfw_update_option( 'currency_image_' . $currency, $image_id );
			$image_url = wp_get_attachment_url( $image_id );
			wp_send_json_success( array(
				'attachment_id' => $image_id,
				'url' => $image_url,
			) );
			wp_die();
		}

		// Otherwise, require file upload
		if ( empty( $_FILES ) || empty( $_FILES['file'] ) ) {
			wp_send_json_error( array( 'message' => __( 'No file uploaded', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Validate file
		$file_validation = ccfw_validate_image_file( $_FILES['file'] );

		if ( isset( $file_validation['error'] ) ) {
			wp_send_json_error( array( 'message' => $file_validation['error'] ) );
			wp_die();
		}

		// Upload file
		$upload = wp_handle_upload(
			$_FILES['file'],
			array(
				'test_form' => false,
				'mimes'     => array(
					'jpg|jpeg' => 'image/jpeg',
					'png'      => 'image/png',
					'webp'     => 'image/webp',
				),
			)
		);

		if ( isset( $upload['error'] ) ) {
			wp_send_json_error( array( 'message' => $upload['error'] ) );
			wp_die();
		}

		// Create attachment
		$attachment_id = wp_insert_attachment(
			array(
				'guid'           => $upload['url'],
				'post_mime_type' => $upload['type'],
				'post_title'     => sanitize_file_name( $_FILES['file']['name'] ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$upload['file']
		);

		if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Failed to create attachment', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Generate attachment metadata
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );

		if ( $attachment_data ) {
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
		}

		// Save currency image
		if ( ! empty( $_POST['currency'] ) ) {
			$currency = strtoupper( sanitize_text_field( $_POST['currency'] ) );
			ccfw_update_option( 'currency_image_' . $currency, $attachment_id );
		}

		wp_send_json_success( array(
			'attachment_id' => $attachment_id,
			'url'           => $upload['url'],
		) );

		wp_die();
	}

	/**
	 * معالجة إزالة الصور
	 *
	 * @return void
	 */
	public function handle_image_remove() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccfw_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Verify user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Validate input
		if ( empty( $_POST['currency'] ) || empty( $_POST['image_id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid parameters', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		$currency  = strtoupper( sanitize_text_field( $_POST['currency'] ) );
		$image_id  = (int) $_POST['image_id'];

		// Delete attachment
		wp_delete_attachment( $image_id, true );

		// Remove from settings
		ccfw_update_option( 'currency_image_' . $currency, 0 );

		wp_send_json_success( array( 'message' => __( 'Image removed successfully', CCFW_TEXT_DOMAIN ) ) );

		wp_die();
	}

	/**
	 * Get currency image for AJAX
	 *
	 * @return void
	 */
	public function handle_get_currency_image() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccfw_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Verify user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Validate input
		if ( empty( $_POST['currency'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid currency', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		$currency = strtoupper( sanitize_text_field( $_POST['currency'] ) );
		$image_id = ccfw_get_option( 'currency_image_' . $currency );

		if ( ! $image_id || $image_id === 0 ) {
			wp_send_json_success( array(
				'image_id' => 0,
				'url' => '',
			) );
			wp_die();
		}

		$image_url = wp_get_attachment_url( $image_id );

		wp_send_json_success( array(
			'image_id' => $image_id,
			'url' => $image_url,
		) );

		wp_die();
	}

	/**
	 * معالجة تحديث المعاينة المباشرة
	 *
	 * @return void
	 */
	public function handle_preview_update() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ccfw_admin_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Verify user capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', CCFW_TEXT_DOMAIN ) ) );
			wp_die();
		}

		// Get preview data
		$preview_data = isset( $_POST['preview_data'] ) ? array_map( 'sanitize_text_field', $_POST['preview_data'] ) : array();

		// Build preview HTML
		$currency      = ! empty( $preview_data['currency'] ) ? strtoupper( $preview_data['currency'] ) : get_woocommerce_currency();
		$display_method = ! empty( $preview_data['display_method'] ) ? $preview_data['display_method'] : 'image_before_price';
		$amount         = ! empty( $preview_data['amount'] ) ? floatval( $preview_data['amount'] ) : 100;

		// Temporarily update settings for preview
		$original_settings = ccfw_get_option();
		$temp_settings     = $original_settings;
		$temp_settings['display_method'] = $display_method;
		$temp_settings['width']          = ! empty( $preview_data['width'] ) ? (int) $preview_data['width'] : 24;
		$temp_settings['height']         = ! empty( $preview_data['height'] ) ? (int) $preview_data['height'] : 24;
		$temp_settings['margin']         = ! empty( $preview_data['margin'] ) ? (int) $preview_data['margin'] : 4;
		$temp_settings['alignment']      = ! empty( $preview_data['alignment'] ) ? $preview_data['alignment'] : 'middle';

		update_option( 'ccfw_settings', $temp_settings );

		// Generate preview
		$price_html = wc_price( $amount, array( 'currency' => $currency ) );
		$preview_html = ccfw_format_price_with_icon( $price_html, $currency, 'product' );

		// Restore original settings
		update_option( 'ccfw_settings', $original_settings );

		wp_send_json_success( array(
			'preview' => $preview_html,
			'currency' => $currency,
		) );

		wp_die();
	}
}
