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
 * Theme Options scripts
*/
function theme_options_scripts() {
	wp_enqueue_media();
	wp_enqueue_script('postbox');
	wp_enqueue_script('script-qtip', get_template_directory_uri() . '/js/jquery.qtip.min.js', array('jquery'), '20130729');
	wp_enqueue_script('script-fontSelector', get_template_directory_uri() . '/js/jquery.ui.fontSelector.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget'), '20130729');
	wp_enqueue_script('script-upload', get_template_directory_uri() . '/js/script-upload.js', array('jquery'), '20130729');
	wp_enqueue_script('script-colorpicker', get_template_directory_uri() . '/js/script-colorpicker.js' , array( 'jquery', 'wp-color-picker' ), '20130729');
	wp_enqueue_script('theme_option_functions', get_template_directory_uri() . '/js/theme_option_functions.js' , array( 'jquery'), '20130730');
}
add_action( 'admin_enqueue_scripts', 'theme_options_scripts' );

/**
 * Theme Options styles
*/
function theme_options_styles() {
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_style('jquery-ui-slider', get_template_directory_uri() . '/css/metabox-slider.css');
	wp_enqueue_style('qtipstyle', get_template_directory_uri() . '/css/jquery.qtip.min.css');
	wp_enqueue_style('fontSelector', get_template_directory_uri() . '/css/jquery.ui.fontSelector.css');
	wp_enqueue_style('theme-options-styles', get_template_directory_uri() . '/css/theme-options.css');
}
add_action( 'admin_enqueue_scripts', 'theme_options_styles' );

/**
 * Page initialization
*/
function infoscreen_init() {
	if(false === get_option('infoscreen_theme_options', infoscreen_get_default_theme_options()))
		add_option('infoscreen_theme_options', infoscreen_get_default_theme_options());

	register_setting('infoscreen_options', 'infoscreen_theme_options', 'infoscreen_theme_options_validate');
	add_settings_section('meta_boxes', '', 'add_infoscreen_meta_boxes', 'theme_options');
}
add_action('admin_init', 'infoscreen_init');

