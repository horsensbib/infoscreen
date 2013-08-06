<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package InfoScreen
 * @since InfoScreen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<script>
var head  = document.getElementsByTagName('head')[0];
<?php $options = get_option('infoscreen_theme_options'); ?>
var title_font = "<?php echo  $options['title_font-family']; ?>";
var body_font = "<?php echo  $options['body_font-family']; ?>";

if(title_font.indexOf("google") != -1){
	var title_font_fixed = title_font.replace("google ", "");
	var link  = document.createElement('link');
	link.rel  = 'stylesheet';
	link.type = 'text/css';
	link.href = 'http://fonts.googleapis.com/css?family='+title_font_fixed;
	link.media = 'all';
	head.appendChild(link);
}
if(body_font.indexOf("google") != -1){
	var body_font_fixed = body_font.replace("google ", "");
	var link  = document.createElement('link');
	link.rel  = 'stylesheet';
	link.type = 'text/css';
	link.href = 'http://fonts.googleapis.com/css?family='+body_font_fixed;
	link.media = 'all';
	head.appendChild(link);
}
</scripT>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>
	
<body <?php body_class('flexslider'); ?>>
	<?php do_action( 'before' ); ?>
	<ul class="slides">