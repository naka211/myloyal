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
class BusinessControllerBusiness extends JControllerForm
{
    public function save($key = null, $urlVar = NULL)
    {
//        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // If registration is disabled - Redirect to login page.
//        if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0)
//        {
//                $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
//
//                return false;
//        }
        $jinput = JFactory::getApplication()->input;
//        $token = $jinput->getAlnum('token');
//        
//        // Check that the token is in a valid format.
//        if ($token === null || strlen($token) !== 32)
//        {
//                JError::raiseError(403, JText::_('JINVALID_TOKEN'));
//
//                return false;
//        }
        $app	= JFactory::getApplication();
        $model	= $this->getModel('Business', 'BusinessModel');

        // Get the user data.
        $requestData = $this->input->post->get('jform', array(), 'array');
        
        $business = array();
        $workingtime = array();
        $userinfo = array();
        
        $business['id'] = $requestData['businessid'];
        $business['businessName'] = $requestData['businessName'];
        $business['cvrNumber'] = $requestData['cvrNumber'];
        $business['shortName'] = $requestData['shortName'];
        $business['phone'] = $requestData['phone'];
        $business['website'] = $requestData['website'];
        $business['icon'] = $requestData['icon'];
        $business['address'] = $requestData['address'];
        $business['postnr'] = $requestData['postnr'];
        $business['postnrBy'] = $requestData['postnrBy'];
        $business['country'] = $requestData['country'];
        $business['latitude'] = $requestData['latitude'];
        $business['longitude'] = $requestData['longitude'];
        
        $resultBusiness = $model->updateBusiness($business);
        
        $userinfo['id'] = $requestData['userid'];
        $userinfo['firstName'] = $requestData['first_name'];
        $userinfo['lastName'] = $requestData['second_name'];
        $userinfo['name'] = $requestData['first_name'] . ' ' . $requestData['second_name'];
        
        $resultUserinfo = $model->updateUserinfo($userinfo);
        
        foreach($requestData as $key=>$field){
            if(strstr($key,'fromTime_') != "")
            {
                $workingtime[str_replace("fromTime_", "", $key)]["fromTime"] = $field;
            }
            elseif(strstr($key,'toTime_') != "")
            {
                $workingtime[str_replace("toTime_", "", $key)]["toTime"] = $field;
            }
            elseif(strstr($key,'date_') != "")
            {
                $workingtime[str_replace("date_", "", $key)]["close"] = $field;
            }
        }
        $resultWorkingtime = $model->updateWorkingtime($workingtime,$business);
        
        if($resultBusiness == TRUE && $resultUserinfo == TRUE && $resultWorkingtime == TRUE)
        {
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=business&layout=complete', false));
        }
        else
        {
            $app->setUserState('com_business.business.data', $business);
            $app->setUserState('com_business.business.workingtime', $requestData);
            $app->setUserState('com_business.business.userinfo', $requestData);
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=business', false));

            return false;
        }
    }
}
