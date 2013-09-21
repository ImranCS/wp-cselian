<?php
class WorksWidget extends WP_Widget
{
	function __construct() {
		$args = array('description' => 'Show all Works', 'classname' => 'bib-works');
		parent::WP_Widget('csb_works_widget', 'CS Works', $args);
	}

	function widget($args, $instance) {
		echo $args['before_widget'];
		echo '<h2 class="widgettitle">'.WorkConfig::dirLink().'</h2>';
		self::worksByType();
		echo $args['after_widget'];
	}
	
	function worksByType()
	{
		$types = 0; $works = 0;
		WorkCache::getWorks(&$types, &$works);

		$byType = array();
		foreach ($types as $t=>$val)
			$byType[$t] = array();

		foreach ($works as $wk)
			$byType[$wk->type][] = $wk->link;
		ksort($byType);

		foreach ($byType as $t=>$wks)
		{
			_nl('<span class="sb-what">' . WorkNav::typeLink($t, $url) . '</span>');
			echo implode('<br/>', $wks);
		}
	}
}

class WorkAuthorsWidget extends WP_Widget
{
	function __construct() {
		$args = array('description' => 'Show all authors of Works');
		parent::WP_Widget('csb_workAuthors_widget', 'CS Work Authors', $args);
	}

	function widget($args, $instance) {
		echo $args['before_widget'];
		echo '<h2 class="widgettitle">Authors</h2>';
		self::worksAuthors();
		echo $args['after_widget'];
	}
	
	function worksAuthors()
	{
		$list = get_terms('work_author');
		foreach ($list as $itm)
			_nl(WorkNav::termLink($itm), 1);
	}
}

add_action('widgets_init', create_function('', 'return register_widget("WorksWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("WorkAuthorsWidget");'));
?>
