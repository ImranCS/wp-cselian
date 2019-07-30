<?php
add_action( 'astra_content_before', 'ym_before_content' );

function ym_content_replace_vars($c) {
    $vars = cs_site_var('contentVars');
    if (!is_array($vars)) return $c;
    foreach ($vars as $key)
        $c = str_replace('%' . $key . '%', str_replace("
", "<br />
", cs_site_var($key)), $c);
    return do_shortcode($c);
}

function ym_before_content() {
    $logged_in = is_user_logged_in();
    $name = 'beforeContent';

    $var = cs_site_var($name);
    if ($var) echo ym_content_replace_vars($var);

    $var = cs_site_var($name . 'LoggedIn');
    if ($logged_in && $var) echo ym_content_replace_vars($var);

    $var = cs_site_var($name . 'Guest');
    if (!$logged_in && $var) echo ym_content_replace_vars($var);
}
?>
