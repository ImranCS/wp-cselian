<?php if (isset($genId)) return 4;

$data = tsv_to_array('#text	name	desig	company	url	img
I worked with Imran for a couple of years, on a number of different projects. I found him to be a very clever and highly productive developer, also with good social skills.	Rasmus Rasmusson	Vice President Strategic Marketing Initatives	EF Englishtown	http://www.englishtown.com/	[link type=img src=rasmus.jpg return=src]
A Programmer of excellent expertise, Imran stands out as a dedicated individual striving to complete any assignment to the last detail. I am certain he will be an asset to any organisation that he chooses. I hold him in high esteem.	Ramesh Raj	Owner	Aikon Cellular	mailto:ram_raj_r@yahoo.com	[link type=img src=ramesh.jpg return=src]
');

foreach ($data as $ix=>$i)
{
	$img = do_shortcode($i[5]);
	echo sprintf('<p><img src="%s" class="right" alt="%s" />%s</p>
	<p><cite>%s<br/>%s<br/><a href="%s">%s</a></cite></p>', $img, $i[1], $i[0], $i[1], $i[2], $i[4], $i[3]);
	if ($ix != count($data) - 1) echo '<hr/>';
}

return 0;
?>
