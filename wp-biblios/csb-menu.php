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
		
		$slugs = $wk['config']['slugs'];
		if (count($slugs) == 1)
			self::one($id, $wk);
		else
			self::two($id, $wk);
	}
	
	function one($id, $wk)
	{
		extract($wk['config']);
		$tcnt = count($titles);
		for ($i = 1; $i < $tcnt; $i++)
			_nl($i . '. ' . CHtml::link($titles[$i], WorkNav::post($id, $slugs[0] . $i)), 1);
	}
	
	function two($id, $wk)
	{
		extract($wk['config']);
		$tcnt = count($titles);
		for ($i = 1; $i < $tcnt; $i++)
		{
		$d = $titles[$i];
		$slug0 = $slugs[0] . $i;
		if (isset($slugKey0) && isset($slugKey0[$i]))
			$slug0 = $slugKey0[$i];
		
		echo sprintf('<span class="sect"><b>%s</b> %s</span>', $i, $d[0]);
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
						$slug0, $slugs[1], $j, $d[$j][2], $d[$j][1], $pg);
					continue;
				}
			
				_nl('    <li>' . CHtml::link($d[$j], WorkNav::post($id, $slug0 . '-' . $slugs[1] . $j)) . '</li>');
			}
			echo '</ul>';
		}
	}

// These are for building
	function getNodeId($wk)
	{
		$node = WorkNav::node();
		extract($wk['config']);
		$tcnt = count($titles);

		if (count($slugs) == 1)
		{
			for ($i = 1; $i < $tcnt; $i++)
			{
				$n = $slugs[0] . $i;
				if ($node == $n) return sprintf($keyFormat, $i);
			}
		}
		else
		{
			for ($i = 1; $i < $tcnt; $i++)
			{
				$d = $titles[$i];
				$slug0 = $slugs[0] . $i;
				$scnt = count($d);
				for ($j = 1; $j < $scnt; $j++)
				{
					$n = $slug0 . '-' . $slugs[1] . $j;
					if ($node == $n) return sprintf($keyFormat, $i, $j);
				}
			}
		}
		
		echo 'Couldnt find node ' . $node;
	}
}
?>
