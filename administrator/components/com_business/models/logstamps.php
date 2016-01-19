<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * HelloWorldList Model
 *
 * @since  0.0.1
 */
class BusinessModelLogstamps extends JModelList
{
        public function __construct($config = array())	
        {	
            if (empty($config['filter_fields']))		
            {			
                $config['filter_fields'] = array(				
                    'id',				
                    'businessName'		
                    );		
            } 		
            parent::__construct($config);	   
        }
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
 
		// Create the base select statement.
		$query->select('a.*,b.businessName,c.username,d.title')
                ->from($db->quoteName('#__log_stamp','a'))
                ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('b.id') . ')')
                ->join('INNER',$db->quoteName('#__users','c'). ' ON (' . $db->quoteName('a.customerId') . ' = ' . $db->quoteName('c.id') . ')')
                ->join('INNER',$db->quoteName('#__promotion','d'). ' ON (' . $db->quoteName('a.promotionId') . ' = ' . $db->quoteName('d.id') . ')');
                
                // Filter: like / search
                
                $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
                
                if (!empty($search))		
                {
                    $like = $db->quote('%' . $search . '%');
                    $query->where('b.businessName LIKE ' . $like);
                } 	
                    
                // Add the list ordering clause.
                $orderCol	= $this->state->get('list.ordering', 'businessName');	
                $orderDirn 	= $this->state->get('list.direction', 'asc'); 	
                
                $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
}