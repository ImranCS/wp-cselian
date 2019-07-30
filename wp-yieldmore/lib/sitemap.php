<?php
function ym_sitemap($a, $content = null)
{
	return wp_nav_menu( array(
		'theme_location' => 'top',
		'menu_id'        => 'sitemap',
		'echo'           => false,
		//'items_wrap' => cs_add_buttons_to_menu()
	) );
}

add_shortcode('sitemap', 'ym_sitemap');
?>
