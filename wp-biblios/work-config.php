<link rel="stylesheet" href="<?php echo cs_var('adm-base') . '/assets/admin.css'; ?>" type="text/css">
<div class="postbox-container">
  <div class="postbox opened">
<?php
if (!isset($_GET['id']))
{ ?>
	<h3>List of Works</h3>
	<div class="notice-side">
		In post edit, the Work Details box has a link to directly edit the config (see config links to left).<br>
		<?php echo CHtml::link('clear cache', home_url() . '?clearcache=1'); ?><br />
	</div>
	<?php
	$wks = get_posts('post_type=work&posts_per_page=-1');
	foreach ($wks as $itm)
	{
		$wk = cs_work($itm->ID);
		if (!$wk || $wk['fol'] == '') continue;
		_nl(CHtml::link($itm->post_title, get_permalink($itm))
			. ' - ' . cs_work_read($itm->ID, 'cfgSummary'), 1);
	}
	?>
	<h3>Importer</h3>
	<?php
	if (isset($_POST['workImport'])) {
		$import = stripslashes($_POST['workImport']);
		_nl('<pre style="font-size: small;height: 100px;overflow:scroll;">' . $import . '</pre>');
		include_once 'functions-dep.php';
		$import = cs_work_import(0, $import);
	}
	_nl(CHtml::tag('form', array('action' => WorkNav::admin($id), 'method' => 'post') ));
	_nl('<div class="notice-side">');
	_nl('		Copy from old biblios into here for conversion of config / titles.<br /><br />');
	_nl(CHtml::submitButton('Convert Settings'));
	_nl('</div>');
	_nl(CHtml::textArea('workImport', $import, array('cols'=>80, 'rows'=> 5, 'id'=>'newcontent')) );
	_nl(CHtml::endForm());
	?>
	<div class="clear"></div>
<?php }
else
{
	$id = $_GET['id'];
	if (isset($_POST['workConfig']))
	{
		cs_work($id, stripslashes($_POST['workConfig']));
		echo '<div class="notice">Config Saved</div>';
	}

	_nl(sprintf('<h3>Config for %s (#%s @ %s)</h3>', get_the_title($id), $id, cs_work_read($id, 'fol')));
	_nl(CHtml::tag('form', array('action' => WorkNav::admin($id), 'method' => 'post') ));

	_nl('<div class="actions">');
	_nl(CHtml::link('Back to List', WorkNav::admin(), array('class' => 'button button-small') ), 1);
	_nl(CHtml::link('Refresh', WorkNav::admin($id), array('class' => 'button button-small') ), 1);
	_nl(CHtml::link('Edit Work', WorkNav::admin($id, 'work'), array('class' => 'button button-small') ), 1);
	_nl(CHtml::submitButton('Save Config'));
	_nl('</div>');
	_nl(CHtml::textArea('workConfig', cs_work_read($id, 'cfgRaw'), 
		array('cols'=>90, 'rows'=> 10, 'id'=>'newcontent') )); //newcontent so that editarea will work
	_nl(CHtml::endForm());
}
?>

  </div>
</div>
