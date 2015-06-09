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
	}

	var $fol, $current;

	function do_shortcode()
	{
		$this->register_scripts();

		$this->fol = dirname(__FILE__) . '/../../../../assets/albums';

		$this->link_albums();
		$this->show_images();
	}

	function register_scripts()
	{
		wp_register_style('css-prettyphoto', cs_var('siteurl') . '../_assets/prettyPhoto.css');
		wp_enqueue_style('css-prettyphoto');
	
		wp_register_script('css-prettyphotoinit', cs_var('siteurl') . '../_assets/prettyPhoto.js', array('css-prettyphoto'));
		wp_enqueue_script('css-prettyphotoinit');

		wp_register_script('css-prettyphoto', cs_var('siteurl') . '../_assets/jquery.prettyPhoto.js', array('jquery'));
		wp_enqueue_script('css-prettyphoto');
	}

	function link_albums()
	{
		$albums = scandir($this->fol);
		$this->current = cs_get('album', $albums[2]);
		$url = get_permalink() . '?album=';
		echo '<div class="albums"><strong>Albums</strong>: ' . PHP_EOL;
		foreach ($albums as $album)
		{
			if ($album == '.' || $album == '..') continue;
			echo sprintf('<a href="%s"%s>%s</a>' . PHP_EOL, $url . $album, $album == $this->current ? ' class="current"' : '', $album);
		}
		echo '</div>' . PHP_EOL . PHP_EOL;
	}

	function show_images()
	{
		$fol = $this->fol . '/' . $this->current;
		$imgs = scandir($fol);
		echo '<div class="photos">' . PHP_EOL;
		$ix = 1;
		$url = home_url() . '/assets/albums/' . $this->current;
		foreach ($imgs as $img)
		{
			if ($img == '.' || $img == '..' || $img == 'tn') continue;
			$this->ensure_thumbnail_exists($fol, $img);
			$title = $ix . ' / ' . ucwords(str_replace('-', ' ', str_replace('.jpg', '', $img)));
			echo sprintf('  <a href="%s/%s" rel="prettyPhoto[pic]"><img src="%s/tn/%s" alt="%s" /></a>' . PHP_EOL,
				$url, $img, $url, $img, $title);
			$ix++;
		}
		echo '</div>' . PHP_EOL;
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