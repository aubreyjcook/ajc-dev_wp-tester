<?php
/**
 * Script dependencies for blocks/message/editor.js.
 *
 * WordPress reads this file when block.json points editorScript at editor.js.
 * There is no build step. Add a handle here for every `wp.*` package the
 * editor script uses.
 *
 * @package WpInitializer
 */

return array(
	'dependencies' => array(
		'wp-blocks',
		'wp-element',
		'wp-block-editor',
		'wp-server-side-render',
		'wp-i18n',
	),
	'version'      => defined( 'WP_INITIALIZER_VERSION' ) ? WP_INITIALIZER_VERSION : '0.1.0',
);
