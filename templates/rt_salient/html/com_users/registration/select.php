<?php
defined('_JEXEC') or die;

$userId = JRequest::getVar("userid");
$package = JRequest::getVar("package");
$subscriptionid = JRequest::getVar("subscriptionid");
if(empty($subscriptionid)){
	$subscriptionid = "test_subscriptionid";
}

if($package == 1){
	$expired = time() + (30 * 24 * 60 * 60);
	$numMonth = 1;
}
if($package == 2){
	$expired = time() + (183 * 24 * 60 * 60);
	$numMonth = 6;
}
if($package == 3){
	$expired = time() + (365 * 24 * 60 * 60);
	$numMonth = 12;
}
$amount = JRequest::getVar("amount")/100;

$db = JFactory:: getDBO();
$db->setQuery("UPDATE #__business SET subscriptionid = '".$subscriptionid."', timeExpired = '".$expired."', numMonthPayment = $numMonth, transactionPayment = '".JRequest::getVar("txnid")."' WHERE userId = ".$userId);
$db->execute();

$q = "INSERT INTO #__log_payment(userId, paymentMoney, transactionId, createdAt) VALUES ($userId, $amount, '".JRequest::getVar("txnid")."', ".time().");";
$db->setQuery($q);
$db->execute();

//$q = "UPDATE #__users SET block = 0, sendEmail = 1, activation = '' WHERE id = ".$userId;
//$db->setQuery($q);
//$db->execute();

$q = "UPDATE #__user_usergroup_map SET group_id = 3 WHERE user_id = ".$userId;
$db->setQuery($q);
$db->execute();

$q = "SELECT id FROM #__business WHERE userId = $userId";
$db->setQuery($q);
$businessId = $db->loadResult();

?>
<script type="application/javascript">
jQuery( document ).ready(function() {
	jQuery(".g-main-nav").hide();
});
</script>
<section class="main mb200">
    <div class="container">
        <div class="select-content">
            <h2 class="text-center mt50">Vælg type Myloyal programmet</h2>
            <p class="text-center">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque<br> aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi.</p>
            <div class="row mt50">
                <div class="col-md-6 mb30">
                    <div class="row">
                        <div class="col-md-6 col-xs-12 mb30">
                            <div class="select-info">
                                <h2 class="text-right">Points</h2>
                                <p class="text-right">Lorem ipsum dolor sit amet, consec tetuer adipicing elit phasellus hendrerit pellen tesque aliquet nibh.</p>
                                <ul class="list-info">
                                    <li>Aliquam tincidunt mauris eu risus</li>
                                    <li>Vestibulum auctor dapibus neque</li>
                                    <li>Nunc dignissim risus id metus</li>
                                    <li>Fusce pellentesque suscipit nibh</li>
                                    <li>Integer vitae libero ac risus egestas</li>
                                    <li>Vestibulum commodo felis quis tortor</li>
                                </ul>
                                <a href="index.php?option=com_users&task=registration.setType&type=1&businessId=<?php echo $businessId;?>" class="btn btnGetgoing pull-right">vælge Points</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 text-center">
                            <img alt="" src="images/phone.png">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb30">
                    <div class="row">
                        <div class="col-md-6 col-xs-12 mb30 text-center">
                            <img alt="" src="images/phone2.png">
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="select-info">
                                <h2>Stampel</h2>
                                <p>Lorem ipsum dolor sit amet, consec tetuer adipicing elit phasellus hendrerit pellen tesque aliquet nibh.</p>
                                <ul class="list-info">
                                    <li>Aliquam tincidunt mauris eu risus</li>
                                    <li>Vestibulum auctor dapibus neque</li>
                                    <li>Nunc dignissim risus id metus</li>
                                    <li>Fusce pellentesque suscipit nibh</li>
                                    <li>Integer vitae libero ac risus egestas</li>
                                    <li>Vestibulum commodo felis quis tortor</li>
                                </ul>
                                <a href="index.php?option=com_users&task=registration.setType&type=2&businessId=<?php echo $businessId;?>" class="btn btnGetgoing">vælge stampel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
