=== WP Initializer ===
Contributors: yourname
Requires at least: 6.7
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Starter plugin for a new WordPress project.

== Description ==

Activate the plugin, then set a sentence under Settings → Initializer.

That sentence is available as:

* the `[wp_initializer]` shortcode
* the Initializer Message block
* the `wp_initializer_message` filter

An optional shortcode attribute overrides the saved sentence:

`[wp_initializer message="Hello"]`

== Installation ==

1. Copy this folder to `wp-content/plugins/wp-initializer`.
2. Activate WP Initializer from the Plugins screen.

== Frequently Asked Questions ==

= Does this require the starter theme? =

No. The plugin, the theme, and the must-use plugin work on their own.

== Changelog ==

= 0.1.0 =
* Initial starter.
