<?php
/**
 * Class Updater
 * معالجة التحديثات من GitHub بدون الحاجة لعمل Releases
 * 
 * @package CCFW
 */

namespace CCFW;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Updater {

	private $plugin_file;
	private $github_repo = 'https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce';
	private $raw_plugin_file = 'https://raw.githubusercontent.com/engmuhammednasser/custom-currency-icon-for-woocommerce/main/custom-currency-icon-for-woocommerce.php';

	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;

		// Setup update hooks
		add_filter( 'site_transient_update_plugins', array( $this, 'check_for_updates' ) );
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_updates' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info_api' ), 10, 3 );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );
		add_filter( 'upgrader_source_selection', array( $this, 'rename_github_zip' ), 10, 3 );

		// Clear cache if manually requested
		if ( isset( $_GET['ccfw_check_updates'] ) && $_GET['ccfw_check_updates'] === '1' ) {
			add_action( 'admin_init', array( $this, 'clear_update_transient' ) );
		}
	}

	public function clear_update_transient() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}
		delete_transient( 'ccfw_remote_version' );
		delete_site_transient( 'update_plugins' );
		wp_safe_redirect( admin_url( 'plugins.php' ) );
		exit;
	}

	public function add_plugin_row_meta( $links, $file ) {
		if ( plugin_basename( $this->plugin_file ) === $file ) {
			$check_url = wp_nonce_url( admin_url( 'plugins.php?ccfw_check_updates=1' ) );
			$row_meta = array(
				'check_update' => '<a href="' . esc_url( $check_url ) . '">' . __( 'التحقق من التحديثات', CCFW_TEXT_DOMAIN ) . '</a>',
			);
			return array_merge( $links, $row_meta );
		}
		return $links;
	}

	public function check_for_updates( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$remote_version = $this->get_remote_version();

		if ( ! $remote_version ) {
			return $transient;
		}

		$current_version = CCFW_VERSION;

		if ( version_compare( $remote_version, $current_version, '>' ) ) {
			$obj = new \stdClass();

			$obj->slug          = dirname( plugin_basename( $this->plugin_file ) );
			$obj->plugin        = plugin_basename( $this->plugin_file );
			$obj->new_version   = $remote_version;
			$obj->tested        = '6.0';
			$obj->requires      = CCFW_MIN_WP_VERSION;
			$obj->requires_php  = CCFW_MIN_PHP_VERSION;
			$obj->package       = $this->get_download_url();
			$obj->url           = $this->github_repo;
			$obj->author        = 'Muhammed Nasser';
			$obj->homepage      = $this->github_repo;

			$transient->response[ plugin_basename( $this->plugin_file ) ] = $obj;
		}

		return $transient;
	}

	public function plugin_info_api( $result, $action, $args ) {
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		if ( plugin_basename( $this->plugin_file ) !== $args->slug && dirname( plugin_basename( $this->plugin_file ) ) !== $args->slug ) {
			return $result;
		}

		$remote_version = $this->get_remote_version();

		if ( ! $remote_version ) {
			return $result;
		}

		$obj = new \stdClass();

		$obj->name             = __( 'Custom Currency Icon for WooCommerce', CCFW_TEXT_DOMAIN );
		$obj->slug             = dirname( plugin_basename( $this->plugin_file ) );
		$obj->version          = $remote_version;
		$obj->tested           = '6.0';
		$obj->requires         = CCFW_MIN_WP_VERSION;
		$obj->requires_php     = CCFW_MIN_PHP_VERSION;
		$obj->download_link    = $this->get_download_url();
		$obj->author           = '<a href="https://github.com/engmuhammednasser">Muhammed Nasser</a>';
		$obj->homepage         = $this->github_repo;
		$obj->plugin_name      = __( 'Custom Currency Icon for WooCommerce', CCFW_TEXT_DOMAIN );
		$obj->description      = __( 'عرض أيقونة مخصصة للعملة بدلاً من الرمز النصي العادي في متجر WooCommerce', CCFW_TEXT_DOMAIN );
		$obj->sections         = array(
			'description' => __( 'تم جلب هذا التحديث مباشرة من GitHub (فرع main).', CCFW_TEXT_DOMAIN ),
			'changelog'   => __( 'يرجى مراجعة GitHub لمعرفة التحديثات الجديدة.', CCFW_TEXT_DOMAIN ),
		);

		return $obj;
	}

	private function get_remote_version() {
		$transient_key = 'ccfw_remote_version';
		$cached_version = get_transient( $transient_key );

		if ( false !== $cached_version && ! empty( $cached_version ) ) {
			return $cached_version;
		}

		$response = wp_remote_get( $this->raw_plugin_file, array(
			'timeout'    => 10,
			'sslverify'  => false, // Avoid local SSL issues
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		
		if ( preg_match( '/^[ \t\/*#@]*Version:\s*(.*)$/im', $body, $matches ) ) {
			$version = trim( $matches[1] );
			set_transient( $transient_key, $version, 12 * HOUR_IN_SECONDS );
			return $version;
		}

		return false;
	}

	private function get_download_url() {
		return 'https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce/archive/refs/heads/main.zip';
	}

	public function rename_github_zip( $source, $remote_source, $upgrader ) {
		global $wp_filesystem;

		$plugin_slug = dirname( plugin_basename( $this->plugin_file ) );

		if ( strpos( $source, $plugin_slug . '-main' ) !== false ) {
			$corrected_source = trailingslashit( $remote_source ) . $plugin_slug . '/';
			if ( $wp_filesystem->move( $source, $corrected_source, true ) ) {
				return $corrected_source;
			}
		}
		return $source;
	}
}
