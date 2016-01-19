<?php
defined('_JEXEC') or die;
$userId = JRequest::getVar("userid");

$db = JFactory:: getDBO();

$q = "DELETE FROM #__business WHERE userId = $userId";
$db->setQuery($q);
$db->execute();

$q = "DELETE FROM #__users WHERE id = $userId";
$db->setQuery($q);
$db->execute();

$q = "DELETE FROM #__user_usergroup_map WHERE user_id = $userId";
$db->setQuery($q);
$db->execute();

?>
<div class="registration-complete<?php echo $this->pageclass_sfx;?>">
	<h1>
		Ordren bliver annulleret!
	</h1>
</div>
