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
class BusinessModelSale extends JModelItem
{
	/**
	 * @var array messages
	 */
        protected $listSale;
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
        public function getListSale()
        {
           
            $filter     = JRequest::getVar('filter', '');

			$this->listSale = array();

            if (isset($this->listSale))
            {
                $db    = JFactory::getDbo();
                $query = $db->getQuery(true);
                // Create the base select statement.
                $query->select('*')->from($db->quoteName('#__sale'));
                if($filter != "" )
                {
                    $query->where($db->quoteName('newId'). ' LIKE "%' . $filter . '%"' . ' or firstName LIKE "%' .$filter . '%"' . ' or lastName LIKE "%' .$filter . '%"');
                    
                }
                                
                $db->setQuery($query);
                $this->listSale = $db->loadAssocList();
                
            }
            return $this->listSale;
        }
		
		 public function getSale()
        {

			$db    = JFactory::getDbo();
			//$query = $db->getQuery(true);
			// Create the base select statement.
			//$query->select('*')->from($db->quoteName('#__sale'))->where('id', JRequest::getVar('id'));
							
			$db->setQuery("SELECT * FROM #__sale WHERE id = ".JRequest::getVar('id'));
			$this->sale = $db->loadObject();

            return $this->sale;
        }
		
        public function deleteSale($id)
        {
            $db = JFactory::getDbo();
            
			$querydelete = $db->getQuery(true);
			$conditions = array(
				$db->quoteName('id') . ' = ' . $id
			);
			$querydelete->delete($db->quoteName('#__sale'));
			$querydelete->where($conditions);
			$db->setQuery($querydelete);
			$resultbusiness = $db->execute();
			
			if($resultbusiness == true)
			{
				return true;
			}
			else
			{
				return false;
			}
            
        }
		
		public function saveSale()
        {
            $db = JFactory::getDBO();
            
			$object = new stdClass();
            $object->firstName = JRequest::getVar('firstName');
            $object->lastName = JRequest::getVar('lastName');
			$object->address = JRequest::getVar('address');
            $object->email = JRequest::getVar('email');
			$object->phone = JRequest::getVar('phone');
            $object->about = JRequest::getVar('about');
			$object->newId = '';

            // Insert the object into the user profile table.
            $result = $db->insertObject('#__sale', $object);
			
			$id = $db->insertid();
			$newId = 3000000 + $id;
			$q = "UPDATE #__sale SET newId = $newId WHERE id = ".$id;
			$db->setQuery($q);
			$db->execute();
			
            return $result;
            
        }
		
		public function editSale()
        {
            $db = JFactory::getDBO();
            
			$object = new stdClass();
			$object->id = JRequest::getVar('id');
            $object->firstName = JRequest::getVar('firstName');
            $object->lastName = JRequest::getVar('lastName');
			$object->address = JRequest::getVar('address');
            $object->email = JRequest::getVar('email');
			$object->phone = JRequest::getVar('phone');
            $object->about = JRequest::getVar('about');

            // Insert the object into the user profile table.
            $result = $db->updateObject('#__sale', $object, 'id');
			
            return $result;
            
        }
}
