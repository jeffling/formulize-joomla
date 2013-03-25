<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// Get the path to Formulize stored as a component parameters
$params = JComponentHelper::getParams( 'com_formulize' );
$formulize_path = $params->get( 'formulize_path' );
// Include API
require_once $formulize_path."/integration_api.php";

// if $_GET["sync"] exists, then do the sync operation and exit script.
if ( $_GET["sync"] == "true" ) {
	$db = JFactory::getDbo();

	echo "<b>Syncing Joomla groups to the Formulize database</b><br />";
	$query = $db->getQuery( true );
	$query->select( array( 'id', 'title' ) )
	->from( '#__usergroups' );
	$db->setQuery( $query );
	$list_of_groups = $db->loadObjectList();
	foreach ( $list_of_groups as &$group ) {
		$exists = Formulize::getXoopsResourceID(0, $group->id);
		if ($exists) {
			echo "Group ".$group->title." already exists, skipping to next group. <br />";
			continue;
		}
		$group_data = array();
		$group_data['groupid'] = $group->id;
		$group_data['name'] = $group->title;
		$new_group = new FormulizeGroup( $group_data );
		if ( Formulize::createGroup( $new_group ) ) {
			echo "Group ".$group->title." created. <br />";
		}
		else {
			echo "Error creating group ".$group->title.".  <br />";
		}
	}

	// $user = clone( JFactory::getUser() );
	// $usersConfig = &JComponentHelper::getParams( 'com_users' );

	// $newUsertype = $usersConfig->get( 'new_usertype' );

	// if (!$newUsertype)
	// {
	//  $newUsertype = 'Registered';
	// }


	echo "<br /><b>Joomla -> Formulize User Sync</b><br />";
	$query = $db->getQuery( true );
	$query->select( array( 'id', 'username', 'name', 'email' ) )
	->from( '#__users' );
	$db->setQuery( $query );

	// QUESTION: Should we clear the formulize user table first?
	$list_of_users = $db->loadObjectList();
	foreach ( $list_of_users as &$user ) {
		// Create a new blank user for Formulize session
		$user_data = array();
		$user_data['uid'] = $user->id;
		$user_data['uname'] = $user->username;
		$user_data['login_name'] = $user->name;
		$user_data['email'] = $user->email;
		// Create a new Formulize user
		$new_user = new FormulizeUser( $user_data );
		// Create or update the user in Formulize
		$exists = Formulize::getXoopsResourceID(1, $user_data['uid']);
		if ( empty($exists) )  // Create
			{
			$flag = Formulize::createUser( $new_user );
			// Display error message if necessary
			if ( !$flag ) {
				echo 'User id: '.$user_data['uname'].': Error creating new user<br />';
			}
			else {
				echo 'User id: '.$user_data['uname'].': New user created. <br />';
			}
		}
		else // Update
			{
			//$flag = Formulize::updateUser($formulizeUser->uid, $formulizeUser);
			$flag = Formulize::updateUser( $user_data['uid'], $user_data );
			// Display error message if necessary
			if ( !$flag ) {
				echo 'User id: '.$user_data['uid'].' Error updating new user<br />';
			}
			else {
				echo 'User id: '.$user_data['uname'].': user updated. <br />';
			}
		}
	}
	echo "<br />Sync completed.<br />";
	// return;
}

// Get the selected formId
// Get the menuitemid number
$input = JFactory::getApplication()->input;
$menuitemid = $input->getInt( 'Itemid' );

if ( $menuitemid ) {
	// Get a reference to the database
	$db = JFactory::getDbo();
	// Query the database for the link's url
	$query = $db->getQuery( true );
	$query->select( 'link' )
	->from( '#__menu ' )
	->where( 'id = ' .  "'". $menuitemid . "'" );
	$db->setQuery( $query );
	if ( !$db->query() ) {
		$this->setError( $this->_db->getErrorMsg() );
		return -1;
	}
	// Get the result
	$rows = $db->loadObjectList();
	$link = $rows[0]->link;
	// Get the very last number (the selected formId)
	$parts = explode( '=', $link );
	$formId = end( $parts );
}

// Add a style sheet for Formulize screens general styling
$document = JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_formulize/formulize.css' );

// Inject the selected form into the screen
include_once $formulize_path."/mainfile.php";
Formulize::renderScreen( $formId );

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
