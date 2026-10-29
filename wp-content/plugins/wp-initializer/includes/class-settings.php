<?php
/**
 * Settings screen under Settings → Initializer.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

namespace WpInitializer;

/**
 * Registers the option, settings UI, and the shared message lookup.
 */
class Settings {
	/**
	 * Hook the settings screen.
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register' ) );
	}

	/**
	 * Add the settings page.
	 */
	public function add_menu() {
		add_options_page(
			__( 'WP Initializer', 'wp-initializer' ),
			__( 'Initializer', 'wp-initializer' ),
			'manage_options',
			'wp-initializer',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register the option and its fields.
	 */
	public function register() {
		register_setting(
			'wp_initializer',
			'wp_initializer_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize' ),
				'default'           => array(
					'message' => '',
				),
				'show_in_rest'      => false,
			)
		);

		add_settings_section(
			'wp_initializer_main',
			__( 'Message', 'wp-initializer' ),
			array( $this, 'render_section' ),
			'wp-initializer'
		);

		add_settings_field(
			'wp_initializer_message',
			__( 'Message', 'wp-initializer' ),
			array( $this, 'render_message_field' ),
			'wp-initializer',
			'wp_initializer_main',
			array(
				'label_for' => 'wp-initializer-message',
			)
		);
	}

	/**
	 * Keep only the known keys, as plain text.
	 *
	 * @param mixed $value Submitted option value.
	 * @return array{message: string}
	 */
	public function sanitize( $value ) {
		if ( ! is_array( $value ) ) {
			$value = array();
		}

		return array(
			'message' => isset( $value['message'] ) ? sanitize_text_field( $value['message'] ) : '',
		);
	}

	/**
	 * Section help text.
	 */
	public function render_section() {
		echo '<p>' . esc_html__( 'Shown by the [wp_initializer] shortcode and the Initializer Message block. Leave it blank to use the default sentence.', 'wp-initializer' ) . '</p>';
	}

	/**
	 * Message field.
	 */
	public function render_message_field() {
		$settings = get_option( 'wp_initializer_settings', array() );
		$message  = ( is_array( $settings ) && isset( $settings['message'] ) ) ? (string) $settings['message'] : '';
		?>
		<input
			type="text"
			class="regular-text"
			id="wp-initializer-message"
			name="wp_initializer_settings[message]"
			value="<?php echo esc_attr( $message ); ?>"
		/>
		<?php
	}

	/**
	 * Settings page markup. The Settings API prints the nonce.
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap wp-initializer-settings">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'wp_initializer' );
				do_settings_sections( 'wp-initializer' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Message saved in settings, or the translated default.
	 *
	 * Filters the plain-text message through `wp_initializer_message`.
	 */
	public static function get_message() {
		$settings = get_option( 'wp_initializer_settings', array() );
		$message  = '';

		if ( is_array( $settings ) && isset( $settings['message'] ) ) {
			$message = (string) $settings['message'];
		}

		if ( '' === $message ) {
			$message = __( 'Hello from WP Initializer.', 'wp-initializer' );
		}

		$message = apply_filters( 'wp_initializer_message', $message );

		return is_string( $message ) ? $message : '';
	}
}
