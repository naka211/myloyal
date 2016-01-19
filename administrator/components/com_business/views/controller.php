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
 * General Controller of HelloWorld component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 * @since       0.0.7
 */
class BusinessController extends JControllerLegacy
{
	/**
	 * The default view for the display method.
	 *
	 * @var string
	 * @since 12.2
	 */
//	public function display($cachable = false, $urlparams = false)
//	{
//		$view = JFactory::getApplication()->input->getCmd('view','');
//		JFactory::getApplication()->input->set('view', $view);
//
//		//parent::display($cachable, $urlparams);
//		parent::display();
//
//		return $this;
//	}
    protected $default_view = 'businesses';
        
}
