<?php
/*
Plugin Name: CS Wheel
Plugin URI: http://github.com/ImranCS/wp-cselian/wiki/Wheel
Description: Provides a Wheel style layout for the home page.
Version: .1
Author: <a href="mailto:imran@cselian.com">Imran Ali Namazi</a>Imran
Author URI: http://cselian.com/blog/about
*/

function csw_menu() {
  $x_menu = add_options_page('CS Wheel', 'CS Wheel', 'manage_options', 'cs-wheel', 'csw_menu_manager');
}

function csw_menu_manager() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  if(isset($_GET['page']) || isset($_POST['page']))
  if($_GET['page']=='cs-wheel' || $_POST['page']=='cs-wheel')
  {
    $projectRoot = 7;
    echo '<link rel="stylesheet" href="../wp-content/plugins/cs-wheel/admin.css" type="text/css">';
    if(isset($_GET['sub']) || isset($_POST['sub']))
    {
      $sub = isset($_GET['sub']) ? $_GET['sub'] : $_POST['sub']; 
      include 'settings-' . $sub . '.php';
    }
    else
    {               
      include 'settings-home.php';
    }
  }
}

function jquery_admin() {
    global $concatenate_scripts;

    $concatenate_scripts = false;
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', ( 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' ), false, '1.x', true );
}

add_action( 'admin_init', 'jquery_admin' );
add_action( 'admin_menu', 'csw_menu');

function __csw_clear($sample) {
  global $wpdb;
  $sql = "delete from cs_wheel_links"; 
  $wpdb->query($sql);
  $msg = "cs_wheel_links table emptied";
  if (!$sample) return $msg;
  
  $names = array(); $names = array(); $lefts = array(); $tops = array(); $heights = array(); $widths = array(); $urls = array();
  $names[] = 'programming'; $lefts[] = 20; $tops[] = 100; $widths[] = 58; $heights[] = 112; $urls[] = 'http://cselian.com/blog/category/tech/';
  $names[] = 'spirituality'; $lefts[] = 22; $tops[] = 30; $widths[] = 85; $heights[] = 65; $urls[] = 'http://biblios.cselian.com/savithri/';
  $names[] = 'peace'; $lefts[] = 43; $tops[] = 18; $widths[] = 50; $heights[] = 24; $urls[] = 'http://cselian.com/blog/?s=peace';
  $names[] = 'music'; $lefts[] = 59; $tops[] = 22; $widths[] = 52; $heights[] = 26; $urls[] = 'http://cselian.com/blog/?s=music';
  $names[] = 'books'; $lefts[] = 75; $tops[] = 40; $widths[] = 44; $heights[] = 46; $urls[] = 'http://biblios.cselian.com/';
  $names[] = 'karma'; $lefts[] = 79; $tops[] = 86; $widths[] = 44; $heights[] = 54; $urls[] = '#karma';
  $names[] = 'life'; $lefts[] = 71; $tops[] = 152; $widths[] = 28; $heights[] = 34; $urls[] = '#life';
  $names[] = 'thracian'; $lefts[] = 51; $tops[] = 202; $widths[] = 28; $heights[] = 78; $urls[] = '#thracian';
  $names[] = 'mati'; $lefts[] = -1; $tops[] = 294; $widths[] = 32; $heights[] = 48; $urls[] = '#mati';
  $names[] = 'lance'; $lefts[] = -43; $tops[] = 302; $widths[] = 44; $heights[] = 36; $urls[] = '#lance';
  $names[] = 'clocks'; $lefts[] = 228; $tops[] = 308; $widths[] = 50; $heights[] = 36; $urls[] = 'http://ganiandsons.com/';
  $names[] = 'micro'; $lefts[] = 114; $tops[] = 332; $widths[] = 50; $heights[] = 24; $urls[] = 'http://cselian.com/imran/electronics/buzzer.php';
  $names[] = 'vb'; $lefts[] = 16; $tops[] = 330; $widths[] = 30; $heights[] = 22; $urls[] = '#vb';
  $names[] = '.net'; $lefts[] = -64; $tops[] = 310; $widths[] = 40; $heights[] = 27; $urls[] = 'http://cselian.com/blog/?s=.net';
  $names[] = 'php'; $lefts[] = -148; $tops[] = 276; $widths[] = 38; $heights[] = 32; $urls[] = 'http://cselian.com/blog/?s=php';
  $names[] = 'html'; $lefts[] = -232; $tops[] = 230; $widths[] = 42; $heights[] = 42; $urls[] = 'http://cselian.com/blog/?s=html';
  $names[] = 'css'; $lefts[] = -284; $tops[] = 186; $widths[] = 24; $heights[] = 30; $urls[] = 'http://cselian.com/blog/?s=css';
  $names[] = 'art'; $lefts[] = -316; $tops[] = 142; $widths[] = 24; $heights[] = 32; $urls[] = 'http://art.cselian.com';
  $names[] = 'blog'; $lefts[] = -332; $tops[] = -54; $widths[] = 40; $heights[] = 34; $urls[] = 'http://cselian.com/blog/';
  $names[] = 'ian'; $lefts[] = 60; $tops[] = -94; $widths[] = 68; $heights[] = 40; $urls[] = 'http://cselian.com/imran/home/';
  $names[] = 'webring'; $lefts[] = 12; $tops[] = 232; $widths[] = 80; $heights[] = 40; $urls[] = 'http://between-spaces.blogspot.com/';
  $names[] = 'genenis'; $lefts[] = 302; $tops[] = 232; $widths[] = 42; $heights[] = 32; $urls[] = 'http://cselian.com/imran/home/genesis.html';
  
  __csw_save($names, $lefts, $tops, $heights, $widths, $urls);
  $msg .= "<br>samples saved";
  return $msg;
}

function __csw_update() {
  global $wpdb;
  $sql = "delete from cs_wheel_links"; 
  $wpdb->query($sql);
  $names = $_POST['names'];
  $lefts = $_POST['lefts'];
  $tops = $_POST['tops'];
  $heights = $_POST['heights'];
  $widths = $_POST['widths'];
  $urls = $_POST['urls'];
  __csw_save($names, $lefts, $tops, $heights, $widths, $urls);
  return "config updated";
}

function _csw_getlinks() {
  global $wpdb;
  $sql = "select * from cs_wheel_links order by id";
  $links = $wpdb->get_results($wpdb->prepare($sql));
  $names = array(); $names = array(); $lefts = array(); $tops = array(); $heights = array(); $widths = array(); $urls = array();
  foreach ($links as $l) {
    $names[] = $l->name; $lefts[] = $l->left; $tops[] = $l->top; $heights[] = $l->height; $widths[] = $l->width; $urls[] = $l->url; 
  }
  return array('names' => $names, 'lefts' => $lefts, 'tops' => $tops, 
    'heights' => $heights, 'widths' => $widths, 'urls' => $urls);
}

function __csw_save($names, $lefts, $tops, $heights, $widths, $urls) {
  global $wpdb;
  $sql = "insert into cs_wheel_links values
";
  $fmt = "(%s, '%s', %s, %s, %s, %s, '%s')
";
  $cnt = count($names);
  for ($i = 0; $i < $cnt ; $i++) {
    if ($names[$i] == "") continue;
  	if ($i != 0) $sql .= ", ";
    $sql .= sprintf($fmt, $i+1, $names[$i], $lefts[$i], $tops[$i], $widths[$i], $heights[$i], $urls[$i]); 
  }
  $wpdb->query($sql);
}

?>
