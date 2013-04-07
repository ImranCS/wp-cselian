<?php require_once("../../../wp-config.php"); //wp-content/plugins/page gen/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Page Generator - Cselian</title>
<style type="text/css">
<!--
table { border-collapse: collapse; }
th, td { padding: 2px 4px 2px 4px; text-align: left; }

//-->
</style>
  </head>
  <body>
<h2>Welcome to Cselian's Wordpress Page Generator</h2>
To get started, please edit the <a target="_new" href="<?php csgen_editLink('pages-list.tsv'); ?>">Page List Csv</a>.<br />
Keep that page open as you will need to keep editing it as you import more and more pages. <br />
Ids and parent_ids are filled step by step because flat csvs are easier than a hierarchic input<br /><br />

<?php
include_once 'generator.php';
$gen = new CSPageGenerator();

$gen->readNext();
if ($_GET['gen'] == 'next')
{
	$gen->createNext();
	echo '<table border="1">';
	$gen->printCreated();
	echo '</table><br />
	Please include the New Id of the pages that were created in the respective row of the csv file and then '
	. '<a href="' . str_replace('?gen=next', '', $_SERVER['REQUEST_URI']) . '">try again</a>';
}
else if (count($gen->next) > 0)
{
	echo '<table border="1">';
	$gen->printNext();
	echo '</table>
<form method="get">
<input type="hidden" name="gen" value="next" />
<input type="submit" value="Generate These Pages" />
</form> or <a href="' . str_replace('?gen=next', '', $_SERVER['REQUEST_URI']) . '">reload page</a>';
}
else
{
	echo 'There are no pages to generate.';
}
?>
  </body>
</html>