/**
 * Add metaboxes to page
*/
function add_infoscreen_meta_boxes(){
	global $screen_layout_columns;
	add_meta_box('meta_box_logo', 'Logo','theme_infoscreen_settings_logo', $page, 'normal', 'core');
	add_meta_box('meta_box_colorfields', 'Colorschemes', 'theme_infoscreen_settings_colorfields', $page, 'normal', 'core');
	add_meta_box('meta_box_animation', 'Animations', 'theme_infoscreen_settings_animations', $page, 'normal', 'core');
	add_meta_box('meta_box_fonts', 'Fonts', 'theme_infoscreen_settings_fonts', $page, 'normal', 'core');
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

/**
 * Default values
*/
function infoscreen_get_default_theme_options() {
	$options = array(
			'colorschemes' => '1',
	);
	return $options;
}

/**
 * Add to appearance menu
*/
function add_infoscreen_appearance_menu() {
	$page = add_theme_page('InfoScreen Settings', 'InfoScreen', 'edit_theme_options', 'infoscreen-settings', 'infoscreen_theme_options_render_page');
}
add_action('admin_menu', 'add_infoscreen_appearance_menu');

/**
 * Colorschemes metabox
*/
function theme_infoscreen_settings_colorfields() {
$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
?>
<table id="colorfields">
	<tr>
		<th class="infoscreen-colorscheme-name-title"><?php _e( 'Color Scheme Name', 'infoscreen' ); ?></th>
		<th class="infoscreen-color-picker-title"><?php _e( 'Font Color', 'infoscreen' ); ?></th>
		<th class="infoscreen-color-picker-title"><?php _e( 'Background Color', 'infoscreen' ); ?></th>
		<th class="infoscreen-colorscheme-name-title"><?php _e( 'Transparency', 'infoscreen' ); ?></th>
		<th class="infoscreen-colorscheme-name-title"><?php _e( '', 'infoscreen' ); ?></th>
	</tr>
	<?php 
	for ($row = 1; $row < esc_attr($options['colorschemes'])+1; $row++){
		//Table row start.
		$uniqueID = $options["csid" . $row];
		if($uniqueID == null){
			$uniqueID = uniqid();
			$options["csid" . $row] = $uniqueID;
		}
		echo "<tr id='infoscreen_theme_options[colorscheme_name" . $row . "]'>";
		
		for ($col = 0; $col <= 4; $col++){
			if($col == 0){
				//output name field
				echo "<td class='infoscreen-colorscheme-name'> ";
				echo "<input type='hidden' name='infoscreen_theme_options[csid" . $row . "]'  id='csid" . $row . "' value='". $uniqueID . "'/>";
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
				echo "<td class='infoscreen-color-picker'> ";
				echo "";
				?>
				<div id="slide<?php echo $row; ?>" style="width: 200px"></div>
				<label>%</label>
				<input id="colorscheme_transparency_field<?php echo $row ?>" name="infoscreen_theme_options[colorscheme_transparency_field<?php echo $row?>]" type="text" style="border: 0; color: #f6931f; font-weight: bold; width: 30px" />
				
				<script> jQuery(document).ready(function($){ 
				 	slide_init_value("slide<?php echo $row; ?>", "colorscheme_transparency_field<?php echo $row; ?>", <?php echo $options['colorscheme_transparency_field'.$row]?>);
				}); </script>
		<?php 
				echo "</td>";
			}
			if($col == 4){
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
	var counter_font_field = 1;
	for (var j = 0; j < color_inputs.length; j++){
			color_inputs[j].id = "colorscheme_font_field" + counter_font_field;
			color_inputs[j].name = "infoscreen_theme_options[colorscheme_font_field" + counter_font_field + "]";
			j++;
			color_inputs[j].id = "colorscheme_bg_field" + counter_font_field;
			color_inputs[j].name = "infoscreen_theme_options[colorscheme_bg_field" + counter_font_field + "]";
			counter_font_field++;
	}
	var counter_name = 1;
	for (var k = 0; k < name_inputs.length; k++){
		var str = name_inputs[k].name;
		if(str.indexOf("colorscheme_name") !== -1){
			name_inputs[k].id = "colorscheme_name" + counter_name;
			name_inputs[k].name = "infoscreen_theme_options[colorscheme_name_field" + counter_name + "]";
			counter_name++;
		}
		if(str.indexOf("csid") !== -1){
			name_inputs[k].id = "csid" + counter_name;
			name_inputs[k].name = "infoscreen_theme_options[csid" + counter_name + "]";
		}
	}
	var counter_remove_btn = 1;
	for (var z = 2; z < remove_inputs.length; z += 3){
		remove_inputs[z].setAttribute("onclick", 'deleteRow("infoscreen_theme_options[colorscheme_name' + counter_remove_btn + ']")');
		counter_remove_btn++;
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
	var transparency_field=row.insertCell(3);
	var remove_btn=row.insertCell(4);

	row.id = "infoscreen_theme_options[colorscheme_name" + rowCount + "]";

	name_field.innerHTML="<input type='hidden' name='infoscreen_theme_options[csid" + rowCount + "]' id='csid" + rowCount + "'value=" + unique_ajax(rowCount) + "> <input type='text' name='infoscreen_theme_options[colorscheme_name_field" + rowCount + "]' id='colorscheme_name" + rowCount + "'value>";
	name_field.className='infoscreen-colorscheme-name';

	font_field.innerHTML="<input type='text' name='infoscreen_theme_options[colorscheme_font_field" + rowCount + "]' id='colorscheme_font_field" + rowCount + "' value='' class='my-color-field'>";
	font_field.className="infoscreen-color-picker";
	
	bg_field.innerHTML="<input type='text' name='infoscreen_theme_options[colorscheme_bg_field" + rowCount + "]' id='colorscheme_bg_field" + rowCount + "' value='' class='my-color-field'>";
	bg_field.className="infoscreen-color-picker";
	
	transparency_field.innerHTML="<div id='slide" + rowCount + "' style='width: 200px'><label>%</label><input id='colorscheme_transparency_field" + rowCount + "' name='infoscreen_theme_options[colorscheme_transparency_field" + rowCount + "]' type='text' style='border: 0; color: #f6931f; font-weight: bold; width: 30px'/>";
	transparency_field.className="infoscreen-color-picker";
	
	
	remove_btn.innerHTML="<input type='button' class='button' value='remove' onclick='deleteRow(\"infoscreen_theme_options[colorscheme_name" + rowCount + "]\")' />";

	slide_init_value("slide"+rowCount, "colorscheme_transparency_field"+rowCount);
	jQuery('.my-color-field').wpColorPicker();
	tooltip();
}
function unique_ajax(id){
	jQuery.ajax({                                      
	     url: '../wp-content/themes/infoscreen/inc/uniqueID.php',         
	     data: "",
	     dataType: 'json',     
	     success: function(data){
		     jQuery('#csid' + id).val(data);
		 } 
	}); 
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

/**
 * Logo metabox
*/
function theme_infoscreen_settings_logo() {
	$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
	?>
<input
	type="hidden" id="logo_url" name="infoscreen_theme_options[logo]"
	value="<?php echo esc_url( $options['logo'] ); ?>" 
	/>
<input
	id="upload_logo_button" type="button" class="button"
	value="<?php _e( 'Upload Logo', 'infoscreen' ); ?>" 
	/>
<?php
theme_infoscreen_settings_logo_preview();
}

/**
 * Logo preview
*/
function theme_infoscreen_settings_logo_preview() {
	$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
	?>
<div id="upload_logo_preview" style="min-height: 100px;">
	<img id="preview_placeholder" style="max-width: 100%;"
		src="<?php echo esc_url( $options['logo'] ); ?>" />
</div>
<?php
}

/**
 * Fonts metabox
*/
function theme_infoscreen_settings_fonts() {
	$options = get_option('infoscreen_theme_options', infoscreen_get_default_theme_options());
	?>
	<table>
		<tr>
			<th><?php _e( 'Title', 'infoscreen' ); ?></th>
			<th><?php _e( 'Body', 'infoscreen' ); ?></th>
		</tr>
		<tr>
			<td>
				<select id="fonts_title" name="infoscreen_theme_options[fonts_title]">
			    	<option value="Chelsea Market"<?php if($options['fonts_title'] == "Chelsea Market"){ echo 'selected="selected"'; }?>>Chelsea Market</option>
			    	<option value="Droid Serif"<?php if($options['fonts_title'] == "Droid Serif"){ echo 'selected="selected"'; }?>>Droid Serif</option>
			    	<option value="Ruluko"<?php if($options['fonts_title'] == "Ruluko"){ echo 'selected="selected"'; }?>>Ruluko</option>
			    	<option value="Ruda"<?php if($options['fonts_title'] == "Ruda"){ echo 'selected="selected"'; }?>>Ruda</option>
			    	<option value="Magra"<?php if($options['fonts_title'] == "Magra"){ echo 'selected="selected"'; }?>>Magra</option>
			    	<option value="Esteban"<?php if($options['fonts_title'] == "Esteban"){ echo 'selected="selected"'; }?>>Esteban</option>
			    	<option value="Lora"<?php if($options['fonts_title'] == "Lora"){ echo 'selected="selected"'; }?>>Lora</option>
			    	<option value="Jura"<?php if($options['fonts_title'] == "Jura"){ echo 'selected="selected"'; }?>>Jura</option>
			  	</select>
			  	  <p id="style_title">
			  	  	  <input id="font-style_title" type="hidden" name="infoscreen_theme_options[font-style_title]" value="<?php echo $options['font-style_title'] ?>"/>
			  	  	  <input id="font-weight_title" type="hidden" name="infoscreen_theme_options[font-weight_title]" value="<?php echo $options['font-weight_title'] ?>"/>
				      <a id="bold_title" href="#" title="bold">Bold</a> |
				      <a id="italic_title" href="#" title="italic">Italic</a>
   				 </p>
  			</td>
  			<td>
			  	<select id="fonts_body" name="infoscreen_theme_options[fonts_body]">
			    	<option value="Chelsea Market"<?php if($options['fonts_body'] == "Chelsea Market"){ echo 'selected="selected"'; }?>>Chelsea Market</option>
			    	<option value="Droid Serif"<?php if($options['fonts_body'] == "Droid Serif"){ echo 'selected="selected"'; }?>>Droid Serif</option>
			    	<option value="Ruluko"<?php if($options['fonts_body'] == "Ruluko"){ echo 'selected="selected"'; }?>>Ruluko</option>
			    	<option value="Ruda"<?php if($options['fonts_body'] == "Ruda"){ echo 'selected="selected"'; }?>>Ruda</option>
			    	<option value="Magra"<?php if($options['fonts_body'] == "Magra"){ echo 'selected="selected"'; }?>>Magra</option>
			    	<option value="Esteban"<?php if($options['fonts_body'] == "Esteban"){ echo 'selected="selected"'; }?>>Esteban</option>
			    	<option value="Lora"<?php if($options['fonts_body'] == "Lora"){ echo 'selected="selected"'; }?>>Lora</option>
			    	<option value="Jura"<?php if($options['fonts_body'] == "Jura"){ echo 'selected="selected"'; }?>>Jura</option>
			  	</select>
			  	  <p id="style_body">
			  	  	  <input id="font-style_body" type="hidden" name="infoscreen_theme_options[font-style_body]" value="<?php echo $options['font-style_body'] ?>"/>
			  	  	  <input id="font-weight_body" type="hidden" name="infoscreen_theme_options[font-weight_body]" value="<?php echo $options['font-weight_body'] ?>"/>
				      <a id="bold_body" href="#" title="bold">Bold</a> |
				      <a id="italic_body" href="#" title="italic">Italic</a>
   				 </p>
  			</td>
  		</tr>
  </table>
 <script>
 jQuery(document).ready( function($) {
	  $('select#fonts_title').fontSelector({
		    options: {
		      inSpeed: 250,
		      outSpeed: "slow",
		    },
		    fontChange: function(e, ui) {
		      //alert("The font is set to "+ui.font+" (was "+ui.oldFont+" before)");
		    	$('ul.fonts').hide();
		    },
		    styleChange: function(e, ui) {
		    }
	});
	  $('select#fonts_body').fontSelector({
		    options: {
		      inSpeed: 250,
		      outSpeed: "slow",
		    },
		    fontChange: function(e, ui) {
		      //alert("The font is set to "+ui.font+" (was "+ui.oldFont+" before)");
		    	$('ul.fonts').hide();
		    },
		    styleChange: function(e, ui) {
		    }
	});
	  $('#style_title a').click(function(){
	      var style = $(this).attr('title'); // This will be bold or italic
	      var current = $('select#fonts_title').fontSelector('option', style);
	      var setTo = true;
	      if(current == true) setTo = false;
	      $('select#fonts_title').fontSelector('option', style, setTo);
	      if(current == false && style == "italic"){
	    	$('#font-style_title').val(style);
		  }
	      else if(current == true && style == "italic"){
			$('#font-style_title').val("");
		  }
	      if(current == false && style == "bold"){
		    	$('#font-weight_title').val(style);
			}
		    else if(current == true && style == "bold"){
				$('#font-weight_title').val("");
		  }
	});
	  $('#style_body a').click(function(){
	      var style = $(this).attr('title'); // This will be bold or italic
	      var current = $('select#fonts_body').fontSelector('option', style);
	      var setTo = true;
	      if(current == true) setTo = false; 
	      $('select#fonts_body').fontSelector('option', style, setTo);
	      if(current == false && style == "italic"){
		    	$('#font-style_body').val(style);
			  }
		      else if(current == true && style == "italic"){
				$('#font-style_body').val("");
			  }
		      if(current == false && style == "bold"){
			    	$('#font-weight_body').val(style);
				}
			    else if(current == true && style == "bold"){
					$('#font-weight_body').val("");
			  }
	});
	  $('select#fonts_title').fontSelector('option', '<?php echo $options['font-style_title'] ?>', true);
	  $('select#fonts_title').fontSelector('option', '<?php echo $options['font-weight_title'] ?>', true);
	  $('select#fonts_body').fontSelector('option', '<?php echo $options['font-style_body'] ?>', true);
	  $('select#fonts_body').fontSelector('option', '<?php echo $options['font-weight_body'] ?>', true);
});
</script>
<?php
}

/**
 * Animations metabox
*/
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

/**
 * Option validation
 * @param All page input, $input saves to db.
*/
function infoscreen_theme_options_validate($input) {
	$options = get_option('infoscreen_theme_options');
 	$colorschemes = 0;
 	update_option('test_2', $input);
	for ($i = 0; $i < sizeOf($input); $i++){
		$name_field = 'colorscheme_name_field' . $i;
		if(array_key_exists($name_field, $input)){
			$colorschemes++;
		}
	}
	$input['colorschemes'] = $colorschemes;

	return $input;
}

/**
 * Render page
*/
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
