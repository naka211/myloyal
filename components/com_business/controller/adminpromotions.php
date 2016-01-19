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
class BusinessControllerAdminpromotions extends JControllerForm
{
    public function delete()
    {
       
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Adminpromotions', 'BusinessModel');
        
        $jinput = JFactory::getApplication()->input;
        
        // Check that the token is in a valid format.

        $id     = $jinput->get('id', 1, 'INT');
        $businessId     = $jinput->get('businessId', 1, 'INT');
        
        $result = $model->deletePromotion($id,$businessId);
        
        if($result == true)
        {
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminpromotions&businessId='.$businessId, false));
//            $this->setRedirect(JRoute::_('index.php?option=com_business&view=promotions&layout=complete', false));
        }
        else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminpromotions&layout=fail&businessId='.$businessId, false));
        }
    }
    public function edit($key = null, $urlVar = NULL)
    {
        $jinput = JFactory::getApplication()->input;
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Promotions', 'BusinessModel');
        $requestData = $this->input->post->get('jform', array(), 'array');
        $promotion = array();
        $promotion['id'] = $requestData['promotionid'];
        $promotion['type'] = $requestData['promotion_type'];
        switch ($promotion['type']){
            case "1":
                $promotion['point'] = $requestData['pointorstamp'];
                $promotion['stamp'] = "";
                break;
            case "2":
                $promotion['stamp'] = $requestData['pointorstamp'];
                $promotion['point'] = "";
                break;
            default :
                break;
        }
        $promotion['title'] = $requestData['title'];
        $promotion['content'] = $requestData['content'];
        $promotion['startDate'] = $this->stringToTime($requestData['startDate']);
        $promotion['endDate'] = $this->stringToTime($requestData['endDate']);
        $promotion['updatedAt'] = time();
        
        $result = $model->updatePromotion($promotion);
        if($result == true)
        {
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminpromotions&businessId='.$requestData['businessid'], false));
//            $this->setRedirect(JRoute::_('index.php?option=com_business&view=promotions&layout=complete', false));
        }
        else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminpromotions&layout=fail&businessId='.$requestData['businessid'], false));
        }
    }
    
    public function newPromotion($key = null, $urlVar = NULL)
    {
        $jinput = JFactory::getApplication()->input;
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Promotions', 'BusinessModel');
        $requestData = $this->input->post->get('jform', array(), 'array');
        $promotion = array();
//        $promotion['id'] = $requestData['promotionid'];
        $promotion['type'] = $requestData['promotion_type'];
        switch ($requestData['promotion_type']){
            case "1":
                $promotion['point'] = $requestData['pointorstamp'];
                $promotion['stamp'] = "";
                break;
            case "2":
                $promotion['stamp'] = $requestData['pointorstamp'];
                $promotion['point'] = "";
                break;
            default :
                break;
        }
        $promotion['title'] = $requestData['title'];
        $promotion['content'] = $requestData['content'];
        $promotion['businessId'] = $requestData['businessid'];
        $promotion['icon'] = $requestData['promotion_icon'];
        $promotion['startDate'] = $this->stringToTime($requestData['startDate']);
        $promotion['endDate'] = $this->stringToTime($requestData['endDate']);
        $promotion['createdAt'] = time();
        $promotion['updatedAt'] = time();
        $allpromotion = $model->getPromotions();
        $total = count($allpromotion);
        if($total >= "4")
        {
            $this->setMessage(JText::_('You can not create promotion because your promotions is maximum now .'),'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminpromotions&layout=fail&businessId='.$requestData['businessid'], false));
        }
        else
        {
            $result = $model->newPromotion($promotion);
            if($result == true)
            {
                $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
                $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminpromotions&businessId='.$requestData['businessid'], false));
    //            $this->setRedirect(JRoute::_('index.php?option=com_business&view=promotions&layout=complete', false));
            }
            else
            {
                $this->setRedirect(JRoute::_('index.php?option=com_business&view=promotions&layout=fail&businessId='.$requestData['businessid'], false));
            }
        }
    }
    
    function stringToTime($date)
    {
        $dates = explode("/", $date);
        $newstring = $dates[2] . '-' . $dates[1] . '-' . $dates[0];
        return strtotime($newstring);
    }
}
