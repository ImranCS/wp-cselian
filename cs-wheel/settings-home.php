<div class="postbox-container">
  <div class="postbox opened">
  <h3>CS Wheel - Options.</h3>
    <a class="button" href="options-general.php?page=cs-wheel&sub=configure">Configure Content</a>
  <h4>Clear DB</h4>
<?php if (isset($_POST['cleardb'])) {
  echo '<div class="log">' . __csw_clear(isset($_POST['sample'])) . '</div>'; }?>
  <form method="post" action="options-general.php?page=cs-wheel">
    <input type="hidden" name="cleardb" value="1" />
    <input type="checkbox" checked="true" name="sample" id="sample" /> <label for="sample">Include Sample Data</label>
    <input type="submit" class="button" value="Clear" />
  </form>

    <h4>Techical Notes:</h4>
    <div class="log">
      None for now.
    </div>
  </div>
</div>
</div>
