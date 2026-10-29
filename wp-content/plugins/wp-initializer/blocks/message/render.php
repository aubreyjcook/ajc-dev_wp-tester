<?php
/**
 * Server render for the wp-initializer/message block.
 *
 * @package WpInitializer
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$message = \WpInitializer\Settings::get_message();

printf(
	'<p %1$s>%2$s</p>',
	get_block_wrapper_attributes(
		array(
			'class' => 'wp-initializer-message',
		)
	),
	esc_html( $message )
);
