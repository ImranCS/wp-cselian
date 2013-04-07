<?php
class CSPageGenerator {
	var $dataFile = 'pages-list.tsv';
	var $baseUrl = 'http://mysite.com';
	
	var $oldFile = 'oldies.tsv';
	var $oldUrl = 'http://myoldsite.com';
	var $showId = 1;
	
	var $defaults = array(
		'title'			 => null,
		'post'			 => null,
		'status'		 => 'future', // publish, inherit, pending, private, future, draft, trash
		'type'			 => 'page',
		'excerpt'		 => null,
		'date'			 => '',
		'tags'			 => null,
		'categories' => null,
		'author'		 => 1,
		'slug'			 => null,
		'parent'		 => 0,
		'order'			 => 0,
	);

	var $log = array();
	
	var $next = array();
	var $existing = array();
	var $created = array();
	
	function readOldies()
	{
		$data = $this->tsv_to_array(file_get_contents($this->oldFile));
		$old = array();
		foreach ($data as $ix=>$row)
			$old[] = sprintf('<a target="_oldie" href="%s/%s">%s</a> (%s)', $this->oldUrl, $row[0], $row[0], $ix);
		return $old;
	}

	function readNext()
	{
		$data = $this->tsv_to_array(file_get_contents($this->dataFile));
		foreach ($data as $ix=>$row)
		{
			$iUrl = 0; $iSlug = 1; $iTitl = 2; $iId = 3; $iPar = 4; $iOrd = 5;
			
			if ($row[$iPar] == '') continue; //exclude if no parent
			
			$item = array(
				'slug'=> substr($row[$iSlug], 1),
				'title' => $row[$iTitl],
				'parent' => intval($row[$iPar]),
				'url' => $row[$iUrl],
				'id' => $row[$iId] == '' ? '' : intval($row[$iId]),
				'rowId' => $ix + 1,
				'order' => intval($row[$iOrd]),
			);
			
			if ($item['id'] == '' && $item['parent'] != -1)
				$this->next[] = $item;
			else
				$this->existing[$item['id']] = $item;
		}
	}
	
	function printNext()
	{
		$fmt = '<tr><td>%s</td><td>%s</td><td>%s</td></tr>
';
		echo sprintf(str_replace('td>', 'th>', $fmt), 'Row', 'New', 'Parent');
		foreach ($this->next as $item)
		{
			$par = $this->existing[$item['parent']];
			echo sprintf($fmt, $item['rowId'], $this->itemText($item), $this->itemText($par));
		}
	}
	
	function createNext()
	{
		foreach ($this->next as $ix=>$item)
		{
			if (function_exists('is_localhost'))
			{
				$id = 70 + $ix;
			}
			else
			{
				$item['post'] = sprintf('Contents of %s (#%s) will be added later.', $item['title'], $item['url']);
				$id = $this->create_post($item);
			}
			$this->created[$id] = $item;
			//break;
		}
	}

	function printCreated()
	{
		$fmt = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>
';
		echo sprintf(str_replace('td>', 'th>', $fmt), 'Row', 'New', 'Id', 'Parent');
		foreach ($this->created as $id=>$item)
		{
			$par = $this->existing[$item['parent']];
			echo sprintf($fmt, $item['rowId'], $this->itemText($item), $id, $this->itemText($par));
		}
	}
	
	function itemText($item)
	{
		$r = sprintf('<a href="%s%s">%s</a>', $this->baseUrl, $item['url'], $item['title']);
		if ($this->showId && $item['id'] != '') $r .= ' (' . $item['id'] . ')';
		return $r;
	}

	function tsv_to_array($data)
	{
		$r = array();
		$lines = explode('
', $data);
		foreach ($lines as $lin)
		{
			if ($lin == '' || $lin[0] == '#') continue;
			$r[] = explode("	", $lin);
		}
		return $r;
	}

	function create_post($data) {
			$data = array_merge($this->defaults, $data);
			//$this->log['error']["type-{$type}"] = sprintf('Unknown post type "%s".', $type);

			$new_post = array(
				'post_title'	 => convert_chars($data['title']),
				'post_content' => wpautop(convert_chars($data['post'])),
				'post_status'	=> $data['status'],
				'post_type'		=> $data['type'],
				//'post_date'		=> $this->parse_date($data['date']),
				//'post_excerpt' => convert_chars($data['excerpt']),
				'post_name'		=> $data['slug'],
				'menu_order'		=> $data['order'],
				'post_author'	=> $data['author'],
				//'tax_input'		=> $this->get_taxonomies($data),
				'post_parent'	=> $data['parent'],
			);

			// create!
			$id = wp_insert_post($new_post);
			return $id;
	}

	/**
	 * Try to split lines of text correctly regardless of the platform the text
	 * is coming from.
	 */
	function split_lines($text) {
			$lines = preg_split("/(\r\n|\n|\r)/", $text);
			return $lines;
	}

	function parse_date($data) {
			$timestamp = strtotime($data);
			if (false === $timestamp) {
					return '';
			} else {
					return date('Y-m-d H:i:s', $timestamp);
			}
	}
}
?>
