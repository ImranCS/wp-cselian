if (typeof($) == 'undefined') $ = jQuery.noConflict(); // added by Imran@cselian.com to use in wordpress

function resizeTeamDetail($detl)
{
	$ht = $detl.parent().height() * .33;
	$detl.css('height', $ht + "px");
	if ($ht > $detl.width() * .27 )
	{
		$ht = $detl.width() * .27;
		$detl.css('height', $ht + "px");
	}
	$detl.css('padding-top', $ht * .66 + "px" );
	$detl.css('margin-top', $ht + "px" );
	$("#team-f", $detl).css('height', $ht / 3 + "px");
	$("#team-s", $detl).css('height', $ht / 3 + "px");
}

$(window).resize(function() {
  var bodyheight = $(window).height();
  $(".pg-tile-cont").each(function() { 
		$(this).css('min-height', bodyheight);
		$(this).css('height', bodyheight); // needed for safari
	});
  $(".singlepage").each(function() { $(this).css('min-height', bodyheight); });
  $(".brand-lnk").each(function() { $(this).css('min-height', bodyheight); });
  $("#cities .corner").each(function() {
		$poffs = $(this).parent().offset();
		$(this).css('left', ($poffs.left - 10) + 'px');
		$(this).css('top', ($poffs.top - 15) + 'px');
	});
  resizeTeamDetail($("#team-detail"));
});
$(document).ready(function(){
	$(window).trigger('resize');
});

// http://www.insitedesignlab.com/how-to-make-a-single-page-website/
$(document).ready(function() {
	function filterPath(string) {
		return string
			.replace(/^\//,'')
			.replace(/(index|default).[a-zA-Z]{3,4}$/,'')
			.replace(/\/$/,'');
	}
	$('#singlepagenav a').each(function() {
		$(this).click(function(e) {
			$('#singlepagenav li').each(function() { $(this).removeAttr('class'); });
			$(this).parent().attr('class', 'active');
			$target = $('[name=' + this.hash.slice(1) +']');
			var targetOffset = $target.offset().top;
			$('html, body').animate({scrollTop: targetOffset}, 1000);
			if (this.hash == '#team') showTeamTab(1);
			e.preventDefault(); // else will jump before beginning to scroll
		});
	});
	$('a[href=#home]').first().trigger('click');
});

/*! Smooth Scroll - v1.4.5 - 2012-07-22
* Copyright (c) 2012 Karl Swedberg; Licensed MIT, GPL */
