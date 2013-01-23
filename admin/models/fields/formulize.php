<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// Import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
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
		
		$options = array();
		$options[] = JHtml::_('select.option', 1, "Form1");
		$options[] = JHtml::_('select.option', 2, "Form2");
		$options[] = JHtml::_('select.option', 3, "Form3");
		$options[] = JHtml::_('select.option', 4, "Form4");
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
	}
}