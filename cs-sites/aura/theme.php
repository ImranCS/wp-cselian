<?php
/*
Based on storefront (woocommerce theme)
Adds facebook like widget (needs fbpage defined in config.php)
Adds favicon (wp_head)
*/
function storefront_credit() {
	?>
	<div class="site-info">
		&copy; 1994 - <?php echo date( 'Y' ) . ' ' . get_bloginfo( 'name' )?>.
		<br />Built by <a href="http://tg.cselian.com/" target="_blank">cselian</a>
		using <a href="http://www.woothemes.com/storefront" target="_blank">Storefront</a>
		and <a href="http://wordpress.org" target="_blank">Wordpress</a>.
	</div><!-- .site-info -->
	<?php
}

add_action( 'after_setup_theme', 'storefront_after_setup_theme');

function storefront_after_setup_theme()
{
	remove_action('storefront_header', 'storefront_secondary_navigation', 30);
	remove_action('storefront_header', 'storefront_header_cart', 60 );
}

add_action( 'storefront_header', 'cs_facebook', 45 );
function cs_facebook()
{ ?>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>
<div id="social" class="noprint site-search">
	<div class="fb-page"><a href="http://www.facebook.com/<?php echo cs_var('fbpage')?>" target="_blank">
		<img src="<?php echo cs_var('siteurl')?>fb.png" /></a></div>
	<div class="fb-like" data-href="http://www.facebook.com/<?php echo cs_var('fbpage')?>"
		data-send="true" data-layout="button_count" data-width="60" data-show-faces="true"></div>
</div>

<?php
}

add_action('wp_head', 'cs_favicon');
function cs_favicon()
{
	echo '<link rel="shortcut icon" href="' . cs_var('siteurl') . 'favicon.ico" />' . PHP_EOL;
}

?>
