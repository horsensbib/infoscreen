<?php
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'infoscreen_create_colorscheme_metabox');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'infoscreen_colorscheme_save_postdata');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'infoscreen_transparency_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function infoscreen_create_colorscheme_metabox() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box(
		'infoscreen-page-colorscheme',
		__( 'Color scheme', 'infoscreen_page_colorscheme' ),
		'colorscheme_metabox',
		'post',
		'normal',
		'high'
				);
	}
}
function colorscheme_metabox() {
	$options = get_option('infoscreen_theme_options');
	$currentvalue = get_post_meta(get_the_ID(), '_infoscreen_colorscheme', true);
	$selected_id = 1;
	?>
<div>
<ul class="layout-controls color-controls">
	<?php for ($i = 1; $i <= $options['colorschemes']; $i++){ ($currentvalue == $options['csid'.$i])? $selected_id = $i : '';  
		$bg = hex2rgb($options['colorscheme_bg_field'.$i]);
		$transparency_value = $options['colorscheme_transparency_field'.$i]/100;
	?>
	<li class="layout-selector" style="background-image:url('<?php infoscreen_img_src('slide-img'); ?>');">
		<label for="csid<?php echo $i?>" class="color-selector" style="color: <?php echo $options['colorscheme_font_field'.$i]; ?>; background: rgba(<?php echo $bg; ?>,<?php echo $transparency_value; ?>);" data-transparency="<?php echo $options['colorscheme_transparency_field'.$i]; ?>">
				<input type="radio" name="_infoscreen_colorscheme" id="csid<?php echo $i?>" onclick="scheme_change(<?php 
					echo $options['colorscheme_transparency_field'.$i];
					?>);" value="<?php echo $options['csid'.$i]; ?>" <?php echo ($currentvalue == $options['csid'.$i])? 'checked="checked"':''; ?> /><br />
				<?php _e($options['colorscheme_name_field'.$i],'infoscreen') ?>
		</label>
	</li>
	<?php } ?>
</ul>

	<input type="hidden" name="infoscreen_colorscheme_noncename"
		id="infoscreen_colorscheme_noncename"
		value="<?php wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
	</div>
	<label>Transparency</label>
	<div id="slider" style="width: 200px"></div>
	<div> 
	<label for="amount">%</label>
  <input name="_infoscreen_transparency" type="text" id="amount" style="border: 0; color: #f6931f; font-weight: bold; width: 30px" />
	</div>
	<script>jQuery(document).ready(function($){
		  $( "#slider" ).slider({
			    range: "min",
			    min: 0,
			    max: 100,
			    value: <?php 
			    $currentvalue = get_post_meta(get_the_ID(), '_infoscreen_transparency', true);
			    if($currentvalue == null){
			    	$currentvalue = $options['colorscheme_transparency_field'.$selected_id];
				}
				echo $currentvalue;?>,
			    slide: function( event, ui ) {
			      $( "#amount" ).val( ui.value );
						slide_change(ui.value);
			    }
			  });
			  $( "#amount" ).val( $( "#slider" ).slider( "value" ) );
			});
	jQuery(document).ready(function($){
		$('#slider').removeClass('ui-widget-content').addClass('ui-widget-content-slider-custom');
		});
	function scheme_change(transparency){
		jQuery("#slider").slider('value', transparency);
		jQuery("#amount").val(transparency);
		var color = jQuery("input[name=_infoscreen_colorscheme]:checked").parent().css('background-color');
		var lastcomma = color.lastIndexOf(',');
		if (transparency != 100) {
			var newColor = color.slice(0,lastcomma) + "," + transparency / 100 + ")";
		} else {
			var newColor = color;
		}
		jQuery("input[name=_infoscreen_colorscheme]:checked").parent().css('background-color',newColor)
	}
	function slide_change(transparency) {
					var color = jQuery("input[name=_infoscreen_colorscheme]:checked").parent().css('background-color');
					var lastcomma = color.lastIndexOf(',');
					if (transparency != 100) {
						var newColor = color.slice(0,lastcomma) + "," + transparency / 100 + ")";
					} else {
						var newColor = color;
					}
					jQuery("input[name=_infoscreen_colorscheme]:checked").parent().css('background-color',newColor);
	}
		  </script>
<?php
}

/* When the post is saved, saves our custom data */
function infoscreen_colorscheme_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// OK, we're authenticated: we need to find and save the data
	$data = $_POST['_infoscreen_colorscheme'];
	if(get_post_meta($post_id, '_infoscreen_colorscheme') == '')
		add_post_meta($post_id, '_infoscreen_colorscheme', $data, true);

	elseif($data != get_post_meta($post_id, '_infoscreen_colorscheme', true))
	update_post_meta($post_id, '_infoscreen_colorscheme', $data);

	elseif($data == '')
	// 		delete_post_meta($post_id, '_infoscreen_layout', get_post_meta($post_id, '_infoscreen_layout', true));

	return $data;
}

/* When the post is saved, saves our custom data */
function infoscreen_transparency_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// OK, we're authenticated: we need to find and save the data
	$data = $_POST['_infoscreen_transparency'];
	if(get_post_meta($post_id, '_infoscreen_transparency') == '')
		add_post_meta($post_id, '_infoscreen_transparency', $data, true);

	elseif($data != get_post_meta($post_id, '_infoscreen_transparency', true))
	update_post_meta($post_id, '_infoscreen_transparency', $data);

	elseif($data == '')
	// 		delete_post_meta($post_id, '_infoscreen_layout', get_post_meta($post_id, '_infoscreen_layout', true));

	return $data;
}