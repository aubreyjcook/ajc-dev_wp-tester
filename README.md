# WP Initializer

Starter layout for a WordPress project. The plugin, the theme, and the must-use plugin are separate: copy one of them into a site, or treat this repository as the WordPress root and download core next to it. Nothing here has to be compiled. Activating the plugin or the theme is enough to start building.

WordPress core, `wp-config.php`, uploads, and bundled themes and plugins are gitignored. This repository tracks only the boilerplate.

Targets WordPress 6.7 through 7.1 and PHP 7.4 or newer. PHP 8.3 is the version WordPress currently recommends. There is no Node or Composer step.

## Layout

```
wp-content/
├── mu-plugins/
│   └── wp-initializer.php          Always loaded. Inert until you add code.
├── plugins/wp-initializer/         Functionality: settings, shortcode, block.
│   ├── wp-initializer.php          Bootstrap and version constant.
│   ├── uninstall.php               Deletes the option when the plugin is deleted.
│   ├── includes/                   PHP classes. Require new files from the bootstrap.
│   ├── blocks/message/             Dynamic block. No build step.
│   ├── assets/                     Admin script and shortcode CSS.
│   └── languages/                  Plugin translations (.po / .mo).
└── themes/wp-initializer-theme/    Presentation. Classic PHP templates.
    ├── style.css                   Theme header and front-end CSS.
    ├── theme.json                  Block editor layout settings.
    ├── functions.php               Supports, menus, sidebar, assets.
    ├── index.php                   Fallback template and blog index.
    ├── single.php, page.php, archive.php, search.php, 404.php
    ├── header.php, footer.php, sidebar.php, comments.php, searchform.php
    ├── template-parts/             Entry markup. content-{post-type}.php is chosen automatically.
    ├── page-templates/full-width.php
    └── languages/                  Theme translations.
```

The plugin does not require the theme. The theme does not require the plugin. The must-use file loads on its own and currently does nothing, so a site does not change until you activate the plugin or theme or write code in the must-use file.

## Use this repository as the WordPress root

