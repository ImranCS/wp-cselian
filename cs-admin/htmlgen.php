<?php
include_once 'csa-base.php';
class CSAdminHtmlGen extends CSAdminBase
{
	public $fol = 'pages/';
	public $baseFol;
	public $pages;
	public $current;
	public $currentId;
	public $content = 0;
	
	function __construct()
	{
		CSScripts::admin();
		$this->slug = CSAdmin::$htmlSlug;
		
		$this->baseFol = cs_var('adm-fol') . '/';
		$this->current = $_POST['page'];
		
		if ($this->isAction('create')) $this->create();
		if ($this->isAction('gen') || $this->isAction('publish')) $this->generate();
		
		$this->find();
		if ($this->isAction('publish')) $this->publish(); // do this after find so that currentId is set
	}
	
	function create()
	{
		$page = $_POST['newPage'];
		$id = $_POST['newId'];
		$tpl = sprintf('<?php if (isset($genId)) return %s;

echo "Sample content of %s"; // replace this with your actual code

return 0;
?' . '>', $id, $page);
		$file = $this->filePath($page);
		if (file_exists($this->baseFol . $file)) {
			$this->msg = 'File "' . $file . '" already exists';
		} else {
			$this->log(sprintf('Created file %s (#%s)', $page, $id));
			file_put_contents($this->baseFol . $file, $tpl);
			$this->msg = 'File "' . $file . '" generated';
		}
		$this->current = $page;
		$this->content = '<pre>' . htmlentities($tpl) . '</pre>';
	}
	
	// from wp-cselian (AIO) wpcs_page()
	function generate()
	{
		$file = $this->filePath($this->current);
		// todo: store date somewhere
		ob_start();
		include $file;
		$op = ob_get_contents();
		ob_end_clean();
		$this->content = sprintf('<!-- cs-admin/htmlgen - NB: content auto generated on %s. -->
%s', date('d M Y'), $op);
	}
	
	function publish()
	{
		$post = array('ID' => $this->currentId, 'post_content' => $this->content);
		wp_update_post($post);
		$this->msg = 'Saved to #' . $this->currentId;
	}
	
	function find()
	{
		$pages = self::get_files($this->baseFol . $this->fol, '.php');
		$data = array();
		$genId = true;
		foreach ($pages as $pg)
		{
			$id = include $this->filePath($pg);
			if ($pg == $this->current) $this->currentId = $id;

			if ($id == 0)
				$data[$pg] = sprintf('%s - page not set', $pg);
			else if (!($title = get_the_title($id)))
				$data[$pg] = sprintf('%s - page #%s not found', $pg, $id);
			else
				$data[$pg] = sprintf('%s - %s %s %s (%s)', $pg, $id,
					CHtml::link($title, get_permalink($id)),
					CHtml::link('...', get_edit_post_link($id, '')),
					ucfirst(get_post_type($id)) );
		}
		$this->pages = $data;
	}
	
	function filePath($pg, $op = 0)
	{
		return $this->fol . $pg . ($op ? '.html' : '.php');
	}

	// from webbq/inc/io.php
	function get_files($fol, $extension = '*', $extn = 0)
	{
		$res = array();
		$dir  = opendir($fol);
		
		while (false !== ($item = readdir($dir)))
		{
			if ($item == "." || $item == "..") continue;
			if (is_dir($fol . $item)) continue;
			
			if ($extension != '*' && !endsWith($item, $extension)) continue;
			
			if (!$extn) $item = preg_replace("/\\.[^.\\s]{3,4}$/", "", $item);
			$res[] = $item;
		}
		
		return $res;
	}
}
$hg = new CSAdminHtmlGen();
?>
<div class="postbox-container">
	<div class="postbox opened">
<?php
_nl('<div class="pb-side">');
$hg->head('Create Page');
$hg->form('create');
_nl(CHtml::textField('newPage', $_POST['newPage'], array('style' => 'width:90px;', 'autocomplete'=>'off')), 1);
_nl('Id: ' . CHtml::textField('newId', $_POST['newId'], array('style' => 'width:30px;', 'autocomplete'=>'off')), 1);
_nl(CHtml::submitButton('Create'));
_nl(CHtml::endForm());
_nl('</div>');
?>
	<h2>Html page generator</h2>
	If a page is built dynamically with shortcodes, search will not work.<br />
	This page updates wp_posts with the html output of a php page.<br /><br />
<?php
$hg->refresh();

$hg->head('Pages');
$hg->message('gen');
if (count($hg->pages) == 0) {
	_nl(sprintf('No pages are added. Please add them in the folder %s/pages', cs_var('adm-base')));
} else {
	$hg->form('gen');
		_nl('', 1);
	echo CHtml::radioButtonList('page', $hg->current, $hg->pages);
	_nl('', 1); _nl('', 1);
	_nl(CHtml::submitButton('Generate'));
	_nl(CHtml::endForm());
}

if ($hg->content) {
	$hg->head('Content');
	$hg->form('publish');
	$hg->message('create'); $hg->message('publish');
	_nl(CHtml::submitButton('Publish to ' . $hg->currentId));
	_nl(CHtml::hiddenField('page', $hg->current));
	_nl(CHtml::endForm());
	_nl('<div id="gen-content">');
	_nl($hg->content);
	_nl('</div>');
}

?>
	</div>
</div>
