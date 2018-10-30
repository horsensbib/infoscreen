<?php 
add_action('wp_head', 'dynamic_stylesheet');

function dynamic_stylesheet() {

	$options = get_option('infoscreen_theme_options');
	
	$getTitleFont = $options['title_font-family'];
	$getTitleBold = $options['title_font-weight'];
	$getTitleItalic = $options['title_font-style'];
	$titleFont = str_replace("google ", "", $getTitleFont);
	$titleBold = ($getTitleBold != '' ? $getTitleBold . ',' : '');
	$titleItalic = ($getTitleItalic == true ? '400i,' : '');
	$titleBoldItalic = ($getTitleBold != '' ? $getTitleBold . 'i' : '');
	$titleBoldCss = ($getTitleBold != '' ? $getTitleBold : 'normal');
	$titleItalicCss = ($getTitleItalic == true ? 'italic' : 'normal');
	
	$getBodyFont = $options['body_font-family'];
	$getBodyBold = $options['body_font-weight'];
	$bodyFont = str_replace("google ", "", $getBodyFont);
	$bodyBold = ($getBodyBold != '' ? $getBodyBold . ',' : '');
	$bodyBoldItalic = ($getBodyBold != '' ? $getBodyBold . 'i' : '');
	$bodyBoldCss = ($getBodyBold != '' ? $getBodyBold : 'normal');

	
	echo '<style type="text/css"> <!-- Fonts -->';

	
		if (strpos($getTitleFont,'google') !== false) {
			echo "@import url('https://fonts.googleapis.com/css?family=". $titleFont . ":400," . $titleItalic . $titleBold . $titleBoldItalic ."');";
		}
	
	if (strpos($getBodyFont,'google') !== false) {
		echo "@import url('https://fonts.googleapis.com/css?family=". $bodyFont . ":400,400i," . $bodyBold . $bodyBoldItalic ."');";
	}
?>

/* --- */

html {font-family: <?php echo $bodyFont; ?>;}

body,
p,
li {
	font-family: <?php echo $bodyFont; ?>;
}

h1,
h2,
h3,
h4,
h5,
h6 {
	font-family: <?php echo $titleFont; ?>;
	font-weight: <?php echo $titleBoldCss; ?>;
	font-style: <?php echo $titleItalicCss; ?>;
}

b,
strong {
	font-weight: <?php echo $titleBoldCss; ?>;
}
i,
em {
	font-style: italic;
}
 </style>
<?php 
}
?>
