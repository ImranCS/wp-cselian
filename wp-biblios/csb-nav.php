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
		
		wp_register_script('bibworks-js', cs_var('bib-base') . '/assets/works.js', array('jquery'));
		wp_enqueue_script('bibworks-js');
		
		if (!cs_work_get('hascontent')) return; 
		CSScripts::tabber();
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
	
	function termLink($t)
	{
		return sprintf('<a href="%s" title="%s">%s</a>',
				 get_term_link($t), $t->description, $t->name);
	}

	function typeLink($t, $url = 0)
	{
		if (!$url) $url = WorkConfig::dirLink('url');
		return sprintf('<a href="%s?type=%s">%s</a> ',
				$url, WorkConfig::formatType($t, 'slug'), WorkConfig::formatType($t));
	}
	
	function type()
	{
		if (!isset($_GET['type'])) return 0;
		$t = $_GET['type'];
		$types = WorkConfig::types('orig', 'slug'); // orig from slug
		return $types[$t];
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
