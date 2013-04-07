<?php
function csc_comments_open($open, $post_id )
{
	$post = get_post( $post_id );

	if ( 'page' == $post->post_type || !is_single())
		$open = false;
	
	return $open;
}

function csc_before_header()
{
	global $attitude_theme_options_settings;
	$attitude_theme_options_settings['header_logo'] = csc_var('base') . '/header.png';
}

function csc_head_theme()
{
	if (csc_var('attitude')) csc_style('attitude');
	csc_script('companion.js');
	if (!csc_var('nocss')) csc_style('companion');
}

add_filter('comments_open', 'csc_comments_open', 10, 2 );
add_action ('attitude_before_header', 'csc_before_header');
add_action ('wp_head', 'csc_head_theme');
?>
