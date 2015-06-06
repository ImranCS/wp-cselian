<?php

function css_assets_shortcode($a, $content = null)
{
	return str_replace('wp-content/', '', content_url('assets/'));
}
add_shortcode('assets', 'css_assets_shortcode');

function css_images_shortcode($a, $content = null)
{
	return str_replace('wp-content/', '', content_url('assets/images/'));
}
add_shortcode('images', 'css_images_shortcode');

function css_docs_shortcode($a, $content = null)
{
	return str_replace('wp-content/', '', content_url('assets/docs/'));
}
add_shortcode('docs', 'css_docs_shortcode');

?>