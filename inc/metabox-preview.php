<?php
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'infoscreen_create_preview_metabox');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'infoscreen_preview_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function infoscreen_create_preview_metabox() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box(
		'infoscreen-page-preview',
		__( 'Preview', 'infoscreen_page_preview' ),
		'preview_metabox',
		'post',
		'side',
		'high'
		);
	}
}
function wp_exist_post($id) {
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM wp_posts WHERE id = '" . $id . "'", 'ARRAY_A');
}
function preview_metabox() {
	
	$frame_src = get_permalink();
	if(get_post_meta(get_the_ID(), '_infoscreen_colorscheme')){
	?>
		<iframe id="slide-preview-id"class="slide-preview" src="<?php echo $frame_src; ?>" frameborder="0" onload="iframe_loaded()"></iframe>
	<?php } else { ?>
		<p><?php _e('Please save the slide to see a preview','infoscreen'); ?></p>
	<?php }	?>
<input
	type="hidden" name="infoscreen_preview_noncename"
	id="infoscreen_preview_noncename"
	value="<?php wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
<script>
function iframe_loaded() {
	jQuery('#slide-preview-id').contents().find('#wpadminbar').remove();
	jQuery('#slide-preview-id').contents().find('html').css('position','relative');
	jQuery('#slide-preview-id').contents().find('html').css('top','-28px');
}
</script>
<?php 
}

/* When the post is saved, saves our custom data */
function infoscreen_preview_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other previews
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// OK, we're authenticated: we need to find and save the data
	$data = $_POST['_infoscreen_preview'];
	if(get_post_meta($post_id, '_infoscreen_preview') == '')
		add_post_meta($post_id, '_infoscreen_preview', $data, true);

	elseif($data != get_post_meta($post_id, '_infoscreen_preview', true))
	update_post_meta($post_id, '_infoscreen_preview', $data);

	elseif($data == '')
	// 		delete_post_meta($post_id, '_infoscreen_layout', get_post_meta($post_id, '_infoscreen_layout', true));

	return $data;
}