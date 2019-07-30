<?php
/*
Plugin Name: YieldMore WP Companion
Plugin URI: https://github.com/ImranCS/wp-cselian/wp-yieldmore
Description: An AIO wordpress plugin solution with multi-library and theme customizations
Version: 1.0
Author: <a href="mailto:imran@cselian.com">Imran Ali Namazi</a>
*/

/**
 * LICENSE: Free to use but recognition due.
 *
 * Copyright (c) <2013> - <2018> <Imran Ali Namazi>
 *
 * @author		Imran Ali Namazi <imran@cselian.com>
 * @copyright 2013-2018 Imran Ali Namazi
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

function cs_site_var($name) {
	$siteName = cs_var('siteName');
	if (!$siteName) return cs_var($name);
	$vars = cs_var('perSiteVars');
	if (!is_array($vars)) return cs_var($name);
	$siteVars = $vars[$siteName];
	return isset($siteVars[$name]) ? $siteVars[$name] : cs_var($name); //falls back to network override
}

function ym_init()
{
	$fol = 'site';

	cs_var('base', ABSPATH . 'wp-content/plugins/wp-yieldmore/' . $fol . '/');
	cs_var('url', content_url('plugins/wp-yieldmore/' . $fol . '/'));
	cs_var('assets-url', content_url('plugins/wp-yieldmore/assets/'));

	$cfgFile = cs_var('base') . 'config.php';
	if (file_exists($cfgFile))
	{
		$cfg = include $cfgFile;
		foreach ($cfg as $key=>$value)
			cs_var($key, $value);
	}

	if (file_exists(cs_var('base') . 'styles.css') || file_exists(cs_var('base') . 'site.js'))
	{
		add_action( 'wp_enqueue_scripts', 'ym_scripts', 20);
		wp_register_style('ym-site', cs_var('url') . 'styles.css');
		wp_register_script('ym-site', cs_var('url') . 'site.js');
	}

	if (file_exists(cs_var('base') . 'functions.php') )
		require_once(cs_var('base') . 'functions.php');

	if (is_multisite() && cs_var('siteNames')) {
		$site = get_current_blog_id();
		$names = cs_var('siteNames');
		cs_var('siteName', $names[$site]['name']);
	}

	$libs = scandir(cs_var('base') . '../lib');
	foreach ($libs as $lib)
	{
		if ($lib == '.' || $lib == '..') continue;
		include_once 'lib/' . $lib;
	}
}

function ym_scripts()
{
	if (file_exists(cs_var('base') . 'styles.css') )
		wp_enqueue_style('ym-site');
	if (file_exists(cs_var('base') . 'site.js') ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('ym-site');
	}
}

ym_init();
?>
