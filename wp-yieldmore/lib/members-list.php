<?php
add_shortcode('members', 'ym_members_shortcode');

function ym_field_sanitize($f, $c) {
	if ($c == 'Email') return sprintf('<a href="mailto:%s" target="_blank">%s</a>', $f, $f);
	if ($c == 'Phone') return sprintf('<a href="tel:+91%s" target="_blank">%s</a>', $f, $f);
	return $f;
}

//[members file=millennium-members.tsv office protected/]
//if protected, looks in ABSPATH/../protected else site/data
//if office, shows separate table for office bearers
function ym_members_shortcode($a, $content) {
	$logged_in = is_user_logged_in();
	//if (!$logged_in) return 'Only Members can see this page.';
	if (!$logged_in && array_search('hide-if-guest', $a) !== false) return $content;

	$protected = array_search('protected', $a) !== false;
	$office = array_search('office', $a) !== false;

	$fol = $protected ? ABSPATH . '/../_protected/' : cs_var('base') . 'data/';
	$fil = $fol . $a['file'];

	$cols = true;
	$tsv = tsv_to_array(file_get_contents($fil), $cols);
	
	$columns = [];
	foreach ($cols as $p=>$v) if (strpos($p, 'Hide') === false && $p !== 'AlwaysShow' && $p !== 'Image') $columns[] = $p;
	unset($columns[0]); //expecting column 1 to be NAME
	$officeName = 'Current Office';

	$imgUrl = '/images/millennium/members/';
	$imgFol = ABSPATH .$imgUrl ;
	$imgDefault = '/images/common/brother.png';
	$r = '<ol class="members">';

	foreach ($tsv as $person) {
		if (!$logged_in && $person[$cols->AlwaysShow] == '') continue;

		$img = sprintf('<img src="%s" width="90" alt="%s" />', 
			file_exists($imgFol . $person[$cols->Image]) ? $imgUrl . $person[$cols->Image] : $imgDefault
			, $person[$cols->Name]);
		
		$office = $person[$cols->$officeName];
		if ($office) $office = ' <small>(' . $office . ')</small>';

		$r .= '<li class="person">' . $img . '<span class="heading">' . $person[$cols->Name] . $office . '</span/>';
		$cs = []; $os = []; //columns (Details) and [Past] offices

		foreach ($columns as $c) {
			if ($person[$cols->$c] == '') continue;

			$h = 'Hide' . $c;
			if (isset($cols->$h) && $person[$cols->$h] != '') continue;
			if (strpos($c, 'Office') !== false && $c != 'Current Office')
				$skip = 1; //$os[str_replace('Office ', '', $c)] = $person[$cols->$c];
			else
				$cs[$c] = ym_field_sanitize($person[$cols->$c], $c);
		}

		$r .= ' <div class="person-info">';
		if (count($cs)) $r .= array_to_list($cs, 'Details');
		if (count($os)) $r .= array_to_list($os, 'Past Offices');
		$r .= ' </div><div style="height: 0px; clear: both"></div>' . PHP_EOL;
		$r .= '</li>' . PHP_EOL;
	}
	$r .= '</ol>';
	return $r;
}
?>
