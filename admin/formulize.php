<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Set the administration interface
// Set the Toolbar
JToolBarHelper::title(JText::_('COM_FORMULIZE_ADMINISTRATION'), 'formulize');
JToolBarHelper::preferences('com_formulize', '300', '700', 'Configure', ' ');

// Add an image to the backend
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-formulize {background-image: url(../media/com_formulize/images/logo-48x48.png);}');

// Set the main body
$params = JComponentHelper::getParams( 'com_formulize' );
echo "Path to Formulize: ".$params->get('formulize_path');
echo "<br /><form method='get' action='".$_SERVER['PHP_SELF']."'><input type='hidden' name='sync' value='true'><input type='hidden' name='option' value='com_formulize'><button type='submit'>Sync</button></form>";

// if $_GET["sync"] exists, then do the sync operation and exit script.
if ( isset($_GET["sync"]) ) {
	$application = JFactory::getApplication();
	$return_string = "<br />";
	require_once $params->get('formulize_path')."/integration_api.php";
	jimport( 'joomla.access.access' );
	$db = JFactory::getDbo();

	// get minimum group id
	$query = $db->getQuery( true );
	$query->select( array( 'MIN(id)' ) )
	->from( '#__usergroups' );
	$db->setQuery( $query );
	$result = $db->loadObjectList();
	$min_group_id = $result[0]->{'MIN(id)'};
	
	// sync the initial 3 groups
	$exists = Formulize::getXoopsResourceID(0, $min_group_id);
	if ( empty( $exists ) ) {
		Formulize::createResourceMapping(0, $min_group_id, 3); // anonymous/public users
	}
	$exists = Formulize::getXoopsResourceID(0, $min_group_id+1);
	if ( empty( $exists ) ) {
		Formulize::createResourceMapping(0, $min_group_id+1, 2); // registered users
	}
	$exists = Formulize::getXoopsResourceID(0, $min_group_id+7);
	if ( empty( $exists ) ) {
		Formulize::createResourceMapping(0, $min_group_id+7, 1); // webmaster/super user
	}

	$return_string = $return_string."<b>Syncing Joomla groups to the Formulize database:</b><br />";
	$query = $db->getQuery( true );
	$query->select( array( 'id', 'title' ) )
	->from( '#__usergroups' );

	$db->setQuery( $query );
	$list_of_groups = $db->loadObjectList();
	foreach ( $list_of_groups as &$group ) {
		$group_data = array();
		$group_data['groupid'] = $group->id;
		$group_data['name'] = $group->title;
		$new_group = new FormulizeGroup( $group_data );

		$exists = Formulize::getXoopsResourceID(0, $group->id);
		if ($exists) {
			Formulize::renameGroup($group_data['groupid'], $group_data['name']);
			continue;
		}
		else {
			if ( Formulize::createGroup( $new_group ) ) {
				$return_string = $return_string."Group ".$group->title." created. <br />";
			}
			else {
				$return_string = $return_string."Error creating group ".$group->title.".  <br />";
			}
		}
	}

	$return_string = $return_string."<br /><b>Syncing Joomla users to the Formulize database:</b><br />";
	$query = $db->getQuery( true );
	$query->select( array( 'id', 'username', 'name', 'email' ) )
	->from( '#__users' );
	$db->setQuery( $query );

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
		$flag = NULL;
		if ( empty($exists) ) {// Create
			$flag = Formulize::createUser( $new_user );
			// Display error message if necessary
			if ( !$flag ) {
				$return_string = $return_string.'User id: '.$user_data['uname'].': Error creating new user<br />';
			}
			else {
				$return_string = $return_string.'User id: '.$user_data['uname'].': New user created. <br />';
				// add user to groups
				$groups = JAccess::getGroupsByUser( $user->id );
				for ( $i = 0; $i<count($groups); $i++ ) {
					if (Formulize::addUserToGroup( $user->id, $groups[$i]) == false ) {
						$return_string = $return_string."Error adding ".$user->id." to ".$groups[$i]."<br />";
					}
				}
			}
		}
		else { // Update
			$flag = Formulize::updateUser( $user_data['uid'], $user_data );
			// Display error message if necessary
			if ( !$flag ) {
				$return_string = $return_string.'User id: '.$user_data['uid'].' Error updating new user<br />';
			}
			else {
				$return_string = $return_string.'User id: '.$user_data['uname'].': user updated. <br />';
			}
		}
	}
	$return_string = $return_string."<br />Sync completed.<br />";
	$application->enqueueMessage(JText::_($return_string));

}