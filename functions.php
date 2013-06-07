<?php
/**
 * InfoScreen functions and definitions
 *
 * @package InfoScreen
 * @since InfoScreen 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since InfoScreen 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

/*
 * Load Jetpack compatibility file.
 */
require( get_template_directory() . '/inc/jetpack.php' );

if ( ! function_exists( 'infoscreen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since InfoScreen 1.0
 */
function infoscreen_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Add Layout Selector to Post Admin
	 */
	require( get_template_directory() . '/inc/metabox-layout.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on InfoScreen, use a find and replace
	 * to change 'infoscreen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'infoscreen', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
}
endif; // infoscreen_setup
add_action( 'after_setup_theme', 'infoscreen_setup' );

/**
 * Setup the WordPress core custom background feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for WordPress 3.3
 * using feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @todo Remove the 3.3 support when WordPress 3.6 is released.
 *
 * Hooks into the after_setup_theme action.
 */
function infoscreen_register_custom_background() {
	$args = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);

	$args = apply_filters( 'infoscreen_custom_background_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-background', $args );
	} else {
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		if ( ! empty( $args['default-image'] ) )
			define( 'BACKGROUND_IMAGE', $args['default-image'] );
		add_custom_background();
	}
}
add_action( 'after_setup_theme', 'infoscreen_register_custom_background' );

/**
 * Get the thumbnail src url - for use inside The Loop only:
 *
 * http://wpengineer.com/2123/use-wordpress-post-thumbnail-as-background-image/
 */
function infoscreen_img_src($size) {
	if ( !has_post_thumbnail($post->ID) ) {
		// default image, if no post thumbnail
		$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/skyline.jpg';
	} else {
		// get post thumbnail
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size); // size for image; defined via add_image_size
		$img_url = esc_attr($image[0]);
	}
	echo $img_url;
}

/**
 * Thumbnail support & Image sizes
 */
add_theme_support( 'post-thumbnails' );
add_image_size( 'slide-img', 1920, 1080, true );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since InfoScreen 1.0
 */
function infoscreen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'infoscreen' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'infoscreen_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function infoscreen_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'infoscreen_scripts' );

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );
