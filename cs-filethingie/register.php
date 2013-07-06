<?php
/*
Plugin Name: Cselian Filethingie
Plugin URI: https://github.com/ImranCS/wp-cselian/filethingie
Description: Filethingie wrapper for wordpress
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

class CSFileThingie
{
	public static $instance;
	
	public static $ftSlug = 'cs-filemgr';
	
	public static $link;
	
	public static $cscRequired = '<p style="font-size: x-large; color: #f66;">Please install plugin cs-companion</p>';
	
	function __construct()
	{
		self::$instance = $this;

		self::$link = content_url('plugins/' . plugin_basename(dirname(__FILE__)));

		global $wp_version;
		if ( version_compare( $wp_version, '2.8alpha', '>' ) )
			add_filter( 'plugin_row_meta', array($this, 'plugin_link'), 10, 2 );

		add_action('admin_menu', array(&$this, 'register_admin'));
		add_action('plugins_loaded', array(&$this, 'check_config'));
	}

	function check_config()
	{
		include_once 'siteconfig.php';
	}
	
	function plugin_link($links, $file)
	{
		if ($file != plugin_basename(__FILE__)) return $links;
		
		$fmt = '<a %shref="%s">%s</a>';
		$link = 'File Manager: '
			. sprintf( $fmt, 'target="_new" ', self::$link, 'full page' ) . ' / '
			. sprintf( $fmt, '', 'upload.php?page=' . self::$ftSlug, 'admin page' );
		return array_merge($links, array($link));
	}
	// TODO: set folder in cookie

	function register_admin()
	{
		add_submenu_page('upload.php', 'File Manager', 'File Manager', 'manage_options', 
			self::$ftSlug, array($this, 'filethingie_frame'));
	}

	function filethingie_frame()
	{
		include 'iframe.php';
	}
}

new CSFileThingie();
?>
