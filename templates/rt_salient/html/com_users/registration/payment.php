<?php
defined('_JEXEC') or die;
$package = JRequest::getVar("package");
$userid = JRequest::getVar("userid");
if($package == 1){
	$amount = 50;
}
if($package == 2){
	$amount = 250;
}
if($package == 3){
	$amount = 500;
}
?>
<form action="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx" method="post" id="paymetForm">
    <input type="hidden" name="merchantnumber" value="8021238">
    <input type="hidden" name="amount" value="<?php echo $amount*100;?>">
    <input type="hidden" name="currency" value="DKK">
    <input type="hidden" name="windowstate" value="3">
    <input type="hidden" name="orderid" value="<?php echo JRequest::getVar("userid");?>">
    <input type="hidden" name="accepturl" value="<?php echo JURI::base();?>index.php?option=com_users&view=registration&layout=select&package=<?php echo $package;?>&userid=<?php echo $userid;?>">
    <input type="hidden" name="cancelurl" value="<?php echo JURI::base();?>index.php?option=com_users&view=registration&layout=fail">
    <input type="hidden" name="subscription" value="1">
</form>
<script type="application/javascript">
jQuery( document ).ready(function() {
	jQuery("#paymetForm").submit();
});
</script>