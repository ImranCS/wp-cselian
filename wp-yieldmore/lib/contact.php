<?php
/*
* Imran@cselian.com, Dec 2017
* For the Catalogue Raisonné of SMF
*/
class CSContact
{
	function __construct()
	{
		add_shortcode('contact', array($this, 'do_shortcode'));
	}

	function do_shortcode($a, $content = null)
	{
		$name = $a['name'];
		//echo 'FORM: ' . $name . '<br/>';
		$form = cs_site_var('forms'); $form = $form[$name];
		if (isset($_POST['formname']) && $_POST['formname'] == $name)
			return $this->send_email($name, $form);
		else
			return $this->render_form($name, $form);
	}

	function render_form($name, $form)
	{
		$r = array();
		$r[] = '<form class="contact" method="POST" enctype="multipart/form-data">';
		$r[] = '<input type="hidden" name="formname" value="' . $name . '">';

		$sno = 1;
		foreach ($form as $ix=>$fld) {
			if (is_numeric($ix)) {
				$label = $fld;
				$type = 'text';
			} else {
				$label = $ix;
				$type = is_array($fld) ? 'radio' : $fld;
			}

			$fn = 'csfld' . $sno;
			$r[] = '<p>';
			$r[] = '<label for="' . $fn . '">' . $label . '</label>';

			if ($type == 'text' || $type == 'date' || $type == 'email' || $type == 'name')
				$r[] = '<input type="text" id="' . $fn . '" name="' . $fn . '" />';
			else if ($type == 'file')
				$r[] = '<input type="file" id="' . $fn . '" name="' . $fn . '" />';
			else if ($type == 'textarea')
				$r[] = '<textarea id="' . $fn . '" name="' . $fn . '"></textarea>';
			else if ($type == 'radio')
				foreach ($fld as $val)
					$r[] = '<input type="radio" id="' . $fn . '_' . $val . '" value="' . $val . '" name="' . $fn . '" />'
						. '<label class="inline" for="' . $fn . '_' . $val . '">' . $val . '</label>';

			$r[] = '</p>';
			$sno++;
		}

		$r[] = '<input type="submit" />';
		$r[] = '</form>';
		return implode(PHP_EOL, $r);
	}

	function send_email($name, $form)
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>'; return;
		$sno = 1;
		$from = 'John Doe';
		$cc = 'noreply@mailinator.com';
		$attach = array();

		$r = array('<table style="border-spacing: 0px;" cellpadding="6">');
		foreach ($form as $ix=>$fld) {
			$r[] = '<tr><th style="width: 320px; text-align: right; border: 1px solid #666;">' . (is_numeric($ix) ? $fld : $ix) . '</th>';

			$val = $fld == 'file' ? basename($_FILES['csfld' . $sno]['name']) : $_POST['csfld' . $sno];
			if ($fld == 'name') $from = $val;
			if ($fld == 'email') $cc = $val;
			if ($fld == 'file') $attach[] = $this->get_file('csfld' . $sno);

			$r[] = '<td style="border: 1px solid #666;">' . $val . '</td></tr>';
			$sno++;
		}
		$r[] = '</table>';
		
		$body = implode(PHP_EOL, $r);

		return $this->send($name, $from, $cc, $body, $attach);
		//print_r($body);
	}

	function get_file($name)
	{
		//https://wordpress.stackexchange.com/a/167404
		if ( ! function_exists( 'wp_handle_upload' ) )
			require_once( ABSPATH . 'wp-admin/includes/file.php' );

		$file  = wp_handle_upload($_FILES[$name], array('test_form' => false));
		if (!$file) {
			echo '<p class="error">Possible file upload attack!</p>';
			return null;
		}
		return $file['file'];
	}

	function send($name, $from, $cc, $body, $attach)
	{
		$to = cs_var('forms-to');
		$headers = count($attach) ? "MIME-Version: 1.0" . "\r\n" : '';
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$site = get_bloginfo('name');
		$headers .= sprintf("From: %s <$to>\r\nReply-to: $cc", $site);
		$headers .= "\r\nCc: " . $cc;

		$subject = sprintf("%s has filled %s Form on %s", $from, $name, $site);
		
		$html = '<html><body>' . $body . '</body></html>';
		//die ($body . (count($attach) ? 'YES' : 'NO'));
		if (count($attach))
			$result = wp_mail($to, $subject, $html, $headers, $attach);
		else
			$result = wp_mail($to, $subject, $html, $headers);

		return $result
			? '<p class="success">Thank you for reaching out to us. We will get back to you shortly.</p>'
			: '<p class="error">an error has occurred: ' . print_r(error_get_last(), 1) . '</p>';
	}
}
new CSContact();
?>
