<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package InfoScreen
 * @since InfoScreen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'infoscreen' ); ?></h1>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'infoscreen' ); ?></p>

					<div class="widget">
						<h2 class="widgettitle"><?php _e( 'Display Locations', 'infoscreen' ); ?></h2>
						<ul>
						<?php wp_list_categories( array( 'orderby' => 'name', 'order' => 'ASC', 'show_count' => 1, 'title_li' => '<a href="' . esc_url( home_url( '/' )) . '">' . __('All slides','infoscreen') . '</a>' ) ); ?>
						</ul>
					</div><!-- .widget -->

					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

				</div><!-- .entry-content -->
			</article><!-- #post-0 .post .error404 .not-found -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>