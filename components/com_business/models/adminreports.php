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
class BusinessModelAdminreports extends JModelItem
{
	/**
	 * @var array messages
	 */
        
        protected $reports;
        protected $totalCheckin;
        protected $infomation;

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
        
	public function getReports()
	{
            $jinput = JFactory::getApplication()->input;
            $businessId     = $jinput->get('businessId', 0, 'INT');
            $year = date('Y' , time());
            $listMonth = array();
            $fromTimeTotal = strtotime($this->convertDate('01/'.$jinput->get('fromTime', '01/'.$year, 'VARCHAR')));
            $fromTime = strtotime($this->convertDate('01/'.$jinput->get('fromTime', '01/'.$year, 'VARCHAR')));
            $toTime = strtotime($this->convertDate('01/'.$jinput->get('toTime', '12/'.$year, 'VARCHAR')));
            $toTimeTotal = strtotime('last day of this month', $toTime);
            while($fromTime < $toTime)
            {
                $month = date('Y-m' , $fromTime);
                $listMonth[$month]['from'] = $fromTime;
                $listMonth[$month]['to'] = strtotime('last day of this month', $fromTime);
                $fromTime = strtotime("+1 month" , $fromTime);
            }
            $month = date('Y-m' , $toTime);
            $listMonth[$month]['from'] = $toTime;
            $listMonth[$month]['to'] = strtotime('last day of this month', $toTime);
            
            if (!is_array($this->reports))
            {
                    $this->reports = array();
            }

            if (isset($this->reports))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    foreach ($listMonth as $key=>$month)
                    {
                        // load List check in
                        $querycheckin = $db->getQuery(true);
                        $querycheckin->select('count(*)')
                        ->from($db->quoteName('#__checkin','a'))
                        ->join('INNER',$db->quoteName('#__business','c'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('c.id') . ')')
                        ->where($db->quoteName('c.id') . ' = ' . $businessId . ' and a.createdAt >= "' .$month['from']. '" and a.createdAt <= "' . $month['to'] . '"');
                        $db->setQuery($querycheckin);
                        $this->reports[$key]['numCheckin'] = $db->loadResult();
                        
                        // load List stamps awarded
                        $querystampsawarded = $db->getQuery(true);
                        $querystampsawarded->select('sum(numStamp)')
                        ->from($db->quoteName('#__log_stamp','a'))
                        ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('b.id') . ')')
                        ->where($db->quoteName('b.id') . ' = ' . $businessId . ' and a.createdAt >= "' .$month['from']. '" and a.createdAt <= "' . $month['to'] . '"' . ' and a.type=1');
                        $db->setQuery($querystampsawarded);
                        $this->reports[$key]['numStampsawarded'] = ($db->loadResult() == "") ? "0": $db->loadResult();
                        
                        // load List rewards redeemed (stamp)
                        $queryrewardsredeemedstamp = $db->getQuery(true);
                        $queryrewardsredeemedstamp->select('count(*)')
                        ->from($db->quoteName('#__log_stamp','a'))
                        ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('b.id') . ')')
                        ->where($db->quoteName('b.id') . ' = ' . $businessId . ' and a.createdAt >= "' .$month['from']. '" and a.createdAt <= "' . $month['to'] . '"' . ' and a.type=2');
                        $db->setQuery($queryrewardsredeemedstamp);
                        $this->reports[$key]['numRewardsredeemedstamp'] = $db->loadResult();
                        
                        // load List points awarded
                        $querypointawarded = $db->getQuery(true);
                        $querypointawarded->select('sum(point)')
                        ->from($db->quoteName('#__log_point','a'))
                        ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('b.id') . ')')
                        ->where($db->quoteName('b.id') . ' = ' . $businessId . ' and a.createdAt >= "' .$month['from']. '" and a.createdAt <= "' . $month['to'] . '"' . ' and a.type=1');
                        $db->setQuery($querypointawarded);
                        $this->reports[$key]['numPointawarded'] = ($db->loadResult() == "") ? "0": $db->loadResult();
                        
                        // load List rewards redeemed (point)
                        $queryrewardsredeemedpoint = $db->getQuery(true);
                        $queryrewardsredeemedpoint->select('count(*)')
                        ->from($db->quoteName('#__log_point','a'))
                        ->join('INNER',$db->quoteName('#__business','b'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('b.id') . ')')
                        ->where($db->quoteName('b.id') . ' = ' . $businessId . ' and a.createdAt >= "' .$month['from']. '" and a.createdAt <= "' . $month['to'] . '"' . ' and a.type=2');
                        $db->setQuery($queryrewardsredeemedpoint);
                        $this->reports[$key]['numRewardsredeemedpoint'] = $db->loadResult();
                        
                        
                    }
                    
            }
            return $this->reports;
	}
        public function getInfomation()
	{
            $jinput = JFactory::getApplication()->input;
            $businessId     = $jinput->get('businessId', 0, 'INT');
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
                    ->where($db->quoteName('b.id') . ' = ' . $businessId);

                    $db->setQuery($query);
                    // Assign the message
                    $this->infomation = $db->loadAssoc();
            }
            return $this->infomation;
	}
        public function getTotalCheckin()
        {
            $jinput = JFactory::getApplication()->input;
            $businessId     = $jinput->get('businessId', 0, 'INT');
            $year = date('Y' , time());
            $listMonth = array();
            $fromTimeTotal = strtotime($this->convertDate('01/'.$jinput->get('fromTime', '01/'.$year, 'VARCHAR')));
            $fromTime = strtotime($this->convertDate('01/'.$jinput->get('fromTime', '01/'.$year, 'VARCHAR')));
            $toTime = strtotime($this->convertDate('01/'.$jinput->get('toTime', '12/'.$year, 'VARCHAR')));
            $toTimeTotal = strtotime('last day of this month', $toTime);
            if (!is_array($this->totalCheckin))
            {
                    $this->totalCheckin = array();
            }

            if (isset($this->totalCheckin))
            {
                    // load data for business
                    $db    = JFactory::getDbo();
                    // load total check in
                    $querytotalcheckin = $db->getQuery(true);
                    $querytotalcheckin->select('count(*)')
                    ->from($db->quoteName('#__checkin','a'))
                    ->join('INNER',$db->quoteName('#__business','c'). ' ON (' . $db->quoteName('a.businessId') . ' = ' . $db->quoteName('c.id') . ')')
                    ->where($db->quoteName('c.id') . ' = ' . $businessId . ' and a.createdAt >= "' .$fromTimeTotal. '" and a.createdAt <= "' . $toTimeTotal . '"');
                    $db->setQuery($querytotalcheckin);
                    $this->totalCheckin = $db->loadResult();
            }
            return $this->totalCheckin;
        }
        function convertDate($date)
        {
            $dates = explode("/", $date);
            $newstring = $dates[2] . '-' . $dates[1] . '-' . $dates[0];
            return $newstring;
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
}
