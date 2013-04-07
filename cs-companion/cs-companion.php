<?php
/*
Plugin Name: Cselian WP Companion Plugin
Plugin URI: http://github.com/ImranCS/wp-cselian/wiki/Companion
Description: Megamenu with custom nav structure. Shortcodes for links to posts, documents, image with links, gallery and html snippets. Widgets for Related links (parent, sibling, children, category posts) and Font resizer.
Version: 1.5
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


include_once 'csc-functions.php';

if (csc_var('megamenu'))
	include_once 'csc-menu.php';
include_once 'csc-theme.php';
include_once 'csc-sidebar.php';
include_once 'csc-shortcodes.php';
include_once 'csc-fontsize.php';

function csc_sampleLink($links, $file)
{
	$plugin = plugin_basename(__FILE__); // this has to be in the plugin main file
	
	if ($file != $plugin)
		return $links;

	$link = sprintf( '<a target="_new" href="%s/samples.php">%s</a>', csc_var('base'), __('Shortcode Samples') );
	$link2 = sprintf( '<a target="_new" href="%s/csc-menu.php">%s</a>', csc_var('base'), __('Nav') );
	$link3 = sprintf( '<a target="_new" href="%s/csc-menu.php?gen=1">%s</a>', csc_var('base'), __('Nav (from tsv)') );
	return array_merge($links, array($link, $link2, $link3));
}

global $wp_version;
if ( version_compare( $wp_version, '2.8alpha', '>' ) )
	add_filter( 'plugin_row_meta', 'csc_sampleLink', 10, 2 );
?>
