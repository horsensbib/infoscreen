<?php header("Content-type: text/css"); 

$get_data = $wpdb->query(" SELECT // some SQL here ") ); // Fetch the data here.
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
	font-family: <?php ?>;
	font-weight: <?php ?>;
	font-style: <?php ?>;
}
p,
li {
	font-family: <?php ?>;
}
b,
strong {
	font-weight: <?php ?>;
}

/*
 * Colors
 * ===================== */
<?php 
	foreach ( $get_data as $post ) { // Make a loop for each post. (Or maybe better, for each color scheme and then comma-seperate the CSS id's?)
?>
#post-<?php // The post ID ?> .slide-content {
	color: <?php ?>;
	background: <?php ?>;
}
<?php 
	} 
?>
