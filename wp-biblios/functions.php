<?php
function cs_var($name, $val = null)
{
	global $cscore;
	if (!isset($cscore)) $cscore = array();
	if ($val != null)
		$cscore[$name] = $val;
	else
		return isset($cscore[$name]) ? $cscore[$name] : false;
}

function cs_work($id, $data = null)
{
	$file = sprintf('%s/%s.dat', cs_var('bib-data'), $id);
	if ($data != null)
	{
		file_put_contents($file, serialize($data));
	}
	else
	{
		if (!file_exists($file))
			return array('type' => 'book', 'fol' => 'books/{name}');
		return unserialize(file_get_contents($file));
	}
}

function _nl($txt, $br = 0)
{
	echo $txt . '
' . ($br ? '<br />' : '');
}

function cs_work_read($id)
{
	$key = 'work-' . $id;
	$wk = cs_var($key);
	if (!$wk)
	{
		$wk = cs_work($id);
		if ($wk['fol'] != '')
		{
			$cfg = sprintf('%s/%s/config.php', cs_var('bib-data'), $wk['fol']);
			$wk['cfgFile'] = $cfg;
			$wk['contentFile'] = sprintf('%s/%s/content.php', cs_var('bib-data'), $wk['fol']);;
			$wk['config'] = include $cfg;
		}
	}
	return $wk;
}

function cs_work_get($what)
{
	if (!is_single()) return false;
	$id = get_the_ID();
	if (get_post_type($id) != 'work') return false;
	if ($what == 'bool') {
		return 1;
	} else if ($what == 'author') {
		$terms = wp_get_post_terms($id, 'work_author', 0);
		return $terms[0];
	}
	$wk = cs_work_read($id);
	if ($what == 'workType') {
		$type = cs_var('workTypes');
		return $type[$wk['type']];
	} else if ($what == 'hasnav') {
		return $wk['fol'] != '';
	} else if ($what == 'hascontent') {
		return WorkNav::nodeOrSearch();
	} else if ($what == 'content') {
		include 'csb-' . (WorkNav::search() ? 'search' : 'content') . '.php';
	} else if ($what == 'sidebar') {
		include 'csb-sidebar.php';
	} else if ($what == 'header') {
		include 'csb-header.php';
	} else {
		throw new Exception($what . ' not supported by cs_work_get');
	}
}

include_once 'inc/CHtml.php';
cs_var('bib-base', content_url('plugins/' . plugin_basename(dirname(__FILE__))));
cs_var('bib-data', WP_CONTENT_DIR . '/data');
cs_var('charset', 'iso-8859-1'); // utf8 not supported
cs_var('workTypes', array('book', 'poem', 'short story', 'letter'));
?>
