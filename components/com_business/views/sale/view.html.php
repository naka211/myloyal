<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.1
 */
class BusinessViewSale extends JViewLegacy
{

        

	public function display($tpl = null)
	{
		// Get the view data.
		if(JRequest::getVar('id')){
			$this->seller            = $this->get('sale');
		} else {
			$this->listSale            = $this->get('ListSale');
		}
                
               // $this->page             = $this->get('Page');
//		$this->data		= $this->get('Business');
//                $this->workingtime         = $this->get('Workingtime');
//		$this->userinfo		= $this->get('Userinfo');
                
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		// Check for layout override
		$active = JFactory::getApplication()->getMenu()->getActive();

		if (isset($active->query['layout']))
		{
			$this->setLayout($active->query['layout']);
		}

		// Escape strings for HTML output
//		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));
//
//		$this->prepareDocument();
        
		return parent::display($tpl);
	}
}
