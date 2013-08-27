<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package InfoScreen
 * @since InfoScreen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>

			<div class="widget">
				<h2 class="widgettitle"><?php _e( 'Display Locations', 'infoscreen' ); ?></h2>
				<ul>
				<?php wp_list_categories( array( 'orderby' => 'name', 'order' => 'ASC', 'show_count' => 1, 'title_li' => '<a href="' . esc_url( home_url( '/' )) . '">' . __('All slides','infoscreen') . '</a>' ) ); ?>
				</ul>
			</div><!-- .widget -->

			<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_footer(); ?>