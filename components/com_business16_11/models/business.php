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
class BusinessModelBusiness extends JModelItem
{
	/**
	 * @var array messages
	 */
	protected $messages;
        protected $data;
        protected $workingtime;
        protected $userinfo;
        
        
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Business', $prefix = 'BusinessTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the message
	 *
	 * @param   integer  $id  Greeting Id
	 *
	 * @return  string        Fetched String from Table for relevant Id
	 */
        public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
//		$form = $this->loadForm('com_business.business', 'business', array('control' => 'jform', 'load_data' => $loadData));
//
//		if (empty($form))
//		{
//			return false;
//		}
//
//		return $form;
	}
        protected function loadFormData()
	{
//		$data = $this->getData();
//
//		$this->preprocessData('com_business.business', $data);
//
//		return $data;
	}
        
        public function getBusiness()
	{
            $user = JFactory::getUser();
            if (!is_array($this->data))
            {
                    $this->data = array();
            }

            if (isset($this->data))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.
                    $query->select('*')
                    ->from($db->quoteName('#__business','a'))
                    ->where($db->quoteName('a.userId') . ' = ' . $user->id);

                    $db->setQuery($query);
                    // Assign the message
                    $this->data = $db->loadAssoc();
            }
            return $this->data;
	}
        public function getUserinfo()
        {
            $user = JFactory::getUser();
            if (!is_array($this->userinfo))
            {
                    $this->userinfo = array();
            }

            if (isset($this->userinfo))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.

                    $queryDatetime = $db->getQuery(true);
                    $queryDatetime->select('*')
                    ->from($db->quoteName('#__users','a'))
                    ->where($db->quoteName('a.id') . ' = ' . $user->id);
                    $db->setQuery($queryDatetime);
                    $this->userinfo = $db->loadAssoc();
            }
            return $this->userinfo;
        }
        
        public function getWorkingtime()
        {
            if (!is_array($this->workingtime))
            {
                    $this->workingtime = array();
            }

            if (isset($this->workingtime))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.

                    $queryDatetime = $db->getQuery(true);
                    $queryDatetime->select('*')
                    ->from($db->quoteName('#__workingtime','a'))
                    ->where($db->quoteName('a.businessId') . ' = ' . $this->data['id']);
                    $db->setQuery($queryDatetime);
                    $results = $db->loadAssocList();
                    foreach($results as $result)
                    {
                        
                        $this->workingtime[$result['dateType']] = $result;
                    }
            }
            return $this->workingtime;
        }
            
        public function updateBusiness($business)
        {
            $object = new stdClass();
            $object->id = $business['id'];
            $object->businessName = $business['businessName'];
			$object->businessEmail = $business['businessEmail'];
            $object->cvrNumber = $business['cvrNumber'];
            $object->shortName = $business['shortName'];
            $object->phone = $business['phone'];
            $object->website = $business['website'];
            $object->icon = $business['icon'];
            $object->address = $business['address'];
            $object->postnr = $business['postnr'];
            $object->postnrBy = $business['postnrBy'];
            $object->country = $business['country'];
            $object->latitude = $business['latitude'];
            $object->longitude = $business['longitude'];
            $result = JFactory::getDbo()->updateObject('#__business', $object, 'id');
            return $result;
        }
        public function updateUserinfo($userinfo)
        {
            $object = new stdClass();
            $object->id = $userinfo['id'];
            $object->firstName = $userinfo['firstName'];
            $object->lastName = $userinfo['lastName'];
            $object->name = $userinfo['name'];
            $result = JFactory::getDbo()->updateObject('#__users', $object, 'id');
            return $result;
        }
        public function updateWorkingtime($workingtime,$business)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            //Delete all workingtime of business
            $conditions = array(
                $db->quoteName('businessId') . ' = ' . $business['id']
            );
            $query->delete($db->quoteName('#__workingtime'));
            $query->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            //Insert new workingtime for business
            if($result == TRUE)
            {
                foreach($workingtime as $key=>$date){
                    $object = new stdClass();
                    $object->businessId = $business['id'];
                    $object->dateType = $key;
                    $object->fromTime = $date['fromTime'];
                    $object->toTime = $date['toTime'];
                    $object->close = $date['close'];
                    $object->createdAt = time();
                    $object->updatedAt = time();

                    // Insert the object into the user profile table.
                    $result = JFactory::getDbo()->insertObject('#__workingtime', $object);
                }
            }
            return $result;
        }
}