From the repository root, with [WP-CLI](https://wp-cli.org/):

```bash
wp core download --skip-content
wp config create --dbname=wordpress --dbuser=root --dbpass=secret --dbhost=localhost
wp db create
wp core install --url=http://localhost --title="WP Initializer" --admin_user=admin --admin_password=secret --admin_email=admin@example.com
wp plugin activate wp-initializer
wp theme activate wp-initializer-theme
```

`--skip-content` leaves `wp-content` in place. Use a local database password. `wp-config.php` stays untracked.

Without WP-CLI, download [WordPress](https://wordpress.org/download/) and copy its files into this directory. Keep the existing `wp-content/plugins/wp-initializer`, `wp-content/themes/wp-initializer-theme`, and `wp-content/mu-plugins` directories. Copy `wp-config-sample.php` to `wp-config.php`, set the database values, then open the site and finish the installer. In wp-admin, activate **WP Initializer** under Plugins and **WP Initializer** under Appearance → Themes.

Useful local flags inside `wp-config.php`:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Debug output goes to `wp-content/debug.log`, which is gitignored.

## Drop the boilerplate into an existing site

Copy only the piece you need:

| Goal | Copy to the install |
| --- | --- |
| Plugin | `wp-content/plugins/wp-initializer` |
| Theme | `wp-content/themes/wp-initializer-theme` |
| Always-on PHP | `wp-content/mu-plugins/wp-initializer.php` |

Activate the plugin and theme from wp-admin. The must-use file loads as soon as it is in `mu-plugins`. Delete it if you do not want an always-on bootstrap.

If that site has its own git repo, copy the whitelist rules from this `.gitignore` so bundled plugins such as Akismet are not committed. When you add another plugin or theme directory, un-ignore it:

```
!/wp-content/plugins/your-plugin/
!/wp-content/themes/your-theme/
```

## What to edit

**Plugin behavior.** `includes/class-plugin.php` attaches the pieces. Add a class under `includes/`, require it from `wp-initializer.php`, and call it from `Plugin::hooks()`.

**Saved message.** Settings → Initializer stores one sentence in the `wp_initializer_settings` option. The same sentence is used by:

- the `[wp_initializer]` shortcode (`includes/class-shortcode.php`)
- the Initializer Message block
- the `wp_initializer_message` filter

`[wp_initializer message="Hello"]` overrides the saved sentence for that one use.

**Block.** `blocks/message/` is a dynamic block: `block.json` is the manifest, `editor.js` is the editor UI, and `render.php` prints the front end. Copy that folder to add another block and register the new directory from `Plugin::register_blocks()`. `editor.asset.php` lists the `wp.*` script handles `editor.js` uses. There is no build.

**Admin assets.** `assets/css/admin.css` and `assets/js/admin.js` load only on Settings → Initializer. `assets/css/public.css` loads when the shortcode renders.

**Theme templates.** `index.php` is the final fallback and the posts index. More specific files win: `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`. Entry HTML lives in `template-parts/`. `get_template_part( 'template-parts/content', get_post_type() )` loads `content-page.php` for pages and `content.php` for everything else. Add `content-{post-type}.php` or `single-{post-type}.php` for a custom type.

**Page template.** `page-templates/full-width.php` is selectable on a page under Template. It skips the sidebar. Do not add `templates/index.html`. WordPress treats a theme as a block theme when that file exists, and it will stop using these PHP templates.

**Menus and sidebar.** Appearance → Menus, assign a menu to Primary. Appearance → Widgets, add a widget to Sidebar. The sidebar column is hidden until that area has a widget.

**Front-end CSS and scripts.** Styles live in the theme's `style.css`. Enqueue more files from `wp_initializer_theme_assets()` in `functions.php`. A `screenshot.png` at 1200×900 in the theme root shows up on the Themes screen. The theme works without it.

**Must-use code.** Hooks that must run even when the plugin is inactive go in `wp-content/mu-plugins/wp-initializer.php`. WordPress does not load subdirectories of `mu-plugins` on its own. Require those files from the top-level PHP file.

**Translations.** Keep each text domain identical to its directory name (`wp-initializer`, `wp-initializer-theme`). Put `.po` and `.mo` files in that package's `languages/` directory. Strings are loaded on `init`, which is what WordPress 6.7 and newer expect.

## Hooks

| Hook | Type | When |
| --- | --- | --- |
| `wp_initializer_loaded` | action | After the plugin has registered its own hooks on `init`. |
| `wp_initializer_message` | filter | Plain-text message before the shortcode or block escapes it. Return a string. |

## Check that it works

1. Activate the plugin. Plugins should show a Settings link. Settings → Initializer should save a sentence.
2. Put `[wp_initializer]` in a post, or insert the Initializer Message block. View the post. The sentence should appear with a left border.
3. Activate the theme. The front end should show the site title, the posts loop, and a skip link. Assign the Primary menu and a sidebar widget and confirm both show up.
4. Edit a page and choose the Full width template. The sidebar should be gone on that page.

## Rename before you ship

Directories and text domains have to stay in sync. Replace longer tokens before shorter ones, or a pass on `wp-initializer` will also rewrite `wp-initializer-theme`.

| Order | Find | Meaning |
| --- | --- | --- |
| 1 | `WpInitializerTheme` | Theme package tag |
| 2 | `wp_initializer_theme` | Theme function prefix |
| 3 | `wp-initializer-theme` | Theme directory and text domain |
| 4 | `WP_INITIALIZER_` | Plugin constants |
| 5 | `WpInitializer` | Plugin namespace |
| 6 | `wp_initializer` | Plugin functions, options, shortcode, hooks |
| 7 | `wp-initializer` | Plugin directory, text domain, handles, block name |
| 8 | `WP Initializer` | Display names in the plugin and theme headers |

Rename the directories to match the new text domains. Bump `Version` in the plugin header and `WP_INITIALIZER_VERSION` together. Bump `Version` in the theme's `style.css`. Bump `Stable tag` and `Tested up to` in `readme.txt` when you actually run that WordPress version.

Search the tree after renaming. `wp_nav_menu()`, `wp_head()`, and the other `wp_*` calls are WordPress itself and should stay.

## Conventions

Follow the [WordPress coding standards](https://developer.wordpress.org/coding-standards/). This boilerplate already does the following:

- Every PHP file exits when `ABSPATH` is missing. `uninstall.php` checks `WP_UNINSTALL_PLUGIN` instead.
- Output is escaped (`esc_html`, `esc_attr`, `esc_url`). Input is sanitized (`sanitize_text_field`). The settings form uses the Settings API, which prints the nonce and checks `manage_options`.
- Names are prefixed, including option names, script handles, and CSS classes.
- The plugin, theme, and must-use plugin are released under GPL v2 or later.

Deactivation leaves the saved option in place. Deleting the plugin from the Plugins screen runs `uninstall.php` and removes `wp_initializer_settings` on each site in a multisite network.

## License

The project is released under the GNU General Public License, version 2 or (at your option) any later version. Those are the same terms as WordPress.

`LICENSE` states that grant and then includes the GPL version 2 text, which is the license document WordPress ships. Plugin, theme, and must-use headers say `GPL v2 or later`. The SPDX identifier on source files is `GPL-2.0-or-later`, the same grant. That is the license the WordPress.org plugin and theme directories require.

Replace the copyright line at the top of `LICENSE` if you ship this under your own name.
