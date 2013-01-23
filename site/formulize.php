<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');

	// Get the selected form id 
	$jinput = JFactory::getApplication()->input;
	$formId = $jinput->get('id', '1', 'INT');
	
	// For debugging
	//echo '<script type="text/javascript">alert("' . $formId . '"); </script>';
	
	// Get the path to Formulize stored as a component parameters
	$params = JComponentHelper::getParams( 'com_formulize' );
	$formulize_path = $params->get('formulize_path');
	
	//Include the selected form
	include_once $formulize_path."/mainfile.php";
	$formulize_screen_id = $formId; 
	include $formulize_path."/modules/formulize/index.php";