<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package InfoScreen
 * @since InfoScreen 1.0
 */
?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'infoscreen_credits' ); ?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'infoscreen' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'infoscreen' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'infoscreen' ), 'InfoScreen', '<a href="http://horsensbibliotek.dk/" rel="designer">Horsens Public Library</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>