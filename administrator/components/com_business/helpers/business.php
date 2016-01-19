<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * HelloWorld component helper.
 *
 * @param   string  $submenu  The name of the active view.
 *
 * @return  void
 *
 * @since   1.6
 */
abstract class BusinessHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @return Bool
	 */
 
	public static function addSubmenu($vName) 
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_BUSINESS_MENU_BUSINESSES'),
			'index.php?option=com_business&view=businesses',
			$vName == 'businesses'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_BUSINESS_MENU_PROMOTION'),
			'index.php?option=com_business&view=promotions',
			$vName == 'promotions'
		);
                JSubMenuHelper::addEntry(
			JText::_('COM_BUSINESS_MENU_LIST_CHECKIN'),
			'index.php?option=com_business&view=checkins',
			$vName == 'checkins'
		);
                JSubMenuHelper::addEntry(
			JText::_('COM_BUSINESS_MENU_LIST_POINT'),
			'index.php?option=com_business&view=points',
			$vName == 'points'
		);
                JSubMenuHelper::addEntry(
			JText::_('COM_BUSINESS_MENU_LIST_STAMP'),
			'index.php?option=com_business&view=stamps',
			$vName == 'stamps'
		);
                JSubMenuHelper::addEntry(
			JText::_('COM_BUSINESS_MENU_LIST_LOG_STAMP'),
			'index.php?option=com_business&view=logstamps',
			$vName == 'logstamps'
		);
                JSubMenuHelper::addEntry(
			JText::_('COM_BUSINESS_MENU_LIST_LOG_POINT'),
			'index.php?option=com_business&view=logpoints',
			$vName == 'logpoints'
		);
	}
}