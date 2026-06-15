<?php
/**
 * Plugin Name: Custom Currency Icon for WooCommerce
 * Plugin URI: https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce
 * Description: عرض أيقونة مخصصة للعملة بدلاً من الرمز النصي العادي في متجر WooCommerce
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Author: Muhammed Nasser
 * Author URI: https://github.com/engmuhammednasser
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-currency-icon-for-woocommerce
 * Domain Path: /languages
 * Update URI: https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce
 * Requires Plugins: woocommerce
 *
 * @package CCFW
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants
define( 'CCFW_VERSION', '1.0.2' );
define( 'CCFW_PLUGIN_FILE', __FILE__ );
define( 'CCFW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCFW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CCFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'CCFW_TEXT_DOMAIN', 'custom-currency-icon-for-woocommerce' );
define( 'CCFW_MIN_WP_VERSION', '5.0' );
define( 'CCFW_MIN_PHP_VERSION', '7.4' );
define( 'CCFW_MIN_WOOCOMMERCE_VERSION', '3.0' );

/**
 * تحقق من متطلبات PHP و WordPress
 */
function ccfw_check_requirements() {
	$errors = array();

	// Check PHP version
	if ( version_compare( PHP_VERSION, CCFW_MIN_PHP_VERSION, '<' ) ) {
		$errors[] = sprintf(
			/* translators: %s: Required PHP version */
			esc_html__( 'Custom Currency Icon for WooCommerce requires PHP version %s or higher. You are running version %s.', CCFW_TEXT_DOMAIN ),
			CCFW_MIN_PHP_VERSION,
			PHP_VERSION
		);
	}

	// Check WordPress version
	if ( version_compare( $GLOBALS['wp_version'], CCFW_MIN_WP_VERSION, '<' ) ) {
		$errors[] = sprintf(
			/* translators: %s: Required WordPress version */
			esc_html__( 'Custom Currency Icon for WooCommerce requires WordPress version %s or higher.', CCFW_TEXT_DOMAIN ),
			CCFW_MIN_WP_VERSION
		);
	}

	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		$errors[] = esc_html__( 'Custom Currency Icon for WooCommerce requires WooCommerce to be installed and activated.', CCFW_TEXT_DOMAIN );
	}

	return $errors;
}

/**
 * عرض رسائل الأخطاء في لوحة التحكم
 */
function ccfw_admin_notice_errors() {
	$errors = ccfw_check_requirements();

	if ( empty( $errors ) ) {
		return;
	}

	foreach ( $errors as $error ) {
		printf(
			'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
			wp_kses_post( $error )
		);
	}
}

/**
 * تحميل البلاجن الرئيسي
 */
function ccfw_init_plugin() {
	// Error log for debugging
	error_log( 'CCFW: Initialization started' );

	// Load text domain
	load_plugin_textdomain( CCFW_TEXT_DOMAIN, false, dirname( CCFW_PLUGIN_BASENAME ) . '/languages' );

	// Check requirements
	$requirements = ccfw_check_requirements();

	if ( ! empty( $requirements ) ) {
		error_log( 'CCFW: Requirements not met - ' . implode( ', ', $requirements ) );
		// Show admin notices on all admin pages
		add_action( 'admin_notices', 'ccfw_admin_notice_errors' );
		return;
	}

	error_log( 'CCFW: Requirements met, loading classes' );

	// Load required classes
	if ( ! class_exists( '\CCFW\Plugin' ) ) {
		$plugin_class_path = CCFW_PLUGIN_DIR . 'includes/class-plugin.php';
		error_log( 'CCFW: Loading plugin class from ' . $plugin_class_path );
		if ( ! file_exists( $plugin_class_path ) ) {
			error_log( 'CCFW ERROR: Plugin class file not found!' );
			return;
		}
		require_once $plugin_class_path;
	}

	if ( ! class_exists( '\CCFW\Updater' ) ) {
		$updater_class_path = CCFW_PLUGIN_DIR . 'includes/class-updater.php';
		error_log( 'CCFW: Loading updater class from ' . $updater_class_path );
		if ( ! file_exists( $updater_class_path ) ) {
			error_log( 'CCFW ERROR: Updater class file not found!' );
			return;
		}
		require_once $updater_class_path;
	}

	// Initialize plugin
	try {
		error_log( 'CCFW: Creating plugin instance' );
		$plugin = new \CCFW\Plugin();
		
		error_log( 'CCFW: Calling plugin init()' );
		$plugin->init();

		error_log( 'CCFW: Creating updater instance' );
		// GitHub Updater
		new \CCFW\Updater( CCFW_PLUGIN_FILE );
		
		error_log( 'CCFW: Plugin initialized successfully' );
	} catch ( Exception $e ) {
		// Log error for debugging
		error_log( 'CCFW Plugin Error: ' . $e->getMessage() );
		error_log( 'CCFW Stack trace: ' . $e->getTraceAsString() );

		// Show error notice
		add_action( 'admin_notices', function() {
			?>
			<div class="notice notice-error is-dismissible">
				<p>
					<strong><?php esc_html_e( 'Custom Currency Icon for WooCommerce:', CCFW_TEXT_DOMAIN ); ?></strong>
					<?php esc_html_e( 'An error occurred while initializing the plugin. Check debug.log for details.', CCFW_TEXT_DOMAIN ); ?>
				</p>
			</div>
			<?php
		});
	}
}

// Load plugin on plugins_loaded hook with priority 99
add_action( 'plugins_loaded', 'ccfw_init_plugin', 99 );

// Register activation/deactivation hooks
register_activation_hook( __FILE__, function() {
	// Verify requirements before activation
	$errors = ccfw_check_requirements();
	
	if ( ! empty( $errors ) ) {
		// Deactivate plugin if requirements not met
		deactivate_plugins( CCFW_PLUGIN_BASENAME );
		wp_die( implode( '<br>', $errors ) );
	}

	// Set default options
	if ( ! get_option( 'ccfw_settings' ) ) {
		update_option( 'ccfw_settings', array(
			'enabled' => true,
			'display_method' => 'image_before_price',
			'width' => 24,
			'height' => 24,
			'margin' => 4,
			'alignment' => 'middle',
			'show_in_product' => true,
			'show_in_shop' => true,
			'show_in_cart' => true,
			'show_in_checkout' => true,
			'show_in_order' => true,
			'show_in_emails' => true,
		));
	}

	// Flush rewrite rules
	flush_rewrite_rules();
});

register_deactivation_hook( __FILE__, function() {
	// Flush rewrite rules
	flush_rewrite_rules();
});
