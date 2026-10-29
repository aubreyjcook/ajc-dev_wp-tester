<?php
/**
 * Activation tasks.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

namespace WpInitializer;

/**
 * Runs when the plugin is activated.
 */
class Activator {
	/**
	 * Store the default settings row if it does not exist yet.
	 *
	 * Deactivation intentionally leaves the option in place.
	 */
	public static function activate() {
		if ( false === get_option( 'wp_initializer_settings' ) ) {
			add_option(
				'wp_initializer_settings',
				array(
					'message' => '',
				)
			);
		}
	}
}
