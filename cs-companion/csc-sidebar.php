<?php
// Lists recent posts
function csc_recent_links($count)
{
	$children = get_posts("numberposts=". $count);
	csc_cat_links(array(), $children, 'rec', 'Recent');
}

function csc_cat_links($args, $children = null, $name = null, $text = null)
{
	if ($children == null)
	{
		$cats = get_the_category();
		if (count($cats) == 0) return;
		$children = get_posts("numberposts=50&category=" . $cats[0]->cat_ID);
		$name = 'cat';
		$text = 'Category';
	}
	
	$fmt = '<span class="sb-what sb-%s">%s</span><ul class="sb-links">%s</ul>';
	$li = '<li class="page_item page-item-%s"><a href="%s">%s</a></li>
';

	foreach($children as $post)
		$posts .= sprintf($li, $post->ID, get_permalink($post),  get_the_title($post));

	echo sprintf($fmt, $name, $text, $posts);
}

// Lists children, parent and siblings
function csc_page_links($args)
{
	$fmt = '<span class="sb-what sb-%s">%s</span><ul class="sb-links">%s</ul>';
	$li = '<li class="page_item page-item-%s"><a href="%s">%s</a></li>
';

	$children = wp_list_pages( sprintf('title_li=&child_of=%s&echo=0&depth=1', get_the_ID()) );
	if ($children)
		echo sprintf($fmt, 'sub', 'Sub Pages', $children);

	global $post;
	if ($post->post_parent)
	{
		$parent = sprintf($li, $post->post_parent, get_permalink($post->post_parent), get_the_title($post->post_parent));
		echo sprintf($fmt, 'par', 'Parent', $parent);
		
		$siblings = wp_list_pages( $s = sprintf('title_li=&child_of=%s&exclude=%s&echo=0&depth=1', $post->post_parent, $post->ID) );
		if ($siblings) echo sprintf($fmt, 'sib', 'Siblings', $siblings);
	}
}

class csc_related_links_widget extends WP_Widget
{
    /** constructor */
  function csc_related_links_widget() {
		$name =			'CS Related Links';
		$desc = 		'Show parent / child / siblings of page or category posts';
		$id_base = 		'csc_related_links_widget';
		$css_class = 	'';
		$alt_option = 	'widget_csc_related';

		$widget_ops = array(
			'classname' => $css_class,
			'description' => $desc,
		);

		//parent::WP_Widget( 'nav_menu', __('Custom Menu'), $widget_ops );
		$this->WP_Widget($id_base, $name, $widget_ops);
		$this->alt_option_name = $alt_option;

		$this->defaults = array(
			//'maxPosts' => 0,
		);
	}

	function widget($args, $instance) {
		extract( $args );
		echo $args['before_widget'];
		echo '<h3 class="widget-title">Related Links</h3>';
		if (is_single() && !is_page())
			csc_recent_links(5);
		else if (is_page())
			csc_page_links($args);
		else
			csc_cat_links($args);
		echo $args['after_widget'];
	}

    /** @see WP_Widget::update */

  function update( $new_instance, $old_instance ) {
		//$instance['maxPosts'] = (int) $new_instance['maxPosts'];
		
		return $instance;
	}

    /** @see WP_Widget::form */
  function form($instance) {
		//$maxPosts = isset( $instance['maxPosts'] ) ? $instance['maxPosts'] : '';
		?>

	<!--p>
		<label for="<?php echo $this->get_field_id('maxPosts'); ?>"><?php _e('Max Posts:') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id('maxPosts'); ?>" name="<?php echo $this->get_field_name('maxPosts'); ?>" value="<?php echo $maxPosts; ?>" />
	</p-->
	<?php 
	}
}

add_action('widgets_init', create_function('', 'return register_widget("csc_related_links_widget");'));

?>
