jQuery(document).ready(function() {
  jQuery("#slideshow div:gt(0)").hide();
  setInterval(function() 
  {
    jQuery('#slideshow > div:first').fadeOut(900) // give 100ms else both are seen together
      .next().fadeIn(1000)
      .end().appendTo('#slideshow');
  },  3000);
});
