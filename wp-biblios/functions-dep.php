<?php
// deprecated functions
function cs_work_import($id, $cfg = null)
{
	if ($cfg == null) {
		$types = cs_var('workTypes');
		$dat = cs_work_dat($id);
		$op = array('#Basic');
		$dat['type'] = $types[$dat['type']];
		foreach ($dat as $key=>$value)
			$op[] = sprintf('%s:%s', $key, $value);
		
		$cfg = sprintf('%s/%s/config.php', cs_var('bib-data'), $dat['fol']);
		$cfg = file_exists($cfg) ? include $cfg : false;
	} else {
		eval($cfg);
		$cfg = compact('titles', 'customTitles', 'slug2', 'slug',
			'heading', 'heading2', 'nodeFormat', 'keyFormat', 'itemName');
	}
	
	if ($cfg)
	{
		if (isset($cfg['settings']))
		{
			$op[] = '#Settings';
			foreach ($cfg['settings'] as $key=>$value)
				$op[] = sprintf('%s:%s', $key, $value);
			unset($cfg['settings']);
		}
		$op[] = '#Config';
		
		$subtitles = isset($cfg['slugs'][1]) || isset($cfg['slug2']);
		$op[] = sprintf('heading:%s', $cfg['headings'][0]);
		if ($subtitles) $op[] = sprintf('heading2:%s', $cfg['headings'][1]);
		unset($cfg['headings']);
		
		$op[] = sprintf('slug:%s', $cfg['slugs'][0]);
		if ($subtitles) $op[] = sprintf('slug2:%s', $cfg['slugs'][1]);
		unset($cfg['slugs']);
		
		if (isset($cfg['preTitle'])) $cfg['preTitle'] = 'NOTIMPL'; // TODO: Auro bio / any other
		if (isset($cfg['extraData'])) $cfg['extraData'] = 'NOTIMPL'; // TODO: Auro bio / any other
		$titles = isset($cfg['titles']) ? $cfg['titles'] : 0;
		unset($cfg['titles']);
		
		if (isset($cfg['customTitles'])) $cfg['customTitles'] = 'true';
		foreach ($cfg as $key=>$value)
			$op[] = sprintf('%s:%s', $key, $value);

		if ($titles)
		{
			$op[] = '#Titles';
			foreach ($titles as $title)
			{
				if ($title == null) continue;
				if ($subtitles) {
					$op[] = 'title:' . $title[0];
					unset($title[0]);
					foreach ($title as $sub)
						$op[] = 'subtitle:' . (is_array($sub) ? $sub[1] : $sub);
				} else {
					if ($customTitles) $title = $title[1];
					$op[] = 'title:' . $title;
				}
			}
		}
	}
	
	return implode(PHP_EOL, $op);
}

// Deprecate after import is over
function cs_work_dat($id, $data = null)
{
	$file = sprintf('%s/%s.dat', cs_var('bib-data'), $id);
	if ($data != null)
	{
		file_put_contents($file, serialize($data));
	}
	else
	{
		if (!file_exists($file))
			return array('type' => 'book', 'fol' => 'books/{name}');
		return unserialize(file_get_contents($file));
	}
}

?>