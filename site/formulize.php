<?php
  // No direct access to this file
  defined('_JEXEC') or die('Restricted access');

  print "<h1>Formulize</h1>";

  $formulize_path = "/Users/jeff/Sites/formulize";
  print $formulize_path."/mainfile.php";
  include_once $formulize_path."/mainfile.php";
  $formulize_screen_id = $_GET['sid'];
  include $formulize_path."/modules/formulize/index.php";
?>