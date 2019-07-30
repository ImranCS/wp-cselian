<?php
//replaces a medium dash — with a new line from bloginfo('description')
function ym_bloginfo_filter($value) {
    return str_replace(' — ', '<br/>' . PHP_EOL, $value);
}

add_filter('option_blogdescription', 'ym_bloginfo_filter', 40);
?>
