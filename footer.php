<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package InfoScreen
 * @since InfoScreen 1.0
 */
global $post;
global $time;
$category_slug = explode("?", $_SERVER['REQUEST_URI']);
$args = array( 'category' => $category_slug[1]);
$myposts = get_posts( $args );
foreach( $myposts as $post ) : setup_postdata($post); ?>
<?php $time += get_post_meta(get_the_ID(), '_infoscreen_time', true) * 1000; ?>
<script>
	$('#post-<?php echo get_the_ID(); ?>').delay(<?php echo $time; ?>).ready(
		function() {$('#post-<?php echo get_the_ID(); ?>').
			animate({
				opacity: 0,
				left: 'toggle',
				height: 'toggle'}, 
				3000, 
				function(){ 
					setTimeout(
						function() { $('#post-<?php echo get_the_ID(); ?>').css('display',''); $('#post-<?php echo get_the_ID(); ?>').css('opacity',''); }, 
						<?php echo $time_print = $time - (get_post_meta(get_the_ID(), '_infoscreen_time', true) * 1000);?>
				)})})
</script>

<?php endforeach; ?>

<header id="masthead" class="site-header" role="banner">
	<hgroup>
		<h1 class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"
				rel="home"><?php bloginfo( 'name' ); ?> </a>
		</h1>
		<h2 class="site-description">
			<?php bloginfo( 'description' ); ?>
		</h2>
	</hgroup>
</header>
<!-- #masthead -->
<footer id="colophon" class="site-footer"
	role="contentinfo">
	<div class="site-info">
		<?php do_action( 'infoscreen_credits' ); ?>
		<a href="http://wordpress.org/"
			title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'infoscreen' ); ?>"
			rel="generator"><?php printf( __( 'Proudly powered by %s', 'infoscreen' ), 'WordPress' ); ?>
		</a> <span class="sep"> | </span>
		<?php printf( __( 'Theme: %1$s by %2$s.', 'infoscreen' ), 'InfoScreen', '<a href="http://horsensbibliotek.dk/" rel="designer">Horsens Public Library</a>' ); ?>
	</div>
	<!-- .site-info -->
</footer>
<!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>
