<?php
//April 7th 2015, based on cs-companion csc-contact.php
//Styles in aura/styles.css
class CSProductContact
{
	function get_fields()
	{
		return array(
			'name' => array('reqd' => 1),
			'email' => array('reqd' => 1, 'caption' => 'Email Address'),
			'products' => array('message' => '*Hold Control to select multiple products.'),
			'message' => array('reqd' => 1, 'multiline' => 1, 'message' => 'Let us know your quantity.'),
		);
	}

	function __construct()
	{
		add_shortcode('productcontact', array($this, 'do_shortcode'));
	}

	function do_shortcode($a, $content = null)
	{
		if ($this->send_message())
			return 'Your message has been sent.';
		return $this->build_form();
	}

	function send_message()
	{
		if (!isset($_POST['formname']) || $_POST['formname'] != 'productcontact')
			return 0;

		$to = cs_var('contact');
		$subject = 'Message from ' . $_POST['pc_name'] . ' on ' . cs_var('sitename');

		$message = 'Dear ' . $_POST['pc_name'] . ',<br/>';
		$message .= 'We will reply to you shortly.<br/><br/>';
		$message .= 'Your message: ' . $_POST['pc_message'] . '<br/>';
		$message .= 'Products: ' . implode(', ', $_POST['pc_products']);

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= sprintf('From: "%s" <%s>', cs_var('sitename'), cs_var('contact')) . "\r\n";
		$headers .= "Reply-to: " . cs_var('contact') . "\r\n";
		$headers .= 'Cc: ' . $_POST['pc_email'] . "\r\n";

		mail($to, $subject, $message, $headers);

		return 1;
	}

	function build_form()
	{
		add_action('wp_footer', array($this, 'footer_js'));
		$nl ='
		';
		$res = $nl . '<form method="post" onsubmit="return ValidateProductContact(this);">';
		$res .= $nl . '<input type="hidden" name="formname" value="productcontact" />';
		$fields = $this->get_fields();

		foreach ($fields as $key=>$fld)
		{
			$input = isset($fld['multiline'])
			? '<textarea rows="5" cols="32" name="pc_%s" id="pc_%s"></textarea>'
			: '<input class="text" type="text" size="40" name="pc_%s" id="pc_%s" />';

			if ($key == 'products')
			{
				global $wpdb;
				$options = array();
				$products = $wpdb->get_results("select post_title from wp_posts where post_type = 'product' and post_status = 'publish' order by 1");
				foreach($products as $val)
					$options[] = sprintf('	<option value="%s">%s</option>', $val->post_title, $val->post_title);
				$input = '<select name="pc_%s[]" id="pc_%s" size="5" multiple>' . implode(PHP_EOL, $options) . PHP_EOL . '</select>';
			}

			$capn = isset($fld['caption']) ? $fld['caption'] : ucfirst($key);
			$message = isset($fld['message']) ? '<span class="contact-message">' . $fld['message'] . '</span>' : '';

			$res .= sprintf('		<div class="contact-label" style="padding-top: 5px;">
				<label for="pc_%s">%s:%s</label>%s</div>
				<div class="contact-field">%s</div>', $key
				, $capn
				, isset($fld['reqd']) ? '<span class="required"> *</span>' : ''
				, $message
				, sprintf($input, $key, $key)
				);
			if (isset($fld['reqd'])) $this->reqd_js('pc_' . $key, $capn);
		}

		$res .= $nl . '<input type="submit" value="Submit" style="margin-top:10px;"></form>';
		return $res;
	}

	private $js = array();

	function reqd_js($name, $caption)
	{
		$this->js[] = sprintf('  if (FieldInvalid(frm.%s, frm.%s.value.length <1, "Please input your %s!")) return false;'
			, $name, $name, $caption);
		if ($name == 'pc_email')
		{
			$this->js[] = sprintf("  emlinv = frm.%s.value.indexOf('@', 0) == -1 || frm.%s.value.indexOf('.', 0) == -1;", $name, $name);
			$this->js[] = sprintf('  if (FieldInvalid(frm.%s, emlinv, "Please input a valid %s!")) return false;', $name, $caption);
		}
	}

	function footer_js() { ?>
<script type="text/javascript">
function ValidateProductContact(frm)
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
new CSProductContact();
?>
