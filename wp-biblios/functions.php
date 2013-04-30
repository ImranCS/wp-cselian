<?php
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
			$wk['contentFile'] = sprintf('%s/%s/content.php', cs_var('bib-data'), $wk['fol']);
			if ($wk['cfgOk'] = file_exists($cfg))
				$wk['config'] = include $cfg;
			else
				$wk['cfgError'] = sprintf('<div class="work-error"><em>Missing Config!</em><br/> Go the the %s folder and add %s/config.php, or %s the folder correctly.</div>', 
					CHtml::link('data', content_url('/data/'), array('target' => '_blank')), $wk['fol'], 
					CHtml::link('edit', get_edit_post_link($id, '')));
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
		_nl('<div class="widget bib-nav">');
		WorkMenu::render($id, $wk);
		_nl('</div>');
	} else if ($what == 'header') {
		include 'csb-header.php';
	} else {
		throw new Exception($what . ' not supported by cs_work_get');
	}
}

cs_var('bib-base', content_url('plugins/' . plugin_basename(dirname(__FILE__))));
cs_var('bib-data', WP_CONTENT_DIR . '/data');
cs_var('charset', 'iso-8859-1'); // utf8 not supported
cs_var('workTypes', array('book', 'poem', 'short story', 'letter'));
?>
