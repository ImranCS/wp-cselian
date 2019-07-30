<?php
add_shortcode('conditional', 'ym_conditional_shortcode');

function ym_conditional_shortcode($a, $content) {
	$logged_in = is_user_logged_in();
	if (!$logged_in && array_search('protected', $a) !== false) return '';
	if ($logged_in && array_search('guest', $a) !== false) return '';
	return $content;
}
?>
