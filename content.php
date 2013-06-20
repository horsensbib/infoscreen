<?php
/**
 * @package InfoScreen
 * @since InfoScreen 1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('slide'); ?> style="background-image:url('<?php infoscreen_img_src('slide-img'); ?>');">
	<div
		class="slide-content <?php 
	$options = get_option('infoscreen_theme_options');
	for ($i = 0; $i < $options['colorschemes']; $i += 2){
		if($options['colorscheme_name'.$i] == get_post_meta(get_the_ID(), '_infoscreen_colorscheme', true)){
			$backgroundID = $i + 1;
			$font = hex2rgb($options['colorscheme'.$i]);
			$bg = hex2rgb($options['colorscheme'.$backgroundID]);
		}
	}
		echo get_post_meta(get_the_ID(), '_infoscreen_layout', true). '"';
		$transparency_value = get_post_meta(get_the_ID(), '_infoscreen_transparency', true)/100;
		echo " style='color: rgb($font); background: rgba($bg,".$transparency_value.")"; ?>
		'>
		<header 
		
		class="entry-header">
		<h1 class="entry-title">
			<?php the_title(); ?>
		</h1>
		<?php if ( 'post' == get_post_type() ) : ?>
		<?php endif; ?>
		</header>
		<!-- .entry-header -->
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'infoscreen' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'infoscreen' ), 'after' => '</div>' ) ); ?>
		</div>
		<!-- .entry-content -->
	</div>
	<!-- .slide-content -->
	<?php //edit_post_link( __( 'Edit', 'infoscreen' ), '<span class="edit-link">', '</span>' ); ?>
</article>
<!-- #post-## -->
