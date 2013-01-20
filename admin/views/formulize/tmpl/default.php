<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
JToolBarHelper::title(JText::_('Formulize-Joomla'), 'formulize');
JToolBarHelper::preferences('com_formulize');

$document = JFactory::getDocument();
$document->setTitle(JText::_('COM_FORMULIZE_ADMINISTRATION'));

$params = JComponentHelper::getParams( 'com_formulize' );
print "formulize path: ".$params->get('formulize_path');
