(function( $ ) {
    if (!$('#menu-top-menu').length) return;
    $('#sites-li').show().insertBefore('#menu-top-menu li:first');
})( jQuery );