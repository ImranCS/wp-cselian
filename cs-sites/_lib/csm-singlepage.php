<?php

// Copied from CSM-SassyMedia Apr 6th 2015
// seen at sassymediagroup.com
// Documentation
// Make sure theres a <body &lt;?php body_class(); ?&gt;>

function csm_singlepage_shortcode($a, $content = null)
{
	wp_register_style('csm-singlepage', cs_var('siteurl') . '../_assets/singlepage.css');
	wp_enqueue_style('csm-singlepage');

	wp_register_script('csm-singlepage', cs_var('siteurl') . '../_assets/singlepage.js', array('jquery'));
	wp_enqueue_script('csm-singlepage'); // added $ = jQuery.noConflict() if undefined
	if ($a == 'admin') return;
	
	$pages = get_pages('include=' . $a['ids'] . '&sort_column=menu_order');
	$res = '<div id="singlepages">';
	//$res .= '<div id="pg-home" class="singlepage"> </div>';
	$links = '<ul id="singlepagenav">';
	foreach ($pages as $ix=>$pg)
	{
		$id = str_replace(' ', '_', strtolower($pg->post_title));
		$links .= sprintf('<li%s><a href="#%s">%s</a></li>', $ix == 0 ? ' class="active"' : '', $id, $pg->post_title);
		$res .= sprintf('<a class="singlepagemarker%s" name="%s"></a><div id="pg-%s" class="singlepage">', $ix == 0 ? ' spm-first' : '', $id, $id);
		$res .= sprintf('<h3>%s</h3><div class="pg-content">', $pg->post_title);
		$res .= do_shortcode($pg->post_content);
		$res .= sprintf('</div></div><!-- %s -->', $id);
	}
	$res .= '</div><!-- singlepages --> ';
	$links .= '</ul>';
	return $links . $res;
}

add_shortcode('singlepage', 'csm_singlepage_shortcode');
if (!is_home() && !is_admin()) csm_singlepage_shortcode('admin'); // so that styles load
?>
