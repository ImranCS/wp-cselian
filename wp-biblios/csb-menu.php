<?php
class WorkMenu
{
	function render($id, $wk)
	{
		_nl(CHtml::link('Home', WorkNav::post($id)), 1);
		if (!$wk['cfgOk'])
		{
			echo $wk['cfgError'];
			return;
		}
		
		if (!isset($wk['config']['slug2']))
			self::one($id, $wk);
		else
			self::two($id, $wk);
		
		if (isset($wk['config']['links']))
			self::links($id, $wk['config']);
	}
	
	function nodeLink($id, $a, $txt)
	{
		$cfg = cs_work_read($id, 'config');
		$node = $cfg['slug'] . $a['node'] . (isset($cfg['slug2']) ? '-' . $cfg['slug2'] . $a['subnode'] : '');
		return CHtml::link($txt, WorkNav::post($id, $node));
	}
	
	private function links($id, $cfg)
	{
		// TODO: make array and remove links: count workaround!
		$dataUrl = WorkConfig::fol($id, '', 1);
		$cnt = intval($cfg['links']);
		_nl('', 1); _nl('<b>Links:</b>', 1);
		for ($i = 1; $i <= $cnt ; $i++)
		{
			$link = explode('|', $cfg['link' . $i]);
			_nl(CHtml::link($link[0], $dataUrl . $link[1]), 1);
		}
	}
	
	private function one($id, $wk)
	{
		extract($wk['config']);
		$tcnt = count($titles);
		$ct = $customTitles;
		$sl = isset($slug);
		for ($i = 1; $i < $tcnt; $i++) {
			$url = $sl ? $slug . $i . self::name($ct, $titles[$i]) : strtolower($titles[$i]);
			_nl($i . '. ' . CHtml::link($titles[$i], WorkNav::post($id, $url)), 1);
		}
	}
	
	private function name($customTitles, $title)
	{
		return $customTitles ? '&name=' . str_replace(' ', '-', strtolower($title)) : '';
	}
	
	private function two($id, $wk)
	{
		extract($wk['config']);
		$tcnt = count($titles);
		for ($i = 1; $i < $tcnt; $i++)
		{
		$d = $titles[$i];
		$slug0 = $slug . $i;
		if (isset($slugKey0) && isset($slugKey0[$i]))
			$slug0 = $slugKey0[$i];
		
		echo sprintf('<span class="sect">%s) %s</span>', $i, $d[0]);
			echo '<ul type="1">';
			$scnt = count($d);
			if (isset($preTitle) && isset($preTitle[$i]))
			{
				foreach ($preTitle[$i] as $file=>$text) {
					echo sprintf('<a class="sp" href="%s-%s">%s</a><br/>
			', 
						$slug0, $file, $text);
				}
			}
			for ($j = 1; $j < $scnt; $j++)
			{
				if (isset($customTitles))
				{
					echo sprintf('<li><a href="%s-%s%s-%s">%s</a>%s</li>
				', 
						$slug0, $slug2, $j, $d[$j][2], $d[$j][1], $pg);
					continue;
				}
			
				if ($d[$j] == '') continue;
				_nl('    <li>' . CHtml::link(sprintf('%s.%s %s', $i, $j, $d[$j]), 
					WorkNav::post($id, $slug0 . '-' . $slug2 . $j)) . '</li>');
			}
			echo '</ul><hr/>';
		}
	}

// These are for building
	function getNodeId($wk)
	{
		$node = WorkNav::node();
		extract($wk['config']);
		$tcnt = count($titles);

		if (!isset($slug2))
		{
			for ($i = 1; $i < $tcnt; $i++)
			{
				$n = $slug . $i;
				if ($node == $n) return sprintf($keyFormat, $i);
			}
		}
		else
		{
			for ($i = 1; $i < $tcnt; $i++)
			{
				$d = $titles[$i];
				$slug0 = $slug . $i;
				$scnt = count($d);
				for ($j = 1; $j < $scnt; $j++)
				{
					$n = $slug0 . '-' . $slug2 . $j;
					if ($node == $n) return sprintf($keyFormat, $i, $j);
				}
			}
		}
		
		echo 'Couldnt find node ' . $node;
	}
}
?>
