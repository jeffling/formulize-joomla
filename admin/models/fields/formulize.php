<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldFormulize extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Formulize';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
			/*
                $db = JFactory::getDBO();
 
                /// $query = new JDatabaseQuery; WARNING - There's an error in this line, JDatabaseQuery is an abstract class
                $query = $db->getQuery(true); // THIS IS THE FIX, WARNING IT MUST BE FIXED IN THE ZIP FILES
 
                $query->select('#__helloworld.id as id,greeting,#__categories.title as category,catid');
                $query->from('#__helloworld');
                $query->leftJoin('#__categories on catid=#__categories.id');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
				if ($messages)
                {
                        foreach($messages as $message) 
                        {
                                $options[] = JHtml::_('select.option', $message->id, $message->greeting .
                                                      ($message->catid ? ' (' . $message->category . ')' : ''));
                        }
                }
				
			*/
				
                $options = array("Form1","Form2","Formm3");
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}