<?php 
add_action('wp_head', 'dynamic_stylesheet');

function dynamic_stylesheet(){
	$options = get_option('infoscreen_theme_options');
	
	if ($_SERVER['SERVER_NAME'] == 'horsensbibliotek.dk' || 'sandbox.wp') {
		$title_font = 'Hermes';
		$body_font = 'Helvetica, Arial';
	} else {
		$title_font = $options['title_font-family'];
		$body_font = $options['body_font-family'];
	}
	?>
<style>
	<?php
	if ($_SERVER['SERVER_NAME'] == 'horsensbibliotek.dk' || 'sandbox.wp') {
	?>
@font-face {
	font-family: 'Hermes';
	font-weight: normal;
	font-style: normal;
	src: url('http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/themes/bibliozine-2/fonts/hermes-webfont.eot');
	src: url('http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/themes/bibliozine-2/fonts/hermes-webfont.eot?#iefix') format('embedded-opentype'),
	     url('http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/themes/bibliozine-2/fonts/hermes-webfont.woff') format('woff'),
	     url('http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/themes/bibliozine-2/fonts/hermes-webfont.ttf') format('truetype'),
       url('http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/themes/bibliozine-2/fonts/hermes-webfont.svg#HermesRegular') format('svg');
}
<?php
	}
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
