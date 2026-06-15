<?php
/**
 * Uninstall Plugin
 * تنظيف البيانات عند حذف البلاجن
 *
 * @package CCFW
 */

// Prevent direct access
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin settings
delete_option( 'ccfw_settings' );

// Delete any currency images (attachments)
$args = array(
	'post_type'      => 'attachment',
	'posts_per_page' => -1,
	'meta_query'     => array(
		array(
			'key'     => '_ccfw_currency_image',
			'compare' => 'EXISTS',
		),
	),
);

$attachments = get_posts( $args );

if ( ! empty( $attachments ) ) {
	foreach ( $attachments as $attachment ) {
		wp_delete_attachment( $attachment->ID, true );
	}
}

// Flush rewrite rules
flush_rewrite_rules();
