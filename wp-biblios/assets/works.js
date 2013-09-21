if (typeof($) == 'undefined') $ = jQuery.noConflict(); // added by Imran@cselian.com to use in wordpress

$(document).ready(function() {
	$(".data-slide .item").first().show();
	$(".data-slide .prev").click(function() {
		$sel = $(".data-slide .item:visible");
		$sel.hide();
		$nxt = $sel.prev();
		if ($nxt.length == 0) $nxt = $(".data-slide .item:last");
		$nxt.show();
	});
	$(".data-slide .next").click(function() {
		$sel = $(".data-slide .item:visible");
		$sel.hide();
		$nxt = $sel.next();
		if ($nxt.length == 0) $nxt = $(".data-slide .item:first");
		$nxt.show();
	});
});
