if (typeof($) == 'undefined') $ = jQuery.noConflict(); // added by Imran@cselian.com to use in wordpress

$(document).ready(function() {
	$(".photos a").prettyPhoto({
		animation_speed:'normal',
		theme:'light_square',
		slideshow:3000,
		autoplay_slideshow: false,
		social_tools:''
	});
});
