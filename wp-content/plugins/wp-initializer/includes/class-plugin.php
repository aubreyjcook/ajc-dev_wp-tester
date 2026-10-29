<?php
/**
 * Plugin hook registration.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

namespace WpInitializer;

/**
 * Connects the plugin's features to WordPress.
 */
class Plugin {
	/**
	 * Register hooks. Safe to call once, from the bootstrap.
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ), 0 );
		add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'init', array( $this, 'loaded' ), 20 );
		add_filter( 'plugin_action_links_' . plugin_basename( WP_INITIALIZER_FILE ), array( $this, 'action_links' ) );

		( new Settings() )->hooks();
		( new Shortcode() )->hooks();
		( new Assets() )->hooks();
	}

	/**
	 * Load translations on init. WordPress 6.7 warns if this happens earlier.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'wp-initializer',
			false,
			dirname( plugin_basename( WP_INITIALIZER_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register dynamic blocks shipped with the plugin.
	 */
	public function register_blocks() {
		register_block_type( WP_INITIALIZER_DIR . 'blocks/message' );

		wp_set_script_translations(
			'wp-initializer-message-editor-script',
			'wp-initializer',
			WP_INITIALIZER_DIR . 'languages'
		);
	}

	/**
	 * Signal that the plugin is ready for other code to extend.
	 */
	public function loaded() {
		do_action( 'wp_initializer_loaded' );
	}

	/**
	 * Link to the settings screen from the Plugins list.
	 *
	 * @param array<int, string> $links Existing action links.
	 * @return array<int, string>
	 */
	public function action_links( $links ) {
		if ( ! is_array( $links ) ) {
			$links = array();
		}

		if ( current_user_can( 'manage_options' ) ) {
			$url           = admin_url( 'options-general.php?page=wp-initializer' );
			$settings_link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'wp-initializer' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}
}
