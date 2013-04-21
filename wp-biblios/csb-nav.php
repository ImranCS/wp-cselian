<?php
// Tells from the url what state its in / what is 
class WorkNav
{
	function __construct()
	{
		add_action('wp', array(&$this, 'action_head')); // if done in init, is_single returns false!
	}
	
	function action_head()
	{
		wp_register_style('bibworks-css', cs_var('bib-base') . '/assets/works.css');
		wp_enqueue_style('bibworks-css');
		
		if (!cs_work_get('hascontent')) return; 
		
		wp_register_script('tabber', cs_var('bib-base') . '/assets/tabber-minimized.js');
		wp_enqueue_script('tabber');
		wp_register_style('tabber-css', cs_var('bib-base') . '/assets/tabber.css');
		wp_enqueue_style('tabber-css');
	}
	
	function admin($id = '', $what = 'config')
	{
		if ($what == 'work')
			return admin_url('post.php?post='.$id.'&action=edit');

		if ($what == 'config') $page = '&page=' . cs_var('bib-config-slug');

		if ($id != '') $id = '&id=' . $id;
		return admin_url('edit.php?post_type=work' . $page . $id);
	}
	
	function post($id, $node = '', $page = '')
	{
		if ($node == 'search') return get_permalink($id);
		$qs = array();
		if ($node != '') $qs[] = 'node=' . $node;
		if ($page != '') $qs[] = 'page=' . $page;
		$qs = count($qs) == 0 ? '' : '?' . implode('&', $qs);
		return get_permalink($id) . $qs;
	}
	
	function authorLink($a)
	{
		return CHtml::link($a->name, get_term_link($a));
	}

// These are getters from url
	function nodeOrSearch()
	{
		return self::node() || self::search();
	}

	function node()
	{
		if (!isset($_GET['node'])) return 0;
		return $_GET['node'];
	}

	function search()
	{
		if (!isset($_GET['s'])) return 0;
		return $_GET['s'];
	}
}
new WorkNav();
?>
