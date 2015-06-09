<?php
//In case running on windows server, the $_GET isnt parsed correctly from 
//the url, so we read it from $_SERVER['QUERY_STRING']
function cs_get($key, $default = false)
{
	//print_r($_GET); //[404;http://iandeye_in:80/pictures/?album] => two
	if (isset($_GET[$key]))
		return $_GET[$key];

	$qs = explode('?', $_SERVER['QUERY_STRING']);

	if (count($qs) == 1 || $qs[1] == '')
		return $default;

	parse_str($qs[1], $qs);
	return isset($qs[$key]) ? $qs[$key] : $default;
}
?>
