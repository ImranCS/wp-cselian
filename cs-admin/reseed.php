<?php
include_once 'csa-base.php';
class CSAdminReseed extends CSAdminBase
{
	// Include any extra tables that point to posts
	public static $tables = array( // dont want a tuple / 2dim array, but have to support multiple fields in the same table
		'postmeta', 'post_id',
		'term_relationships', 'object_id',
		'posts', 'post_parent',
		'posts', 'ID',
	);

	public $ok;
	public $del;
	public $fix;
	public $rem;
	public $seed = 1;
	public $tblSeed;
	public $tblNext;

	function __construct()
	{
		CSScripts::admin();
		if ($this->isAction('del')) $this->delete();
		else if ($this->isAction('fix') || $this->isAction('change')) $this->fix();
		else if ($this->isAction('remove')) $this->remove();

		$this->seed();
		$this->okOrMessed();
		$this->deletable();
	}

	function remove()
	{
		$id = $_POST[$this->action . 'Id'];
		wp_delete_post($id, 1);
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
	
	function seed()
	{
		global $wpdb;
		$sql = sprintf("SHOW TABLE STATUS LIKE '%sposts'", $wpdb->prefix);
		$res = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
		$this->tblSeed = $res[0]['Auto_increment'];

		$sql = sprintf("select max(id) from %sposts", $wpdb->prefix);
		$max = $wpdb->get_col($wpdb->prepare($sql));
		$this->tblNext = $max[0] + 1;
		
		if ($this->isAction('setseed'))
		{
			$this->msg = sprintf('Set seed from %s to %s', $this->tblSeed, $this->tblNext);
			$this->tblSeed = $this->tblNext;
			$sql = sprintf('ALTER TABLE %sposts AUTO_INCREMENT = %s', $wpdb->prefix, $this->tblNext);
			$wpdb->query($sql);
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
		
		$ub = count(self::$tables);
		for ($i = 0; $i < $ub ; $i+=2)
		{
			$tbl = self::$tables[$i]; $id = self::$tables[$i + 1];
			$sql = sprintf('update %s%s set %s = %s where %s = %s', $wpdb->prefix, $tbl, $id, $to, $id, $from);
			if (1) $wpdb->query($sql); else _nl($sql, 1);
		}
	}
	
	function okOrMessed()
	{
		$posts = get_posts('post_type=any&orderby=ID&order=ASC');
		$id = 0;
		$messed = false;
		foreach ($posts as $itm)
		{
			$messed = $messed || $itm->ID != $id + 1;
			$show = self::_r($itm);
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
		
		$posts = get_posts('post_status=draft&orderby=ID&order=ASC');
		foreach ($posts as $itm)
			$this->rem[$itm->ID] = self::_r($itm, 'status');
	}
	
	function _r($itm, $include = '')
	{
		$show = sprintf('%s %s - %s', $itm->ID, $itm->post_title, 
			CHtml::link('view ' . ucfirst(get_post_type($itm->ID)), get_permalink($itm)));
		if ($include == 'status') $show .= ' / ' . $itm->post_status;
		if ($itm->post_parent != 0) $show .= ' / par: ' . $itm->post_parent;
		return $show;
	}
}
$rs = new CSAdminReseed();
?>
<div class="postbox-container">
	<div class="postbox opened">
<?php
_nl('<div class="pb-side">');
$rs->head('Table Seed');
$rs->message('setseed');
_nl('Current Seed: ' . $rs->tblSeed, 1);
if ($rs->seed == $rs->tblSeed)
{
	_nl('No change needed');
}
else
{
	$rs->form('setseed');
	_nl(CHtml::submitButton('Set seed to ' . $rs->tblNext));
	_nl(CHtml::endForm());
}
_nl('</div>');
?>
	<h2>Reseed</h2>
	As you create revisions and trash posts, you will IDs are no longer contiguous
		(20 posts with IDs like 45, 68 and 112)<br />
	This tool helps you remove revisions and clean-up/reorder these IDs.<br />
	Then it lets you change the AUTO_INCREMENT (Seed) of the posts table<br /><br />
<?php
_nl(CHtml::link('Refresh', '?page=' . CSAdmin::$reseedSlug, array('class' => 'button button-small') ), 1);

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

if (count($rs->rem) > 0) {
	$rs->head('Removable posts');
	$rs->message('remove');
	$rs->form('remove');
		_nl('', 1);
	echo CHtml::radioButtonList('removeId', $_POST['removeId'] , $rs->rem);
	_nl('', 1); _nl('', 1);
	_nl(CHtml::submitButton('Remove '));
	_nl(CHtml::endForm());
}

$rs->head('Contiguous posts');
$rs->message('change');
if (count($rs->ok) == 0) {
	_nl('All posts are contiguous', 1);
} else {
	$rs->form('change');
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
