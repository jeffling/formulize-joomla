<?php
  // No direct access to this file
  defined('_JEXEC') or die('Restricted access');

  print "<h1>Formulize</h1>";

  $params = JComponentHelper::getParams( 'com_formulize' );
  $formulize_path = $params->get('formulize_path');
  include_once $formulize_path."/mainfile.php";
  $formulize_screen_id = 2;
  include $formulize_path."/modules/formulize/index.php";
?>