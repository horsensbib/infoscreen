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
	if($currentvalue == null){
		$currentvalue = "fade";
	}
	echo "<fieldset>";
	echo "<input type='radio' name='_infoscreen_animation' value='slide'";
	echo ($currentvalue == 'slide')? 'checked="checked"':'';
	echo "/>Slide<br />";
	
	echo "<input type='radio' name='_infoscreen_animation' value='fade'";
	echo ($currentvalue == 'fade')? 'checked="checked"':'';
	echo "/>Fade<br />";
	
	?>
<input
	type="hidden" name="infoscreen_animation_noncename"
	id="infoscreen_animation_noncename"
	value="<?php wp_create_nonce( plugin_basename(__FILE__) ); ?>" />

<?php 
}

/* When the post is saved, saves our custom data */
function infoscreen_animation_save_postdata( $post_id ) {
	global $temp_post;
	$cat_ID = wp_get_post_categories($post_id);
	$args = array( 'category' => $cat_ID[0]);
	$myposts = get_posts($args);
	$data = $_POST['_infoscreen_animation'];
	foreach( $myposts as $temp_post ) : setup_postdata($temp_post);
		$temp_post_cat = wp_get_post_categories($temp_post->ID);
		if(in_array($cat_ID[0], $temp_post_cat)){
			$temp_post_id = $temp_post->ID;
			update_post_meta($temp_post_id, '_infoscreen_animation', $data);
		}
	endforeach;
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other animations
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// OK, we're authenticated: we need to find and save the data
	
	if(get_post_meta($post_id, '_infoscreen_animation') == '')
		add_post_meta($post_id, '_infoscreen_animation', $data, true);

	elseif($data != get_post_meta($post_id, '_infoscreen_animation', true))
	update_post_meta($post_id, '_infoscreen_animation', $data);

	elseif($data == '')
	// 		delete_post_meta($post_id, '_infoscreen_layout', get_post_meta($post_id, '_infoscreen_layout', true));

	return $data;
}