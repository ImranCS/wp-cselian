<?php
function csc_var($name, $val = null)
{
	global $cscomp;
	if (!isset($cscomp)) $cscomp = array();
	if ($val != null)
		$cscomp[$name] = $val;
	else
		return isset($cscomp[$name]) ? $cscomp[$name] : false;
}

function csc_script($file, $echo = 1, $intl = 0)
{
	$name = 'csc-' . $file;
	$file = csc_var('base') . ($intl ? '/intl/' : '/js/') . $file;
	wp_enqueue_script( $name, $file, array( 'jquery') );
	$res = '
	<script src="'. $file . '" type="text/javascript"></script>';
	if ($echo) echo $res; else return $res;
}

function csc_jqScript($script)
{
	echo sprintf('<script type="text/javascript">
jQuery(document).ready(function($) {
%s
});
</script>', $script);
}

function csc_style($file)
{
	echo sprintf('<link rel="stylesheet" href="%s/%s.css" type="text/css" />', csc_var('base'), $file);
}

csc_var('megamenu', $_SERVER['HTTP_HOST'] == 'spanda.lailaborrie.com');
csc_var('attitude', csc_var('megamenu'));
csc_var('nocss', $_SERVER['HTTP_HOST'] == 'cselian.com');
csc_var('base', content_url('plugins/' . plugin_basename(dirname(__FILE__))));
?>
