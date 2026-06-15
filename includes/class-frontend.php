<?php
/**
 * Class Frontend
 * معالجة عرض أيقونة العملة في الواجهة الأمامية
 *
 * @package CCFW
 */

namespace CCFW;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Frontend {

	/**
	 * Initialize frontend functionality
	 */
	public function init() {
		// Check if WooCommerce is available
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Always register hooks - let them handle the enabled check internally
		// This ensures the plugin works properly even on first activation

		// Hook into WooCommerce price display
		add_filter( 'woocommerce_price_html', array( $this, 'add_currency_icon_to_price' ), 10, 2 );
		add_filter( 'woocommerce_cart_totals_after_total', array( $this, 'add_currency_icon_to_cart_total' ) );
		add_filter( 'woocommerce_order_amount_total', array( $this, 'add_currency_icon_to_order_total' ) );

		// Register shortcode
		add_shortcode( 'currency_icon', array( $this, 'handle_currency_icon_shortcode' ) );

		// Product page price
		add_filter( 'woocommerce_get_price_html', array( $this, 'add_currency_icon_to_product_price' ), 10, 2 );

		// Shop/Archive pages
		add_filter( 'woocommerce_cart_item_price', array( $this, 'add_currency_icon_to_cart_item_price' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'add_currency_icon_to_cart_item_subtotal' ), 10, 3 );

		// Checkout
		add_filter( 'woocommerce_checkout_totals_subtotal_html', array( $this, 'add_currency_icon_to_checkout_total' ) );
		add_filter( 'woocommerce_checkout_totals_total_html', array( $this, 'add_currency_icon_to_checkout_total' ) );

		// Order emails
		add_filter( 'woocommerce_email_order_total_html', array( $this, 'add_currency_icon_to_email_total' ) );

		// Order details page
		add_filter( 'woocommerce_order_item_subtotal_html', array( $this, 'add_currency_icon_to_order_item_total' ), 10, 2 );
	}

	/**
	 * إضافة أيقونة العملة إلى السعر العام
	 *
	 * @param string $price_html HTML السعر
	 * @param object $product منتج WooCommerce
	 * @return string
	 */
	public function add_currency_icon_to_price( $price_html, $product = null ) {
		// في صفحة المتجر
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_shop'] ) {
			return $price_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $price_html, $currency, 'shop' );
	}

	/**
	 * إضافة أيقونة العملة إلى سعر المنتج
	 *
	 * @param string $price_html HTML السعر
	 * @param object $product منتج WooCommerce
	 * @return string
	 */
	public function add_currency_icon_to_product_price( $price_html, $product ) {
		if ( ! is_a( $product, 'WC_Product' ) ) {
			return $price_html;
		}

		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_product'] ) {
			return $price_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $price_html, $currency, 'product' );
	}

	/**
	 * إضافة أيقونة العملة إلى إجمالي السلة
	 *
	 * @param string $total_html HTML الإجمالي
	 * @return string
	 */
	public function add_currency_icon_to_cart_total( $total_html ) {
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_cart'] ) {
			return $total_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $total_html, $currency, 'cart' );
	}

	/**
	 * إضافة أيقونة العملة إلى سعر عنصر السلة
	 *
	 * @param string $price_html HTML السعر
	 * @param array  $cart_item عنصر السلة
	 * @param string $cart_item_key مفتاح عنصر السلة
	 * @return string
	 */
	public function add_currency_icon_to_cart_item_price( $price_html, $cart_item, $cart_item_key ) {
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_cart'] ) {
			return $price_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $price_html, $currency, 'cart' );
	}

	/**
	 * إضافة أيقونة العملة إلى الإجمالي الجزئي لعنصر السلة
	 *
	 * @param string $subtotal_html HTML الإجمالي الجزئي
	 * @param array  $cart_item عنصر السلة
	 * @param string $cart_item_key مفتاح عنصر السلة
	 * @return string
	 */
	public function add_currency_icon_to_cart_item_subtotal( $subtotal_html, $cart_item, $cart_item_key ) {
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_cart'] ) {
			return $subtotal_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $subtotal_html, $currency, 'cart' );
	}

	/**
	 * إضافة أيقونة العملة إلى إجمالي الدفع
	 *
	 * @param string $total_html HTML الإجمالي
	 * @return string
	 */
	public function add_currency_icon_to_checkout_total( $total_html ) {
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_checkout'] ) {
			return $total_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $total_html, $currency, 'checkout' );
	}

	/**
	 * إضافة أيقونة العملة إلى إجمالي الطلب
	 *
	 * @param string $total_html HTML الإجمالي
	 * @return string
	 */
	public function add_currency_icon_to_order_total( $total_html ) {
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_order'] ) {
			return $total_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $total_html, $currency, 'order' );
	}

	/**
	 * إضافة أيقونة العملة إلى عنصر الطلب
	 *
	 * @param string $subtotal_html HTML الإجمالي الجزئي
	 * @param array  $item عنصر الطلب
	 * @return string
	 */
	public function add_currency_icon_to_order_item_total( $subtotal_html, $item ) {
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_order'] ) {
			return $subtotal_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $subtotal_html, $currency, 'order' );
	}

	/**
	 * إضافة أيقونة العملة إلى إيميلات الطلب
	 *
	 * @param string $total_html HTML الإجمالي
	 * @return string
	 */
	public function add_currency_icon_to_email_total( $total_html ) {
		$settings = ccfw_get_currency_display_settings();

		if ( ! $settings['show_in_emails'] ) {
			return $total_html;
		}

		$currency = get_woocommerce_currency();

		return ccfw_format_price_with_icon( $total_html, $currency, 'email' );
	}

	/**
	 * معالجة shortcode [currency_icon]
	 *
	 * @param array $atts سمات الـ shortcode
	 * @return string
	 */
	public function handle_currency_icon_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'amount'   => 0,
				'currency' => get_woocommerce_currency(),
			),
			$atts,
			'currency_icon'
		);

		$amount   = floatval( $atts['amount'] );
		$currency = strtoupper( sanitize_text_field( $atts['currency'] ) );

		if ( $amount <= 0 ) {
			return '';
		}

		return ccfw_format_amount( $amount, $currency );
	}
}
