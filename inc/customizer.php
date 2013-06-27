<?php

/**
 * InfoScreen Theme Customizer
 *
 * @package InfoScreen
 * @since InfoScreen 1.2
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since InfoScreen 1.2
 */
function infoscreen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'infoscreen_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since InfoScreen 1.2
*/
function infoscreen_customize_preview_js() {
	wp_enqueue_script( 'infoscreen_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130304', true );

}
add_action( 'customize_preview_init', 'infoscreen_customize_preview_js' );

/**
 * InfoScreen settings page
*/
function mw_enqueue_color_picker() {
	wp_enqueue_style('wp-color-picker');

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js');
	
	if(strpos($_SERVER['REQUEST_URI'], "post.php")){
		wp_enqueue_style('jquery-ui', '/wp-content/themes/infoscreen/inc/metabox-slider.css');
	}
	wp_enqueue_style('font-picker', '/wp-content/themes/infoscreen/inc/style-fontselector.css');

	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
	
	wp_register_script( 'script-upload', '/wp-content/themes/infoscreen/inc/script-upload.js', array('jquery','media-upload','thickbox') );
	wp_enqueue_script( 'script-colorpicker_fontselector', '/wp-content/themes/infoscreen/inc/script-colorpicker_fontselector.js' , array( 'wp-color-picker' ), false, true );
	
	wp_register_script( 'script-slider', '/wp-content/themes/infoscreen/inc/script-slider.js');
	
	wp_enqueue_script('media-upload');
	wp_enqueue_script('script-upload');
	wp_enqueue_script('script-slider');
	
}
add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );

/**
 * Theme Options styles
*/
function theme_options_styles() {
	wp_enqueue_style('theme-options-styles', get_template_directory_uri() . '/css/theme-options.css');
}
add_action( 'admin_enqueue_scripts', 'theme_options_styles' );

function infoscreen_settings_api_init() {
	if(false === get_option('infoscreen_theme_options', infoscreen_get_default_theme_options()))
		add_option('infoscreen_theme_options', infoscreen_get_default_theme_options());

	register_setting('infoscreen_options', 'infoscreen_theme_options', 'infoscreen_theme_options_validate');
	add_settings_section('meta_boxes', '', 'add_infoscreen_meta_boxes', 'theme_options');
	
}
function add_infoscreen_meta_boxes(){
	global $screen_layout_columns;
	add_meta_box('meta_box_logo', 'Logo','theme_infoscreen_settings_logo', $page, 'normal', 'core');
	add_meta_box('meta_box_colorfields', 'Colorschemes', 'theme_infoscreen_settings_colorfields', $page, 'normal', 'core');
	add_meta_box('meta_box_font_default', 'Default Fonts', 'theme_infoscreen_settings_default_fonts', $page, 'normal', 'core');
	?>
		<div id="poststuff" class="metabox-holder<?php echo 2 == $screen_layout_columns ? ' has-right-sidebar' : ''; ?>">
							<?php do_meta_boxes($page, 'normal', $data); ?>
							<?php do_meta_boxes($page, 'additional', $data); ?>
					<br class="clear"/>
				</div>	
		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready( function($) {
				// close postboxes that should be closed
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				// postboxes setup
				postboxes.add_postbox_toggles('<?php echo $page; ?>');
			});
			//]]>
		</script>
			
			<?php
}

function infoscreen_options_setup() {
	global $pagenow;
	if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
		add_filter( 'gettext', 'replace_thickbox_text' , 1, 2 );
	}
}
add_action( 'admin_init', 'infoscreen_options_setup' );

function replace_thickbox_text($translated_text, $text ) {
	if ( 'Insert into Post' == $text ) {
		$referer = strpos( wp_get_referer(), 'infoscreen-settings' );
		if ( $referer != '' ) {
			return __('I want this to be my logo!', 'infoscreen' );
		}
	}

	return $translated_text;
}

function infoscreen_get_default_theme_options() {
	$options = array(
			'colorscheme_default' => '#b4d455',
			'colorschemes' => '2',
			'logo' => '',
			'default_font' => 'Arial'
	);
	return $options;
}

add_action('admin_init', 'infoscreen_settings_api_init');

