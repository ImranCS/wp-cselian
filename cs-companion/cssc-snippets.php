<?php
function csc_snippet_shortcode($a, $content = null)
{
	$name = 'snippets' . DIRECTORY_SEPARATOR . $a['id'] . '.html';
	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . $name;
	if (!file_exists($file)) return sprintf('[file %s not found]', $name);	
	
	$file = do_shortcode(file_get_contents($file));
	return $file;
}

add_shortcode('snippet', 'csc_snippet_shortcode');
?>
