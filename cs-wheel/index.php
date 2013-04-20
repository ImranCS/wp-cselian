<?php
if (!function_exists('add_action'))
{
  require_once(ABSPATH . "wp-config.php");
  require_once("cs-wheel.php");
}

$links = _csw_getlinks(); extract($links);

include 'render.php';
?>
