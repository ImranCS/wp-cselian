jQuery(document).ready(function($) {
	var COOKIE_NAME = "fontresizer";
	var fontsize = "13px"; 
	
	// if cookie exists set font size to saved value, otherwise create cookie
	if($.cookie(COOKIE_NAME)) fontsize = $.cookie(COOKIE_NAME);
	//set initial font size for this page view:
	$("body").css("font-size", fontsize);
	//set up appropriate class on font resize link:
	$("#accessibility a").removeClass("current");
	$("#accessibility .size-" + fontsize).addClass("current");

	// large font-size link:
	$("#accessibility a").bind("click", function() {
		fontsize = $(this).css("font-size");
		$("body").css("font-size", fontsize);
		$("#accessibility a").removeClass("current");
		$("#accessibility .size-" + fontsize).addClass("current");
		$.cookie(COOKIE_NAME, fontsize);
		return false;	
	});
});
