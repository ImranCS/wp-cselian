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
		if ($node == 'search') return home_url(); //$node = '&search=1';
		if ($node != '') $node = '&node=' . $node;
		if ($page != '') $page = '&page=' . $page;
		return home_url() . '?post_type=work&p=' . $id . $node . $page;
	}
	
	function author($a)
	{
		return home_url() . '/?work_author=' . $a->slug;
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
		if (!isset($_GET['search'])) return 0;
		return $_GET['s'];
	}

// These are for building
	function getNodeId($wk)
	{
		$node = self::node();
		$titles = $wk['config']['titles'];
		$slugs = $wk['config']['slugs'];
		$tcnt = count($titles);
		if (count($slugs) == 1)
		{
			for ($i = 1; $i < $tcnt; $i++)
			{
				$n = $slugs[0] . $i;
				if ($node == $n) return sprintf($wk['config']['keyFormat'], $i);
			}
		}
		else
		{
			echo 'NOT IMPLEMENTED FOR 2 Level menu';
		}
		
		echo 'Couldnt find node ' . $node;
	}
}
new WorkNav();
?>
