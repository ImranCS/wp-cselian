<?php
function csc_menu_head() { csc_style('black'); }
function csc_menu_foot(){ csc_jqScript("$('.mega-menu').dcMegaMenu({rowItems: '1',speed: 'fast',effect: 'fade'});
	$('.mega-menu .page-item-13').parent().css('float', 'left');
	$('.mega-menu .page-item-52').parent().css('float', 'left');
	$('.mega-menu .page-item-56').parent().css('float', 'left');
	$('.mega-menu .page-item-180').parent().css('float', 'left');
"); }

function csc_listPagesArgs($args)
{
	$args['exclude'] = '2';
	return $args;
}

function csc_listPages($mnu)
{
	$login = '<li class="loginout">' . wp_loginout(null, false) . '</li>';
	$mnu = str_replace('<div class="root"><ul>', '<div class="root"><ul class="mega-menu">', $mnu);
	$mnu = str_replace('</ul></div>', $login . '</ul></div>', $mnu);
	return $mnu;
}

// gen = 1 to test the implementation locally
function csc_menu($gen = 0)
{
	include_once 'site-nav.php';
	if ($gen)
	{
		$genFol = '../cs-page-generator/';
		include_once $genFol . 'generator.php';
		$gen = new CSPageGenerator();
		$gen->showId = 0; // breaks megamenu
		$gen->dataFile = $genFol . $gen->dataFile;
		$gen->readNext();
	}
	
	$nl = '
	';
	$nl2 = $nl . '	';
	$nl3 = $nl2 . '	';
	$eo1 = '</ul>';
	$eo2 = '</ul>' . $nl . '</li>' . $nl;
	$eo3 = '</li>' . $nl2;
	$res = '<ul class="mega-menu">' . $nl;
	$fmt = '<li class="page-item-%s">%s%s';
	if (csc_var('home'))
	{
		$lnk = csc_page_link($gen, csc_var('home'));
		$res .= sprintf($fmt, csc_var('home'), $lnk, '</li>');
	}

	$pages = csc_var('pages');
	foreach ($pages as $id => $sublinks)
	{
		$res .= sprintf($fmt, $id, csc_page_link($gen, $id), $nl);
		$res .= '<ul>' . $nl2;
		
		if (count($sublinks) == 0)
		{
			if (!$gen)
			{
				$res .= wp_list_pages(array_merge(array('child_of' => $id, 'echo' => 0, 'title_li' => 0)));
				$res .= $eo2;
				continue;
			}

			$all = 1;
			$sublinks = csc_pages_find($gen, array('child_of' => $id));
		}
		else
		{
			$all = 0;
		}
		
		foreach ($sublinks as $itm=>$args)
		{
			if ($all) $lnk = $gen->itemText($args);
			else $lnk = is_numeric($itm) ? csc_page_link($gen, $itm) : sprintf('<a href="#">%s</a>', $itm);
			
			$res .= sprintf($fmt, $itm, $lnk, $nl2);
			
			if (!$gen)
			{
				$res .= '<ul>' . $nl2;
				csc_flatten_exclude(&$args);
				$res .= wp_list_pages(array_merge($args, array('echo' => 0, 'title_li' => 0, 'depth' => 1)));
				$res .= '</ul>' . $nl2;
				$res .= $eo3;
				continue;
			}

			if ($all) $gcs = csc_pages_find($gen, array('child_of' => $args['id']));
			else $gcs = csc_pages_find($gen, $args);
			
			if (count($gcs) > 0)
			{
				$res .= '<ul>' . $nl2;
				foreach ($gcs as $gc)
					$res .= '	' . sprintf($fmt, $gc['id'], $gen->itemText($gc), '</li>' . $nl2);
				$res .= '</ul>' . $nl2;
			}
			
			$res .= $eo3;
		}
		$res .= $eo2;
	}
	$res .= $eo1;
	return $res;
}

function csc_page_link($gen, $id)
{
	return !$gen ? csc_link_shortcode(array('type' => 'post', 'id' => $id)) : $gen->itemText($gen->existing[$id]);
}

function csc_flatten_exclude(&$args)
{
	if (!isset($args['exclude'])) return;
	if (!is_array($args['exclude'])) return;
	$args['exclude'] = implode(',', $args['exclude']);
}

function csc_pages_find($gen, $filter)
{
	$out = array();
	$exclude = isset($filter['exclude']) ? $filter['exclude'] : array();
	if (!is_array($exclude)) $exclude = array($exclude);
	foreach ($gen->existing as $itm)
	{
		if ($itm['parent'] != $filter['child_of']) continue;
		$no = array_search($itm['id'], $exclude) !== false;
		if ($no) continue;
		$out[] = $itm;
	}
	return $out;
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
{
	include_once '../../../wp-config.php'; //wp-content/plugins/cs-companion/
	include_once 'csc-functions.php';
	$usegen = function_exists('is_localhost') || $_GET['gen'] == 1;
	echo '<html><head><title>Nav - Cselian WP Companion</title>';
	csc_style('black'); csc_script('jquery.js', 1, 1);
	csc_script('jquery.hoverIntent.js'); csc_script('jquery.dcmegamenu.1.3.2.js');
	echo '</head><body>';
	echo csc_menu($usegen);
	csc_menu_foot();
	echo '</body></html>';
	die();
}

add_action('wp_head', 'csc_menu_head');
add_action('wp_footer', 'csc_menu_foot');
add_filter('wp_page_menu', 'csc_listPages');
add_filter('wp_page_menu_args', 'csc_listPagesArgs');
wp_enqueue_script( 'csc-hoverIntent', csc_var('base') . '/js/jquery.hoverIntent.js', array( 'jquery') );
wp_enqueue_script( 'csc-megamenu', csc_var('base') . '/js/jquery.dcmegamenu.1.3.2.js', array( 'jquery') );
?>
