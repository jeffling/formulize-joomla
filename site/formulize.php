<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');

	// Get the selected form id 
	$jinput = JFactory::getApplication()->input;
	$formId = $jinput->get('id', '1', 'INT');
	
	// For debugging
	//echo '<script type="text/javascript">alert("' . $formId . '"); </script>';
	
	$params = JComponentHelper::getParams( 'com_formulize' );
	$formulize_path = $params->get('formulize_path');
	
	// Include the selected form
	include_once $formulize_path."/mainfile.php";
	//TODO: get screen list dynamically and allow parameter setting
	$formulize_screen_id = 10; 
	//$formulize_screen_id = 2;
	include $formulize_path."/modules/formulize/index.php";