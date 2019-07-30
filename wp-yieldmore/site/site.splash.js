if (typeof($) == 'undefined') $ = jQuery.noConflict();

$(document).ready(function() {
	$("#splash").click(function(e) {
		$('#splash').hide();
		$('#page').show();
		$(window).trigger('resize'); //so the menu repositions itself
	});
	$('.custom-header').click(function(e){
		$('#page').hide();
		$('#splash').show();
	});
	if (window.showSplash && location.hash != '#nosplash')
		$('.custom-header').trigger('click');
});
