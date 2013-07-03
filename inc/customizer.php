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
	wp_enqueue_script('qtip','http://qtip2.com/v/2.0.1/jquery.qtip.min.js');
	wp_enqueue_style('qtipstyle','http://qtip2.com/v/2.0.1/jquery.qtip.min.css');
	
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
	add_meta_box('meta_box_animation', 'Animations', 'theme_infoscreen_settings_animations', $page, 'normal', 'core');
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
			'colorschemes' => '1',
			'logo' => '',
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
	for ($row = 1; $row < esc_attr($options['colorschemes'])+1; $row++){
		//Table row start.
		echo "<tr id='infoscreen_theme_options[colorscheme_name" . $row . "]'>";

		for ($col = 0; $col < 4; $col++){
			
			if($col == 0){
				//output name field
				echo "<td class='infoscreen-colorscheme-name'> ";
				echo "<input ";
 				echo "type='text' ";
				echo "name='infoscreen_theme_options[colorscheme_name_field" . $row . "]' ";
				echo "id='colorscheme_name" . $row . "' ";
				echo "value= " . '"' . $options['colorscheme_name_field' . $row] . '"';
				echo "/>";
				echo "</td> ";
			}
			if($col == 1){
				//output font color field
				echo "<td class='infoscreen-color-picker'> ";
				echo "<input ";
				echo "type='text' ";
				echo "name='infoscreen_theme_options[colorscheme_font_field" . $row . "]' ";
				echo "id='colorscheme_font_field" . $row . "' "; 
				echo "value= " . '"' . $options['colorscheme_font_field' . $row] . '"';
				echo "class='my-color-field' />";
			}
			if($col == 2){
				//output bg color field
				echo "<td class='infoscreen-color-picker'> ";
				echo "<input ";
				echo "type='text' ";
				echo "name='infoscreen_theme_options[colorscheme_bg_field" . $row . "]' ";
				echo "id='colorscheme_bg_field" . $row . "' ";
				echo "value= " . '"' . $options['colorscheme_bg_field' . $row] . '"';
				echo "class='my-color-field' />";
			}
			if($col == 3){
				//output remove button
				echo "<td>";
				echo "<input type='button' class='button' value='remove' onclick='deleteRow(\"infoscreen_theme_options[colorscheme_name". $row . "]\")' />";
				echo "</td>";
			}
		}
	}
?>
</table>
<input
	type="hidden" name="infoscreen_theme_options[colorschemes]"
	id="colorschemes"
	value="<?php echo esc_attr($options['colorschemes']); ?>" 
	/>
