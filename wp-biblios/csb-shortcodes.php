<?php
class WorksShortcodes
{
	function init()
	{
		$cls = get_class();
		add_shortcode('tab', array($cls, 'do_tab'));
		add_shortcode('works', array($cls, 'do_works'));
		add_shortcode('work', array($cls, 'do_work'));
	}
	
	function do_tab($a, $content = null)
	{
		if ($a[0] == 'start') {
			CSScripts::tabber();
			_nl('<div class="tabber">');
		} else if ($a[0] == 'end') {
			_nl('</div>');
		} else {
			$file = WorkConfig::fol(get_the_ID(), $a['name'] . '.html');
			_nl(sprintf('<div class="tabbertab"><h2>%s</h2>', $content));
			$file = file_get_contents($file);
			$file = str_replace('src="', 'src="' . WorkConfig::fol(get_the_ID(), '', 1), $file);
			echo $file;
			_nl('</div>');
		}
	}
	
	function do_works($a, $content = null)
	{
		$types = 0; $works = 0;
		WorkCache::getWorks(&$types, &$works);
		
		$links = self::types_r($types, count($works));
		self::works_r($works, $links);
	}
	
	function do_work($a, $content = null)
	{
		$id = isset($a['id']) ? $a['id'] : get_the_ID();
		
		if (isset($a['src'])) {
			$a['src'] = WorkConfig::fol($id, $a['src'], 1);
			$a['type'] = 'img';
			return csc_link_shortcode($a, $content);
		} else if (!isset($a['page']) && !isset($a['type'])) {
			return WorkMenu::nodeLink($id, $a, $content);
		} else {
			$contentInc = 1;
			include_once 'csb-content.php';
			$a['content'] = $content;
			if (isset($a['type']))
				return WorkContent::showData($id, $a);
			else
				return WorkContent::nodeQuote($id, $a);
		}
	}
	
	private function types_r($types, $all)
	{
		$links = array();
		$url = get_permalink(get_the_id());
		$op = array();
		foreach ($types as $t=>$cnt) {
			$lnk = WorkNav::typeLink($t, $url);
			$links[$t] = $lnk;
			
			$lnk = str_replace('</a>', ' (' . $cnt . ')</a>', $lnk);
			if ($t === WorkNav::type()) {
				$lnk = str_replace('">', '"><b>', $lnk);
				$lnk = str_replace('</a>', '</b></a>', $lnk);
			}
			$op[] = $lnk;
		}
		echo str_replace('</a>', ' (' . $all . ')</a>', WorkConfig::dirLink('All Works'));
		echo ' : ' . implode('&nbsp; / &nbsp;', $op) . '<br /><br />';
		return $links;
	}

	private function works_r($works, $links)
	{
		$admin = current_user_can('manage_options');
		$op = '<table><tr><th>';
		$op .= str_replace('	', '</th><th>', WorkCache::$cols);
		$op .= '</th></tr>' . PHP_EOL;
		$t = WorkNav::type();
		$cnt = 0;
		foreach ($works as $wk) {
			if ($t && $wk->type != $t) continue;
			$cnt += 1;
			$wk->data[WorkCache::$colType] = $links[$wk->type];
			$wk->data[WorkCache::$colName] = $wk->link . ($admin ? $wk->edit : '');
			$op .= '<tr><td>' . str_replace('	', '</td><td>',
				implode('	', $wk->data) ) . '</td></tr>' . PHP_EOL;
		}
		$op .= '</table>';
		echo sprintf('<b style="float: right;">%s %s</b>', $cnt, $t ? WorkConfig::formatType($t) : 'Works');
		echo $op;
	}
}
WorksShortcodes::init();
?>
