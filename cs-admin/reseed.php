<?php
include_once 'csa-base.php';
class CSAdminReseed extends CSAdminBase
{
	// Include any extra tables that point to posts
	public static $tables = array(
		'postmeta' => 'post_id',
		'term_relationships' => 'object_id',
		'posts' => 'ID'
	);

	public $ok;
	public $del;
	public $fix;
	public $seed = 1;

	function __construct()
	{
		if ($this->isAction('del')) $this->delete();
		else if ($this->isAction('fix') || $this->isAction('change')) $this->fix(); 

		$this->okOrMessed();
		$this->deletable();
	}

	function delete()
	{
		if (function_exists('tw_disable_revisions_install')) {
			tw_disable_revisions_install();
			$this->msg= 'Cleared Revisions';
			$this->log('Cleared Revisions');
		} else {
			$this->msg= 'Need to install the tw_disable_revisions plugin';
		}
	}
	
	function deletable()
	{
		global $wpdb;
		$del = $wpdb->get_results($wpdb->prepare( "select `id`, post_title, post_type, post_parent, post_status from wp_posts where post_status = 'trash' or post_type = 'revision'"), ARRAY_A);
		foreach ($del as $itm)
		{
			$s = 'revision';
			if ($itm['post_status'] == 'trash')
			{
				$t = get_post_type($itm['id']);
				$s = CHtml::link(ucfirst($t . 's Trashcan'), admin_url('edit.php?post_status=trash&post_type=' . $t));
			}
			else
			{
				$s .= ' ' . CHtml::link('edit', get_edit_post_link($itm['id'], ''));
			}

			$this->del[] = sprintf('%s (%s)', $itm['id'] . ' ' . $itm['post_title'], $s);
		}
	}
	
	function fix()
	{
		$err = '';
		if (!isset($_POST[$this->action . 'Id']) || !isset($_POST[$this->action . 'To']))
			$err = 'Id and Seed need to be set';
		else if ($_POST[$this->action . 'Id'] == NULL)
			$err = 'Select a post to fix first';
		else if ($_POST[$this->action . 'To'] == '')
			$err = 'Enter a id to change it to';
			
		if ($err != '')
		{
			$this->msg = $err . '! Please try again.';
			return;
		}
		
		$from = $_POST[$this->action . 'Id'];
		$to = $_POST[$this->action . 'To'];
		$this->log(sprintf('Changed #%s (%s) to #%s', $from, get_the_title($from), $to));

		global $wpdb;
		$sql = sprintf('select post_title, post_type, post_status from %sposts where ID = %s', $wpdb->prefix, $to);
		$e = $wpdb->get_results($wpdb->prepare($sql), ARRAY_N);
		if (count($e) > 0)
		{
			$e = $e[0];
			$this->msg= sprintf('Cannot overwrite %s where %s, a %s with status "%s" exists.', $to, $e[0], ucFirst($e[1]), $e[2]);
			return;
		}
		
		$this->msg= sprintf('Changed %s to %s', $from, $to);
		
		foreach (self::$tables as $tbl=>$id)
		{
			$sql = sprintf('update %s%s set %s = %s where %s = %s', $wpdb->prefix, $tbl, $id, $to, $id, $from);
			$wpdb->query($sql); 
		}
	}
	
	function okOrMessed()
	{
		$wks = get_posts('post_type=any&orderby=ID&order=ASC');
		$id = 0;
		$messed = false;
		foreach ($wks as $itm)
		{
			$messed = $messed || $itm->ID != $id + 1;
			$show = sprintf('%s %s - %s', $itm->ID, $itm->post_title, 
				CHtml::link('view ' . ucfirst(get_post_type($itm->ID)), get_permalink($itm)));
				
			if (!$messed)
			{
				$this->ok[$itm->ID] = $show;
				$id++;
				$this->seed = $id + 1;
			}
			else
			{
				$this->fix[$itm->ID] = $show;
			}
		}
	}
}
$rs = new CSAdminReseed();
?>
<link rel="stylesheet" href="<?php echo cs_var('bib-base') . '/assets/admin.css'; ?>" type="text/css">
<div class="postbox-container">
	<div class="postbox opened">
	<h2>Reseed</h2>
	As you create revisions and trash posts, you will IDs are no longer contiguous
		(20 posts with IDs like 45, 68 and 112)<br />
	This tool helps you remove revisions and clean up these IDs.<br /><br />
<?php
_nl(CHtml::link('Refresh', '?page=' . CSAdmin::$reseedSlug, array('class' => 'button button-small') ), 1);
_nl('', 1); _nl('', 1);

$rs->head('Deletable posts');
$rs->message('del');
if (count($rs->del) == 0) {
	_nl('Nothing to delete. There are no revisions / trashed posts', 1);
} else {
	$rs->form('del');
	$rs->show($rs->del);
	_nl('', 1); _nl('', 1);
	_nl(CHtml::submitButton('Delete'));
	_nl(CHtml::endForm());
}	
_nl('', 1); _nl('', 1);

$rs->head('Contiguous posts');
$rs->message('change');
if (count($rs->ok) == 0) {
	_nl('All posts are contiguous', 1);
} else {
	$rs->form('change');
		_nl('', 1);
	echo CHtml::radioButtonList('changeId', $_POST['changeId'] , $rs->ok);
	_nl('', 1); _nl('', 1);
	_nl(CHtml::submitButton('Change to ') . CHtml::textField('changeTo', '', array('style'=> 'width:40px;')));
	_nl(CHtml::endForm());
}

$rs->head('Posts to Fix');
$rs->message('fix');
if (count($rs->fix) == 0) {
	_nl('Nothing to fix. All posts are contiguous', 1);
} else {
	$rs->form('fix');
		_nl('', 1);
	echo CHtml::hiddenField('fixTo', $rs->seed);
	echo CHtml::radioButtonList('fixId', $_POST['fixId'] , $rs->fix);
	_nl('', 1); _nl('', 1);
	_nl(CHtml::submitButton('Change to ' .$rs->seed));
	_nl(CHtml::endForm());
}
?>
	</div>
</div>
