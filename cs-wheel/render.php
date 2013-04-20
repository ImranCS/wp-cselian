<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=windows-1250" />
    <meta name="generator" content="PSPad editor, www.pspad.com" />
    <title>CSELIAN - Technical Domain of Imran Ali Namazi</title>
    <style type="text/css">
/*<![CDATA[*/
    <!--
    body { background-color: #000; margin: 0px; }
    div#circle { background: url(/imran/img/home/csercle.jpg) no-repeat top left; 
        height: 450px; width: 450px; }
    #circle a { display: block; position: absolute; float: left; }
    #circle a:hover { background: url(/imran/img/home/csercle-hover.png) no-repeat top left; 
        /*border: 1px cyan solid; border-width: 1px 0px 0px 1px; */ }
    //-->
    /*]]>*/
    </style>
  </head>
  <body>
<div id="circle">
<?php
$cnt = count($names);
$item = '<a href="%s" style="left: %spx; top: %spx; width: %spx; height: %spx; background-position: -%spx -%spx;"></a><!-- %s -->
';
for ($i = 0; $i < $cnt ; $i ++) {
	echo sprintf($item, $urls[$i], $lefts[$i], $tops[$i], $widths[$i], $heights[$i], $lefts[$i], $tops[$i], $names[$i]);
}
?>
</div>
  </body>
</html>
