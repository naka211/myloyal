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
class BusinessModelAdminselectbusiness extends JModelItem
{
	/**
	 * @var array messages
	 */
        protected $listbusiness;
        protected $page;


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
        public function getPage()
        {
            $numrowPage = 1000000;
            
            $jinput = JFactory::getApplication()->input;
            $businessName     = $jinput->get('businessName', "", 'varchar');
            $page = $jinput->get('page', 1, 'INT');
            $filter     = $jinput->get('filter', "", 'varchar');
            $order = $jinput->get('order', "", 'varchar');
            $limitStart = ($page - 1) * $numrowPage;
            $limitEnd = $numrowPage;
            
            if($order == "")
            {
                $order = "ASC";
            }
            
            if (!is_array($this->page))
            {
                    $this->page = array();
            }
            
            if (isset($this->page))
            {
                    // load data for business

                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    // Create the base select statement.
                    $query->select('count(*) as countCheckin,a.*')
                    ->from($db->quoteName('#__business','a'))
					->join('INNER',$db->quoteName('#__users','c'). ' ON (' . $db->quoteName('a.userId') . ' = ' . $db->quoteName('c.id') . ')')
                    ->join('LEFT',$db->quoteName('#__checkin','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.businessId') . ')');
                    if($businessName != "" )
                    {
                        $query->where($db->quoteName('a.businessName'). ' like "%' . $businessName . '%"' . ' or cvrNumber like "%' .$businessName . '%"'  );

                    }

                    if($filter != "")
                    {
                        $query->group($db->quoteName('a.id'));
                        $query->order($filter . ' ' . $order);
                    }
                    else
                    {
                        $query->group($db->quoteName('a.id'));
                    }
                    $db->setQuery($query);
                    // Assign the message
                    $listBusiness = $db->loadObjectList();

                    $numBusiness = count($listBusiness);
                    
                    if($numBusiness % $numrowPage == 0)
                    {
                        $this->page = (INT)($numBusiness / $numrowPage);
                    }
                    else
                    {
                        $this->page = (INT)($numBusiness / $numrowPage) + 1;
                    }
                    return $this->page;
            }
        }
        public function getListBusiness()
        {
            $numrowPage = 1000000;
            $jinput = JFactory::getApplication()->input;
            $page = $jinput->get('page', 1, 'INT');
            $businessName     = $jinput->get('businessName', "", 'varchar');
            $filter     = $jinput->get('filter', "", 'varchar');
            $order = $jinput->get('order', "", 'varchar');
            $limitStart = ($page - 1) * $numrowPage;
            $limitEnd = $numrowPage;
            if($order == "")
            {
                $order = "ASC";
            }
            else
            {
                $order = ($order == "ASC")?"DESC":"ASC";
            }
            if (!is_array($this->listbusiness))
            {
                    $this->listbusiness = array();
            }

            if (isset($this->listbusiness))
            {
                $db    = JFactory::getDbo();
                $query = $db->getQuery(true);
                // Create the base select statement.
                $query->select('count(*) as countCheckin,a.*')
                ->from($db->quoteName('#__business','a'))
                ->join('INNER',$db->quoteName('#__users','c'). ' ON (' . $db->quoteName('a.userId') . ' = ' . $db->quoteName('c.id') . ')')
                ->join('INNER',$db->quoteName('#__user_usergroup_map','d'). ' ON (' . $db->quoteName('a.userId') . ' = ' . $db->quoteName('d.user_id') . ')')
                ->join('LEFT',$db->quoteName('#__checkin','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.businessId') . ')');
                if($businessName != "" )
                {
                    $query->where($db->quoteName('a.businessName'). ' like "%' . $businessName . '%"' . ' or cvrNumber like "%' .$businessName . '%"');
                    
                }
                
                if($filter != "")
                {
                    $query->group($db->quoteName('a.id'));
                    $query->order($filter . ' ' . $order ." limit " . $limitStart . ',' . $limitEnd);
                }
                else
                {
                    $query->where($db->quoteName('d.group_id'). ' = 3');
                    $query->group($db->quoteName('a.id') . " limit " . $limitStart . ',' . $limitEnd);
                }
                
                $db->setQuery($query);
                $this->listbusiness = $db->loadAssocList();
                
            }
            return $this->listbusiness;
        }
        public function deleteBusiness($businessId)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('b.*')
                    ->from($db->quoteName('#__users','a'))
                    ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                    ->where($db->quoteName('b.id') . ' = ' . $businessId);
            $db->setQuery($query);
            $result = $db->loadAssoc();
            $userId = $result['userId'];
            
            if(!empty($result))
            {
                //delete business
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('id') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__business'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultbusiness = $db->execute();
                //delete deals
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('businessId') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__deals'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultdeals = $db->execute();
                //delete promotion
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('businessId') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__promotion'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultpromotion = $db->execute();
                //delete checkin
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('businessId') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__checkin'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultcheckin = $db->execute();
                //delete point
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('businessId') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__point'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultpoint = $db->execute();
                //delete stamp
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('businessId') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__stamp'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultstamp = $db->execute();
                //delete log point
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('businessId') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__log_point'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultlogpoint = $db->execute();
                //delete log stamp
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('businessId') . ' = ' . $businessId
                );
                $querydelete->delete($db->quoteName('#__log_stamp'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultlogstamp = $db->execute();
                //delete user
                $querydelete = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('id') . ' = ' . $userId
                );
                $querydelete->delete($db->quoteName('#__users'));
                $querydelete->where($conditions);
                $db->setQuery($querydelete);
                $resultuser = $db->execute();
                if($resultbusiness == true && $resultuser == true)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
            
        }
}
