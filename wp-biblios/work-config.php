<link rel="stylesheet" href="<?php echo cs_var('bib-base') . '/assets/admin.css'; ?>" type="text/css">
<div class="postbox-container">
  <div class="postbox opened">
<?php
if (!isset($_GET['id']))
{ ?>
	<h3>List of Works</h3>
	<div class="notice-side">
		In post edit, the Work Details box has a link to directly edit the config.<br>
		Select a work below to edit its configuration<br />
	</div>
	<?php
	$wks = get_posts('post_type=work');
	foreach ($wks as $itm)
	{
		$wk = cs_work($itm->ID);
		if (!$wk || $wk['fol'] == '') continue;
		_nl(CHtml::link($itm->post_title, get_permalink($itm)), 1);
	}
	?>
<?php }
else
{
	$id = $_GET['id'];
	$wk = cs_work_read($id);
	$file = $wk['cfgFile'];
	_nl(sprintf('<h3>Config for %s (#%s @ %s)</h3>', get_the_title($id), $id, $wk['fol']));
	if (isset($_POST['workConfig']))
	{
		file_put_contents($file, stripslashes($_POST['workConfig']));
		echo '<div class="notice">Config Saved</div>';
	}
	
	_nl(CHtml::tag('form', array('action' => WorkNav::admin($id), 'method' => 'post') ));

	_nl('<div class="actions">');
	_nl(CHtml::link('Back to List', WorkNav::admin(), array('class' => 'button button-small') ), 1);
	_nl(CHtml::link('Refresh', WorkNav::admin($id), array('class' => 'button button-small') ), 1);
	_nl(CHtml::link('Edit Work', WorkNav::admin($id, 'work'), array('class' => 'button button-small') ), 1);
	_nl(CHtml::submitButton('Save Config'));
	_nl('</div>');

	_nl(CHtml::textArea('workConfig', file_get_contents($file), 
		array('cols'=>180, 'rows'=> 35, 'id'=>'newcontent') ));
	_nl(CHtml::endForm());
}
?>

  </div>
</div>
