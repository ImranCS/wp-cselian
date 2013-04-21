<?php
class WorkContent
{
	private static $itemFormat = '<p>%s</p>
';
	public function display($wk)
	{
		$data = array();
		include $wk['contentFile'];
		if (isset($wk['config']['itemFormat'])) self::$itemFormat = $wk['config']['itemFormat'];
		$node = $data[WorkMenu::getNodeId($wk)];
		self::nodeTabs($node);
	}
	
	private function nodeTabs($node)
	{
		foreach ($node as $i=>$items)
		{
			$pg = $_GET['page']; if ($pg != null) $pg = intval($pg);
			self::tabberTab($tabPrefix . $i, $i == $pg);
			$icnt = count($items);
			//0th is always empty
			for ($j = 1; $j < $icnt; $j++)
				echo self::formatItem($items[$j], false, $i, $i - $tabOffs, $node);
		
				if ($nextPgLink) echo $nextPgLink;
				
				
			self::tabberTab(0);
		}
	}
	
	private function tabberTab($heading, $selected = 0)
	{
		if (!$heading)
		{
			echo '</div>';
			return;
		}
		
		$act = $selected ? " tabbertabdefault" : "";
		echo sprintf('<div class="tabbertab%s">
	<h2>%s</h2>
	', $act, $heading);
	}

	function formatItem($txt, $search, $i = null, $tab = null, $node = null)
	{
		return $search ? $txt : sprintf(self::$itemFormat, $txt);
	}
}

if (WorkNav::search()) return;
?>
<div class="tabber">
<?php WorkContent::display($wk); ?>
</div>
