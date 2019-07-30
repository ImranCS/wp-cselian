<?php
add_filter( 'body_class', function( $classes ) {
	return array_merge( $classes, array('network-name', 'site-' . cs_var('site')) );
} );

if (cs_var('site') === 'mwrw' || cs_var('site') === 'mitc') return;

add_action('wp_footer', 'ym_multisite');

function ym_multisite() {
$btn = ' <button class="dropdown-toggle" aria-expanded="false"><svg class="icon icon-angle-down" aria-hidden="true" role="img"> <use href="#icon-angle-down" xlink:href="#icon-angle-down"></use> </svg><span class="svg-fallback icon-angle-down"></span><span class="screen-reader-text">Expand child menu</span></button>';
?>
<link rel="stylesheet" href="//spanda.org/assets/images/home/multisite.css" type="text/css" />
<li id="sites-li" style="display: none" class="<?php echo cs_var('site') === false ? 'menu-root ' : ''; ?>menu-item menu-item-has-children"><a target="_blank" class="projects" href="//spanda.org/">Spanda Projects</a><?php echo $btn; ?>
	<ul id="sites-ul" class="sub-menu">
		<li class="jubilo menu-item menu-item-has-children"><a target="_blank" href="//spanda.org/work/jubilo/">Jubilo</a><?php echo $btn; ?><ul class="sub-menu">
			<li class="tea"><a target="_blank" href="//teaforpeace.wordpress.com/">Tea for Peace</a></li>
			<li class="mcr"><a target="_blank" href="//middlecouncilrounds.wordpress.com/" title="Middle Council Rounds">Middle Council</a></li></ul>
		</li>
		<li class="musike menu-item menu-item-has-children"><a target="_blank" href="//spanda.org/work/musike/">Musike</a><?php echo $btn; ?><ul class="sub-menu">
			<li class="concerts"><a target="_blank" href="//spanda.org/work/musike/concerts/">Concerts</a></li>
			<li class="musike-journal"><a target="_blank" href="//spanda.org/work/library/musike-journal/">Journal</a></li></ul>
		</li>
		<li class="mantra menu-item menu-item-has-children"><a target="_blank" href="//spanda.org/work/mantra/">Mantra</a><?php echo $btn; ?><ul class="sub-menu">
			<li class="mapuche"><a target="_blank" href="//mapuchechapter.spanda.org/en/">Mapuche Chapter</a></li></ul>
		</li>
		<li class="lila menu-item"><a target="_blank" href="//spanda.org/work/lila/">Lila</a></li>
		<li class="akarma menu-item"><a target="_blank" href="//spanda.org/work/akarma/">Akarma</a></li>
		<li class="mitc menu-item menu-item-has-children"><a target="_blank" href="//projects.spanda.org/mitc/">Meeting in the Cave</a><?php echo $btn; ?><ul class="sub-menu">
			<li class="cave3"><a target="_blank" href="//cave3.org/">Cave 3.0</a></li>
			<li class="mwrw"><a target="_blank" href="//projects.spanda.org/mwrw/" title="Meetings with Remarkable Women">Remarkable Women</a></li></ul>
		</li>
		<li class="journal menu-item"><a target="_blank" href="//spanda.org/library/journal/">Journal</a></li>
		<li class="smf menu-item"><a target="_blank" href="//sahlanmomo.org" title="Sahlan Momo Foundation">SMF</a></li>
	</ul>
</li>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sites-li').show().appendTo('#top-menu');
});
</script>
<?php }?>
