<?php
add_action('wp_footer', 'ym_sites_tlm');

function ym_sites_tlm() {
  wp_register_script('ym-sites-tlm', cs_var('assets-url') . 'sites-top-level-menu.js');
  wp_enqueue_script('ym-sites-tlm');

  $blogId = get_current_blog_id();
  $blogUrl = home_url();
  $blogName = 'Finding...';
  $sites = '';
  $siteItem = '    <li><a href="%s">%s</a></li>' . PHP_EOL;

  $blogs = get_sites();
  foreach( $blogs as $b ){
    if ($b->blog_id == $blogId) {
      $blogName = get_bloginfo('name');
      continue;
    }

    switch_to_blog( $b->blog_id );
    $sites .= sprintf($siteItem, $b->path, get_bloginfo('name'));
    restore_current_blog();
  }

  
$btn = ' <button class="submenu-expand" tabindex="-1"><svg class="svg-icon" width="24" height="24" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"></path><path fill="none" d="M0 0h24v24H0V0z"></path></svg></button>';
?>
<li id="sites-li" class="menu-item current-menu-item menu-item-has-children" style="display: none;">
  <a class="projects" href="<?php echo $blogUrl; ?>"><?php echo $blogName; ?></a><?php echo $btn; ?>
  <ul id="sites-ul" class="sub-menu">
    <?php echo $sites; ?>
  </ul>
</li>
<?php
}
?>
