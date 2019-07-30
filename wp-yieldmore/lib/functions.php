<?php
// from microvic
function tsv_to_array($data, &$cols = null)
{
	$r = array();
	$lines = explode('
', $data);
	foreach ($lines as $lin)
	{
    if ($lin == '' || $lin[0] == '#')
    {
      if ($cols === true && $lin != '')
        tsv_set_cols($lin, $cols);
      continue;
    }
		$r[] = explode("	", $lin);
	}
	return $r;
}

function tsv_set_cols($lin, &$c)
{
	$lin = substr($lin, 1);
	$r = explode("	", $lin);
	$c = new stdClass();
	foreach ($r as $key => $value)
		$c->$value = $key;
}

//for members
function array_to_list($a, $c)
{
	$r = PHP_EOL . ' <ul><u>' . $c . '</u>';
	foreach ($a as $k=>$v)
		$r .= sprintf('  <li><b>%s</b>: %s</li>' . PHP_EOL, $k, $v);
	$r .= ' </ul>' . PHP_EOL;
	return $r;
}
?>
