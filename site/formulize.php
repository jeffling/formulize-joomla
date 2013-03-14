<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	
	// Get the path to Formulize stored as a component parameters
	$params = JComponentHelper::getParams( 'com_formulize' );
	$formulize_path = $params->get('formulize_path');
	// Include API
	require_once $formulize_path."/integration_api.php";
	
	// Get the selected formId
	// Get the menuitemid number
	$input = JFactory::getApplication()->input;
    $menuitemid = $input->getInt( 'Itemid' );  
	
    if ($menuitemid) {
        // Get a reference to the database
		$db = JFactory::getDbo();
		// Query the database for the link's url
        $query = $db->getQuery(true);      
        $query->select('link')
			->from('#__menu ')
			->where('id = ' .  "'". $menuitemid . "'" );            
        $db->setQuery($query);    
        if (!$db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return -1;
        }  
		// Get the result 
		$rows = $db->loadObjectList();  
		$link = $rows[0]->link;
		// Get the very last number (the selected formId)
		$parts = explode('=', $link);
		$formId = end($parts);
    }
	
	// Add a style sheet for Formulize screens general styling
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::base() . 'components/com_formulize/formulize.css');
	
	// Inject the selected form into the screen wrapped into a formulize-screen id
	echo '<div id="formulize-screen">';
	include_once $formulize_path."/mainfile.php";
	$formulize_screen_id = $formId; 
	include $formulize_path."/modules/formulize/index.php";
	echo '</div>';
	
	/*****************************************************
	 * Note that what follows might be used to customize  
	 * the screen appearance by the webmaster 
	 
	 * Create a lighter shade of a hex color
	function hexLighter($hex,$factor = 30) 
    { 
    $new_hex = ''; 
     
    $base['R'] = hexdec($hex{0}.$hex{1}); 
    $base['G'] = hexdec($hex{2}.$hex{3}); 
    $base['B'] = hexdec($hex{4}.$hex{5}); 
     
    foreach ($base as $k => $v) 
        { 
        $amount = 255 - $v; 
        $amount = $amount / 100; 
        $amount = round($amount * $factor); 
        $new_decimal = $v + $amount; 
     
        $new_hex_component = dechex($new_decimal); 
        if(strlen($new_hex_component) < 2) 
            { $new_hex_component = "0".$new_hex_component; } 
        $new_hex .= $new_hex_component; 
        } 
         
    return $new_hex;     
    } 
	
	* Remove all style from the component if needed
	$document->_styleSheets= array();
	
	* Add a style with the colors determined using a new menu item param
	$style1 = '#formulizeform {
    background-color: yellow;
	}';
	$style2 = '#formulizeform {
    background-color: brown;
	}';
	// Will overwrite formulize.css
	$document->addStyleDeclaration($style1);
	$document->addStyleDeclaration($style2);
	*/