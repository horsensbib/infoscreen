<?php
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'infoscreen_create_time_metabox');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'infoscreen_time_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function infoscreen_create_time_metabox() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box(
		'infoscreen-page-time',
		__( 'Time', 'infoscreen_page_time' ),
		'time_metabox',
		'post',
		'normal',
		'high'
				);
	}
}
function time_metabox() {
	$currentvalue = get_post_meta(get_the_ID(), '_infoscreen_time', true);
	if($currentvalue == null){
		$currentvalue = 5;
	}
	echo "<label>Slide is shown for</label>";
	echo "<input name='_infoscreen_time' style='width: 4em' type='number' value='";
	echo ($currentvalue == '5')? '5': $currentvalue;
	echo"'/>";
	echo "<label>seconds</label>";
	?>
<input
	type="hidden" name="infoscreen_time_noncename"
	id="infoscreen_time_noncename"
	value="<?php wp_create_nonce( plugin_basename(__FILE__) ); ?>" />

<?php 
}

/* When the post is saved, saves our custom data */
function infoscreen_time_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// OK, we're authenticated: we need to find and save the data
	$data = $_POST['_infoscreen_time'];
	if(get_post_meta($post_id, '_infoscreen_time') == '')
		add_post_meta($post_id, '_infoscreen_time', $data, true);

	elseif($data != get_post_meta($post_id, '_infoscreen_time', true))
	update_post_meta($post_id, '_infoscreen_time', $data);

	elseif($data == '')
	// 		delete_post_meta($post_id, '_infoscreen_layout', get_post_meta($post_id, '_infoscreen_layout', true));

	return $data;
}