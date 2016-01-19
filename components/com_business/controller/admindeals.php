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
class BusinessControllerAdmindeals extends JControllerForm
{
    public function delete()
    {
       
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Admindeals', 'BusinessModel');
        
        $jinput = JFactory::getApplication()->input;
        
        // Check that the token is in a valid format.

        $id     = $jinput->get('id', 1, 'INT');
        $businessId     = $jinput->get('businessId', 0, 'INT');
        
        $result = $model->deleteDeals($id,$businessId);
        
        if($result == true)
        {
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=admindeals&businessId='.$businessId, false));
        }
        else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=admindeals&layout=fail&businessId='.$businessId, false));
        }
    }
    public function edit($key = null, $urlVar = NULL)
    {
        $jinput = JFactory::getApplication()->input;
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Admindeals', 'BusinessModel');
        $requestData = $this->input->post->get('jform', array(), 'array');
        $deals = array();
        
        $deals['id'] = $requestData['dealsid'];
        $deals['title'] = $requestData['title'];
        $deals['content'] = $requestData['content'];
        $deals['businessId'] = $requestData['businessid'];
        $deals['endDate'] = $this->stringToTime($requestData['endDate']);
        
        $result = $model->updateDeals($deals);
        if($result == true)
        {
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=admindeals&businessId='.$requestData['businessid'], false));
//            $this->setRedirect(JRoute::_('index.php?option=com_business&view=deals&layout=complete', false));
        }
        else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=admindeals&layout=fail&businessId='.$requestData['businessid'], false));
        }
    }
    
    public function newDeals($key = null, $urlVar = NULL)
    {
        $jinput = JFactory::getApplication()->input;
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Admindeals', 'BusinessModel');
        $requestData = $this->input->post->get('jform', array(), 'array');
        $deals = array();
        
        
        
        $deals['title'] = $requestData['title'];
        $deals['content'] = $requestData['content'];
        $deals['businessId'] = $requestData['businessid'];
        $deals['endDate'] = $this->stringToTime($requestData['endDate']);
        $deals['createdAt'] = time();
        $deals['status'] = '0';
        $deals['countDownTime'] = strtotime('+2 days', $deals['createdAt']);
        
        $result = $model->newDeals($deals);
        if($result)
        {
			$businessId = $deals['businessId'];
		
			$db = JFactory::getDBO();
			$db->setQuery("SELECT businessName FROM #__business WHERE id = $businessId");
			$businessName = $db->loadResult();
			
			$db->setQuery("SELECT DISTINCT(customerId) FROM #__checkin WHERE businessId = $businessId");
			$ids = $db->loadColumn();
			foreach($ids as $id){
				$arr[] = 'T("userId", EQ, '.$id.')';
			}
			$str = implode("+", $arr);
			
			$data['pushType'] = 3;
			$data['businessId'] = $businessId;
			$data['businessType'] = 0;
			$data = json_encode($data);
			
			$url = 'https://cp.pushwoosh.com/json/1.3/createTargetedMessage';
			$send['request'] = array('auth' => 'C4jIJrQCJLlubwb7pPvBDsdcA9SdGSIkRynZC2vZ0J4y7jkEuUiq6GjDK7LFVMeifC72FuSVtRqjzDqXpEYX', 'send_date'=>'now', 'content'=>$businessName.': '.$deals['title'], 'devices_filter'=>'A("9727D-054A0") * ('.$str.')', 'data'=>$data);
	
			$request = json_encode($send);
		 
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=admindeals&businessId='.$requestData['businessid'], false));
//            $this->setRedirect(JRoute::_('index.php?option=com_business&view=deals&layout=complete', false));
        }
        else
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=admindeals&layout=fail&businessId='.$requestData['businessid'], false));
        }
    }
    
    function stringToTime($date)
    {
        $dates = explode("/", $date);
        $newstring = "23:59:59 ".$dates[2] . '-' . $dates[1] . '-' . $dates[0];
        return strtotime($newstring);
    }
}
