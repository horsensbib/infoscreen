<?php
/**
 * @package InfoScreen
 * @since InfoScreen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="background-image:url('<?php infoscreen_img_src('slide-img'); ?>');">
	<div class="slide-content">
		<header class="entry-header">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'infoscreen' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

			<?php if ( 'post' == get_post_type() ) : ?>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'infoscreen' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'infoscreen' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
	</div><!-- .slide-content -->

	<?php edit_post_link( __( 'Edit', 'infoscreen' ), '<span class="edit-link">', '</span>' ); ?>
</article><!-- #post-## -->
