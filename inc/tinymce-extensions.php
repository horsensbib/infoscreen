<?php
/*
 * Add HR to TinyMCE
 * ------------------------------- */
function enable_more_buttons($buttons) {
	$buttons[] = 'hr'; 
	return $buttons;
}
add_filter("mce_buttons", "enable_more_buttons");

add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

/**
 * Add the Style selectbox to the second row of MCE buttons
 * Method: http://alisothegeek.com/2011/05/tinymce-styles-dropdown-wordpress-visual-editor/
 * ------------------------------- */
function my_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
add_filter( 'tiny_mce_before_init', 'my_mce_before_init' );

function my_mce_before_init( $settings ) {
	$style_formats = array(
		array(
			'title' => 'Lille skrift',
			'block' => 'small',
			// 'classes' => 'faktaboks alignleft',
			'wrapper' => true
		)
	);
	$settings['style_formats'] = json_encode( $style_formats );
	return $settings;
}

?>