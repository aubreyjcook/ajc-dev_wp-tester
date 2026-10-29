<?php
/**
 * [wp_initializer] shortcode.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

namespace WpInitializer;

/**
 * Renders the initializer message from a shortcode.
 */
class Shortcode {
	/**
	 * Hook registration.
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register the shortcode.
	 */
	public function register() {
		add_shortcode( 'wp_initializer', array( $this, 'render' ) );
	}

	/**
	 * Print the saved message, or a message passed as an attribute.
	 *
	 * Attribute values are untrusted post content, so they are sanitized
	 * before they are escaped on output.
	 *
	 * @param mixed $atts Shortcode attributes. WordPress passes an empty string when none are set.
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'message' => '',
			),
			$atts,
			'wp_initializer'
		);

		if ( '' !== $atts['message'] ) {
			$message = sanitize_text_field( $atts['message'] );
		} else {
			$message = Settings::get_message();
		}

		wp_enqueue_style( 'wp-initializer-public' );

		return '<p class="wp-initializer">' . esc_html( $message ) . '</p>';
	}
}
