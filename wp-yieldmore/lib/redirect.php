<?php

function ym_get_slug()
{
	$r = $_SERVER['REQUEST_URI'];
	$s = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	$slug = substr($r, strlen($s));
	return $slug;
}

function ym_redirect()
{
	$key = untrailingslashit(ym_get_slug());
	$links = cs_var('redirects');
	if (!$links || !isset($links[$key])) return;
	header("Location: " . get_home_url() . '/' . $links[$key]);
	exit;
}

ym_redirect();
//add_filter('pre_handle_404', 'ym_redirect', 200, 2);
?>