<?php
// http://www.thesheep.co.uk/examples/font-resizer/
// how about a single button popup like this http://css-tricks.com/snippets/jquery/styled-popup-menu/
function csc_fontsize($sizes = array(10, 13, 16, 20))
{
	echo '<div id="accessibility">';
	foreach ($sizes as $i)
	{
		echo sprintf('<a href="#" class="size-%spx" style="font-size: %spx;">A</a>', $i, $i);
	}
	echo '</div>';
}

function csc_font_head($echo = 1)
{
	return csc_script('jquery.cookie.1.3.1.js', $echo) . csc_script('fontsize.js', $echo);
}

class csc_fontsize_widget extends WP_Widget
{
	function csc_fontsize_widget() {
		$name =			'CS Fontsize';
		$desc = 		'Show a list of links for adjusting the font size';
		$id_base = 		'csc_fontsize_widget';
		$css_class = 	'';
		$alt_option = 	'widget_csc_related';

		$widget_ops = array(
			'classname' => $css_class,
			'description' => $desc,
		);

		//parent::WP_Widget( 'nav_menu', __('Custom Menu'), $widget_ops );
		$this->WP_Widget($id_base, $name, $widget_ops);
		$this->alt_option_name = $alt_option;
	}

	function widget($args, $instance) {
		echo $args['before_widget'];
		//echo '<h3 class="widget-title">Font Size</h3>';
		csc_fontsize();
		echo $args['after_widget'];
	}
}

add_action('widgets_init', create_function('', 'return register_widget("csc_fontsize_widget");'));
add_action('wp_head', 'csc_font_head');
?>
