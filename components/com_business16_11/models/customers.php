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
class BusinessModelCustomers extends JModelItem
{
	/**
	 * @var array messages
	 */
        
        protected $customers;
        protected $customersById;
        protected $data;
        

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
	public function getTable($type = 'Promotion', $prefix = 'BusinessTable', $config = array())
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
        
	public function getCustomers()
	{
            $user = JFactory::getUser();
            $jinput = JFactory::getApplication()->input;
            $page = $jinput->get('page', 1, 'INT');
            
            
            $limitStart = ($page - 1) * 20;
            $limitEnd = $page * 20;
            
            if (!is_array($this->customers))
            {
                    $this->customers = array();
            }

            if (isset($this->customers))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.
                    $query->select('DISTINCT b.* , c.createdAt as checkintime')
                    ->from($db->quoteName('#__checkin','a'))
                    ->join('INNER',$db->quoteName('#__users','b'). ' ON (' . $db->quoteName('a.customerId') . ' = ' . $db->quoteName('b.id') . ')')
                    ->join('INNER',$db->quoteName('#__business','c'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('c.id') . ')')
                    ->where($db->quoteName('c.userId') . ' = ' . $user->id . " limit " . $limitStart . "," .$limitEnd);
                    
                    
                    $db->setQuery($query);
                    // Assign the message
                    $this->customers = $db->loadAssocList();
            }
            foreach($this->customers as $key=>$customer)
            {
                $this->customers[$key]['stringtime'] = $this->_timeElapsedString($this->customers[$key]['checkintime']);
            }
            
            return $this->customers;
	}
        
        public function _timeElapsedString($ptime){
            $etime = time() - $ptime;
            if ($etime < 10)
            {
                return 'just now';
            }
            $a = array( 
                12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );
            foreach ($a as $secs => $str){
                $d = $etime / $secs;
                if ($d >= 1){
                    $r = round($d);
                    return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
                }
            }
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
        public function getCustomerById()
	{
            $jinput = JFactory::getApplication()->input;
            $customerId     = $jinput->get('customerid', 1, 'INT');
            
            $user = JFactory::getUser();
            
            if (!is_array($this->customersById))
            {
                    $this->customersById = array();
            }

            if (isset($this->customersById))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    // Create the base select statement.
                    $query->select('a.*')
                    ->from($db->quoteName('#__users','a'))
                    ->where($db->quoteName('a.id') . ' = ' . $customerId);

                    $db->setQuery($query);
                    // Assign the message
                    $this->customersById = $db->loadAssoc();
            }
            
            return $this->customersById;
	}
}
