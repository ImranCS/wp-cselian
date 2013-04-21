<?php
class WorksWidget extends WP_Widget
{
	function __construct() {
		$args = array('description' => 'Show all Works', 'classname' => 'bib-works');
		parent::WP_Widget('csb_works_widget', 'CS Works', $args);
	}

	function widget($args, $instance) {
		echo $args['before_widget'];
		echo '<h2 class="widgettitle">Works</h2>';
		self::worksByType();
		echo $args['after_widget'];
	}
	
	function worksByType()
	{
		$wks = get_posts('post_type=work');
		$data = array();
		foreach ($wks as $itm)
		{
			$wk = cs_work($itm->ID);
			$data[$wk['type']] .= CHtml::link($itm->post_title, get_permalink($itm)) . '<br/>
';
		}
		ksort($data);
		$type = cs_var('workTypes');
		foreach ($data as $t=>$wks)
		{
			_nl('<span class="sb-what">' . ucfirst($type[$t]) . 's</span>');
			echo $wks;
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
			_nl(WorkNav::authorLink($itm), 1);
	}
}

add_action('widgets_init', create_function('', 'return register_widget("WorksWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("WorkAuthorsWidget");'));
?>
