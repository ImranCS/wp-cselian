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

// Nice to have - a post id reseed using
// http://stackoverflow.com/a/5437720
// but wordpress doesnt use foreign keys in the first place

include_once 'functions.php';
include_once 'csa-scripts.php';

class CSAdmin
{
	public static $instance;
	
	function __construct()
	{
		self::$instance = $this;

		global $wp_version;
		if ( version_compare( $wp_version, '2.8alpha', '>' ) )
			add_filter( 'plugin_row_meta', array($this, 'plugin_link'), 10, 2 );

		add_action('admin_menu', array(&$this, 'register_admin'));
	}
	
	public static $reseedSlug = 'csadmin-reseed';
	public static $htmlSlug = 'csadmin-htmlgen';
	
	function register_admin()
	{
		// Made Other a plugin Multisite (if config found)
		add_submenu_page('tools.php', 'Reseed Posts', 'Reseed', 'manage_options', 
			self::$reseedSlug, array($this, 'pages_reseed'));

		add_submenu_page('tools.php', 'Generate Html for Pages', 'Html Gen', 'manage_options', 
			self::$htmlSlug, array($this, 'pages_htmlgen'));
	}
	
	function plugin_link($links, $file)
	{
		if ($file != plugin_basename(__FILE__)) return $links;
		
		$fmt = '<a target="_new" href="%s">%s</a>';
		$link = sprintf( $fmt, cs_var('adm-base') . '/ms-plugins.php', __('MS Plugins') );
		return array_merge($links, array($link));
	}
	
	function pages_reseed()
	{
		include 'reseed.php';
	}

	function pages_htmlgen()
	{
		include 'htmlgen.php';
	}
}
new CSAdmin();
?>
