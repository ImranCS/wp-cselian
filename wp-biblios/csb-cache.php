<?php
class WorkCache
{
	function clear()
	{
		self::value(false);
	}
	
	private function value($val = null)
	{
		$cacheKey = 'workCache';
		if ($val === false)
			delete_transient($cacheKey);
		else if ($val == null)
			return get_transient($cacheKey);
		else
			set_transient($cacheKey, $val);
	}
	
	public static $cols = 'Name	Type	Author	Category	Tags	Date';
	public static $colName = 0;
	public static $colType = 1;
	
	function getWorks(&$types, &$works)
	{
		$cache = self::value();
		if ($cache && !isset($_GET['clearcache']))
		{
			$types = $cache['types'];
			$works = $cache['works'];
			return;
		}
		
		//echo '&hellip;Setting workCache';
		$types = WorkConfig::types(0);
		$posts = get_posts('post_type=work&posts_per_page=-1');
		$works = array();
		foreach ($posts as $wk)
		{
			$id = $wk->ID;
			$name = $wk->post_title;
			$type = cs_work_read($id, 'type');
			$date = mysql2date('j M Y', $wk->post_date_gmt);
			$author = self::terms_r($wk, 'work_author');
			$category = self::terms_r($wk, 'category');
			$tags = self::terms_r($wk, 'post_tag');

			$types[$type] += 1;
			$o = new stdClass;
			$o->id = $id;
			$o->type = $type;
			$o->link = sprintf('<a href="%s">%s</a>', get_permalink($id), $name);
			$o->edit = sprintf(' <a href="%s">&hellip;%s</a>', get_edit_post_link($id), $id);
			$o->data = array($o->link, $type, $author, $category, $tags, $date);
			$works[] = $o;
		}
		
		self::value(array('types' => $types, 'works' => $works));
	}
	
	private function terms_r($wk, $type)
	{
		$op = array();
		$terms = get_the_terms($wk, $type);
		if ($terms) foreach ($terms as $t)
			$op[] = WorkNav::termLink($t);
		return count($op) == 0 ? '-' : implode(' ', $op);
	}
}
?>