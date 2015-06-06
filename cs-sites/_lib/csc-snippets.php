<?php
// Taken from http://github.com/ImranCS/wp-cselian/wiki/Companion on May 6 2015
//.html file to be stored in wp-content/plugins/cs-sites/site folder/snippets
add_shortcode('snippet', 'csc_snippet_shortcode');

function csc_snippet_shortcode($a, $content = null)
{
	$name = 'snippets' . DIRECTORY_SEPARATOR . $a['id'] . '.html';
	$file = (cs_var('sitebase') ? cs_var('sitebase') : dirname(__FILE__) ) . DIRECTORY_SEPARATOR . $name;

	if (!file_exists($file)) return sprintf('[file %s not found]', $name);	
	return do_shortcode(file_get_contents($file));
}
?>
