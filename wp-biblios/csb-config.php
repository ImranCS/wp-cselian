<?php
class WorkConfig
{
	public static $optional = array('subtype', 'renderer', 'formats');
	
	function read($id, $raw)
	{
		$cfg = self::parse($raw);
		$op = array(
			'type' => $cfg['type'],
			'fol' => $cfg['fol'],
			'contentFile' => sprintf('%s/%s/content.php', cs_var('bib-data'), $cfg['fol']),
			'cfgRaw' => $raw,
			'cfgSummary' => sprintf('%s (%s)', $cfg['type'], $cfg['fol'] == '' ? 'simple' : self::edit_link($id))
		);

		unset($cfg['fol']); unset($cfg['type']);
		$op['config'] = $cfg;
		
		$ok = !isset($cfg['errors']);
		$op['cfgOk'] = $ok;
		if (!$ok)
			$op['cfgError'] = sprintf('<div class="work-error"><em>Config Error</em><br/> %s the errors: <ol><li>%s</li></ol></div>', self::edit_link($id, 'edit and fix') , implode('</li><li>', $cfg['errors']) );
		
		return $op;
	}
	
	function fol($id, $rel, $url = 0)
	{
		$fol = cs_work_read($id, 'fol');
		return sprintf('%s/%s/%s', $url ? content_url('data') : cs_var('bib-data'), $fol, $rel);
	}
	
	function edit_link($id, $txt = 'config')
	{
		return CHtml::link($txt, WorkNav::admin($id));
	}
	
	function dirLink($txt = 'Works')
	{
		$id = $_SERVER['HTTP_HOST'] == 'localhost' ? 12 : 13;
		$url = get_permalink($id);
		return $txt == 'url' ? $url : CHtml::link($txt, $url);
	}
	
	function types($how = 'display', $key = 'orig')
	{
		$cache = cs_var('workTypesCache');
		if (!$cache)
		{
			$types = get_option('work_types');
			if (!$types) $types = str_replace('	', PHP_EOL, 'book	poem	short story	letter	quotes');
			
			$cache = array();
			$types = explode(PHP_EOL, $types);
			foreach ($types as $type)
				$cache[$type] = $type;
			
			cs_var('workTypesCache', $cache);
		}
		
		$op = array();
		foreach ($cache as $type)
			$op[self::formatType($type, $key)] = self::formatType($type, $how);
		//if ($how !== 'display') echo '<br />Types: ' . $how . ' -> ' . print_r($op, 1);
		return $op;
	}
	
	function formatType($t, $how = 'display')
	{
		if ($how === 0)
			return 0;
		else if ($how == 'display')
			return  ucwords($t);
		else if ($how == 'slug')
			return str_replace(' ', '-', $t);
		else if ($how == 'orig')
			return $t;
		else
			throw new exception('Unknows format for type: ' . $how);
	}
	
	function import($cfg, $save = 1)
	{
		include_once 'functions-dep.php';
		// TODO: Remove after import
		$cfg = cs_work_import($id);
		cs_work($id, $cfg);
	}
	
	function merge($raw, $new)
	{
		$lines = explode(PHP_EOL, self::sanitize($raw));
		$op = array();
		foreach ($lines as $line) {
			$l = explode(':', $line, 2);
			if (isset($new[$l[0]])) {
				$line = $l[0]. ':' . $new[$l[0]];
				unset($new[$l[0]]);
			}
			$op[] = $line;
		}
		foreach ($new as $k=>$v)
			$op[] = $k. ':' . $v;
		return implode(PHP_EOL, $op);
	}
	
	function sanitize($raw)
	{
		$raw = str_replace(PHP_EOL, chr(10), $raw);
		return str_replace(chr(10), PHP_EOL, $raw);
	}

	function parse($cfg)
	{
		// limit to 2 because headings & titles may have : in them
		$lines = explode(PHP_EOL, self::sanitize($cfg));
		$op = array();
		foreach ($lines as $line) {
			if ($line == '') continue; // TODO: itemFormat of savithri...
			$l = explode(':', $line, 2);
			if (count($l) == 1) {
				$op[$l[0]] = '';
			} else if (!self::parse_title($l, &$op)) {
				$op[$l[0]] = trim($l[1]); // EOL differs windows to linux?
			}
		}
		return $op;
	}
	
	function parse_title($l, &$op)
	{
		if ($l[0] != 'title' && $l[0] != 'subtitle') return 0;
		if (!isset($op['titles'])) $op['titles'] = array(null);
	
		if ($l[0] == 'subtitle') {
			$last = count($op['titles']);
			if ($last == 0) { // test error display: || $l[1] == 'The World-Stair'
				self::parse_error(&$op, sprintf('Subtitle "%s" cannot preceed title', $l[1]));
				return 1;
			}
			if (!is_array($op['titles'][$last - 1]))
				$op['titles'][$last - 1] = array($op['titles'][$last - 1]);
			$op['titles'][$last - 1][] = $l[1];
		} else {
			$op['titles'][] = $l[1];
		}
		return 1;
	}
	
	function parse_error(&$op, $error)
	{
		if (!isset($op['errors'])) $op['errors'] = array();
		$op['errors'][] = $error;
	}
}
?>
