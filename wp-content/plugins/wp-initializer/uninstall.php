<?php
/**
 * Removes plugin data when the plugin is deleted from the admin screen.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete this plugin's options on one site.
 */
function wp_initializer_delete_site_options() {
	delete_option( 'wp_initializer_settings' );
}

if ( is_multisite() ) {
	$wp_initializer_offset = 0;

	do {
		$wp_initializer_site_ids = get_sites(
			array(
				'fields' => 'ids',
				'number' => 100,
				'offset' => $wp_initializer_offset,
			)
		);

		foreach ( $wp_initializer_site_ids as $wp_initializer_site_id ) {
			switch_to_blog( (int) $wp_initializer_site_id );
			wp_initializer_delete_site_options();
			restore_current_blog();
		}

		$wp_initializer_offset += 100;
	} while ( 100 === count( $wp_initializer_site_ids ) );
} else {
	wp_initializer_delete_site_options();
}
