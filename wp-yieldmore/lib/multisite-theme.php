<?php
$siteName = cs_var('siteName');
if (file_exists(cs_var('base') . $siteName . '.css')) {
	wp_register_style('ym-site-name', cs_var('url') . $siteName . '.css');
	add_action( 'wp_enqueue_scripts', 'ym_site_scripts', 20);
}

function ym_site_scripts() {
	wp_enqueue_style('ym-site-name');
}

?>
