<?php
function tg_head_theme()
{
	cs_var('header-image', do_shortcode('[link src=cselian.gif return=src]'));
	csc_style('styles', 1); // unable to enqueue style after attitude_style-css
	add_action ('bit_content_container_end', 'tg_content_end');
	add_action ('bit_main_container_end', 'tg_container_end');
	remove_action( 'bit_footer_credit', 'orbit_credit');
	add_action( 'bit_footer_credit', 'tg_credit');
}
add_action ('wp_head', 'tg_head_theme');

function tg_content_end() {
	echo '</div><div id="footer-wrapper">';
}
function tg_container_end() {
	echo '</div>';
}
function tg_credit() {
	echo 'Powered by <a href="http://wordpress.org/" title="Semantic Personal Publishing Platform">WordPress</a> and based on
		<a href="http://bitado.com/" title="Orbit Theme by bitados.com">Orbit</a>.';
}

function csc_menu()
{
	ob_start();
	orbit_custom_menu( 'header-menu', array( 'dropdown', 'dropdown-horizontal' ), 'main-nav', 0 );
	$res = ob_get_clean();
	$end = '</ul></div>';
	$docs = 3;
	$link = sprintf('<li class="posts"><a href="%s">%s</a></li>', get_category_link($docs), get_the_category_by_ID($docs) );
	echo str_replace($end, $link . $end, $res);
	ob_end_clean();
}


cs_var('orbit-home-boxes', array(
	1 => '<h2>Why choose us</h2>
<ul>
	<li>We do turnkey IT development projects.</li>
	<li>We put our diverse [link type=post id=7]experience[/link] at your disposal.</li>
	<li>We believe in quality and sound ability.</li>
	<li>We help you meet project goals by careful analysis and communication.</li>
	<li>Concept, design and engineering of [link type=post id=3]websites[/link].</li>
</ul>
See more of our services [link type=post id=2]here[/link].',
	2 => '<h2>What we do</h2>
<div style="white-space: pre;">Content / Portfolio websites
eCommerce / Business websites
Website and logo design
Desktop and Mobile apps
Retail and oem apps
Project Management / Consulting
Developer mentoring</div>',
	3 => '<h2>What technologies we use</h2>
<ul>
	<li>Php / .Net <a href="http://ivy.cselian.com">windows apps</a> and websites.</li>
	<li>MVC Frameworks like Yii for business sites</li>
	<li>Wordpress for content / portfolio sites</li>
	<li>Microvic for lightweight websites</li>
	<li>Windows 7 &amp; 8 Mobile Programming.</li>
	<li>Word and VB Automation</li>
	<li>Embedded Programming with <a href="http://mini.cselian.com">mini electronics</a>.</li>
</ul>'
));

cs_var('orbit-footer-boxes', array(
	1 => '					<h2>Pages</h2>
					[link type=post id=1][/link]
					[link type=post id=2][/link]
					[link type=post id=4][/link]
					[link type=post id=6][/link]
',
	3 => '					<h2>Contact</h2>
					<p>Phone <strong>+91 95661 66880</strong></p>
					<p>Email <strong>imran@cselian.com</strong></p>
',
	'copy' => sprintf('&copy 2005 - %s, Imran Ali Namazi / Cselian Tech', date('Y') )
));
?>
