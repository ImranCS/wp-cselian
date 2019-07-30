<?php
//https://www.acmethemes.com/blog/2017/10/excerpt-field-wordpress/
add_post_type_support( 'page', 'excerpt' );
add_post_type_support( 'post', 'excerpt' );

add_action('wp_head', 'ym_description');
//https://www.kohactive.com/blog/wordpres-hacks-using-post-excerpts-as-meta-descriptions
function ym_description() {
	if (is_single() || is_page()) {
		global $post;
		$ex = get_the_excerpt($post->ID);
		if (!$ex) return;

		$template = cs_var('base') . 'meta.html';
		$template = file_exists($template) ? file_get_contents($template) : false;
		$replaces = array();
		if ($template) {
			if (cs_var('meta_description')) $replaces['description'] = cs_var('meta_description');
			if (cs_var('meta_keywords')) $replaces['keywords'] = cs_var('meta_keywords');
			if (cs_var('meta_email')) $replaces['email'] = cs_var('meta_email');
		}

		$isMeta = stripos($ex, '<meta');
		$isColon = stripos($ex, ':');
		if ($isMeta !== false) {
			echo $ex;
		} else if ($isColon !== false) {
			$lines = explode(PHP_EOL, $ex);
			foreach ($lines as $l) {
				$isLineColon = stripos($l, ':');
				if ($isLineColon !== false) {
					$bits = explode(':', $l, 2);
					if ($template)
						$replaces[$bits[0]] = trim($bits[1]);
					else
						echo '<meta name="' . $bits[0] . '" content="' . trim($bits[1]) . '" />' . PHP_EOL;
				} else {
					echo $l . PHP_EOL;
				}
			}
			if ($template) {
				foreach ($replaces as $key=>$value)
					$template = str_replace('%' . $key . '%', $value, $template);
				echo $template . PHP_EOL;
			}
		} else {
			echo '<meta name="description" content="' .  $ex . '" />' . PHP_EOL;
		}
	} else if(is_home())  {
		//echo '<meta name="description" content="Welcome to Spanda">' . PHP_EOL;
	} else if(is_category()) {
		echo '<meta name="description" content="' . strip_tags(category_description(get_category_by_slug(strtolower(get_the_category()))->term_id)) . '" />' . PHP_EOL;
	}
}
?>