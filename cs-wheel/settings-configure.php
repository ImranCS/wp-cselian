<div class="postbox-container pb-full">
  <div class="postbox opened">
  <h3>AD Wheel - Configure. <a class="button" href="options-general.php?page=ad-wheel">Back</a></h3>
  <div class="inside">
<script src="/blog/wp-content/plugins/cs-wheel/jquery.imgareaselect.pack.js" type="text/javascript"></script>
<script type="text/javascript">
function SettingsSubmit(what)
{
  var frm = document.forms["wheelpreview"];
  if (what == "save")
  {
    frm.action = "";
    frm.target = "";
  }
  else
  {
    frm.action = "/blog/wp-content/plugins/cs-wheel/preview.php";
    frm.target = "wheelpreview";
  }
  frm.submit();
}
</script>

<h4>Configure
  <a class="button" href="javascript:SettingsSubmit('preview');">Preview</a>
  <a class="button" href="javascript:SettingsSubmit('save');">Save</a>
</h4>

<?php if (isset($_POST['names'])) {
  echo '<div class="log">' . __csw_update() . '</div>'; } ?>

<form name="wheelpreview" method="post">
<div style="float: left; padding-right: 5px;">
  <img id="csercle" src="/imran/img/home/csercle.jpg" />
<br><br><table>
 <tr>
  <td>Left</td>
  <td>Top</td>
  <td>Width</td>
  <td>Height</td>
  <td>Actions</td>
 </tr>
  <tr>
  <td><input type="text" class="intbox" id="leftitm" /></td>
  <td><input type="text" class="intbox" id="topitm" /></td>
  <td><input type="text" class="intbox" id="widthitm" /></td>
  <td><input type="text" class="intbox" id="heightitm" /></td>
  <td><a class="link" id="areaget">&lt;</a> <a class="link" id="areaset">&gt;</a></td>
 </tr>

</table>
</div>
<table id="opt-wheel" border="1" cellspacing="0">
 <tr>
  <th>Name</th>
  <th>Actions</th>
  <th>Left</th>
  <th>Top</th>
  <th>Wt.</th>
  <th>Ht.</th>
  <th>Url</th>
 </tr>
<?php
$links = _csw_getlinks(); extract($links);
$cnt = count($names);
/**/
$item = ' <tr>
  <td><input type="text" class="shortbox" value="%s" name="names[]" /></td>
  <td> 
    <input type="radio" name="swapwith" /><a 
      class="link rowup">&uarr;</a><a 
      class="link rowdn">&darr;</a><a
      class="link rowdel">&chi;</a><a 
      class="link rowswap">&int;</a></td>
  <td><input type="text" class="intbox lf" value="%s" name="lefts[]" /></td>
  <td><input type="text" class="intbox tp" value="%s" name="tops[]" /></td>
  <td><input type="text" class="intbox wd" value="%s" name="widths[]" /></td>
  <td><input type="text" class="intbox ht" value="%s" name="heights[]" /></td>
  <td><input type="text" class="shortbox" value="%s" name="urls[]" /></td>
 </tr>
';
for ($i = 0; $i < $cnt ; $i ++) {
	echo sprintf($item, $names[$i], $lefts[$i], $tops[$i], $widths[$i], $heights[$i], $urls[$i]);
}
?>
</table>
</form>
  </div>
</div>
</div>
<script type="text/javascript">
function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;

    $('#leftitm').val(selection.x1);
    $('#topitm').val(selection.y1);
    $('#widthitm').val(selection.width);
    $('#heightitm').val(selection.height);    
}

//BASED ON: http://odyniec.net/projects/imgareaselect/
$(document).ready(function () {
  $.fn.swap = function(other) {
      $(this).replaceWith($(other).after($(this).clone(true)));
  };
  $("#areaget").click(function () {
    $row = $('input:radio[name=swapwith]:checked').parent().parent();
    
    $('#leftitm').val($(".lf", $row).val());
    $('#topitm').val($(".tp", $row).val());
    $('#widthitm').val($(".wd", $row).val());
    $('#heightitm').val($(".ht", $row).val());
    var x2 = parseInt($('#leftitm').val()) + parseInt($('#widthitm').val());
    var y2 = parseInt($('#topitm').val()) + parseInt($('#heightitm').val());
    $('img#csercle').imgAreaSelect({ handles: true,
        fadeSpeed: 200, onSelectChange: preview,
        x1: $('#leftitm').val(), y1: $('#topitm').val(), x2: x2, y2: y2 }); 
  });
  $("#areaset").click(function () {
    $row = $('input:radio[name=swapwith]:checked').parent().parent();
    
    $(".lf", $row).val($('#leftitm').val());
    $(".tp", $row).val($('#topitm').val());
    $(".wd", $row).val($('#widthitm').val());
    $(".ht", $row).val($('#heightitm').val());
  });
  $(".rowswap").click(function () {
    $row = $(this).parent().parent();
    $dest = $('input:radio[name=swapwith]:checked').parent().parent();
    $row.swap($dest);
  });
  $(".rowup").click(function () {
    $row = $(this).parent().parent();
    $row.swap($row.prev());
  });
  $(".rowdn").click(function () {
    $row = $(this).parent().parent();
    $row.swap($row.next());
  });
  $(".rowdel").click(function () {
    $row = $(this).parent().parent();
    var name = $('input:first', $row).val();
    var yes = confirm("Really delete "+name+"?");
    if (yes) $row.remove();
  });
});
</script>
