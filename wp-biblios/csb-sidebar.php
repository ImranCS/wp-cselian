<div class="widget widget_categories">
<?php
_nl(CHtml::link('Home', WorkNav::post($id)), 1);
$titles = $wk['config']['titles'];
$slugs = $wk['config']['slugs'];
$tcnt = count($titles);
if (count($slugs) == 1)
{
	for ($i = 1; $i < $tcnt; $i++)
		_nl($i . '. ' . CHtml::link($titles[$i], WorkNav::post($id, $slugs[0] . $i)), 1);
}
else
{
	echo 'NOT IMPLEMENTED FOR 2 Level menu';
}
?>
</div>
