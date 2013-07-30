<?php
/**
 * InfoScreen functions and definitions
 *
 * @package InfoScreen
 * @since InfoScreen 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 * ================================================================= */
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
	 * ==================================== */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 * ============================================================== */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 * ==================== */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Add Layout Selector to Post Admin
	 * ================================= */
	require( get_template_directory() . '/inc/metabox-layout.php' );
	
	/**
	 * Add Color scheme Selector to Post Admin
	 * ======================================= */
	require( get_template_directory() . '/inc/metabox-colorscheme.php' );
	
	/**
	 * Add Time Selector to Post Admin
	 * =============================== */
	require( get_template_directory() . '/inc/metabox-time.php' );
	
	/**
	 * Add Preview to Post admin
	 * ========================= */
	require( get_template_directory() . '/inc/metabox-preview.php' );
	
	/**
	 * Custom functions
	 * ================ */
	require( get_template_directory() . '/inc/custom_functions.php' );
	
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on InfoScreen, use a find and replace
	 * to change 'infoscreen' to the name of your theme in all the template files
	 * ========================================================================== */
	load_theme_textdomain( 'infoscreen', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 * ===================================================== */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 * ================================== */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Enable support for Post Formats
	 * =============================== */
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
		global $post;
		if (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-img') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/moss.jpg';
		} elseif (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-txt') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/cloud.jpg';
		} elseif (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-img-left') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/sunset-hair.jpg';
		} elseif (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-img-right') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/cactus.jpg';
		} elseif (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-img-top') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/girl-flowers.jpg';
		} elseif (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-img-bottom') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/tracks.jpg';
		} elseif (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-txt-left') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/walking.jpg';
		} elseif (get_post_meta(get_the_ID(), '_infoscreen_layout', true) == 'layout-txt-right') {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/fishing.jpg';
		} else {
			$img_url = '' . get_bloginfo('stylesheet_directory') . '/img/grass.jpg';
		}
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
 * ========================== */
function infoscreen_scripts() {
	
	wp_enqueue_style( 'style', get_stylesheet_uri() );

// 	wp_enqueue_script( 'navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	
	wp_enqueue_script( 'plugins', get_template_directory_uri() . '/js/jquery.plugins.js', array( 'jquery' ), '20130625' );

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'infoscreen_scripts' );

/**
 * Remove link buttons from editor
 * =============================== */
function change_mce_options( $init ) {
 $init['theme_advanced_disable'] = 'link,unlink,wp_more';
 return $init;
}
add_filter('tiny_mce_before_init', 'change_mce_options');

/**
 * Remove specified unnecessary metaboxes.
 * ======================================= */
function remove_post_metaboxes() {
 remove_meta_box( 'commentstatusdiv' , 'post' , 'normal' );
 remove_meta_box( 'commentsdiv' , 'post' , 'normal' );
 remove_meta_box( 'trackbacksdiv' , 'post' , 'normal' );
 remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
 remove_meta_box( 'postcustom' , 'post' , 'normal' );
}
add_action( 'admin_menu' , 'remove_post_metaboxes' );

/**
 * Move post submit metabox
 * ======================== */
function move_submit_metabox() {
	remove_meta_box( 'submitdiv', 'post', 'side' );
	add_meta_box('submitdiv', __('Publish'), 'post_submit_meta_box', 'post', 'side', 'high');
}
add_action('add_meta_boxes', 'move_submit_metabox' );

/**
 * Rename post thumbnail metabox
 * ============================= */
function rename_thumb_metabox() {
	remove_meta_box( 'postimagediv', 'post', 'side' );
	add_meta_box('postimagediv', __('Background Image'), 'post_thumbnail_meta_box', 'post', 'side', 'high');
}
add_action('add_meta_boxes', 'rename_thumb_metabox' );

/**
 * Rename post category metabox
 * ============================ */
function rename_category_metabox() {
	remove_meta_box( 'categorydiv', 'post', 'side' );
	add_meta_box('categorydiv', __('Screen Locations'), 'post_categories_meta_box', 'post', 'side', 'high');
}
add_action('add_meta_boxes', 'rename_category_metabox' );

/**
 * Rename Post Type in the Admin Menu
 * http://wp.tutsplus.com/tutorials/creative-coding/customizing-your-wordpress-admin/
 * ======================================================== */
function edit_admin_menus() {
	global $menu;
	global $submenu;
	
	$menu[5][0] = __('Slideshow'); // Change Posts to Recipes
	
	$submenu['edit.php'][5][0] = __('All slides');
	$submenu['edit.php'][10][0] = __('Add a slide');
	$submenu['edit.php'][15][0] = __('Screen Locations'); // Rename categories to meal types
}
add_action( 'admin_menu', 'edit_admin_menus' );

/**
 * Rename Admin Object Labels
 * http://stackoverflow.com/questions/12949722/change-label-posts-to-articles-in-wordpress
 * ======================================================== */
function change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = __('All Slides');
	$labels->singular_name = __('Slide');
	$labels->add_new = __('Add Slide');
	$labels->add_new_item = __('Add Slide');
	$labels->edit_item = __('Edit Slide');
	$labels->new_item = __('Slide');
	$labels->view_item = __('View Slide');
	$labels->search_items = __('Search Slides');
	$labels->not_found = __('No Slides found');
	$labels->not_found_in_trash = __('No Slides found in Trash');
}
add_action( 'init', 'change_post_object_label' );

