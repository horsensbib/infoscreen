<?php 
add_action('wp_head', 'dynamic_stylesheet');

function dynamic_stylesheet(){
	$options = get_option('infoscreen_theme_options');
	
	$title_font = $options['title_font-family'];
	$body_font = $options['body_font-family'];
	?>
<style>
	<?php
	if ($title_font != $body_font){
		if (strpos($title_font,'google') !== false) {
			echo "@import url('http://fonts.googleapis.com/css?family=". str_replace("google ", "", $title_font) ."');";
		}
	}
	if (strpos($body_font,'google') !== false) {
		echo "@import url('http://fonts.googleapis.com/css?family=". str_replace("google ", "", $body_font) ."');";
	}
	?>
/*
 * Fonts
 * ===================== */
h1,
h2,
h3,
h4,
h5,
h6 {
	font-family: <?php echo str_replace("google ", "", $title_font); ?>;
}
p,
li {
	font-family: <?php echo str_replace("google ", "", $body_font); ?>;
}
b,
strong {
	font-weight: <?php ?>;
}
 </style>
<?php 
}
?>
