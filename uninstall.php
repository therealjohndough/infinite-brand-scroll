<?php
/**
 * Uninstall Script
 *
 * Fired when the plugin is uninstalled.
 *
 * @package InfiniteBrandScroll
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options.
delete_option( 'infinite_brand_scroll_options' );

// For multisite installations.
if ( is_multisite() ) {
	global $wpdb;

	// Get all blog IDs.
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		delete_option( 'infinite_brand_scroll_options' );
		restore_current_blog();
	}
}

// Clear any cached data.
wp_cache_flush();
