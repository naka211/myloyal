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
class BusinessModelAdmincustomers extends JModelItem
{
	/**
	 * @var array messages
	 */
        
        protected $customers;
        protected $customersById;
        protected $data;
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
        public function getPage()
        {
            $jinput = JFactory::getApplication()->input;
            $businessId     = $jinput->get('businessId', 0, 'INT');
            $numrowPage = 20;
            if (!is_array($this->page))
            {
                    $this->page = array();
            }
            
            if (isset($this->page))
            {
                    // load data for business

                    $db    = JFactory::getDbo();
                    $querynum = $db->getQuery(true);

                    // Create the base select statement.
                    $querynum->select('DISTINCT a.customerId , a.businessId')
                    ->from($db->quoteName('#__checkin','a'))
                    ->join('INNER',$db->quoteName('#__users','b'). ' ON (' . $db->quoteName('a.customerId') . ' = ' . $db->quoteName('b.id') . ')')
                    ->join('INNER',$db->quoteName('#__business','c'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('c.id') . ')')
                    ->where($db->quoteName('c.id') . ' = ' . $businessId)
                    ->group($db->quoteName('a.customerId'));              

                    $db->setQuery($querynum);
                    // Assign the message
                    $listCustomer = $db->loadObjectList();

                    $numCustomer = count($listCustomer);
                    if($numCustomer % $numrowPage == 0)
                    {
                        $this->page = (INT)($numCustomer / $numrowPage);
                    }
                    else
                    {
                        $this->page = (INT)($numCustomer / $numrowPage) + 1;
                    }
                    return $this->page;
            }
        }
	public function getCustomers()
	{
            $jinput = JFactory::getApplication()->input;
            $page = $jinput->get('page', 1, 'INT');
            $businessId     = $jinput->get('businessId', 0, 'INT');
//            
//            $numrowPage = 20;
//            
//            $limitStart = ($page - 1) * $numrowPage;
//            $limitEnd = $numrowPage;
           
            $db    = JFactory::getDbo();
            $querynum = $db->getQuery(true);

            // Create the base select statement.
            $querynum->select('DISTINCT a.customerId , a.businessId')
            ->from($db->quoteName('#__checkin','a'))
            ->join('INNER',$db->quoteName('#__users','b'). ' ON (' . $db->quoteName('a.customerId') . ' = ' . $db->quoteName('b.id') . ')')
            ->join('INNER',$db->quoteName('#__business','c'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('c.id') . ')')
            ->where($db->quoteName('c.id') . ' = ' . $businessId )
            ->group($db->quoteName('a.customerId'));              
    
            $db->setQuery($querynum);
            // Assign the message
            $listCustomer = $db->loadObjectList();
            
            $numCustomer = count($listCustomer);
            $topmember = round($numCustomer / 10);
            $star = $topmember;
            // top member
//            $star = 0;
//            if(($topmember - $numrowPage * ($page - 1)) >= $numrowPage)
//            {
//                $star = $numrowPage;
//            }
//            else
//            {
//                $star = $topmember - $numrowPage * ($page - 1);
//            }
            //
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
                    $query->select('DISTINCT b.* , max(a.createdAt) as checkintime , count(*) as numcheckin')
                    ->from($db->quoteName('#__checkin','a'))
                    ->join('INNER',$db->quoteName('#__users','b'). ' ON (' . $db->quoteName('a.customerId') . ' = ' . $db->quoteName('b.id') . ')')
                    ->join('INNER',$db->quoteName('#__business','c'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('c.id') . ')')
                    ->where($db->quoteName('c.id') . ' = ' . $businessId )
                    ->group($db->quoteName('a.customerId'))
                    //->order('numcheckin DESC' . " limit " . $limitStart . ',' . $limitEnd);
                    ->order('numcheckin DESC');
                    
                    $db->setQuery($query);
                    // Assign the message
                    $this->customers = $db->loadAssocList();
            }
            foreach($this->customers as $key=>$customer)
            {
                $this->customers[$key]['stringtime'] = $this->_timeElapsedString($this->customers[$key]['checkintime']);
                if($star > 0 )
                {
                    $this->customers[$key]['star'] = 1;
                    $star --;
                }  
                else
                {
                    $this->customers[$key]['star'] = 0;
                }
            }
            
            return $this->customers;
	}
        
        public function _timeElapsedString($ptime){
            $etime = time() - $ptime;
            if ($etime < 10)
            {
                return 'lige nu';
            }
            $a = array( 
                12 * 30 * 24 * 60 * 60  =>  'år',
                30 * 24 * 60 * 60       =>  'måneder',
                24 * 60 * 60            =>  'dage',
                60 * 60                 =>  'timer',
                60                      =>  'minutter',
                1                       =>  'sekunder'
                );
            foreach ($a as $secs => $str){
                $d = $etime / $secs;
                if ($d >= 1){
                    $r = round($d);
                    return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' siden';
                }
            }
        }
        public function getBusiness()
	{
            $jinput = JFactory::getApplication()->input;
            $businessId     = $jinput->get('businessId', 0, 'INT');
            
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
                    $query->select('b.*,a.firstName,a.lastName,a.newId')
                    ->from($db->quoteName('#__users','a'))
                    ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.userId') . ')')
                    ->where($db->quoteName('b.id') . ' = ' . $businessId);

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
            $businessId     = $jinput->get('businessId', 0, 'INT');
            
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
