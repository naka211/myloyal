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
class BusinessControllerAdminbusiness extends JControllerForm
{
    public $listNameIcon = array(
            "Bar" => "beer.png",
            "Caf" => "coffee.png",
            "Sport" => "fitness.png",
            "Frisr" => "hairsalon.png",
            "Indkvartering" => "hotel.png",
            "Spisested" => "restaurant.png",
            "Butik" => "shop.png"
        );
    public function save($key = null, $urlVar = NULL)
    {

        $jinput = JFactory::getApplication()->input;

        $app	= JFactory::getApplication();
        $model	= $this->getModel('Adminbusiness', 'BusinessModel');

        // Get the user data.
        $requestData = $this->input->post->get('jform', array(), 'array');
        $icon = $this->input->post->get('jform_icon');
        
        $business = array();
        $workingtime = array();
        $userinfo = array();
        
        $business['id'] = $requestData['businessid'];
        $business['businessName'] = $requestData['businessName'];
        $business['cvrNumber'] = $requestData['cvrNumber'];
        $business['shortName'] = $requestData['shortName'];
        $business['phone'] = $requestData['phone'];
        $business['businessEmail'] = $requestData['businessEmail'];
        $business['website'] = $requestData['website'];
        $business['icon'] = $this->listNameIcon[$icon];
        $business['address'] = $requestData['address'];
        $business['postnr'] = $requestData['postnr'];
        $business['postnrBy'] = $requestData['postnrBy'];
        $business['country'] = $requestData['country'];
        $business['latitude'] = $requestData['latitude'];
        $business['longitude'] = $requestData['longitude'];
        $business['timeExpired'] = $this->stringToTime($requestData['time_expired']);
        if(isset($requestData['pointDescription']) && $requestData['pointDescription'] != "")
        {
            $business['pointDescription'] = $requestData['pointDescription'];
        }
        
        
        $returnPassword = TRUE;
        
        $userinfo['id'] = $requestData['userid'];
        $userinfo['firstName'] = $requestData['first_name'];
        $userinfo['lastName'] = $requestData['second_name'];
        $userinfo['name'] = $requestData['first_name'] . ' ' . $requestData['second_name'];
        if(isset($requestData['password']) && $requestData['password'] != "")
        {
            
            if(strlen($requestData['password']) < 4)
            {
                $returnPassword = FALSE;
            }
            else
            {
                $userinfo['password'] = JUserHelper::hashPassword($requestData['password']);
                $returnPassword = TRUE;
            }
        }
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
        
        $resultBusiness = $model->updateBusiness($business);
        
        $resultUserinfo = $model->updateUserinfo($userinfo);
        
        
        $resultWorkingtime = $model->updateWorkingtime($workingtime,$business);
        
        if($resultBusiness == TRUE && $resultUserinfo == TRUE && $resultWorkingtime == TRUE && $returnPassword == TRUE)
        {
            $this->setMessage(JText::_('Dine ændringen er nu gemt!'));
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=adminbusiness&businessId='.$requestData['businessid'], false));
//            $this->setRedirect(JRoute::_('index.php?option=com_business&view=business&layout=complete', false));
        }
        else
        {
            $app->setUserState('com_business.business.data', $business);
            $app->setUserState('com_business.business.workingtime', $requestData);
            $app->setUserState('com_business.business.userinfo', $requestData);
            $this->setMessage(JText::_('Adgangskoden er for kort. Adgangskoden skal være på mindst 4 karakterer.'), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_business&view=business$businessId='.$requestData['businessid'], false));

            return false;
        }
    }
    function stringToTime($date)
    {
        $dates = explode("/", $date);
        $newstring = $dates[2] . '-' . $dates[1] . '-' . $dates[0];
        return strtotime($newstring);
    }
}
