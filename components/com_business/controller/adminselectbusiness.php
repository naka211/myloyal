<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_business
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class BusinessControllerAdminselectbusiness extends JControllerForm
{
    public function search()
    {
        $requestData = $this->input->post->get('jform', array(), 'array');
        $businessName = $requestData['businessName'];
        $jinput = JFactory::getApplication()->input;
        $filter     = $jinput->get('filter', "", 'varchar');
        $order = $jinput->get('order', "", 'varchar');
        $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminselectbusiness&businessName='.$businessName.'&filter='.$filter.'&order='.$order, false));
    }
    public function delete()
    {
       
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Adminselectbusiness', 'BusinessModel');
        
        $jinput = JFactory::getApplication()->input;
        
        // Check that the token is in a valid format.
        $businessId     = $jinput->get('businessId', 0, 'INT');
        
        $result = $model->deleteBusiness($businessId);
        
        if($result == true)
        {
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminselectbusiness', false));
//            $this->setRedirect(JRoute::_('index.php?option=com_business&view=promotions&layout=complete', false));
        }
        else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminselectbusiness&layout=fail', false));
        }
    }
}