function example_menu_options() {
	$page = add_theme_page('InfoScreen Settings', 'InfoScreen', 'edit_theme_options', 'infoscreen-settings', 'infoscreen_theme_options_render_page');
}
add_action('admin_menu', 'example_menu_options');
function theme_infoscreen_settings_colorfields() {
$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
?>
<table id="colorfields">
	<tr>
		<th class="infoscreen-colorscheme-name-title"><?php _e( 'Color Scheme Name', 'infoscreen' ); ?></th>
		<th class="infoscreen-color-picker-title"><?php _e( 'Font Color', 'infoscreen' ); ?></th>
		<th class="infoscreen-color-picker-title"><?php _e( 'Background Color', 'infoscreen' ); ?></th>
	</tr>
	<?php 
	for ($i = 0; $i < esc_attr($options['colorschemes']); $i++){
		?>
	<?php if($i&1) { 
} else {?>
	<tr>
		<td class="infoscreen-colorscheme-name"><input type="text"
			name="infoscreen_theme_options[colorscheme_name<?php echo $i . ']'?>"
			id='colorscheme_name<?php echo $i ?>'
			value='<?php echo esc_attr($options['colorscheme_name' . $i]); ?>' />
		</td>
		<?php } ?>

		<td class="infoscreen-color-picker"><input type="text"
			name="infoscreen_theme_options[colorscheme<?php echo $i . ']' ?>"
			id='colorscheme<?php echo $i ?>'
			value='<?php echo esc_attr($options['colorscheme' . $i]); ?>'
			class=" my-color-field" />
		</td>
		<?php if($i&1) { 
			echo '</tr>';
		}
} ?>

</table>
<input
	type="hidden" name="infoscreen_theme_options[colorschemes]"
	id="colorschemes"
	value="<?php echo esc_attr($options['colorschemes']); ?>" />
<script>
function addRow() {
	 
    var table = document.getElementById('colorfields');

    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);

    var colCount = table.rows[0].cells.length;

    var firstCellOnly = 0;
    for(var i=0; i<colCount; i++) {

        var newcell = row.insertCell(i);
		
        newcell.innerHTML = table.rows[0].cells[i].innerHTML;
			if(firstCellOnly == 0) {
				newcell.childNodes[0].id = "colorscheme_name" + (rowCount * 2);
				newcell.childNodes[0].name = "infoscreen_theme_options[colorscheme_name" + (rowCount * 2) + "]";
				newcell.childNodes[0].value = "";
				firstCellOnly = 1; 	
			}
			if(newcell.childNodes[0].childNodes[1]){
               	newcell.childNodes[0].childNodes[1].childNodes[0].id = "colorscheme" + (rowCount * 2);
               	newcell.childNodes[0].childNodes[1].childNodes[0].name = "infoscreen_theme_options[colorscheme" + (rowCount * 2)+ "]";
               	newcell.childNodes[0].childNodes[1].childNodes[0].value = "";
            	newcell.childNodes[0].childNodes[0].style.background = "";
            	console.log(newcell.childNodes[0].childNodes[0].style.background);
			}
        }
    firstCellOnly = 0;
    var x = Number(document.getElementById('colorschemes').value);
    var y = 2;
    document.getElementById('colorschemes').value = x+y;
    document.getElementById('submit').click();
    }
</script>

<input type="button"
	class="button"
	value="Add Row"
	onclick="addRow()" />
<?php }
function theme_infoscreen_settings_logo() {
	$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
	?>
<input
	type="hidden" id="logo_url" name="infoscreen_theme_options[logo]"
	value="<?php echo esc_url( $options['logo'] ); ?>" />
<input
	id="upload_logo_button" type="button" class="button"
	value="<?php _e( 'Upload Logo', 'infoscreen' ); ?>" />
<?php
theme_infoscreen_settings_logo_preview();
}
function theme_infoscreen_settings_logo_preview() {
	$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
	?>
<div id="upload_logo_preview" style="min-height: 100px;">
	<img style="max-width: 100%;"
		src="<?php echo esc_url( $options['logo'] ); ?>" />
</div>
<?php
}
function theme_infoscreen_settings_default_fonts() {
	update_option('infoscreen_default_fonts', array('Arial', 'Times New Roman', 'Courier New'));
	$font_options = get_option('infoscreen_default_fonts');
	$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
	?>
		
		<ul class="fonts_id">
			<li><a onmouseover="mopen('m2')" onmouseout="mclosetime()"
				id="current_font" style="font-family: <?php echo esc_attr($options['default_font']); ?>"> <?php echo esc_attr($options['default_font']); ?>
			</a>
				<div id="m2" onmouseover="mcancelclosetime()"
					onmouseout="mclosetime()">
					<input type="hidden" id="default_font" name="infoscreen_theme_options[default_font]"/>
					<?php 
					for ($j = 0; $j < 3; $j++){
				echo "<a style=\"font-family: " . $font_options[$j] . "\" " . "onclick=\"getCurrentFont('" . $font_options[$j] . "')" . '">' . $font_options[$j] . "</a>";
			}
			?>
				</div>
			</li>
		</ul>
		<div style="clear: both"></div>
<?php
echo $_SERVER['REQUEST_URI'];
}
function infoscreen_theme_options_validate($input) {
	$options = get_option('infoscreen_theme_options');
	$output = $defaults = infoscreen_get_default_theme_options();
	$output['colorschemes'] = empty($input['colorschemes']) ? $defaults['colorschemes'] : $input['colorschemes'];
	$output['logo'] = $input['logo'];
	for($i = 0; $i < $input['colorschemes']; $i++) {
			$output['colorscheme_name' . $i] = $input['colorscheme_name' . $i];
			$i++;
	}

	for($i = 0; $i < $input['colorschemes']; $i++) {
			$output['colorscheme' . $i] = $input['colorscheme' . $i];
	}
	$output['default_font'] = empty($input['default_font']) ? $options['default_font'] : $input['default_font'];
	
	return $output;
}
function infoscreen_theme_options_render_page() { ?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Theme options</h2>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
		settings_fields('infoscreen_options');
		do_settings_sections('theme_options');
		submit_button();
		?>
	</form>
</div>
<?php }
