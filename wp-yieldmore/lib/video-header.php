<?php
//USAGE: add this to the theme
//if (function_exists('ym_video_header')) ym_video_header();
function ym_video_header() {
	$videos = cs_var('videoHeaders');
	$blogId = get_current_blog_id();
	if (!isset($videos[$blogId])) return;
	$blogVideos = $videos[$blogId];

	$video = $blogVideos['default']; //TODO: check if single and add id if matches

	echo do_shortcode('<header id="video-banner">
<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</header>');
}
?>