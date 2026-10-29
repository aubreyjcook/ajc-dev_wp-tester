<?php
/**
 * Site footer.
 *
 * Closes `.site-main` and `.site`, opened in header.php.
 *
 * @package WpInitializerTheme
 * @license GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</div>
	<footer class="site-footer">
		<p>
			<?php
			printf(
				/* translators: 1: current year, 2: site name. */
				esc_html__( '© %1$s %2$s', 'wp-initializer-theme' ),
				esc_html( gmdate( 'Y' ) ),
				esc_html( get_bloginfo( 'name' ) )
			);
			?>
		</p>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
