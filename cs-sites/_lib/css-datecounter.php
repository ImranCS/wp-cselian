<?php
//April 7th 2015. Styles found in musesh/styles.css
class CSDateCounter
{
	static function init()
	{
		add_shortcode('datecounter', array('CSDateCounter', 'do_shortcode'));
	}

	function do_shortcode($a, $content = null)
	{
		//UTC - 5.30
		$date1 = new DateTime($a['year'] . '-' . $a['month'] . '-' . ($a['day'] - 1) . ' 18:30:00');
		$date2 = new DateTime();
		$diff = $date1->diff($date2);
		//print_r($diff);
		return sprintf('<div class="datecounter">%s days, %s hours remaining</div>', $diff->days, $diff->h);
	}
}
CSDateCounter::init();
?>
