function slide_init_value( sliderID , result_field , value ){
	if(arguments.length == 2){ var value = 80;}
	jQuery("#" + sliderID).slider({
		range: "min",
		min: 0,
		max: 100,
		value: value,
		slide: function( event, ui ) {
				jQuery( "#"+result_field ).val( ui.value );
			}
	});
	jQuery( "#"+result_field ).val( jQuery( "#"+sliderID ).slider( "value" ) );
	jQuery( "#"+sliderID).removeClass('ui-widget-content').addClass('ui-widget-content-slider-custom');
}