<?php require_once("../../../wp-config.php"); //wp-content/plugins/page gen/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Page Index - Spanda</title>
<style type="text/css">
<!--
table { border-collapse: collapse; }
th, td { padding: 2px 4px 2px 4px; text-align: left; }

//-->
</style>
  </head>
  <body>
<?php
include_once 'generator.php';
$gen = new CSPageGenerator();

$gen->readNext();

echo '<table border="1">';
$fmt = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>
';
echo sprintf(str_replace('td>', 'th>', $fmt), 'Ix', 'Url', 'View', 'Edit', 'Old');

$olds = $gen->readOldies();
$ix = 0;
foreach ($gen->existing as $id=>$item)
{
	$vw = str_replace('href', 'target="sp_v'.$id.'" href', $gen->itemText($item));
	$ed = sprintf('<a target="sp_e%s" href="%s/wp-admin/post.php?post=%s&action=edit">edit</a>', $id, $gen->baseUrl, $id);
	$old = $ix < count($olds) ? $olds[$ix] : '';
	$ix++;
	echo sprintf($fmt, $ix, $item['slug'], $vw, $ed, $old);
}
echo '</table>';
?>
  </body>
</html>