<script>
function renameRows(){
	var table = document.getElementById('colorfields');
	var rows = table.getElementsByTagName('TR');
	var color_inputs = table.getElementsByClassName('my-color-field');
	var name_inputs = table.getElementsByTagName('input');
	var remove_inputs = table.getElementsByClassName('button');
	for (var i =0; i < rows.length; i++){
		if(i != 0){
			rows[i].id = "infoscreen_theme_options[colorscheme_name" + i + "]";
		}
	}
	var counter = 1;
	for (var j = 0; j < color_inputs.length; j++){
			color_inputs[j].id = "colorscheme_font_field" + (counter);
			color_inputs[j].name = "infoscreen_theme_options[colorscheme_font_field" + (counter) + "]";
			j++;
			color_inputs[j].id = "colorscheme_bg_field" + (counter);
			color_inputs[j].name = "infoscreen_theme_options[colorscheme_bg_field" + (counter) + "]";
			counter++;
	}
	var counter2 = 1;
	for (var k = 0; k < name_inputs.length; k++){
		var str = name_inputs[k].name;
		if(str.indexOf("colorscheme_name") !== -1){
			name_inputs[k].name = "colorscheme_name" + counter2;
			name_inputs[k].id = "colorscheme_name" + counter2;
			counter2++;
		}
	}
	var counter3 = 1;
	for (var z = 2; z < remove_inputs.length; z += 3){
		remove_inputs[z].setAttribute("onclick", 'deleteRow("infoscreen_theme_options[colorscheme_name' + counter3 + ']")');
		counter3++;
	}
}
function deleteRow(rowid) {   
    var row = document.getElementById(rowid);
    var table = row.parentNode;
    while ( table && table.tagName != 'TABLE' )
        table = table.parentNode;
    if ( !table )
        return;
    table.deleteRow(row.rowIndex);
    renameRows();
    tooltip();
}
function appendRow(){
	var table = document.getElementById('colorfields');
	var rowCount = table.rows.length;
	var row=table.insertRow(rowCount);
	var name_field=row.insertCell(0);
	var font_field=row.insertCell(1);
	var bg_field=row.insertCell(2);
	var remove_btn=row.insertCell(3);

	row.id = "infoscreen_theme_options[colorscheme_name" + rowCount + "]";
	
	name_field.innerHTML="<input type='text' name='infoscreen_theme_options[colorscheme_name_field" + rowCount + "]' id='colorscheme_name" + rowCount + "'value>";
	name_field.className='infoscreen-colorscheme-name';

	font_field.innerHTML="<input type='text' name='infoscreen_theme_options[colorscheme_font_field" + rowCount + "]' id='colorscheme_font_field" + rowCount + "' value='' class='my-color-field'>";
	font_field.className="infoscreen-color-picker";
	
	bg_field.innerHTML="<input type='text' name='infoscreen_theme_options[colorscheme_bg_field" + rowCount + "]' id='colorscheme_bg_field" + rowCount + "' value='' class='my-color-field'>";
	bg_field.className="infoscreen-color-picker";
	
	remove_btn.innerHTML="<input type='button' class='button' value='remove' onclick='deleteRow(\"infoscreen_theme_options[colorscheme_name" + rowCount + "]\")' />";

	jQuery(document).ready(function($){
		$('.my-color-field').wpColorPicker();
	});
	tooltip();
}
var tooltip_obj = false;
function tooltip(){
jQuery(document).ready(function($){
	var inputs = jQuery('input[type="text"]'), button = jQuery('#submit');
	inputs.each(function() { jQuery(this).data('val', this.value); }).on('keyup', 
					function() {
    					var str = jQuery(this).attr('name');
						if(str.indexOf("colorscheme_name") !== -1){
							if (!isUnique()){
							//Do something if name is not unique
								var activeObj = document.activeElement;
								var resObj = document.getElementById(activeObj.id);
								if(!resObj.getAttribute('data-hasqtip')){
									jQuery(resObj).qtip({
										content: {
											text: 'A unique name must be chosen'
										},
										show: {
											solo: false,
											event: ''
										},
										hide: {
											fixed: true,
											event: ''
										}
									});
								}
								if(!tooltip_obj){
									jQuery(resObj).qtip("show");
									tooltip_obj = resObj;
									jQuery(resObj).css("background", "#f9d5d1");
									jQuery('#submit').attr("disabled", 'disabled');
								}
							}
							if (isUnique()){
								if(tooltip_obj){
									jQuery(tooltip_obj).qtip("hide");
									jQuery(tooltip_obj).removeAttr("style");
									jQuery('#submit').removeAttr("disabled");
									tooltip_obj = false;
								}
							}
						}
					});
	});
}
tooltip();
function isUnique() {
    // Collect all values in an array
    var values = [];
    jQuery('input[type="text"]').each( function(idx,val){ 
    	var str = jQuery(this).attr('name');
		if(str.indexOf("colorscheme_name") !== -1){
			if(jQuery(val).val()){
        		values.push(jQuery(val).val());
			} 
        }});
    // Sort it
    values.sort();

    // Check whether there are two equal values next to each other
    for( var k = 1; k < values.length; ++k ) {
        if( values[k] == values[k-1] ) {
            return false;
        }
    }
    return true;
}
</script>

<input type="button"
	   class="button"
	   value="Append Row"
	   id="appendBtn"
	   onClick="appendRow()" />
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
// 	update_option('infoscreen_default_fonts', array('Arial', 'Times New Roman', 'Courier New'));
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
}
function theme_infoscreen_settings_animations() {
	$cat_array = get_categories();
	$cat_options = get_option('infoscreen_theme_options');
	?>
	<table>
	<tr>
		<th class="infoscreen-animation-category"><?php _e( 'Category', 'infoscreen' ); ?></th>
		<th class="infoscreen-animation-radio"><?php _e( 'Animation', 'infoscreen' ); ?></th>
	</tr>
	<?php for ($i = 0; $i < count($cat_array); $i++){ ?>
	<tr>
		<td><?php echo $cat_array[$i]->name; ?></td>
		<td>
			<input type="radio" name="infoscreen_theme_options[animation_<?php echo $cat_array[$i]->cat_ID; ?>]" value="fade" <?php echo ($cat_options['animation_' . $cat_array[$i]->cat_ID] == 'fade') ? 'checked':($cat_options['animation_' . $cat_array[$i]->cat_ID] == '') ? 'checked' : ''; ?>>Fade<br>
			<input type="radio" name="infoscreen_theme_options[animation_<?php echo $cat_array[$i]->cat_ID; ?>]" value="slide" <?php echo ($cat_options['animation_' . $cat_array[$i]->cat_ID] == 'slide') ? 'checked':''; ?>>Slide<br>
		</td>
	</tr>
	<?php } ?>
	</table>
	<?php 
}
function infoscreen_theme_options_validate($input) {
 	$colorschemes = 0;
	for ($i = 0; $i < sizeOf($input); $i++){
		$name_field = 'colorscheme_name_field' . $i;
		if(array_key_exists($name_field, $input)){
			$colorschemes++;
		}
	}
	$input['colorschemes'] = $colorschemes;

	return $input;
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
