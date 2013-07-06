<?php
// Gets the csc_assetsfol and writes it to a per-domain file
function csf_assets($val = null)
{
	$hostName = str_replace('www.', '', $_SERVER['HTTP_HOST']);
	$file = dirname(__FILE__) . '/' . $hostName . '.txt';
	if ($val == null)
		return file_exists($file) ? file_get_contents($file) : 'assets';

	if (!file_exists($file) || $val != csf_assets()) // if differing, write it
		file_put_contents($file, $val);
}

if (isset($ft)) {
	$fol = csf_assets();
	$ft["settings"]["DIR"] = $url = sprintf('../../../%s/', $fol);
	$ft["settings"]["explore"] = sprintf('Browse: <a target="_new" href="%s">%s</a>', $url, $fol);
	return;
}
else
{
	if (!function_exists('csc_assetsfol'))
		echo CSFileThingie::$cscRequired;
	else
		csf_assets(csc_assetsfol());
}
?>
