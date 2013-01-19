<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<h1><?php echo $this->msg; ?></h1>

<?php
  $formulize_path = "/Users/jeff/Sites/formulize";
  include_once $formulize_path."/mainfile.php";
  $formulize_screen_id = $_GET['sid'];
  include $formulize_path."/modules/formulize/index.php";
?>