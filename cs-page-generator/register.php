<?php
/*
Plugin Name: Cselian Page Generator
Plugin URI: http://github.com/ImranCS/wp-cselian/wiki/Generator
Description: Build up a set of parent-child pages from a spreadsheet.
Version: 1.2
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

function csgen_pluginLink($links, $file)
{
	$plugin = plugin_basename(__FILE__);
	
	if ($file != $plugin)
		return $links;
	
	$link = content_url('plugins/' . plugin_basename(dirname(__FILE__)));
	$link = sprintf( '<a target="_new" href="%s">%s</a>', $link, __('Generate Pages') );
	return array_merge($links, array($link));
}

function csgen_editLink($page)
{
	$plugin = plugin_basename(__FILE__);
	$file = plugin_basename(dirname(__FILE__) . '/' . $page);
	$link = admin_url(sprintf('plugin-editor.php?file=%s&plugin=%s', $file, $plugin));
	echo $link;
}

global $wp_version;
if ( version_compare( $wp_version, '2.8alpha', '>' ) )
	add_filter( 'plugin_row_meta', 'csgen_pluginLink', 10, 2 );

?>
