<?php
/*
Plugin Name: Cselian Multisites
Plugin URI: https://github.com/ImranCS/wp-cselian/cs-sites
Description: A place for per-site files for theme control and configuring other cselian plugins.
Version: 1.0
Author: <a href="mailto:imran@cselian.com">Imran Ali Namazi</a>
*/

/**
 * LICENSE: Free to use but recognition due.
 *
 * Copyright (c) <2013> <Imran Ali Namazi>
 *
 * @author		Imran Ali Namazi <imran@cselian.com>
 * @copyright 2013 Imran Ali Namazi
 * @license	 The CS Atribution License
 * }}}
 */

if (!function_exists('cs_var')) {
function cs_var($name, $val = null)
{
	global $cscore;
	if (!isset($cscore)) $cscore = array();
	if ($val != null)
		$cscore[$name] = $val;
	else
		return isset($cscore[$name]) ? $cscore[$name] : false;
} }

function cs_is_not_fol($fol)
{
	if ($fol == '.' || $fol == '..' || $fol[0] == '_') return 1;
	return '' != pathinfo($fol, PATHINFO_EXTENSION);
}

function cssites_init()
{
	$dom = $_SERVER['HTTP_HOST'];
	cs_var('baseurl', 'http://' . $dom);

	$fols = scandir(dirname(__FILE__));
	foreach ($fols as $fol)
	{
		if (cs_is_not_fol($fol)) continue;
		$cfgFile = $fol . DIRECTORY_SEPARATOR . 'config.php';
		$domainIds = 1;
		$doms = include $cfgFile;
		if (array_search($dom, $doms) === false) continue;

		unset($domainIds);
		$cfg = include $cfgFile;

		foreach ($cfg as $key=>$value)
			cs_var($key, $value);

		cs_var('sitebase', ABSPATH . 'wp-content/plugins/cs-sites/' . $fol . '/');
		cs_var('siteurl', content_url('plugins/cs-sites/' . $fol . '/'));

		global $wp_version;
		if (file_exists(cs_var('sitebase') . 'styles.php') && version_compare( $wp_version, '2.8alpha', '>' ) )
			add_filter( 'plugin_row_meta', 'css_admin_links', 10, 2 );

		if (file_exists(cs_var('sitebase') . 'styles.css') )
		{
			wp_register_style('cs-site', cs_var('siteurl') . 'styles.css');
			wp_enqueue_style('cs-site');
		}

		if (file_exists(cs_var('sitebase') . 'functions.php') )
			require_once(cs_var('sitebase') . 'functions.php');

		if (file_exists(cs_var('sitebase') . 'theme.php') )
			require_once(cs_var('sitebase') . 'theme.php');

		break;
	}

	$libs = scandir(cs_var('sitebase') . '../_lib');
	foreach ($libs as $lib)
	{
		if ($lib == '.' || $lib == '..') continue;
		include_once '_lib/' . $lib;
	}
}

function css_admin_links($links, $file)
{
	$plugin = plugin_basename(__FILE__); // this has to be in the plugin main file
	if ($file != $plugin) return $links;
	if (file_exists(cs_var('sitebase') . 'styles.php'))
		$links[] = sprintf('<a target="_new" href="%s">%s</a>', cs_var('siteurl') . 'styles.php', ucfirst(cs_var('sitecode')) . ' Styles');
	if (file_exists(cs_var('sitebase') . 'pages.php'))
		$links[] = sprintf('<a target="_new" href="%s">%s</a>', cs_var('siteurl') . 'pages.php', 'Pages');
	return $links;
}

cssites_init();
?>
