<?php
if (isset($ft)) {
	$fol = 'assets';
	$ft["settings"]["DIR"] = $url = sprintf('../../../%s/', $fol);
	$ft["settings"]["explore"] = sprintf('Browse: <a target="_new" href="%s">%s</a>', $url, $fol);
	return;
}
?>
