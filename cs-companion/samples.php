<?php require_once("../../../wp-config.php"); //wp-content/plugins/cs-companion/
require_once 'cs-companion.php';
csc_var('sample', 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Samples - Cselian WP Companion</title>
<link rel="stylesheet" href="attitude.css" type="text/css">
<link rel="stylesheet" href="companion.css" type="text/css">
<link rel="stylesheet" href="intl/tabber.css" type="text/css">
<script src="intl/tabber-minimized.js" type="text/javascript"></script>
<script src="intl/jquery.js" type="text/javascript"></script>
<script src="js/jquery.cookie.1.3.1.js" type="text/javascript"></script>
<script src="js/fontsize.js" type="text/javascript"></script>
<style type="text/css">
<!--
body { background-color: #fff; }
table { border-collapse: collapse; }
th, td { padding: 2px 4px 2px 4px; text-align: left; }
.tabbertab a { color: #33a; }
#accessibility { float: right; }
#social { width: 330px!important; }
//-->
</style>
  </head>
  <body>
<div id="content">
<?php csc_fontsize(); ?>
<h2>Shortcodes</h2>
These are used in page content to insert images, documents, videos etc.
Use type = img|doc|mp3|vid (defaults to image)
<div class="tabber">
<?php
$groups = array(
 'image' => array(
	'[link src=sample.jpg width=50 enclose=div class=right]' => 'Enclosing div / class',
	'[link src=sample.jpg width=50 enclose=div]' => 'Enclosing div',
	'[link src=sample.jpg width=50 imgclass=rimg enclose=div]' => 'Enclosing div, class on img',
	'[link src=sample.jpg alt=founder width=40 class=right]' => 'class and alt',
 ),
 'post-links' => array(
	'[link type=post id=71]' => 'Id only',
	'[link type=post id=71]my last post[/link]' => 'With text',
	'[link type=post id=71 notitle]the earlier one[/link]' => 'Text and no title',
	'[link type=post id=71 section=history]' => 'With section',
 ),
 'docs' => array(
	'[link type=doc]registration form[/link]' => 'Doc only',
	'[link type=doc ext=zip]zip file[/link]' => 'Custom extension',
	'[link type=doc file=noextn]empty file[/link]' => 'No extension',
	'[link type=doc file=sample.pdf]guidelines[/link]' => 'Pdf to show icon',
	'[link type=doc file=sample.xls]report[/link]' => 'Xl to show icon',
 ),
 'misc' => array(
	'[link type=mp3 width=200]' => 'Audio only',
	'[link type=mp3 file=http://cselian.com/d/books/audio/roark.mp3 width=200]' => 'Audio with absolute url',
	'[gallery type=slide id=social fol=logos images=youtube.gif|facebook.gif|twitter.png links=www.youtube.com/user/cseliantech|www.facebook.com/underthepeacocktree|www.twitter.com/SpandaFoundatio height=33]' => 'gallery',
	'[gallery type=slide fol=home images=one.jpg|two.jpg links=1|ms.com height=320 width=300 enclose=div]' => 'Gallery with slideshow',
 ),
 'imglink' => array(
	'[link href=momo.org src=sample.jpg width=50 target=_new class=right]' => 'Image with href link',
	'[link id=20 src=sample.jpg width=50 target=_new class=right]' => 'Image with post link',
	'[link type=wiki url=Jimmy_Wales section=Political_and_economic_views]Jimbo[/link]' => 'Wikipedia Link with section',
	'[link id=40 src=sample.jpg width=53 height=48]<br/>Link Text[/link]' => 'Image, link with content',
 ),
 'snippets' => array(
	'[snippet id=paypal]' => 'paypal button',
 ),
);

$fmt = '<tr><td rowspan="2" width="130">%s</td><td rowspan="2" width="200">%s</td><td>%s</td></tr><tr><td>%s</td></tr>
';
$th = str_replace('<td', '<th', str_replace('</td>', '</th>', $fmt));

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'link';
function csbib_tab($id)
{
	;
}

foreach ($groups as $name => $shortcodes)
{
	echo sprintf('<div class="tabbertab%s"><h2>%s</h2>', $tab == $name ? ' tabbertabdefault' : '', $name);
	echo '<table border="1">';
	echo sprintf($th, 'What', 'Output', 'Code', 'Html');
	foreach ($shortcodes as $code => $text)
	{
		// ENT_QUOTES
		$op = do_shortcode($code);
		$code = htmlspecialchars($code);
		echo sprintf($fmt, $text, $op, $code, htmlspecialchars($op));
	}
	echo '</table></div>';
}
?>
</div><!-- tabber -->
</div><!-- content -->
</body>
</html>
