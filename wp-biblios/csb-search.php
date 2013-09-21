<?php 
class WorkSearch
{
	function find($wk, $id)
	{
		$s = WorkNav::search();
		$nodes = self::nodeList($wk);
		$totalItems = 0;
		$found = 0;
		$end = $single ? 0 : 1;
		
		$data = array();
		include $wk['contentFile'];
		$slug = $wk['config']['slug'];
		$titles = $wk['config']['titles'];
		include_once 'csb-content.php';
		
		foreach ($nodes as $ix=>$key)
		{
			if (!isset($data[$key])) continue; // for bg which is incomplete
			$node = $slug . $ix;
			foreach ($data[$key] as $pg=>$items) {
				//foreach ($items as $itm) {
				$cnt = count($items);
				for ($i = 1; $i < $cnt; $i++) {
					$totalItems++;
					$itm = $items[$i]; //so we can mention the para number
					if (stripos($itm, $s) != "") {
						$link = CHtml::link($titles[$ix], WorkNav::post($id, $node , $pg));
						if ($nonTag) $itm = str_ireplace($s, $r, $itm);
						echo sprintf('<div><b>%s pg %s, %s %s:</b>
	%s</div>', $link, $pg, $itemName, $i, WorkContent::formatItem($itm, true));
	
						$found++;
					}
				}
				//die("done"); //search only chapter 1 for now
			}
		}
		if ($found) echo "<hr>";
		echo sprintf("Found %s matches in %s items.", $found, $totalItems);
	}
	
	private function nodeList($wk)
	{
		$titles = $wk['config']['titles'];
		$tcnt = count($titles);
		
		$nodes = array();
		if (!isset($wk['config']['slug2']))
		{
			for ($n = 1; $n < $tcnt; $n++)
				$nodes[$n] = sprintf($wk['config']['keyFormat'], $n);
		}
		else
		{
			throw new Exception('nodeList not implemented for 2 level menu');
		}
		return $nodes;
	}
}
WorkSearch::find($wk, $id);
?>
