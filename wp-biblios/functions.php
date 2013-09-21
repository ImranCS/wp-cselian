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
	if ($data != null)
	{
		if (is_array($data))
			$data = WorkConfig::merge(cs_work($id), $data);
		else
			$data = WorkConfig::sanitize($data);
		WorkCache::clear();
		update_post_meta($id, 'workConfig', $data);
	}
	else
	{
		$cfg = get_post_meta($id, 'workConfig', true);
		if ($cfg == '') // OR bib-redo-import
			$cfg = WorkConfig::import($id);
		return $cfg;
	}
}

function cs_work_read($id, $retKey = null, $subKey = null)
{
	$key = 'work-' . $id;
	$wk = cs_var($key);
	if (!$wk)
	{
		$wk = cs_work($id);
		if ($wk['fol'] != '')
			$wk = WorkConfig::read($id, $wk);
		cs_var($key, $wk); // cache in memory
	}
	if ($retKey == null) return $wk;
	if ($subKey != null) return isset($wk[$subKey][$subKey]) ? $wk[$subKey][$subKey] : false;
	return isset($wk[$retKey]) ? $wk[$retKey] : false;
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
	} else if (array_search($what, WorkConfig::$optional) !== false) {
		return isset($wk['config'][$what]) ? $wk['config'][$what] : false;
	} else {
		throw new Exception($what . ' not supported by cs_work_get');
	}
}

cs_var('bib-base', content_url('plugins/' . plugin_basename(dirname(__FILE__))));
cs_var('bib-data', WP_CONTENT_DIR . '/data');
cs_var('charset', 'iso-8859-1'); // utf8 not supported
cs_var('workTypes', WorkConfig::types());
?>
