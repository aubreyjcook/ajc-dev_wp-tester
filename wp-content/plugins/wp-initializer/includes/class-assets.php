<?php
/**
 * Styles and scripts.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

namespace WpInitializer;

/**
 * Registers assets and enqueues the admin files on the settings screen.
 */
class Assets {
	/**
	 * Hook asset registration.
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'register_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
	}

	/**
	 * Register the front-end stylesheet. The shortcode enqueues it.
	 */
	public function register_styles() {
		wp_register_style(
			'wp-initializer-public',
			WP_INITIALIZER_URL . 'assets/css/public.css',
			array(),
			WP_INITIALIZER_VERSION
		);
	}

	/**
	 * Enqueue admin assets only on Settings → Initializer.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_admin( $hook_suffix ) {
		if ( 'settings_page_wp-initializer' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'wp-initializer-admin',
			WP_INITIALIZER_URL . 'assets/css/admin.css',
			array(),
			WP_INITIALIZER_VERSION
		);

		wp_enqueue_script(
			'wp-initializer-admin',
			WP_INITIALIZER_URL . 'assets/js/admin.js',
			array(),
			WP_INITIALIZER_VERSION,
			true
		);
	}
}
