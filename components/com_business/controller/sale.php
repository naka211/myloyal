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
class BusinessControllerSale extends JControllerForm
{
    public function search()
    {
        $requestData = $this->input->post->get('jform', array(), 'array');
        $businessName = $requestData['businessName'];
        $jinput = JFactory::getApplication()->input;
        $filter     = $jinput->get('filter', "", 'varchar');
        $order = $jinput->get('order', "", 'varchar');
        $this->setRedirect(JRoute::_('index.php?option=com_business&view=sale&filter='.$filter, false));
    }
    public function delete()
    {
       
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Sale', 'BusinessModel');
        
        $jinput = JFactory::getApplication()->input;
        
        // Check that the token is in a valid format.
        $id     = $jinput->get('id', 0, 'INT');
        
        $result = $model->deleteSale($id);
        
        if($result == true)
        {
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=sale', false));
//            $this->setRedirect(JRoute::_('index.php?option=com_business&view=promotions&layout=complete', false));
        }
        else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=sale&layout=fail', false));
        }
    }
	
	public function save()
    {
        $model	= $this->getModel('Sale', 'BusinessModel');
       
		$result = $model->saveSale();
		if($result == true)
		{
			$this->setMessage(JText::_('Ny sælger gemmes !!'));
			$this->setRedirect(JRoute::_('index.php?option=com_business&view=sale', false));
		}
		else
		{
			$this->setMessage(JText::_('Besparelse er mislykket!'));
			$this->setRedirect(JRoute::_('index.php?option=com_business&view=sale', false), 'error');
		}
    }
	
	public function edit()
    {
        $model	= $this->getModel('Sale', 'BusinessModel');
       
		$result = $model->editSale();
		if($result == true)
		{
			$this->setMessage(JText::_('Data er gemt!'));
			$this->setRedirect(JRoute::_('index.php?option=com_business&view=sale', false));
		}
		else
		{
			$this->setMessage(JText::_('Redigering er mislykket!'));
			$this->setRedirect(JRoute::_('index.php?option=com_business&view=sale', false), 'error');
		}
    }
}
