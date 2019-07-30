<?php
/*
* Imran@cselian.com, June 2015
* Based on the albums of lastresort.co.in and gani.co.in
*/
class CSAlbums
{
	function __construct()
	{
		add_shortcode('albums', array($this, 'do_shortcode'));
		add_shortcode('album', array($this, 'do_shortcode'));
	}

	var $fol, $url, $current, $single;

	function do_shortcode($a, $content = null)
	{
		$this->register_scripts();

		if (!is_array($a)) $a = array();
		$this->single = !is_user_logged_in();
		$this->current = !$this->single && isset($_GET['album']) ? $_GET['album'] : $a['fol'];
		$this->fol = dirname(__FILE__) . '/../../../../images/' . cs_var('siteName') . '/photos/';
		$this->url = home_url() . '/images/' . cs_var('siteName') . '/photos/' . $this->current;

		$r = $this->link_albums();
		$r .= $this->show_images();
		return $r;
	}

	function register_scripts()
	{
		wp_register_style('css-prettyphoto', cs_var('assets-url') . 'prettyPhoto.css');
		wp_enqueue_style('css-prettyphoto');
	
		wp_register_script('css-prettyphoto', cs_var('assets-url') . 'jquery.prettyPhoto.js', array('jquery'));
		wp_enqueue_script('css-prettyphoto');
	}

	function link_albums()
	{
		if ($this->single) return '';
		$albums = scandir($this->fol);
		$this->current = isset($_GET['album']) ? $_GET['album'] : $albums[2];
		$url = get_permalink() . '?album=';
		$r = '<div class="albums"><strong>Albums</strong>: <ol>' . PHP_EOL;
		foreach ($albums as $album)
		{
			if ($album == '.' || $album == '..') continue;
			$r .= sprintf('  <li><a href="%s"%s>%s</a></li>' . PHP_EOL, $url . $album, $album == $this->current ? ' class="current"' : '', str_replace('-', ' ', $album));
		}
		$r .= '</ol></div>' . PHP_EOL . PHP_EOL;
		return $r;
	}

	function show_images()
	{
		$fol = $this->fol . '/' . $this->current;
		$imgs = scandir($fol);
		$r = '<div class="photos">' . PHP_EOL;
		$ix = 1;
		foreach ($imgs as $img)
		{
			if ($img == '.' || $img == '..' || $img == 'tn') continue;
			$this->ensure_thumbnail_exists($fol, $img);
			$title = $ix . ' / ' . ucwords(str_replace('-', ' ', str_replace('.jpg', '', $img)));
			$r .= sprintf('  <a href="%s/%s" title="%s" rel="prettyPhoto[pic]"><img src="%s/tn/%s" alt="%s" /></a>' . PHP_EOL,
				$this->url, $img, $title, $this->url, $img, $title);
			$ix++;
		}
		$r .= '</div>' . PHP_EOL;
		return $r;
	}

	function ensure_thumbnail_exists($fol, $img)
	{
		$tn = $fol . '/tn/';

		if (!is_dir($tn)) mkdir($tn);
		if (file_exists($tn . $img)) return;

		$image = wp_get_image_editor($fol . '/' . $img);
		if ( ! is_wp_error( $image ) ) {
			$image->resize( 150, 150, true );
			$image->save($tn . $img);
		}
	}
}
new CSAlbums();
?>