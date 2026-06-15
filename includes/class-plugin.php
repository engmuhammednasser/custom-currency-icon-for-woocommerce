<?php
/**
 * Class Plugin
 * الفئة الرئيسية لتهيئة البلاجن وتنسيق وحدات مختلفة
 *
 * @package CCFW
 */

namespace CCFW;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	/**
	 * Admin instance
	 *
	 * @var Admin
	 */
	private $admin;

	/**
	 * Frontend instance
	 *
	 * @var Frontend
	 */
	private $frontend;

	/**
	 * Settings instance
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * إنشاء singleton instance
	 */
	public static function get_instance() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Load dependencies
		$this->load_dependencies();

		// Setup hooks
		$this->setup_hooks();

		// Initialize sub-modules
		$this->admin    = new Admin();
		$this->frontend = new Frontend();
		$this->settings = new Settings();

		// Initialize modules
		$this->admin->init();
		$this->frontend->init();
		$this->settings->init();

		/**
		 * تشغيل hook بعد تهيئة البلاجن
		 */
		do_action( 'ccfw_loaded' );
	}

	/**
	 * تحميل ملفات التبعيات
	 */
	private function load_dependencies() {
		// Load required classes
		require_once CCFW_PLUGIN_DIR . 'includes/class-admin.php';
		require_once CCFW_PLUGIN_DIR . 'includes/class-frontend.php';
		require_once CCFW_PLUGIN_DIR . 'includes/class-settings.php';
		require_once CCFW_PLUGIN_DIR . 'includes/helpers.php';
	}

	/**
	 * إعداد الhooks الأساسية
	 */
	private function setup_hooks() {
		// Load admin and frontend styles/scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_assets' ) );
	}

	/**
	 * تحميل أصول (CSS/JS) Admin
	 *
	 * @param string $hook_suffix الـ page hook
	 */
	public function load_admin_assets( $hook_suffix ) {
		// Only load on plugin settings page
		if ( strpos( $hook_suffix, 'ccfw' ) === false ) {
			return;
		}

		// Register and enqueue CSS
		wp_enqueue_style(
			'ccfw-admin',
			CCFW_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			CCFW_VERSION
		);

		// Register and enqueue JS
		wp_enqueue_script(
			'ccfw-admin',
			CCFW_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			CCFW_VERSION,
			true
		);

		// Add media library support
		wp_enqueue_media();

		// Localize script data
		wp_localize_script(
			'ccfw-admin',
			'ccfwData',
			array(
				'nonce' => wp_create_nonce( 'ccfw_admin_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'textDomain' => CCFW_TEXT_DOMAIN,
			)
		);
	}

	/**
	 * تحميل أصول (CSS/JS) Frontend
	 */
	public function load_frontend_assets() {
		// Only load if plugin is enabled
		if ( ! ccfw_is_enabled() ) {
			return;
		}

		// Enqueue frontend CSS
		wp_enqueue_style(
			'ccfw-frontend',
			CCFW_PLUGIN_URL . 'assets/css/frontend.css',
			array(),
			CCFW_VERSION
		);
	}

	/**
	 * الحصول على Admin instance
	 *
	 * @return Admin
	 */
	public function get_admin() {
		return $this->admin;
	}

	/**
	 * الحصول على Frontend instance
	 *
	 * @return Frontend
	 */
	public function get_frontend() {
		return $this->frontend;
	}

	/**
	 * الحصول على Settings instance
	 *
	 * @return Settings
	 */
	public function get_settings() {
		return $this->settings;
	}
}
