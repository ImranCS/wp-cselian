<?php
$csaDbName = 'cselian_'; // the prefix used for db names
$csaSites = array
(
	'mini.cselian.com' => 'wp_mini',
	'spanda.lailaborrie.com' => 'wp_spanda', 
	'wp.cselian.com/share' => 'wp_multi',
	//'wp.cselian.com/share' => array('multi', '_2'),
	//'wp.cselian.com/fwds' => array('multi', '_3'),
	'tg.cselian.com' => 'wp_tg',
	'subhanigroup.com' => 'subhani',
	'store.cselian.com' => 'store',
	'cselian.com/blog' => 'wrdp1',
);

function checkStandalone($csaSites)
{
	$list = array(
		'subhanigroup.com' => 0,
		'store.cselian.com' => 0,
		'cselian.com' => 'cselian.com/blog',
	);
	$site = $_SERVER['HTTP_HOST'];
	if (isset($list[$site]))
	{
		$key = $list[$site];
		if ($key) $site = $key;
		$csaSites = array($key => $csaSites[$key]);
	}
}
checkStandalone(&$csaSites);
?>
