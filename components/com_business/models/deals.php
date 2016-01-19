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
class BusinessModelDeals extends JModelItem
{
	/**
	 * @var array messages
	 */
        protected $infomation;
        protected $deals;
        protected $dealsById;
        protected $dealinmonth;
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
	public function getTable($type = 'Deals', $prefix = 'BusinessTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
        
        public function getDealinmonth()
        {
            $time = date('Y-m' , time());
            $firstDay = strtotime($time."-01 00:00:00");
            $lastDay = strtotime(date('Y-m-d 23:59:59', strtotime('last day of this month', $firstDay)));
            
            $user = JFactory::getUser();
            if (!is_array($this->dealinmonth))
            {
                    $this->dealinmonth = array();
            }

            if (isset($this->dealinmonth))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.
                    $query->select('c.*')
                    ->from($db->quoteName('#__users','a'))
                    ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                    ->join('INNER',$db->quoteName('#__deals','c'). ' ON (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('c.businessId') . ')')
                    ->where($db->quoteName('a.id') . ' = ' . $user->id . ' and endDate >=' . $firstDay . ' and endDate <=' . $lastDay);

                    $db->setQuery($query);
                    // Assign the message
                    $list = $db->loadAssocList();
                    $this->dealinmonth = count($list);
            }
            
            return $this->dealinmonth;
            
            
        }
	/**
	 * Get the message
	 *
	 * @param   integer  $id  Greeting Id
	 *
	 * @return  string        Fetched String from Table for relevant Id
	 */
        public function getDealsById()
	{
            $jinput = JFactory::getApplication()->input;
            $id     = $jinput->get('id', 1, 'INT');
            
            $user = JFactory::getUser();
            
            if (!is_array($this->dealsById))
            {
                    $this->dealsById = array();
            }

            if (isset($this->dealsById))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.
                    $query->select('c.*')
                    ->from($db->quoteName('#__users','a'))
                    ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                    ->join('INNER',$db->quoteName('#__deals','c'). ' ON (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('c.businessId') . ')')
                    ->where($db->quoteName('a.id') . ' = ' . $user->id . " and " . $db->quoteName('c.id') . " = " . $id);

                    $db->setQuery($query);
                    // Assign the message
                    $this->dealsById = $db->loadAssoc();
            }
            
            return $this->dealsById;
	}
        
        public function getInfomation()
	{
            $user = JFactory::getUser();
            if (!is_array($this->infomation))
            {
                    $this->infomation = array();
            }

            if (isset($this->infomation))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.
                    $query->select('b.*,a.firstName,a.lastName,a.newId')
                    ->from($db->quoteName('#__users','a'))
                    ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                    ->where($db->quoteName('a.id') . ' = ' . $user->id);

                    $db->setQuery($query);
                    // Assign the message
                    $this->infomation = $db->loadAssoc();
            }
            return $this->infomation;
	}
        
	public function getDeals()
	{
            $user = JFactory::getUser();
            if (!is_array($this->deals))
            {
                    $this->deals = array();
            }

            if (isset($this->deals))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.
                    $query->select('c.*')
                    ->from($db->quoteName('#__users','a'))
                    ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                    ->join('INNER',$db->quoteName('#__deals','c'). ' ON (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('c.businessId') . ')')
                    ->where($db->quoteName('a.id') . ' = ' . $user->id . ' and status = 0');

                    $db->setQuery($query);
                    // Assign the message
                    $this->deals = $db->loadAssocList();
            }
            return $this->deals;
	}
        public function deleteDeals($id)
        {
            $user = JFactory::getUser();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('c.*')
                    ->from($db->quoteName('#__users','a'))
                    ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                    ->join('INNER',$db->quoteName('#__deals','c'). ' ON (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('c.businessId') . ')')
                    ->where($db->quoteName('a.id') . ' = ' . $user->id . " and " . $db->quoteName('c.id') . " = " . $id);
            $db->setQuery($query);
            $result = $db->loadAssocList();
            if(!empty($result))
            {
//                $querydelete = $db->getQuery(true);
//                $conditions = array(
//                    $db->quoteName('id') . ' = ' . $id
//                );
//                $querydelete->delete($db->quoteName('#__deals'));
//                $querydelete->where($conditions);
//                $db->setQuery($querydelete);
//                $result = $db->execute();
//                return $result;
                $object->id = $id;
                $object->status = '1';
                $result = JFactory::getDbo()->updateObject('#__deals', $object, 'id');
                return $result;
            }
            else
            {
                return false;
            }
            
        }
        public function updateDeals($deals)
        {
            $object = new stdClass();
            $object->id = $deals['id'];
            $object->title = $deals['title'];
            $object->content = $deals['content'];
            $object->businessId = $deals['businessId'];
            $object->endDate = $deals['endDate'];
            $result = JFactory::getDbo()->updateObject('#__deals', $object, 'id');
            return $result;
        }
        public function newDeals($deals)
        {
            $object = new stdClass();
            
            $object->title = $deals['title'];
            $object->content = $deals['content'];
            $object->businessId = $deals['businessId'];
            $object->endDate = $deals['endDate'];
            $object->createdAt = $deals['createdAt'];
            $object->status = $deals['status'];
            $object->countDownTime = $deals['countDownTime'];

            // Insert the object into the user profile table.
            $result = JFactory::getDbo()->insertObject('#__deals', $object);
            return $result;
        }
}
