<?php
function csc_link_url($type, $rel)
{
	if (!strncmp($rel, 'http://', strlen('http://'))) return $rel;
	if (csc_var('sample')) return csc_var('base') . '/intl/' . $rel;
	$bases = array(
		'img' => '/assets/images/',
		'doc' => '/assets/docs/',
		'mp3' => '/assets/audio/',
		'vid' => '/assets/videos/',
	);
	$base = $bases[$type];
	return site_url($base . $rel);
}

function csc_extension($file)
{
	$aliases = array(
		'zip' => array('rar'),
		'xl' => array('csv', 'xls', 'xlsx'),
		'doc' => array('rtf', 'docx'),
	);
	
	$bits = explode('.', $file);
	$ext = 'file';
	if (count($bits) > 1)
	{
		$ext = $bits[count($bits) - 1];
		foreach ($aliases as $for=>$list)
			foreach ($list as $val) if ($val == $ext) return $for;
	}
	return $ext;
}

function csc_link_build($fmt, $a)
{
	$atts = null;
	if(isset($a['section']))
	{
		$a['href'] .= '#' . $a['section'];
		unset($a['section']);
	}
	foreach ($a as $key=>$value)
		$atts .= sprintf('%s%s="%s"', $atts != null ? ' ' : '', $key, $value);

	return sprintf($fmt, $atts);
}

function csc_link_shortcode($a, $content = null)
{
	$type = isset($a['type']) ? $a['type'] : 'img';
	unset($a['type']);
	$return = isset($a['return']) ? $a['return'] : 0;
	unset($a['return']);
	// no nesting. added hybrid links instead
	// if ($content != null) $content = do_shortcode($content);

	if ($type == 'img')
	{
		$a = array_merge(array('src' => 'sample.jpg') , $a);
		if (!isset($a['alt'])) $a['alt'] = $a['src'];
		$a['src'] = csc_link_url($type, $a['src']);
		if ($return === 'src') return $a['src'];
		if (isset($a['enclose']))
		{
			$fmt = sprintf('<%s></%s>', $a['enclose'], $a['enclose']);
			if (isset($a['class']))
			{
				$fmt = str_replace('><', sprintf(' class="%s">%s<', $a['class'], '%s'), $fmt);
				unset($a['class']);
			}
			else
			{
				$fmt = str_replace('><', '>%s<', $fmt);
			}
			
			if (isset($a['imgclass']))
			{
				$a['class'] = $a['imgclass'];
				unset($a['imgclass']);
			}
			unset($a['enclose']);
		}
		if (isset($a['href']) || isset($a['id']))
		{
			if (isset($a['id']))
			{
				$lnk = csc_link_shortcode(array('type' => 'post', 'id' => $a['id']), '%s');
				unset($a['id']);
			}
			else
			{
				$can = array('href', 'target', 'class');
				$a['href'] = 'http://' . $a['href'];
				$b = array();
				foreach ($can as $k) if (isset($a[$k])) { $b[$k] = $a[$k]; unset($a[$k]); }
				$lnk = csc_link_build('<a %s>', $b) . '%s</a>';
			}
			if ($content != null) $lnk = str_replace('%s', '%s'. $content, $lnk);
			$fmt = isset($fmt) ? str_replace('%s', $lnk, $fmt) : $lnk;
		}
		
		$link = csc_link_build('<img %s/>', $a);
		return isset($fmt) ? sprintf($fmt, $link) : $link;
	}
	else if ($type == 'mp3')
	{
		$a = array_merge( array('height' => 27, 'quality' => 'best', 'file' => 'sample.mp3') , $a);
		$a['flashvars'] = "audioUrl=" . csc_link_url($type, $a['file']);
		unset($a['file']);
		return csc_link_build('<embed type="application/x-shockwave-flash"
	src="http://www.google.com/reader/ui/3523697345-audio-player.swf" %s></embed>', $a);
	}
	else if ($type == 'doc')
	{
		$a = array_merge( array('file' => 'sample.doc') , $a);
		if ($content == null) $content = $a['file'];
		
		$ext = (isset($a['ext'])) ? $a['ext'] : csc_extension($a['file']);
		unset($a['ext']);
		$a['class'] .= (isset($a['ext']) ? ' ' : '') . 'doc-link ext-' . $ext;

		$a['href'] = csc_link_url($type, $a['file']);
		unset($a['file']);
		
		return csc_link_build('<a %s>', $a) . $content . '</a>';
	}
	else if ($type == 'post')
	{
		$a = array_merge( array('id' => 1, 'notitle' => 'no') , $a);
		$id = intval($a['id']);
		unset($a['id']);
		$a['href'] = get_permalink($id);
		
		if ($a['notitle'] == 'no')
			$a['title'] = get_the_title($id);
		unset($a['notitle']);

		if ($content == null)
			$content = get_the_title($id);

		return csc_link_build('<a %s>', $a) . $content . '</a>';
	}
	else if ($type == 'wiki')
	{
		$url = $a['url'];
		unset($a['url']);
		$a['href'] = 'http://en.wikipedia.org/wiki/' . $url;
		$a['class'] .= (isset($a['class']) ? ' ' : '') . 'lnk-wiki';
		$a['target'] = '_new';
		
		if ($content == null) $content = $url;
		return csc_link_build('<a %s>', $a) . $content . '</a>';
	}
}

add_shortcode('link', 'csc_link_shortcode');
?>
