<?php
function csc_gallery_shortcode($a, $content = null)
{
	$type = isset($a['type']) ? $a['type'] : 'img';
	unset($a['type']);
	if ($content != null) $content = do_shortcode($content);
	
	if ($type == 'slide')
	{
		$fol = $a['fol'];
		unset($a['fol']);

		$images = $a['images'];
		unset($a['images']);

		$images = explode('|', $images);
		$imgs = array();
		
		$id = isset($a['id']) ? $a['id'] : 'slideshow';
		unset($a['id']);
		
		$links = isset($a['links']) ? explode('|', $a['links']) : null;
		unset($a['links']);
		
		foreach ($images as $i=>$img)
		{
			$a['src'] = $fol . '/' . $img;
			if ($links != null)
				$a[intval($links[$i]) != 0 ? 'id' : 'href'] = $links[$i];
			
			$imgs[] = csc_link_shortcode($a, null);
			unset($a['id']); unset($a['href']);
		}
		
		return '<div id="'.$id.'">' . implode('
', $imgs) . '</div>';
	}
}

add_shortcode('gallery', 'csc_gallery_shortcode');
?>
