<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// Import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * ADD THESE TWO LINES OF CODE AT THE TOP OF YOUR FILE
 * ---------------------------------------------------
 * $formulize_path = variable_get('formulize_full_path', NULL);
 * require_once(dirname($formulize_path) .  DIRECTORY_SEPARATOR . 'integration_api.php');
 * ---------------------------------------------------
 */
	$params = JComponentHelper::getParams( 'com_formulize' );
	$formulize_path = $params->get('formulize_path');
	require_once $formulize_path."/integration_api.php";
	//require_once(dirname($formulize_path) .  DIRECTORY_SEPARATOR . 'integration_api.php');
/**
 * Formulize Form Field class
 */
class JFormFieldFormulize extends JFormFieldList
{
    protected $type = 'Formulize';

	/**
	 * Method to get a list of options for a list input.
	 * @return An array of JHtml options.
	 */
	protected function getOptions() 
	{		
		// Need to use the formulize API here to populate the 
		// array with the available forms(ids and names)
		
		$options = Formulize::getScreens();
		return $options;
	}
}