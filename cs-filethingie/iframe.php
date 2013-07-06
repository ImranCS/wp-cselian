<?php
if (function_exists('csc_jqScript')) {
	csc_jqScript(sprintf('
	$(window).resize(function() {
		var bodyheight = $(window).height();
		$("#%s").css("min-height", bodyheight - $("#%s").offset().top);
	});
	$(window).trigger("resize");',
		CSFileThingie::$ftSlug, CSFileThingie::$ftSlug));
} else {
	echo CSFileThingie::$cscRequired;
}

echo sprintf('<iframe id="%s" src="%s" %s></iframe>',
	CSFileThingie::$ftSlug, CSFileThingie::$link,
	'width="100%" height="100%"');
?>
<style type="text/css">
<!--
#wpfooter { display: none; }
//-->
</style>