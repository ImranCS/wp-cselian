<?php
/*
Plugin Name: Cselian Admin Plugin
Plugin URI: http://github.com/ImranCS/wp-cselian/wiki/Admin
Description: WP Admin tools. Show plugins in all multisites.
Version: 1.1
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

function csadm_pluginLink($links, $file)
{
	$plugin = plugin_basename(__FILE__);
	
	if ($file != $plugin)
		return $links;
	
	$link = content_url('plugins/' . plugin_basename(dirname(__FILE__) . '/ms-plugins.php'));
	$link = sprintf( '<a target="_new" href="%s">%s</a>', $link, __('MS Plugins') );
	return array_merge($links, array($link));
}

global $wp_version;
if ( version_compare( $wp_version, '2.8alpha', '>' ) )
	add_filter( 'plugin_row_meta', 'csadm_pluginLink', 10, 2 );
?>
