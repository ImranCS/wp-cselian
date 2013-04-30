<?php
include_once 'inc/CHtml.php';

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

cs_var('adm-base', content_url('plugins/' . plugin_basename(dirname(__FILE__))));

function _nl($txt, $br = 0)
{
	echo $txt . '
' . ($br ? '<br />' : '');
}
?>