/**
 * Slide Show
 * ========== */
function slideshow() {
	global $wp_query;
	if(is_category() || is_single()){
		$cat_ID = get_query_var('cat');
	}
	global $post;
	$args = array( 'category' => $cat_ID);
	$myposts = get_posts( $args );
	$slide_durations = array();
	foreach( $myposts as $post ) : setup_postdata($post);
		if(get_post_meta(get_the_ID(), '_infoscreen_time', true) == null){
			$slide_durations[] = 5000;
		}
		else {
			$slide_durations[] = get_post_meta(get_the_ID(), '_infoscreen_time', true) * 1000;
		}
	endforeach;
	$temp_cat_options = get_option('infoscreen_theme_options');
?>
<script type="text/javascript">
var timesUp = false;
function timedOut(){
	timesUp = true;
}
setTimeout("timedOut()", 3600000);
jQuery(window).load(function() {
	var delays = [ <?php echo implode(', ', $slide_durations); ?> ],
        _curr_index = 0,
        _last_index = -1,
        _delay = false, 
        _aa_timeout = null,
        _auto_advancing = false;
    
	jQuery(document).ready(function($){
		$('.flexslider').flexslider({
			animation: "<?php if (is_category()) {
					echo $temp_cat_options['animation_'.$cat_ID];
				} else {
					echo 'fade';
				} ?>",
			animationSpeed: 2000,
			controlNav: false,
			slideshow: false,
			after: function( slider ){
		    	// If we weren't advancing to the next slide from the auto_advance_slide() function(from user controls for instance), we need to fix some things
		        if ( ! _auto_advancing ) {
		        	clearTimeout( _aa_timeout ); // Clear the auto advance timeout
		            _curr_index = slider.currentSlide; // Fix the current index
		            _last_index = slider.count -1;
		            if(_last_index == _curr_index){
		            	if(timesUp){
		                	setTimeout(function() { location.reload(true) }, 2000);
		            	}
		            }
		        };
		        // Set-up the next timer for auto advancing
		    auto_advance_slide();
		   },
			end: function( slider ){
				
			 }
		});
	});
    function auto_advance_slide() {
        if ( typeof( delays[ _curr_index ] ) != 'undefined' ) {
            _delay = delays[ _curr_index ];
        } else {
            _delay = delays[ 0 ];
            _curr_index = 0;
        };

        // Set time out to switch to next slide
        _aa_timeout = setTimeout( function(){
            _auto_advancing = true;
            // Switch to next slide.
            jQuery('.flexslider').flexslider('next');

            _auto_advancing = false;
        }, _delay );

		
        // Increase the current index. 
        _curr_index ++;
    }

    auto_advance_slide();
});
</script>
<?php
}
add_action('wp_footer', 'slideshow');

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );
