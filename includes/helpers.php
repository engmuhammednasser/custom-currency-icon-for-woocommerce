<?php
/**
 * Helper Functions
 * دوال مساعدة لسهولة التعامل مع الإعدادات والبيانات
 *
 * @package CCFW
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * الحصول على إعدادات البلاجن
 *
 * @param string $key مفتاح الإعدادات (اختياري)
 * @param mixed  $default القيمة الافتراضية
 * @return mixed
 */
function ccfw_get_option( $key = '', $default = false ) {
	$options = get_option( 'ccfw_settings', array() );

	if ( empty( $key ) ) {
		return $options;
	}

	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/**
 * تحديث إعدادات البلاجن
 *
 * @param string $key مفتاح الإعدادات
 * @param mixed  $value القيمة الجديدة
 * @return bool
 */
function ccfw_update_option( $key, $value ) {
	$options = get_option( 'ccfw_settings', array() );

	$options[ $key ] = $value;

	return update_option( 'ccfw_settings', $options );
}

/**
 * التحقق من تفعيل البلاجن
 *
 * @return bool
 */
function ccfw_is_enabled() {
	return ccfw_get_option( 'enabled', true );
}

/**
 * الحصول على صورة العملة
 *
 * @param string $currency رمز العملة (مثل 'SAR', 'USD')
 * @return array|false
 */
function ccfw_get_currency_image( $currency = '' ) {
	if ( empty( $currency ) ) {
		$currency = get_woocommerce_currency();
	}

	$currency = strtoupper( sanitize_text_field( $currency ) );
	$image_id = ccfw_get_option( 'currency_image_' . $currency, 0 );

	if ( ! $image_id ) {
		return false;
	}

	$image_data = wp_get_attachment_image_src( $image_id, 'full' );

	if ( ! $image_data ) {
		return false;
	}

	return array(
		'id'  => $image_id,
		'src' => $image_data[0],
		'alt' => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: $currency,
	);
}

/**
 * الحصول على إعدادات العرض للعملة
 *
 * @param string $currency رمز العملة
 * @return array
 */
function ccfw_get_currency_display_settings( $currency = '' ) {
	if ( empty( $currency ) ) {
		$currency = get_woocommerce_currency();
	}

	$currency = strtoupper( sanitize_text_field( $currency ) );

	return array(
		'display_method'  => ccfw_get_option( 'display_method', 'image_before_price' ),
		'width'           => (int) ccfw_get_option( 'width', 24 ),
		'height'          => (int) ccfw_get_option( 'height', 24 ),
		'margin'          => (int) ccfw_get_option( 'margin', 4 ),
		'alignment'       => ccfw_get_option( 'alignment', 'middle' ),
		'custom_class'    => sanitize_text_field( ccfw_get_option( 'custom_class', '' ) ),
		'show_in_product' => ccfw_get_option( 'show_in_product', true ),
		'show_in_shop'    => ccfw_get_option( 'show_in_shop', true ),
		'show_in_cart'    => ccfw_get_option( 'show_in_cart', true ),
		'show_in_checkout' => ccfw_get_option( 'show_in_checkout', true ),
		'show_in_order'   => ccfw_get_option( 'show_in_order', true ),
		'show_in_emails'  => ccfw_get_option( 'show_in_emails', true ),
	);
}

/**
 * HTML لعرض أيقونة العملة
 *
 * @param string $currency رمز العملة
 * @param float  $amount المبلغ (اختياري)
 * @return string
 */
function ccfw_get_currency_icon_html( $currency = '', $amount = null ) {
	if ( ! ccfw_is_enabled() ) {
		return '';
	}

	if ( empty( $currency ) ) {
		$currency = get_woocommerce_currency();
	}

	$image = ccfw_get_currency_image( $currency );

	if ( ! $image ) {
		// Fallback to default currency symbol
		return '';
	}

	$settings = ccfw_get_currency_display_settings( $currency );

	$width  = $settings['width'];
	$height = $settings['height'];
	$margin = $settings['margin'];
	$align  = sanitize_text_field( $settings['alignment'] );

	$style = sprintf(
		'width: auto; height: 1.2em; margin: 0 %dpx; vertical-align: %s; display: inline-block;',
		$margin,
		$align
	);

	$custom_class = ! empty( $settings['custom_class'] ) ? ' ' . sanitize_html_class( $settings['custom_class'] ) : '';

	$html = sprintf(
		'<img src="%s" alt="%s" class="ccfw-currency-icon%s" style="%s" />',
		esc_url( $image['src'] ),
		esc_attr( $image['alt'] ),
		$custom_class,
		$style
	);

	/**
	 * تصفية HTML لأيقونة العملة
	 *
	 * @param string $html HTML الأيقونة
	 * @param string $currency رمز العملة
	 * @param array  $image بيانات الصورة
	 * @param array  $settings إعدادات العرض
	 */
	return apply_filters( 'ccfw_currency_icon_html', $html, $currency, $image, $settings );
}

/**
 * معالجة السعر مع أيقونة العملة
 *
 * @param string $price HTML السعر الأصلي
 * @param string $currency رمز العملة
 * @param string $display_location مكان العرض (product, shop, cart, checkout, order, email)
 * @return string
 */
function ccfw_format_price_with_icon( $price, $currency = '', $display_location = 'product' ) {
	if ( ! ccfw_is_enabled() ) {
		return $price;
	}

	if ( empty( $currency ) ) {
		$currency = get_woocommerce_currency();
	}

	$settings = ccfw_get_currency_display_settings( $currency );

	// تحقق من إمكانية العرض في هذا الموقع
	$show_key = 'show_in_' . sanitize_key( $display_location );
	if ( ! isset( $settings[ $show_key ] ) || ! $settings[ $show_key ] ) {
		return $price;
	}

	$image = ccfw_get_currency_image( $currency );

	if ( ! $image ) {
		return $price;
	}

	$icon_html = ccfw_get_currency_icon_html( $currency );

	if ( empty( $icon_html ) ) {
		return $price;
	}

	$display_method = $settings['display_method'];

	// إزالة الرمز النصي القديم من السعر في الحالات التي لا نحتاج فيها للرمز
	if ( in_array( $display_method, array( 'image_only', 'image_before_price', 'image_after_price', 'image_with_name' ) ) ) {
		// إزالة الرمز المحاط بـ span (الوضع الافتراضي لووكوميرس)
		$price = preg_replace( '/<span class="woocommerce-Price-currencySymbol">.*?<\/span>/', '', $price );
		
		// إزالة الرمز النصي العادي في حال كان موجوداً خارج الـ span
		$raw_symbol = get_woocommerce_currency_symbol( $currency );
		if ( $raw_symbol ) {
			$price = str_replace( $raw_symbol, '', $price );
		}
		
		// إزالة المسافات الزائدة
		$price = str_replace( '&nbsp;', '', $price );
		$price = trim( $price );
	}

	// اختيار طريقة العرض
	switch ( $display_method ) {
		case 'image_only':
			return $icon_html . ' ' . $price;

		case 'image_before_price':
			return $icon_html . ' ' . $price;

		case 'image_after_price':
			return $price . ' ' . $icon_html;

		case 'symbol_only':
			// العودة للرمز الافتراضي فقط (مع الرمز القديم)
			return $price;

		case 'image_with_symbol':
			// سيتم التعامل معه في الفئات الأخرى
			return $icon_html . ' ' . $price;

		case 'image_with_name':
			$currency_name = get_woocommerce_currencies()[ $currency ] ?? $currency;
			return $icon_html . ' ' . $price . ' <span class="ccfw-currency-name">(' . esc_html( $currency_name ) . ')</span>';

		default:
			return $icon_html . ' ' . $price;
	}
}

/**
 * تنظيف بيانات الصور المرفوعة
 *
 * @param array $file بيانات الملف
 * @return array
 */
function ccfw_validate_image_file( $file ) {
	// الملحقات المسموحة
	$allowed_extensions = array( 'jpg', 'jpeg', 'png', 'webp' );

	// الأنواع المسموحة MIME
	$allowed_mimes = array(
		'jpg|jpeg' => 'image/jpeg',
		'png'      => 'image/png',
		'webp'     => 'image/webp',
	);

	if ( empty( $file['name'] ) ) {
		return array( 'error' => __( 'No file selected', CCFW_TEXT_DOMAIN ) );
	}

	// Check file size (max 2MB)
	if ( $file['size'] > 2 * 1024 * 1024 ) {
		return array( 'error' => __( 'File size exceeds 2MB limit', CCFW_TEXT_DOMAIN ) );
	}

	// Check extension
	$file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
	if ( ! in_array( $file_ext, $allowed_extensions, true ) ) {
		return array(
			'error' => sprintf(
				/* translators: %s: allowed extensions */
				__( 'Invalid file type. Allowed types: %s', CCFW_TEXT_DOMAIN ),
				implode( ', ', $allowed_extensions )
			),
		);
	}

	// Check MIME type using WordPress function
	$file_type = wp_check_filetype( $file['name'], $allowed_mimes );
	if ( ! $file_type['type'] ) {
		return array( 'error' => __( 'Invalid file MIME type', CCFW_TEXT_DOMAIN ) );
	}

	return array(
		'success' => true,
		'name'    => $file['name'],
		'tmp_name' => $file['tmp_name'],
		'size'    => $file['size'],
		'type'    => $file['type'],
	);
}

/**
 * تنسيق السعر مع أيقونة العملة لعرض مباشر
 *
 * @param float  $price السعر
 * @param string $currency رمز العملة
 * @return string HTML
 */
function ccfw_format_amount( $price = 0, $currency = '' ) {
	if ( empty( $currency ) ) {
		$currency = get_woocommerce_currency();
	}

	$price_html = wc_price( $price, array( 'currency' => $currency ) );

	return ccfw_format_price_with_icon( $price_html, $currency, 'product' );
}

/**
 * التحقق من أن المستخدم له صلاحيات الإدارة
 *
 * @return bool
 */
function ccfw_current_user_can_manage() {
	return current_user_can( 'manage_options' );
}
