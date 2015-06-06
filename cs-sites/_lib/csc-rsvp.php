<?php
//April 7th 2015, based on cs-companion csc-contact.php
class CSRsvp
{
	function get_fields()
	{
		return array(
			'name' => array('reqd' => 1),
			'email' => array('reqd' => 1, 'caption' => 'Email Address'),
			'coming' => array('options' => array('Yes', 'No', 'Maybe') ),
		);
	}

	function __construct()
	{
		add_shortcode('rsvp', array($this, 'do_shortcode'));
	}

	function do_shortcode($a, $content = null)
	{
		if (isset($_GET['show']))
			return $this->get_details();
		if ($this->save_response())
			return 'Your Rsvp has been saved.';
		return $this->build_form();
	}

	function save_response()
	{
		if (!isset($_POST['formname']) || $_POST['formname'] != 'rsvp')
			return 0;

		$line = sprintf('%s	%s	%s' . PHP_EOL, date('d M y h:m:i'), $_POST['rsvp_name'], $_POST['rsvp_email']);

		$file = cs_var('sitebase') . 'coming-' . $_POST['rsvp_coming'] . '.txt';
		$contents = file_exists($file) ? file_get_contents($file) : '';
		$contents .= $line;
		file_put_contents($file, $contents);

		return 1;
	}

	function get_details()
	{
		$op = '<div style="padding: 20px;">';
		$options = $this->get_fields()['coming']['options'];

		if (isset($_GET['coming'])) $op .= '<a href="./?show=1">Back to All</a>';

		foreach ($options as $val)
		{
			$file = cs_var('sitebase') . 'coming-' . $val . '.txt';
			if (isset($_GET['coming']))
			{
				if ($_GET['coming'] == $val)
					$op .= '<h3>' . $val . '</h3><textarea cols="80" rows="15">' . (file_exists($file) ? file_get_contents($file) : 'None') . '</textarea>';
			}
			else
			{
				$op .= '<a href="./?show=1&coming=' . $val . '">' . $val . '</a>: ' . 
					(file_exists($file) ? count(explode(PHP_EOL, file_get_contents($file))) -1 : 'None') . '<br/>';
			}
		}
		$op .= '</div>';
		return $op;
	}

	function build_form()
	{
		add_action('wp_footer', array($this, 'footer_js'));
		$nl ='
		';
		$res = $nl . '<form method="post" style="text-align: left; max-width: 350px; min-width: 200px" onsubmit="return ValidateRsvp(this);">';
		$res .= $nl . '<input type="hidden" name="formname" value="rsvp" />';
		$fields = $this->get_fields();

		foreach ($fields as $key=>$fld)
		{
			$input = isset($fld['multiline'])
			? '<textarea rows="5" cols="32" name="rsvp_%s" id="rsvp_%s"></textarea>'
			: '<input class="text" type="text" size="40" name="rsvp_%s" id="rsvp_%s" />';

			if (isset($fld['options']))
			{
				$options = array();
				foreach($fld['options'] as $val)
					$options[] = sprintf('	<option value="%s">%s</option>', $val, $val);
				$input = '<select name="rsvp_%s" id="rsvp_%s">' . implode(PHP_EOL, $options) . PHP_EOL . '</select>';
			}

			$capn = isset($fld['caption']) ? $fld['caption'] : ucfirst($key);

			$res .= sprintf('		<div class="contact-label" style="padding-top: 5px;">
				<label for="rsvp_%s">%s:%s</label></div>
				<div class="contact-field">%s</div>', $key
				, $capn
				, isset($fld['reqd']) ? '<span class="required"> *</span>' : ''
				, sprintf($input, $key, $key)
				);
			if (isset($fld['reqd'])) $this->reqd_js('rsvp_' . $key, $capn);
		}

		$res .= $nl . '<input type="submit" value="Submit" style="margin-top:10px;"></form>';
		return $res;
	}

	private $js = array();

	function reqd_js($name, $caption)
	{
		$this->js[] = sprintf('  if (FieldInvalid(frm.%s, frm.%s.value.length <1, "Please input your %s!")) return false;'
			, $name, $name, $caption);
		if ($name == 'rsvp_email')
		{
			$this->js[] = sprintf("  emlinv = frm.%s.value.indexOf('@', 0) == -1 || frm.%s.value.indexOf('.', 0) == -1;", $name, $name);
			$this->js[] = sprintf('  if (FieldInvalid(frm.%s, emlinv, "Please input a valid %s!")) return false;', $name, $caption);
		}
	}

	function footer_js() { ?>
<script type="text/javascript">
function ValidateRsvp(frm)
{
<?php echo implode('
', $this->js); ?>

  return true;
}

function FieldInvalid(what, when, msg)
{
  if (when)
  {
    alert(msg);
    what.focus();
    return true;
  }
  return false;
}
</script><?php
	}
}
new CSRsvp();
?>
