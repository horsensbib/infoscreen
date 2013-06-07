jQuery(document).ready(function($){
	$('.my-color-field').wpColorPicker();
});

var timeout	= 500;
var closetimer	= 0;
var ddmenuitem	= 0;
var currentFont;

//open hidden layer
function mopen(id)
{	
	// cancel close timer
	mcancelclosetime();

	// close old layer
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';

	// get new layer and show it
	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.visibility = 'visible';

}
//close showed layer
function mclose()
{
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
}

//go close timer
function mclosetime()
{
	closetimer = window.setTimeout(mclose, timeout);
}

//cancel close timer
function mcancelclosetime()
{
	if(closetimer)
	{
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

//close layer when click-out
document.onclick = mclose; 

function getCurrentFont(font)
{	
	document.getElementById('current_font').innerHTML = font;
	document.getElementById('current_font').style.fontFamily = font;
	document.getElementById('default_font').value = font;
}