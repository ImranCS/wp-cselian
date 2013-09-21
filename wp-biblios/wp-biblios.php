<?php
/*
Plugin Name: Cselian Biblios
Plugin URI: http://github.com/ImranCS/wp-cselian/wiki/Biblios
Description: Powers the site b.cselian.com - Creates post type work, adds editor for settings, nav and presents content in book form.
Version: 1.3
Author: <a href="mailto:imran@cselian.com">Imran Ali Namazi</a>
Author URI: http://cselian.com/blog/about
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

include_once 'csb-config.php'; // used in functions
include_once 'functions.php';
cs_var('bib-file', __FILE__);
include_once 'csb-work.php';
include_once 'csb-cache.php';
include_once 'csb-nav.php';
include_once 'csb-menu.php';
include_once 'csb-widgets.php';
include_once 'csb-shortcodes.php';
?>
