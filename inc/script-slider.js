jQuery(document).ready(function($){
  $( "#slider" ).slider({
    range: "min",
    min: 0,
    max: 100,
    value: 80,
    slide: function( event, ui ) {
      $( "#amount" ).val( ui.value );
    }
  });
  $( "#amount" ).val( $( "#slider" ).slider( "value" ) );
});
