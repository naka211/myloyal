<?php
defined('_JEXEC') or die;
$package = JRequest::getVar("package", 1);
$userid = JRequest::getVar("userid");
if($package == 1){
	//$amount = 349 + (349*0.25);
	$amount = 0;
}
if($package == 2){
	//$amount = 3490 + (3490*0.25);
	$amount = 0;
}
$merchant = '6284736'; // real
//$merchant = '8021933'; // test
?>
<form action="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx" method="post" id="paymetForm">
    <input type="hidden" name="merchantnumber" value="<?php echo $merchant;?>">
    <input type="hidden" name="amount" value="<?php echo $amount*100;?>">
    <input type="hidden" name="currency" value="DKK">
    <input type="hidden" name="windowstate" value="3">
    <input type="hidden" name="orderid" value="<?php echo JRequest::getVar("userid");?>">
<!--    <input type="hidden" name="accepturl" value="<?php echo JURI::base();?>index.php?option=com_users&view=registration&layout=select&package=<?php echo $package;?>&userid=<?php echo $userid;?>">
    <input type="hidden" name="cancelurl" value="<?php echo JURI::base();?>index.php?option=com_users&view=registration&layout=fail">-->
    <input type="hidden" name="accepturl" value="<?php echo JURI::base();?>index.php?option=com_users&view=registration&layout=select&package=<?php echo $package;?>&userid=<?php echo $userid;?>">
    <input type="hidden" name="cancelurl" value="<?php echo JURI::base();?>index.php?option=com_users&view=registration&layout=fail&userid=<?php echo $userid;?>">
    <input type="hidden" name="subscription" value="1">
</form>
<script type="application/javascript">
jQuery( document ).ready(function() {
	jQuery("#paymetForm").submit();
});
</script>