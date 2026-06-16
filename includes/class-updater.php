<?php
/**
 * Class Updater
 * معالجة التحديثات من GitHub
 * 
 * استخدام Plugin Update Checker بطريقة آمنة بدون token
 *
 * @package CCFW
 */

namespace CCFW;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Updater {

	/**
	 * Plugin file path
	 *
	 * @var string
	 */
	private $plugin_file;

	/**
	 * GitHub repository URL
	 *
	 * @var string
	 */
	private $github_repo = 'https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce';

	/**
	 * GitHub API URL
	 *
	 * @var string
	 */
	private $github_api = 'https://api.github.com/repos/engmuhammednasser/custom-currency-icon-for-woocommerce/releases/latest';

	/**
	 * Constructor
	 *
	 * @param string $plugin_file مسار ملف البلاجن الرئيسي
	 */
	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;

		// Setup update hooks
		add_filter( 'site_transient_update_plugins', array( $this, 'check_for_updates' ) );
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_updates' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info_api' ), 10, 3 );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );

		// Clear cache if manually requested
		if ( isset( $_GET['ccfw_check_updates'] ) && $_GET['ccfw_check_updates'] === '1' ) {
			add_action( 'admin_init', array( $this, 'clear_update_transient' ) );
		}
	}

	/**
	 * تفريغ الكاش الخاص بالتحديثات للتحقق اليدوي
	 */
	public function clear_update_transient() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}
		delete_transient( 'ccfw_remote_version' );
		delete_site_transient( 'update_plugins' );
		wp_safe_redirect( admin_url( 'plugins.php' ) );
		exit;
	}

	/**
	 * إضافة رابط "التحقق من التحديثات" في صفحة الإضافات
	 */
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

	/**
	 * فحص التحديثات من GitHub
	 *
	 * @param object $transient بيانات التحديثات
	 * @return object
	 */
	public function check_for_updates( $transient ) {
		// تجاهل إذا لم تكن هناك بيانات
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// الحصول على آخر إصدار من GitHub
		$remote_version = $this->get_remote_version();

		if ( ! $remote_version ) {
			return $transient;
		}

		// مقارنة الإصدارات
		$current_version = CCFW_VERSION;

		if ( version_compare( $remote_version, $current_version, '>' ) ) {
			$obj = new \stdClass();

			$obj->slug          = dirname( plugin_basename( $this->plugin_file ) );
			$obj->plugin        = plugin_basename( $this->plugin_file );
			$obj->new_version   = $remote_version;
			$obj->tested        = '6.0';
			$obj->requires      = CCFW_MIN_WP_VERSION;
			$obj->requires_php  = CCFW_MIN_PHP_VERSION;
			$obj->package       = $this->get_download_url( $remote_version );
			$obj->url           = $this->github_repo;
			$obj->author        = 'Muhammed Nasser';
			$obj->homepage      = $this->github_repo;

			// جلب بيانات الإصدار من GitHub
			$release_info = $this->get_release_info( $remote_version );

			if ( $release_info ) {
				$obj->sections = array(
					'description' => $release_info['description'] ?? '',
					'changelog'   => $release_info['changelog'] ?? '',
				);
			}

			$transient->response[ plugin_basename( $this->plugin_file ) ] = $obj;
		}

		return $transient;
	}

	/**
	 * معالجة استدعاءات API لمعلومات البلاجن
	 *
	 * @param false  $result النتيجة الافتراضية
	 * @param string $action نوع الإجراء
	 * @param object $args المعاملات
	 * @return object|false
	 */
	public function plugin_info_api( $result, $action, $args ) {
		// تجاهل إذا لم يكن هذا البلاجن
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		if ( plugin_basename( $this->plugin_file ) !== $args->slug ) {
			return $result;
		}

		// الحصول على معلومات البلاجن من GitHub
		$remote_version = $this->get_remote_version();

		if ( ! $remote_version ) {
			return $result;
		}

		$obj = new \stdClass();

		$obj->name             = __( 'Custom Currency Icon for WooCommerce', CCFW_TEXT_DOMAIN );
		$obj->slug             = plugin_basename( $this->plugin_file );
		$obj->version          = $remote_version;
		$obj->tested           = '6.0';
		$obj->requires         = CCFW_MIN_WP_VERSION;
		$obj->requires_php     = CCFW_MIN_PHP_VERSION;
		$obj->download_link    = $this->get_download_url( $remote_version );
		$obj->author           = '<a href="https://github.com/engmuhammednasser">Muhammed Nasser</a>';
		$obj->homepage         = $this->github_repo;
		$obj->plugin_name      = __( 'Custom Currency Icon for WooCommerce', CCFW_TEXT_DOMAIN );
		$obj->description      = __( 'عرض أيقونة مخصصة للعملة بدلاً من الرمز النصي العادي في متجر WooCommerce', CCFW_TEXT_DOMAIN );
		$obj->requires_plugins = array( 'woocommerce/woocommerce.php' );

		// جلب بيانات الإصدار من GitHub
		$release_info = $this->get_release_info( $remote_version );

		if ( $release_info ) {
			$obj->sections = array(
				'description' => $release_info['description'] ?? __( 'عرض أيقونة مخصصة للعملة بدلاً من الرمز النصي العادي في متجر WooCommerce', CCFW_TEXT_DOMAIN ),
				'changelog'   => $release_info['changelog'] ?? __( 'تحديث جديد', CCFW_TEXT_DOMAIN ),
			);
		}

		return $obj;
	}

	/**
	 * الحصول على آخر إصدار من GitHub
	 *
	 * @return string|false
	 */
	private function get_remote_version() {
		// استخدام cache transient
		$transient_key = 'ccfw_remote_version';
		$cached_version = get_transient( $transient_key );

		if ( false !== $cached_version && ! empty( $cached_version ) ) {
			return $cached_version;
		}

		// الحصول على آخر إصدار من GitHub API
		$response = wp_remote_get( $this->github_api, array(
			'timeout'    => 10,
			'sslverify'  => true,
			'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['tag_name'] ) ) {
			return false;
		}

		$version = str_replace( 'v', '', $data['tag_name'] );

		// Cache for 12 hours
		set_transient( $transient_key, $version, 12 * HOUR_IN_SECONDS );

		return $version;
	}

	/**
	 * جلب بيانات الإصدار من GitHub
	 *
	 * @param string $version رقم الإصدار
	 * @return array|false
	 */
	private function get_release_info( $version ) {
		$transient_key = 'ccfw_release_info_' . $version;
		$cached_info   = get_transient( $transient_key );

		if ( false !== $cached_info && ! empty( $cached_info ) ) {
			return $cached_info;
		}

		$response = wp_remote_get( $this->github_api, array(
			'timeout'    => 10,
			'sslverify'  => true,
			'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data ) ) {
			return false;
		}

		$info = array(
			'description' => wp_kses_post( $data['body'] ?? '' ),
			'changelog'   => wp_kses_post( $data['body'] ?? '' ),
		);

		// Cache for 12 hours
		set_transient( $transient_key, $info, 12 * HOUR_IN_SECONDS );

		return $info;
	}

	/**
	 * الحصول على رابط التحميل من GitHub Releases
	 *
	 * @param string $version رقم الإصدار
	 * @return string
	 */
	private function get_download_url( $version ) {
		// رابط التحميل من GitHub
		return sprintf(
			'https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce/archive/refs/tags/v%s.zip',
			esc_attr( $version )
		);
	}
}
