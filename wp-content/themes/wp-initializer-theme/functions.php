<?php
/**
 * Theme setup.
 *
 * @package WpInitializerTheme
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register supports that do not need translated strings.
 */
function wp_initializer_theme_setup() {
	global $content_width;

	if ( ! isset( $content_width ) ) {
		$content_width = 720;
	}

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);
}
add_action( 'after_setup_theme', 'wp_initializer_theme_setup' );

/**
 * Load translations on init.
 *
 * WordPress 6.7 and newer warn if a text domain is loaded earlier than init.
 */
function wp_initializer_theme_load_textdomain() {
	load_theme_textdomain( 'wp-initializer-theme', get_template_directory() . '/languages' );
}
add_action( 'init', 'wp_initializer_theme_load_textdomain', 0 );

/**
 * Register navigation menus.
 */
function wp_initializer_theme_menus() {
	register_nav_menus(
		array(
			'primary' => __( 'Primary', 'wp-initializer-theme' ),
		)
	);
}
add_action( 'init', 'wp_initializer_theme_menus' );

/**
 * Register widget areas.
 */
function wp_initializer_theme_sidebars() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'wp-initializer-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Shown beside the main content when this area has widgets.', 'wp-initializer-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'wp_initializer_theme_sidebars' );

/**
 * Enqueue the theme stylesheet.
 *
 * Add front-end scripts here. Use get_template_directory_uri() for files in
 * this theme, and get_stylesheet_directory_uri() when a child theme should
 * be able to replace them.
 */
function wp_initializer_theme_assets() {
	wp_enqueue_style(
		'wp-initializer-theme',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'wp_initializer_theme_assets' );
