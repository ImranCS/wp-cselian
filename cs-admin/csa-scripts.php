<?php
class CSScripts
{
	function admin()
	{
		echo '<link rel="stylesheet" href="' . cs_var('adm-base') . '/assets/admin.css" type="text/css">';
	}

	function tabber()
	{
		wp_register_script('tabber', cs_var('adm-base') . '/assets/tabber-minimized.js');
		wp_enqueue_script('tabber');
		wp_register_style('tabber-css', cs_var('adm-base') . '/assets/tabber.css');
		wp_enqueue_style('tabber-css');
	}
}
?>
