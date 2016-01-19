<?php
defined('_JEXEC') or die;

$userId = JRequest::getVar("userid");
$package = JRequest::getVar("package");
$subscriptionid = JRequest::getVar("subscriptionid");
if(empty($subscriptionid)){
	$subscriptionid = "test_subscriptionid";
}

if($package == 1){
	$expired = strtotime('+1 month');
	$numMonth = 1;
	$productName = "Måndligt abonnement";
	$productPrice = 0;
}
if($package == 2){
	$expired = strtotime('+1 month');
	$numMonth = 12;
	$productName = "Årligt abonnement";
	$productPrice = 0;
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

$q = "SELECT * FROM #__business WHERE userId = $userId";
$db->setQuery($q);
$business = $db->loadObject();
$businessId = $business->id;

$q = "SELECT * FROM #__users WHERE id = $userId";
$db->setQuery($q);
$user = $db->loadObject();

$newId = 200000000 + $userId;
$q = "UPDATE #__users SET newId = $newId WHERE id = ".$userId;
$db->setQuery($q);
$db->execute();

$apikey = '025754d9255be8adab8fbc40796313ea-us12';
$auth = base64_encode( 'user:'.$apikey );

$data = array(
	'apikey'        => $apikey,
	'email_address' => $user->email,
	'status'        => 'subscribed',
	'merge_fields'  => array(
		'FNAME' => $user->firstName,
		'LNAME' => $user->lastName,
	)
);
$json_data = json_encode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://us12.api.mailchimp.com/3.0/lists/c8581bdf3c/members/');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

$result = curl_exec($ch);


/*$html = '<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Myloyal</title>
</head>
<body style="font-family: Arial, Helvetica, Verdana; font-size: 16px; line-height: 1.8em; color:#303030; position:relative;-webkit-text-size-adjust:none; padding:0; margin:0;">
	<div id="page" width="100%" style="padding: 10px; border: 1px solid #ccc; margin: 20px;">
		<h1 style="font-size: 30px;">FAKTURA</h1>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="20%" style="padding: 5px; border: 1px solid #ccc;"><strong>Betalingsdato:</strong></td>
				<td style="padding: 5px; border: 1px solid #ccc;"><strong>'.date("d/m/Y").'</strong></td>
			</tr>
			<tr>
				<td width="20%" style="padding: 5px; border: 1px solid #ccc;"><strong>Ordrenummer:</strong></td>
				<td style="padding: 5px; border: 1px solid #ccc;"><strong>'.sprintf("%'.05d\n", $userId).'</strong></td>
			</tr>
			<tr>
				<td style="padding: 5px; border: 1px solid #ccc;"> <strong>E-mail:</strong></td>
				<td style="padding: 5px; border: 1px solid #ccc;"> <strong>'.$user->email.'</strong></td>
			</tr>
		</table>

		<h2>Kundeoplysninger:</h2>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="padding: 5px; border: 1px solid #ccc;" width="20%">CVR:</td>
				<td style="padding: 5px; border: 1px solid #ccc;">'.$business->cvrNumber.'</td>
			</tr>
			<tr>
				<td style="padding: 5px; border: 1px solid #ccc;" width="20%">Navn:</td>
				<td style="padding: 5px; border: 1px solid #ccc;">'.$user->name.'</td>
			</tr>
			<tr>
				<td style="padding: 5px; border: 1px solid #ccc;">Telefon nr.:</td>
				<td style="padding: 5px; border: 1px solid #ccc;">'.$business->phone.'</td>
			</tr>
			<tr>
				<td style="padding: 5px; border: 1px solid #ccc;">Firmanavn:</td>
				<td style="padding: 5px; border: 1px solid #ccc;">'.$business->businessName.'</td>
			</tr>
			<tr>
				<td style="padding: 5px; border: 1px solid #ccc;">Referencer:</td>
				<td style="padding: 5px; border: 1px solid #ccc;">'.$business->referencer.'</td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th width="70%">Produkt</th>
					<th>Pris i alt</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="50%" style="padding: 5px; border: 1px solid #ccc;">'.$productName.'</td>
					<td style="text-align: right; padding: 5px; border: 1px solid #ccc;">'.$productPrice.' DKK</td>
				</tr>
				<tr>
					<td style="text-align: right; padding: 5px; border: 1px solid #ccc;">Moms:</td>
					<td style="text-align: right; padding: 5px; border: 1px solid #ccc;">'.number_format($productPrice*0.25, 2, ".", ",").' DKK</td>
				</tr>
				<tr>
					<td style="text-align: right; padding: 5px; border: 1px solid #ccc;"><strong>TOTAL INKL. MOMS:</strong></td>
					<td style="text-align: right; padding: 5px; border: 1px solid #ccc;"><strong>'.number_format($productPrice+($productPrice*0.25), 2, ".", ",").' DKK</strong></td>
				</tr>
				<tr>
					<td style="padding: 5px; border: 1px solid #ccc;" colspan="2">
						<p style="text-align: center; font-size: 12px;">© 2015 MYLOYAL APS - BIRKEVANG 20C, 3500 VÆRLØSE - TLF. +45 6048 3972 - EMAIL INFO@MYLYOAL.DK - CVR. 37100196 - UDVIKLET AF AZ WEB</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>';*/

$html = '<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Myloyal</title>
</head>
<body style="font-family: Arial, Helvetica, Verdana; font-size: 16px; line-height: 1.8em; color:#303030; position:relative;-webkit-text-size-adjust:none; padding:0; margin:0;">
	<h1 style="margin: 15px auto; width: 260px;"><img src="logo.png" alt=""></h1>
	<div id="page" width="100%" style="padding: 10px; margin: 20px;">
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="padding: 10px;"><strong>'.$business->cvrNumber.'<br>
				'.$business->address.'<br>
				'.$business->phone.'<br>
				CVR-nr. '.$business->businessName.'</strong></td>
				<td style="padding: 10px; text-align: right;"><strong>MyLoyal Aps<br>
				Birkevang 20c<br>
				3500 Værløse<br><br>

				Tlf. +45 6048 3972<br>
				E-mail: info@myloyal.dk<br>
				CVR-nr. 37100196</strong></td>
			</tr>
		</table>

		<h1 style="font-size: 30px;">FAKTURA</h1>
		<table width="100%" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th style="text-align: left; padding: 10px;">Årlig abonnemenet gældende fra og til: '.date("d/m/Y").' - '.date("d/m/Y", $expired).'</th>
					<th style="padding: 10px;">Ordrenummer: '.sprintf("%'.05d\n", $userId).'</th>
					<th style="text-align: right; padding: 10px;">Betalingsdato: '.date("d/m/Y").'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="padding: 10px; border-top: 1px solid #000;">Beskrivelse</td>
					<td style="padding: 10px; border-top: 1px solid #000;"></td>
					<td style="text-align: right; padding: 10px; border-top: 1px solid #000;">Pris i alt</td>
				</tr>
				<tr>
					<td style="padding: 10px; border-top: 1px solid #000;">'.$productName.'</td>
					<td style="padding: 10px; border-top: 1px solid #000;"></td>
					<td style="text-align: right; padding: 10px; border-top: 1px solid #000;">'.$productPrice.' DKK</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right; padding: 10px; border-top: 1px solid #000;">25% moms :</td>
					<td style="text-align: right; padding: 10px; border-top: 1px solid #000;">'.number_format($productPrice*0.25, 2, ".", ",").' DKK</td>
				</tr>
				<tr>
					<td></td>
					<td style="text-align: right; padding: 10px; border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong>TOTAL INKL. MOMS:</strong></td>
					<td style="text-align: right; padding: 10px; border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong>'.number_format($productPrice+($productPrice*0.25), 2, ".", ",").' DKK</strong></td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>';
			
	$app = JFactory::getApplication();
	$mailfrom = $app->get('mailfrom');
	$fromname = $app->get('fromname');
		
	$mail = JFactory::getMailer();
	$mail->addRecipient($user->email);
	$mail->AddCC('info@myloyal.dk');
	$mail->setSender(array($mailfrom, $fromname));
	$mail->setSubject('Bekræftet ordre '.sprintf("%'.05d\n", $userId));
	$mail->isHTML(true);
	$mail->setBody($html);
	$sent = $mail->Send();

?>
<script type="application/javascript">
jQuery( document ).ready(function() {
	jQuery(".g-main-nav").hide();
});
</script>
<section class="main mb200">
    <div class="container">
        <div class="select-content">
            <h2 class="text-center mt50">Vælg din type loyalitetsprogram</h2>
            <p class="text-center">Vælg, om du vil tilbyde et digitalt stempelkort eller et kort med et digitalt pointsystem.</p>
            <div class="row mt50">
                <div class="col-md-6 mb30">
                    <div class="row">
                        <div class="col-md-6 col-xs-12 mb30">
                            <div class="select-info">
                                <h2 class="text-right" style="color:#f7901e;">Points</h2>
                                <p class="text-right">Et loyalitetsprogram med et digitalt pointsystem virker til enhver forretning. Er særligt velegnet til dig ønsker at få loyale kunder gennem flere varer i din forretning.</p>
                                <!--<ul class="list-info">
                                    <li>Aliquam tincidunt mauris eu risus</li>
                                    <li>Vestibulum auctor dapibus neque</li>
                                    <li>Nunc dignissim risus id metus</li>
                                    <li>Fusce pellentesque suscipit nibh</li>
                                    <li>Integer vitae libero ac risus egestas</li>
                                    <li>Vestibulum commodo felis quis tortor</li>
                                </ul>-->
                                <a href="index.php?option=com_users&task=registration.setType&type=1&businessId=<?php echo $businessId;?>" style="background:#f7901e;text-transform: uppercase;color:white !important;" class="btn btnGetgoing pull-right">VÆLG POINTS</a>
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
                                <h2 style="color:#f7901e;">Stempel</h2>
                                <p>Et loyalitetsprogram med et digitalt stempelkort virker til enhver forretning. Er særligt velegnet til dig ønsker at få loyale kunder gennem flere varer i din forretning.</p>
                                <!--<ul class="list-info">
                                    <li>Aliquam tincidunt mauris eu risus</li>
                                    <li>Vestibulum auctor dapibus neque</li>
                                    <li>Nunc dignissim risus id metus</li>
                                    <li>Fusce pellentesque suscipit nibh</li>
                                    <li>Integer vitae libero ac risus egestas</li>
                                    <li>Vestibulum commodo felis quis tortor</li>
                                </ul>-->
                                <a href="index.php?option=com_users&task=registration.setType&type=2&businessId=<?php echo $businessId;?>" style="background:#f7901e;text-transform: uppercase;color:white !important;" class="btn btnGetgoing">VÆLG STEMPEL</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
