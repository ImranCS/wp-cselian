<?php
// Base class for Admin Pages
class CSAdminBase
{
	public $msg;
	public $action;
	
	function show($what, $empty = 'Nothing')
	{
		echo $what == null || count($what) == 0 ? $empty : implode('<br/>
', $what);
	}
	
	function message($a)
	{
		if ($this->msg == null || $this->action != $a) return;
		_nl('<div class="notice">' . $this->msg . '</div>');
	}

	function head($txt)
	{
		_nl('<h3>'. $txt . '</h3>');
	}
	
	function form($a = '')
	{
		if ($a != '') $a = sprintf('&%s=1', $a);
		_nl(CHtml::tag('form', array('action' => '?page=' . CSAdmin::$reseedSlug . $a, 'method' => 'post') ));
	}
	
	protected function isAction($a)
	{
		if ($yes = isset($_GET[$a])) $this->action = $a;
		return $yes;
	}
	
	protected function log($msg)
	{
		$file = strtolower(str_replace('CSAdmin', '', get_class($this)));
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . $file . '.log';
		$contents = file_exists($file) ? file_get_contents($file) . '
' : '';
		file_put_contents($file, $contents . date('d M y H:i:s e') . '	' . $msg);
	}
}
?>
