<?php require_once("../../../wp-config.php"); //wp-content/plugins/cs-admin/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Multisite Plugins - Cselian Admin</title>
<link rel="stylesheet" href="attitude.css" type="text/css">
<style type="text/css">
<!--
body { background-color: #fff; }
table { border-collapse: collapse; }
div { float: left; margin-right: 30px; }
h2 { margin: 0 0 15px 0; }
th, td { padding: 2px 4px 2px 4px; text-align: left; }
a { color: #33a; }
//-->
</style>
  </head>
  <body>
<div id="content">
<h2>Multisite Plugins</h2>
<?php

// from http://plugins.trac.wordpress.org/browser/wp-list-plugins/trunk/wp-list-plugins.php
require_once (ABSPATH . 'wp-admin/includes/plugin.php');

function csa_empty($v) { return ''; }

global $all;
$all = get_plugins();
global $unused;
$unused = array_map('csa_empty', array_merge($all, array()));

function csa_remove_used($ofSite)
{
	global $unused;
	foreach ($ofSite as $ix=>$key)
	{
		if (isset($unused[$key])) unset($unused[$key]);
	}
}

function csa_r($plugins)
{
	global $all;
	$r = '';
	foreach ($plugins as $value)
	{
		if (!isset($all[$value])) $r .= $value;
		else $r .= sprintf('<a href="/#%s">%s</a>', $value, $all[$value]['Name']);
		$r .= '<br/>
';
	}
	return $r;
}

include_once 'ms-config.php';
echo '<table border="1">';
$fmt = '<tr><td>%s</td><td>%s</td><td>%s</td></tr>
';
$th = str_replace('<td', '<th', str_replace('</td>', '</th>', $fmt));
echo sprintf($th, 'Site', 'Home', 'Plugins');
foreach ($csaSites as $url => $site)
{
	if (is_array($site)) { $sfx = $site[1]; $site = $site[0]; } else $sfx = '';
	$sql = sprintf("select option_value from %s%s.wp%s_options where option_name = 'active_plugins'", $csaDbName, $site, $sfx);
	$plugins = $wpdb->get_results($sql);
	$plugins = $plugins[0]->option_value;
	$plugins = unserialize($plugins);
	csa_remove_used($plugins);
	echo sprintf($fmt, str_replace('wp_', '', $site), $url, csa_r($plugins));
}
echo '</table></div>';

echo '<div><h2>Unused</h2>' . csa_r(array_keys($unused));

?>
</div>
<br /><br /><br />
</body>
</html>
