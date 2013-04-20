<?php
// Registers the work post type with its taxonomies and editors
class WorkRegistry
{
	public static $instance;
	
	function __construct()
	{
		self::$instance = $this;
		
		register_taxonomy( 'work_author', 'work', array(
			'label' => 'Author', 'labels' => array( 'name' => 'Authors' ),
			'rewrite' => false, //array( 'slug' => 'authors' ),
		));

		register_taxonomy_for_object_type('category', 'work');
		register_taxonomy_for_object_type('post_tag', 'work');

		register_post_type('work', array(
			'description' => 'Works like books, poems etc', 'public' => true,
			'label' => 'Works', 'labels' => array('name' => 'Works', 'add_new_item' => 'Add New Work'),
			'menu_position' => 5, 'menu_icon' => cs_var('bib-base') . '/assets/work.png',
			'rewrite' => false, // TODO: Error add_rewrite_tag() on a non-object
			//'rewrite' => array( 'slug' => 'works' ),
			'capability_type' => 'post', //'capabilities' => array(''),
			//'supports' => '',
			'map_meta_cap' => true,
			'taxonomies' => array('category', 'post_tag', 'work_author'),
			'register_meta_box_cb' => array(&$this, 'register_meta_box'),
		));
		
		add_action( 'save_post', array(&$this, 'save_meta_box'));
		add_action( 'admin_menu', array(&$this, 'register_admin'));
	}
	
	function register_meta_box()
	{
		// http://codex.wordpress.org/Function_Reference/add_meta_box
		add_meta_box('bib_work', 'Work Details', array(&$this, 'render_meta_box'), 'work', 'side', 'high');
	}
	
	function render_meta_box()
	{
		global $post;
		$wk = cs_work($post->ID);
		echo 'Type: ' . CHtml::dropDownList('workType', $wk['type'], cs_var('workTypes'));
		echo '<br/>Data: ' . CHtml::textField('workFol', $wk['fol']);
		echo '<br/>' . CHtml::link('Edit Config', WorkNav::admin($post->ID));
	}
	
	function save_meta_box()
	{
		global $post;
		if ($post->post_type != 'work') return;
		$wk = cs_work($post->ID);
		$wk['type'] = $_POST['workType'];
		$wk['fol'] = $_POST['workFol'];
		cs_work($post->ID, $wk);
	}
	
	function register_admin()
	{
		add_submenu_page('edit.php?post_type=work', 'Work Config', 'Config', 'manage_options', 
			cs_var('bib-config-slug'), array($this, 'options_config'));
	}
	
	function options_config()
	{
		cs_var('editarea', 'php');
		add_action('admin_footer','wp_editarea'); // that is initialized before this
		include 'work-config.php';
	}
}

cs_var('bib-config-slug','config-work');
new WorkRegistry();
?>
