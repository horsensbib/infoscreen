<?php
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'infoscreen_create_layout_metabox');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'infoscreen_layout_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function infoscreen_create_layout_metabox() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box(
		'infoscreen-page-layout',
		__( 'Layout', 'infoscreen_page_layout' ),
		'layout_metabox',
		'post',
		'normal',
		'high'
				);
	}
}

/* Prints the inner fields for the custom post/page section */
function layout_metabox() {
	$currentvalue = get_post_meta(get_the_ID(), '_infoscreen_layout', true);
	if($currentvalue == null){
		$currentvalue = "layout-img-right";
	}
	?>
<div>
<ul class="layout-controls">
	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-img" value="layout-img" <?php echo ($currentvalue == 'layout-img')? 'checked="checked"':''; ?> />
	<label for="layout-img">
		<?php _e('Image only','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/layout-img.png">
	</label>
	</li>

	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-img-left" value="layout-img-left" <?php echo ($currentvalue == 'layout-img-left')? 'checked="checked"':''; ?> />
	<label for="layout-img-left">
		<?php _e('Image left','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/layout-img-left.png">
	</label>
	</li>

	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-img-right" value="layout-img-right" <?php echo ($currentvalue == 'layout-img-right')? 'checked="checked"':''; ?> />
	<label for="layout-img-right">
		<?php _e('Image right','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/Layout-img-right.png">
	</label>
	</li>

	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-txt" value="layout-txt" <?php echo ($currentvalue == 'layout-txt')? 'checked="checked"':''; ?> />
	<label for="layout-txt">
		<?php _e('Text only','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/layout-txt.png">
	</label>
	</li>

	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-img-top" value="layout-img-top" <?php echo ($currentvalue == 'layout-img-top')? 'checked="checked"':''; ?> />
	<label for="layout-img-top">
		<?php _e('Text bottom','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/layout-img-top.png">
	</label>
	</li>

	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-img-bottom" value="layout-img-bottom" <?php echo ($currentvalue == 'layout-img-bottom')? 'checked="checked"':''; ?> />
	<label for="layout-img-bottom">
		<?php _e('Text top','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/layout-img-bottom.png">
	</label>
	</li>

	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-txt-left" value="layout-txt-left" <?php echo ($currentvalue == 'layout-txt-left')? 'checked="checked"':''; ?> />
	<label for="layout-txt-left">
		<?php _e('Text left','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/layout-txt-left.png">
	</label>
	</li>

	<li class="layout-selector">
	<input type="radio" name="_infoscreen_layout" id="layout-txt-right" value="layout-txt-right" <?php echo ($currentvalue == 'layout-txt-right')? 'checked="checked"':''; ?> />
	<label for="layout-txt-right">
		<?php _e('Text right','infoscreen') ?>
		<img src="<?php echo get_template_directory_uri(); ?>/img/thumbnails/layout-txt-right.png">
	</label>
	</li>
</ul>

	<input type="hidden" name="infoscreen_layout_noncename"
		id="infoscreen_layout_noncename"
		value="<?php wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
</div>
<?php
}

/* When the post is saved, saves our custom data */
function infoscreen_layout_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !current_user_can( 'edit_post', $post_id ))
  	return $post_id;

  // OK, we're authenticated: we need to find and save the data
  $data = $_POST['_infoscreen_layout'];
  if(get_post_meta($post_id, '_infoscreen_layout') == '')
  	add_post_meta($post_id, '_infoscreen_layout', $data, true);

  elseif($data != get_post_meta($post_id, '_infoscreen_layout', true))
  update_post_meta($post_id, '_infoscreen_layout', $data);

  elseif($data == '')
  // 		delete_post_meta($post_id, '_infoscreen_layout', get_post_meta($post_id, '_infoscreen_layout', true));

  return $data;
}

/* Prints the output to the front end of the website */
function layout() {
	// If it exists, display content of custom fields.
	$display_layout = get_post_custom_values('_infoscreen_layout');
	$counter = 1;
	$queryArgs = array(
		'cat' => $display_layout[0],
		'posts_per_page' => 10,
		'orderby' => 'date',
		'order' => 'DESC'
	);
	if ($display_layout > 0) {
	?>
<ul class="article-list">
	<?php
	query_posts($queryArgs);
	if (have_posts()) : while (have_posts()) : the_post();

	if(in_category($display_layout)) { // Only display posts from the selected category. We don't want the sub-categories.
	?>

	<li id="article-<?php the_ID(); ?>"
	<?php post_class('item-' . $counter); ?>><a class="thumbnail"
		href="<?php the_permalink() ?>"><?php
		if ( has_post_thumbnail() ) {	// Check if there is a manually chosen thumbnail
		the_post_thumbnail('category-post-thumbnail');
	} else { 	// Else, display a default image
	?><img class="attachment-category-post-thumbnail wp-post-image"
			src="<?php bloginfo('stylesheet_directory'); ?>/graphics/horsens-bibliotek-230x90.jpg" />
			<?php }?> </a> <header>
			<h2 class="entry-title">
				<a href="<?php the_permalink() ?>" rel="bookmark"
					title="<?php the_title_attribute(); ?>"><?php the_title(); ?> </a>
			</h2>
		</header> <!-- /article-header -->
		<p>
			<?php
			$excerpt = get_the_excerpt();
			echo string_limit_words($excerpt,12); // Set the number of words to display in the excerpt.
			?>
			...
		</p> <?php /* <footer>	
			<time class="publication-date" datetime="<?php the_time('c'); ?>"><?php the_time('j. M Y'); ?></time>
			<span class="comments"><?php comments_popup_link('(0)', '(1)', '(%)'); ?></span>
			<span class="more"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php _e('L&aelig;s mere', 'infoscreen'); ?></a></span>
			<?php edit_post_link(__('Rediger', ''), '<span class="edit">', '</span> '); ?>
			</footer> */ ?></li>
	<!--/article-<?php the_ID(); ?>-->

	<?php }
	endwhile; endif;
	wp_reset_query();  // Restore global post data stomped by the_post(). ?>
</ul>
<?php
	}		
}
?>