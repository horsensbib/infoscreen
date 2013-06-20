<?php
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'infoscreen_create_animation_metabox');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'infoscreen_animation_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function infoscreen_create_animation_metabox() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box(
		'infoscreen-page-animation',
		__( 'Animation', 'infoscreen_page_animation' ),
		'animation_metabox',
		'post',
		'normal',
		'high'
				);
	}
}
function animation_metabox() {
	$currentvalue = get_post_meta(get_the_ID(), '_infoscreen_animation', true);
	echo "<fieldset>";
	echo "<input type='radio' name='_infoscreen_animation' value='left_slide_in'";
	echo ($currentvalue == 'left_slide_in')? 'checked="checked"':'';
	echo "/>Slide in from left<br />";
	
	echo "<input type='radio' name='_infoscreen_animation' value='right_slide_in'";
	echo ($currentvalue == 'right_slide_in')? 'checked="checked"':'';
	echo "/>Slide in from right<br />";
	
	echo "<input type='radio' name='_infoscreen_animation' value='desolve'";
	echo ($currentvalue == 'desolve')? 'checked="checked"':'';
	echo "/>Desolve<br />";	
	?>
<input
	type="hidden" name="infoscreen_animation_noncename"
	id="infoscreen_animation_noncename"
	value="<?php wp_create_nonce( plugin_basename(__FILE__) ); ?>" />

<?php 
}

/* When the post is saved, saves our custom data */
function infoscreen_animation_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other animations
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// OK, we're authenticated: we need to find and save the data
	$data = $_POST['_infoscreen_animation'];
	if(get_post_meta($post_id, '_infoscreen_animation') == '')
		add_post_meta($post_id, '_infoscreen_animation', $data, true);

	elseif($data != get_post_meta($post_id, '_infoscreen_animation', true))
	update_post_meta($post_id, '_infoscreen_animation', $data);

	elseif($data == '')
	// 		delete_post_meta($post_id, '_infoscreen_layout', get_post_meta($post_id, '_infoscreen_layout', true));

	return $data;
}