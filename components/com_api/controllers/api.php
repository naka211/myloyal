<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class ApiControllerApi extends JControllerLegacy {

	public function test(){
		die("Loyal API is ok!!!");
	}
	
	public function testEmail(){
		$app = JFactory::getApplication();
		$mailfrom = $app->get('mailfrom');
		$fromname = $app->get('fromname');
			
		$mail = JFactory::getMailer();
		$mail->addRecipient('trung@mywebcreations.dk');
		//$mail->AddCC('trung@mywebcreations.dk');
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject('Bekræftet ordre '.time());
		$mail->isHTML(true);
		$mail->setBody('test mail function');
		$sent = $mail->Send();

		if($sent == 1){
			print_r(time());exit;
		} else {
			print_r($sent);exit;
		}
	}
	
	public function customerRegister(){
		$firstName = JRequest::getVar("firstName");
		$lastName = JRequest::getVar("lastName");
		$email = JRequest::getVar("email");
		$password = JRequest::getVar("password");
		$confirmPassword = JRequest::getVar("confirmPassword");
		$avatar = JRequest::getVar("avatar");
		$newsletter = JRequest::getVar("newsletter");
		
		if($password != $confirmPassword){
			$return["result"] = 0;
			$return["error"] = "Confirm password is not match";
			die(json_encode($return));
		}
		$db = JFactory::getDBO();
		$db->setQuery("SELECT id FROM #__users WHERE email = '".$email."'");
		if($db->loadResult()){
			$return["result"] = 0;
			$return["error"] = "You can't use this email, it is in use";
			die(json_encode($return));
		}
		
		jimport('joomla.user.helper');
		$pass = JUserHelper::hashPassword($password);
		$name = $firstName." ".$lastName;
		if($avatar){
			$fileName = sha1(uniqid()).".jpg";
			$decoded_img=base64_decode($avatar);
			file_put_contents(JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$fileName,$decoded_img);
			$q = "INSERT INTO #__users(name, username, email, password, sendEmail, registerDate, lastName, firstName, avatar) VALUES ('".$name."', '".$email."', '".$email."', '".$pass."', 1, NOW(), '".$lastName."', '".$firstName."', '".$fileName."')";
			$avatar_link = JURI::base()."images/avatar/".$fileName;
		} else {
			$q = "INSERT INTO #__users(name, username, email, password, sendEmail, registerDate, lastName, firstName) VALUES ('".$name."', '".$email."', '".$email."', '".$pass."', 1, NOW(), '".$lastName."', '".$firstName."')";
			$avatar_link = "";
		}
		$db->setQuery($q);
		$db->execute();
		
		$newUserId = $db->insertid();
		
		$q = "INSERT INTO #__user_usergroup_map VALUES (".$db->insertid().", 2)";
		$db->setQuery($q);
		$db->execute();
		
		$newId = 100000000 + $newUserId;
		$q = "UPDATE #__users SET newId = $newId WHERE id = ".$newUserId;
		$db->setQuery($q);
		$db->execute();
		
		if($newsletter == 1){
			$apikey = '025754d9255be8adab8fbc40796313ea-us12';
            $auth = base64_encode( 'user:'.$apikey );

            $data = array(
                'apikey'        => $apikey,
                'email_address' => $email,
                'status'        => 'subscribed',
                'merge_fields'  => array(
                    'FNAME' => $firstName,
					'LNAME' => $lastName,
                )
            );
            $json_data = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://us12.api.mailchimp.com/3.0/lists/ac58bef439/members/');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
			
            $result = curl_exec($ch);
			$return["mailchimp"] = $result;
		}
				
		$return["result"] = 1;
		$return['error'] = "";
		$data['userId'] = $newUserId;
		$data['firstName'] = $firstName;
		$data['lastName'] = $lastName;
		$data['email'] = $email;
		$data['avatar'] = $avatar_link;
		$data['facebookId'] = "";
		$data['firstTime'] = 1;
		$data['newId'] = $newId;
		
		$return['data'] = $data;
		
		die(json_encode($return));	
	}
	
    public function customerLogin() {
		$app    = JFactory::getApplication();
		$email = JRequest::getVar("email");
		$password = JRequest::getVar("password");
		
		$credentials["username"] = $email;
		$credentials["password"] = $password;
		
		$options['remember'] = false;
		$options['return']   = '';
		
		$result = $app->login($credentials, $options);
		if($result == true){
			$db = JFactory::getDBO();
			
			$user = JFactory::getUser();
			
			$return['result'] = 1;
			$return['error'] = "";
			$return['userId'] = $user->id;
			$return['firstName'] = $user->firstName;
			$return['lastName'] = $user->lastName;
			$return['email'] = $user->email;
			$return['avatar'] = JURI::base()."images/avatar/".$user->avatar;
			$return['facebookId'] = "";
			$return['firstTime'] = $user->firstTime;
			$return['newId'] = $user->newId;
		} else {
			$return["result"] = 0;
			$return["error"] = "Dit brugernavn eller kodeord er forkert!";
		}
        die(json_encode($return));
    }
	
	public function customerUpdate(){
		$userId = JRequest::getVar("userId");
		$firstName = JRequest::getVar("firstName");
		$lastName = JRequest::getVar("lastName");
		$password = JRequest::getVar("password");
		$confirmPassword = JRequest::getVar("confirmPassword");
		$avatar = JRequest::getVar("avatar");
		$removeAvatar = JRequest::getVar("removeAvatar");
		
		if($password != $confirmPassword){
			$return["result"] = 0;
			$return["error"] = "Kodeordene matcher ikke.";
			die(json_encode($return));
		}
		
		if($password){
			jimport('joomla.user.helper');
			$pass = JUserHelper::hashPassword($password);
			$passStr = ", password = '".$pass."'";
		} else {
			$passStr = "";
		}
		if($avatar){
			$user = JFactory::getUser($userId);
			$userAvatar = $user->avatar;
			if($userAvatar){
				unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$userAvatar);
			}
			$fileName = sha1(uniqid()).".jpg";
			$decoded_img=base64_decode($avatar);
			file_put_contents(JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$fileName,$decoded_img);
			$avatarStr = ", avatar = '".$fileName."'";
		} else {
			$avatarStr = "";
		}
		
		if($removeAvatar){
			$user = JFactory::getUser($userId);
			$userAvatar = $user->avatar;
			if($userAvatar){
				unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$userAvatar);
			}
			$avatarStr = ", avatar = ''";
		}
		
		$db = JFactory::getDBO();
		$name = $firstName." ".$lastName;
		$q = "UPDATE #__users SET name = '".$name."', firstName = '".$firstName."', lastName = '".$lastName."'".$passStr.$avatarStr." WHERE id = ".$userId;
		$db->setQuery($q);
		if($db->execute()){
			$db->setQuery("SELECT * FROM #__users WHERE id = $userId");
			$user1 = $db->loadObject();
			$return["result"] = 1;
			$return["error"] = "";
			$return['userId'] = $userId;
			$return['firstName'] = $user1->firstName;
			$return['lastName'] = $user1->lastName;
			$return['email'] = $user1->email;
			if($user1->avatar){
				$return['avatar'] = JURI::base()."images/avatar/".$user1->avatar;
			} else {
				$return['avatar'] = "";
			}
			$return['facebookId'] = $user1->facebookId;
		} else {
			$return["result"] = 0;
			$return["error"] = "Kunne ikke opdatere profilen.";
		}
		die(json_encode($return));
	}
	
	public function facebookLogin(){
		$facebookId = JRequest::getVar("facebookId");
		$email = JRequest::getVar("email");
		$firstName = JRequest::getVar("firstName");
		$lastName = JRequest::getVar("lastName");
		$name = $firstName." ".$lastName;
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT id FROM #__users WHERE facebookId = '".$facebookId."'");	
		$userId = $db->loadResult();
		
		$visitDate = date("Y-m-d H:i:s", time());
		if($userId){
			$db->setQuery("UPDATE #__users SET name = '".$name."', firstName = '".$firstName."', lastName = '".$lastName."', lastvisitDate = '".$visitDate."' WHERE id = ".$userId);
			$db->execute();
			
			$user = JFactory::getUser($userId);		
			$return['result'] = 1;
			$return['error'] = "";
			$return['userId'] = $user->id;
			$return['firstName'] = $firstName;
			$return['lastName'] = $lastName;
			$return['email'] = $user->email;
			$return['avatar'] = "";
			$return['facebook_id'] = $facebookId;
			
			$db->setQuery("SELECT post FROM #__users WHERE facebookId = '".$facebookId."'");	
			$post = $db->loadResult();
			$return['post'] = $post;
			$return['firstTime'] = 1;
			
		} else {
			$db->setQuery("SELECT id FROM #__users WHERE email = '".$email."'");
			if($db->loadResult()){
				$return["result"] = 0;
				$return["error"] = "Denne e-mail adresse er allerede brugt.";
			} else {
				$q = "INSERT INTO #__users(name, username, email, sendEmail, registerDate, lastName, firstName, facebookId, post, lastvisitDate) VALUES ('".$name."', '".$email."', '".$email."', 1, NOW(), '".$lastName."', '".$firstName."', '".$facebookId."', 1, '".$visitDate."')";
				$db->setQuery($q);
				$db->execute();
				
				$newUserId = $db->insertid();
				
				$q = "INSERT INTO #__user_usergroup_map VALUES ($newUserId, 2)";
				$db->setQuery($q);
				$db->execute();
				
				$newId = 100000000 + $newUserId;
				$q = "UPDATE #__users SET newId = $newId WHERE id = ".$newUserId;
				$db->setQuery($q);
				$db->execute();
				
				$return['result'] = 1;
				$return['error'] = "";
				$return['userId'] = $newUserId;
				$return['firstName'] = $firstName;
				$return['lastName'] = $lastName;
				$return['email'] = $email;
				$return['avatar'] = "";
				$return['facebook_id'] = $facebookId;
				$return['post'] = 1;
				$return['firstTime'] = 0;
				$return['newId'] = $newId;
				
			}
		}
		die(json_encode($return));
	}
	
	public function changePostSetting(){
		$status = JRequest::getVar("status");
		$userId = JRequest::getVar("userId");
		
		$db = JFactory::getDBO();
		$db->setQuery("UPDATE #__users SET post = $status WHERE id = $userId");
		if($db->execute()){
			$return['result'] = 1;
			$return['error'] = "";
		} else {
			$return['result'] = 0;
			$return['error'] = "Opdaterings fejl.";
		}
		die(json_encode($return));
	}
	
	public function forgotPassword(){
		$email = JRequest::getVar("email");
		
		$new_pass = $this->_generateRandomString();
		
		$app = JFactory::getApplication();
		$mailfrom = $app->get('mailfrom');
		$fromname = $app->get('fromname');
		$sitename = $app->get('sitename');
		$body   = "Hejsa, \r\n\r\n Dette er din nye kodeord: ".$new_pass." \r\n\r\n MyLoyal";
			
		$mail = JFactory::getMailer();
		$mail->addRecipient($email);
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($sitename . ': Ny Kodeord');
		$mail->setBody($body);
		$sent = $mail->Send();
		if($sent){
			jimport('joomla.user.helper');
			
			$db = JFactory::getDBO();
			$pass = JUserHelper::hashPassword($new_pass);
			$db->setQuery("UPDATE #__users SET password = '".$pass."' WHERE email = '".$email."'");
			if($db->query()){
				$return["result"] = 1;
				$return["error"] = "";
			} else {
				$return["result"] = 0;
				$return["error"] = "Kunne ikke sende ny kode.";
			}
		} else {
			$return["result"] = 0;
			$return["error"] = "Kunne ikke sende mail.";
		}
		die(json_encode($return));
	}
	
	private function _generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public function addToken(){
		$type = JRequest::getVar("type");
		$token = JRequest::getVar("token");
		$userId = JRequest::getVar("userId");
		$hwId = JRequest::getVar("hwId");
		
		$db = JFactory::getDBO();
		
		$visitDate = date("Y-m-d H:i:s", time());
		$db->setQuery("UPDATE #__users SET lastvisitDate = '".$visitDate."' WHERE id = ".$userId);
		$db->execute();
		
		$db->setQuery("SELECT id FROM #__user_tokens WHERE userId = ".$userId." AND hwId = '".$hwId."'");
		$id = $db->loadResult();
		if($id){
			$db->setQuery("UPDATE #__user_tokens SET token = '".$token."' WHERE userId = ".$userId." AND hwId = '".$hwId."'");
			if($db->execute()){
				$return["result"] = 1;
				$return["error"] = "";
			} else {
				$return["result"] = 0;
				$return["error"] = "Can not insert new token";
			}
		} else {
			$db->setQuery("INSERT INTO #__user_tokens (userId, token, hwId, type) VALUES (".$userId.", '".$token."', '".$hwId."', '".$type."')");
			if($db->execute()){
				$return["result"] = 1;
				$return["error"] = "";
			} else {
				$return["result"] = 0;
				$return["error"] = "Can not insert new token";
			}
		}
		
		//register device
		$url = 'https://cp.pushwoosh.com/json/1.3/registerDevice';
		$send['request'] = array('application' => '9727D-054A0', 'push_token'=>$token, 'language'=>'da', 'hwid'=>$hwId, 'timezone'=>3600, 'device_type'=>$type);
		$request = json_encode($send);
	 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	 
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$return["api_register_device"] = $response;
		
		//set tags
		
		$url = 'https://cp.pushwoosh.com/json/1.3/setTags';
		$send['request'] = array('application' => '9727D-054A0', 'hwid'=>$hwId, 'tags'=>array('userId'=>$userId));
		$request = json_encode($send);
	 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	 
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$return["api_set_tag"] = $response;
		
		die(json_encode($return));
	}
	
	public function deleteToken(){
		$hwId = JRequest::getVar("hwId");
		
		$db = JFactory::getDBO();
		$q = "DELETE FROM #__user_tokens WHERE hwId = '".$hwId."'";
		$db->setQuery($q);
		if($db->query()){
			$return["result"] = 1;
			$return["error"] = "";
		} else {
			$return["result"] = 0;
			$return["error"] = "Can not delete token";
		}
		
		//unregister device
		$url = 'https://cp.pushwoosh.com/json/1.3/unregisterDevice';
		$send['request'] = array('application' => '9727D-054A0', 'hwid'=>$hwId);
		$request = json_encode($send);
	 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	 
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$return["api_unregister_device"] = $response;
		
		die(json_encode($return));
	}
	
	function getTags(){
		$hw_id = JRequest::getVar('hw_id');
		$url = 'https://cp.pushwoosh.com/json/1.3/getTags';
		$send['request'] = array('auth'=>'C4jIJrQCJLlubwb7pPvBDsdcA9SdGSIkRynZC2vZ0J4y7jkEuUiq6GjDK7LFVMeifC72FuSVtRqjzDqXpEYX','application' => '9727D-054A0', 'hwid'=>$hw_id);
		$request = json_encode($send);
	 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	 
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		print_r($response);exit;
	}
	
	public function getTerm(){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT introtext FROM #__content WHERE id = 22");
		$data = $db->loadObject();

		$return['result'] = 1;
		$return['text'] = $data->introtext;
		die(json_encode($return));
	}
	
	public function getNearest(){
		$lat = JRequest::getVar("lat");
		$long = JRequest::getVar("long");
		
		$db = JFactory::getDBO();
		$q = "SELECT id as businessId, userId, businessName, icon, type, businessEmail, address, city, latitude, longitude, ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$long.") ) + sin( radians(".$lat.") ) * sin( radians( latitude ) ) ) ) AS distance FROM #__business HAVING distance < 10000 ORDER BY distance;";
		$db->setQuery($q);
		$stores = $db->loadAssocList();

		$q = "SELECT u.id FROM #__users u INNER JOIN #__user_usergroup_map um ON u.id = um.user_id WHERE um.group_id = 3";
		$db->setQuery($q);
		$ids = $db->loadColumn();
		if($stores){
			$i = 0;
			foreach($stores as $store){
				if(!in_array($store['userId'], $ids)){
					unset($stores[$i]);
				}
				$i++;
			}
			$stores = array_values($stores);
			$i = 0;
			foreach($stores as $store){
				if($store['icon']){
					$stores[$i]['icon'] = JURI::base()."images/business/".$store['icon'];
				}
				$i++;
			}
			$return['result'] = 1;
			$return['error'] = "";
			$return['data'] = $stores;
		} else {
			$return['result'] = 0;
			$return['error'] = "Butikker blev ikke fundet. Klik her for at opdatere.";
		}
		die(json_encode($return));
	}
	
	public function searchBusiness(){
		$lat = JRequest::getVar("lat");
		$long = JRequest::getVar("long");
		$keyword = JRequest::getVar("keyword");
		$keyword = strtolower($keyword);
		
		$db = JFactory::getDBO();
		$q = "SELECT id as businessId, businessName, icon, type, businessEmail, address, city, latitude, longitude FROM #__business WHERE LOWER(`businessName`) LIKE '%".$keyword."%'";
		$db->setQuery($q);
		$stores = $db->loadAssocList();
		
		if($stores){
			$i = 0;
			foreach($stores as $store){
				$stores[$i]['distance'] = $this->distance($store['latitude'], $store['longitude'], $lat, $long);
				if($store['icon']){
					$stores[$i]['icon'] = JURI::base()."images/business/".$store['icon'];
				}
				$i++;
			}
			$return['result'] = 1;
			$return['error'] = "";
			$return['data'] = $stores;
		} else {
			$return['result'] = 0;
			$return['error'] = "Butikker blev ikke fundet. Klik her for at opdatere.";
		}
		die(json_encode($return));
	}
	
	function distance($lat1, $lon1, $lat2, $lon2) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		
		return ($miles * 1.609344);
	}
	
	public function getFavouriteBusiness(){
		$customerId = JRequest::getVar("customerId");
		$lat = JRequest::getVar("lat");
		$long = JRequest::getVar("long");
		
		$db = JFactory::getDBO();
		$q = "SELECT c.businessId, c.createdAt, b.businessName, b.icon, b.type, b.businessEmail, b.address, b.city, b.latitude, b.longitude FROM #__checkin c INNER JOIN #__business b ON c.businessId = b.id WHERE c.customerId = ".$customerId." GROUP BY c.businessId ORDER BY c.createdAt LIMIT 20";
		$db->setQuery($q);
		$stores = $db->loadAssocList();
		
		if($stores){
			$i = 0;
			foreach($stores as $store){
				$stores[$i]['distance'] = $this->distance($store['latitude'], $store['longitude'], $lat, $long);
				if($store['icon']){
					$stores[$i]['icon'] = JURI::base()."images/business/".$store['icon'];
				}
				$i++;
			}
			$return['result'] = 1;
			$return['error'] = "";
			$return['data'] = $stores;
		} else {
			$return['result'] = 0;
			$return['error'] = "Butikker blev ikke fundet. Klik her for at opdatere.";
		}
		die(json_encode($return));
	}
	
	public function checkIn(){
		$customerId = JRequest::getVar("customerId");
		$customerFirstName = JRequest::getVar("customerFirstName");
		$customerLastName = JRequest::getVar("customerLastName");
		$businessId = JRequest::getVar("businessId");
		
		$db = JFactory::getDBO();
		$q = "INSERT INTO #__checkin (customerId, businessId, createdAt) VALUES ($customerId, $businessId, ".time().")";
		$db->setQuery($q);
		if($db->execute()){
			$return['result'] = 1;
			$return['error'] = "";
			$db->setQuery("SELECT userId, type FROM #__business WHERE id = $businessId");
			$business = $db->loadObject();
			$return['push'] = $this->pushNotification($business->userId, $customerFirstName." ".$customerLastName." er nu checket ind i forretningen.", 1, $businessId, $business->type);
		} else {
			$return['result'] = 0;
			$return['error'] = "Kunne ikke check-in";
		}
		
		die(json_encode($return));
	}
	
	public function getBusinessDetail(){
		$businessId = JRequest::getVar("businessId");
		
		$db = JFactory::getDBO();
		$q = "SELECT dateType, fromTime, toTime, close FROM #__workingtime WHERE businessId = ".$businessId." ORDER BY dateType";
		$db->setQuery($q);
		$times = $db->loadAssocList();
		
		$q = "SELECT businessName, businessEmail, address, city, icon, latitude, longitude, type, phone, website FROM #__business WHERE id = ".$businessId;
		$db->setQuery($q);
		$data = $db->loadAssoc();
		
		$return['result'] = 1;
		$return['error'] = "";
		if($data['icon']){
			$data['icon'] = JURI::base()."images/business/".$data['icon'];
		}
		$return['data'] = $data;
		$return['data']['workingTime'] = $times;
		
		die(json_encode($return));
	}
	
	public function getBusinessSimple(){
		$businessId = JRequest::getVar("businessId");
		
		$db = JFactory::getDBO();
		$q = "SELECT id as businessId, businessName, businessEmail, address, city, icon, latitude, longitude, type FROM #__business WHERE id = ".$businessId;
		$db->setQuery($q);
		$data = $db->loadAssoc();
		
		$return['result'] = 1;
		$return['error'] = "";
		if($data['icon']){
			$data['icon'] = JURI::base()."images/business/".$data['icon'];
		}
		$return['data'] = $data;
		die(json_encode($return));
	}
	
	public function getPromotions(){
		$customerId = JRequest::getVar("customerId");
		$businessId = JRequest::getVar("businessId");
		$businessType = JRequest::getVar("businessType");
		
		$db = JFactory::getDBO();
		
		if($businessType == 1){
			$db->setQuery("SELECT pointDescription, businessName FROM #__business WHERE id = ".$businessId);
			$business = $db->loadObject();
			
			$return['pointDescription'] = $business->pointDescription;
			$return['businessName'] = $business->businessName;
			
			$return['myPoint'] = $this->_getPoint($customerId, $businessId);
			
			$q = "SELECT id, title, content, point, icon FROM #__promotion WHERE businessId = ".$businessId." AND endDate > ".time();
			$db->setQuery($q);
			$promotions = $db->loadAssocList();
			
			if($promotions){
				$return['result'] = 1;
				$return['error'] = "";
				for($i=0; $i<count($promotions); $i++){
					$promotions[$i]['icon'] = JURI::base()."images/promotion/".$promotions[$i]['icon'];
				}
				$return['promotions'] = $promotions;
			} else {
				$return['result'] = 0;
				$return['error'] = "No result";
			}
		} else {
			$q = "SELECT id, title, content, stamp, icon FROM #__promotion WHERE businessId = ".$businessId." AND endDate > ".time();
			$db->setQuery($q);
			$promotions = $db->loadAssocList();
			
			if($promotions){
				$return['result'] = 1;
				$return['error'] = "";
				$i = 0;
				foreach($promotions as $promotion){
					$db->setQuery("SELECT numStamp FROM #__stamp WHERE promotionId = ".$promotion['id']." AND customerId = ".$customerId);
					$promotions[$i]['myStamp'] = $db->loadResult();
					$promotions[$i]['icon'] = JURI::base()."images/promotion/".$promotions[$i]['icon'];
					$i++;
				}
				$return['promotions'] = $promotions;
			} else {
				$return['result'] = 0;
				$return['error'] = "No result";
			}
		}
		die(json_encode($return));
	}
	public function _getPoint($customerId, $businessId){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT point FROM #__point WHERE customerId = ".$customerId." AND businessId = ".$businessId);
		if($db->loadResult()){
			return $db->loadResult();
		} else {
			return 0;
		}
	}
	
	
	// Business
	public function businessLogin() {
		$app    = JFactory::getApplication();
		$email = JRequest::getVar("email");
		$password = JRequest::getVar("password");
		
		$credentials["username"] = $email;
		$credentials["password"] = $password;
		
		$options['remember'] = false;
		$options['return']   = '';
		
		$result = $app->login($credentials, $options);
		if($result == true){
			$db = JFactory::getDBO();
			
			$user = JFactory::getUser();
			
			$db->setQuery("SELECT id, businessName, type FROM #__business WHERE userId = ".$user->id);
			$data = $db->loadObject();
			
			$return['result'] = 1;
			$return['error'] = "";
			$return['userId'] = $user->id;
			$return['businessId'] = $data->id;
			$return['businessName'] = $data->businessName;
			$return['businessType'] = $data->type;
			$return['firstTime'] = $user->firstTime;
			
		} else {
			$return["result"] = 0;
			$return["error"] = "Dit brugernavn eller kodeord er forkert!";
		}
        die(json_encode($return));
    }
	
	function _timeElapsedString($ptime){
		$etime = time() - $ptime;
		if ($etime < 10)
		{
			return 'lige nu';
		}
		$a = array( 12 * 30 * 24 * 60 * 60  =>  'år',
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
				return $r . ' ' . $str . ($r > 1 ? '' : '') . ' siden';
			}
		}
	}
	
	public function getCheckInList(){
		$businessId = JRequest::getVar("businessId");
		$page = JRequest::getVar("page", 1);
		
		$time = time()-(15*60);
		
		$limitstart = ($page-1)*20;
		$db = JFactory::getDBO();
		$q = "SELECT customerId, MAX(createdAt) as createdAt FROM (SELECT customerId, createdAt FROM #__checkin WHERE businessId = ".$businessId." AND createdAt > $time ORDER BY createdAt DESC) a GROUP BY customerId ORDER BY createdAt DESC";
		$db->setQuery($q);
		$users = $db->loadAssocList();
		if($users){
			$i = 0;
			foreach($users as $user){
				$q = "SELECT firstname, lastname, avatar, facebookId FROM #__users WHERE id = ".$user['customerId'];
				$db->setQuery($q);
				$tmp = $db->loadObject();
				$users[$i]['firstname'] = $tmp->firstname;
				$users[$i]['lastname'] = $tmp->lastname;
				$users[$i]['facebookId'] = $tmp->facebookId;
				if($tmp->avatar){
					$users[$i]['avatar'] = JURI::base()."images/avatar/".$tmp->avatar;
				} else {
					$users[$i]['avatar'] = "";
				}
				$users[$i]['elapsed'] = $this->_timeElapsedString($user['createdAt']);
				if(in_array($user['customerId'], $this->getBestCustomer())){
					$users[$i]['star'] = 1;
				} else {
					$users[$i]['star'] = 0;
				}
				
				$i++;
			}
			$return['result'] = 1;
			$return['error'] = "";
			$return['data'] = $users;
		} else {
			$return['result'] = 0;
			$return['error'] = "Der er ingen som har checket ind i butikken endnu.";
			$return['data'] = "";
		}
		die(json_encode($return));
	}
	
	public function searchCustomer(){
		$businessId = JRequest::getVar("businessId");
		$keyword = JRequest::getVar("keyword");
		$keyword = strtolower($keyword);
		
		$db = JFactory::getDBO();
		$q = "SELECT id, firstname, lastname, avatar, facebookId FROM #__users WHERE LOWER(`firstname`) LIKE '%".$keyword."%' OR LOWER(`lastname`) LIKE '%".$keyword."%'";
		$db->setQuery($q);
		$users = $db->loadAssocList();
		
		if($users){
			$i = 0;
			foreach($users as $user){
				if($user['avatar']){
					$users[$i]['avatar'] = JURI::base()."images/avatar/".$user['avatar'];
				} else {
					$users[$i]['avatar'] = "";
				}
				
				$db->setQuery("SELECT createdAt FROM #__checkin WHERE customerId = ".$user['id']." ORDER BY createdAt DESC LIMIT 1");
				$users[$i]['createdAt'] = $db->loadResult();
				$users[$i]['elapsed'] = $this->_timeElapsedString($users[$i]['createdAt']);
				$users[$i]['customerId'] = $user['id'];
				if(in_array($user['id'], $this->getBestCustomer())){
					$users[$i]['star'] = 1;
				} else {
					$users[$i]['star'] = 0;
				}
				$i++;
			}
			function cmp_by_createdAt($a, $b) {
				return $b["createdAt"] - $a["createdAt"];
			}
			usort($users, "cmp_by_createdAt");
			$return['result'] = 1;
			$return['error'] = "";
			$return['data'] = $users;
		} else {
			$return['result'] = 0;
			$return['error'] = "Butikker blev ikke fundet. Klik her for at opdatere.";
		}
		die(json_encode($return));
	}
	
	public function getCustomerDetail(){
		$customerId = JRequest::getVar("customerId");
		$businessId = JRequest::getVar("businessId");
		$businessType = JRequest::getVar("businessType");
		
		$return['result'] = 1;
		$return['error'] = "";
		$db = JFactory::getDBO();
		$q = "SELECT firstname, lastname, avatar FROM #__users WHERE id = ".$customerId;
		$db->setQuery($q);
		$customer = $db->loadAssoc();
		if($customer['avatar']){
			$customer['avatar'] = JURI::base()."images/avatar/".$customer['avatar'];
		}
		$q = "SELECT createdAt FROM #__checkin WHERE customerId = ".$customerId." AND businessId = ".$businessId." ORDER BY createdAt DESC LIMIT 1";
		$db->setQuery($q);
		$createdAt = $db->loadResult();
		$customer['elapsed'] = $this->_timeElapsedString($createdAt);
		
		if($businessType == 1){
			$q = "SELECT point FROM #__point WHERE customerId = ".$customerId." AND businessId = ".$businessId;
			$db->setQuery($q);
			$customer['customerPoint'] = $db->loadResult();	
			$return['customer'] = $customer;
			$q = "SELECT id, title, content, point, icon FROM #__promotion WHERE businessId = ".$businessId." AND endDate > ".time();
			$db->setQuery($q);
			$promotions = $db->loadAssocList();
			if($promotions){
				for($i=0; $i<count($promotions); $i++){
					$promotions[$i]['icon'] = JURI::base()."images/promotion/".$promotions[$i]['icon'];
				}
				$return['promotions'] = $promotions;
			} else {
				$return['promotions'] = array();
			}
			
			$q = "SELECT pointDescription FROM #__business WHERE id = ".$businessId;
			$db->setQuery($q);
			$return['pointDescription'] = $db->loadResult();	
		} else {
			$return['customer'] = $customer;
			$q = "SELECT id, title, content, stamp, icon FROM #__promotion WHERE businessId = ".$businessId." AND endDate > ".time();
			$db->setQuery($q);
			$promotions = $db->loadAssocList();
			
			if($promotions){
				$i = 0;
				foreach($promotions as $promotion){
					$db->setQuery("SELECT numStamp FROM #__stamp WHERE promotionId = ".$promotion['id']." AND customerId = ".$customerId);
					$promotions[$i]['customerStamp'] = $db->loadResult();
					$promotions[$i]['icon'] = JURI::base()."images/promotion/".$promotions[$i]['icon'];
					$i++;
				}
				$return['promotions'] = $promotions;
			} else {
				$return['promotions'] = array();
			}
		}
		die(json_encode($return));
	}
	
	public function givePoint(){
		$customerId = JRequest::getVar("customerId");
		$businessId = JRequest::getVar("businessId");
		$businessName = JRequest::getVar("businessName");
		$point = JRequest::getVar("point");
		
		$db = JFactory::getDBO();
		$q = "INSERT INTO #__log_point(point, customerId, businessId, type, createdAt) VALUES ($point, $customerId, $businessId, 1, '".time()."')";
		$db->setQuery($q);
		if($db->execute()){
			$q = "SELECT id FROM #__point WHERE customerId = $customerId AND businessId = $businessId";
			$db->setQuery($q);
			$id = $db->loadResult();
			if($id){
				$q = "UPDATE #__point SET point = point + $point WHERE id = ".$id;
				$db->setQuery($q);
				$db->execute();
			} else {
				$q = "INSERT INTO #__point(customerId, businessId, point) VALUES ($customerId, $businessId, $point)";
				$db->setQuery($q);
				$db->execute();
			}
			
			$q = "SELECT point FROM #__point WHERE customerId = $customerId AND businessId = $businessId";
			$db->setQuery($q);
			$newPoint = $db->loadResult();
			
			$q = "SELECT type, latitude, longitude FROM #__business WHERE id = $businessId";
			$db->setQuery($q);
			$business = $db->loadObject();
			
			$return['result'] = 1;
			$return['error'] = "";
			$return['newPoint'] = $newPoint;
			$return['push'] = $this->pushNotification($customerId, "Du har nu modtaget ".$point." point fra ".$businessName, 2, $businessId, $business->type, $business->latitude, $business->longitude);
		} else {
			$return['result'] = 0;
			$return['error'] = "Give point fail";
		}
		
		die(json_encode($return));	
	}
	
	public function redeemPoint(){
		$customerId = JRequest::getVar("customerId");
		$businessId = JRequest::getVar("businessId");
		$businessName = JRequest::getVar("businessName");
		$point = JRequest::getVar("point");
		
		$db = JFactory::getDBO();
		$q = "INSERT INTO #__log_point(point, customerId, businessId, type, createdAt) VALUES ($point, $customerId, $businessId, 2, '".time()."')";
		$db->setQuery($q);
		if($db->execute()){
			$q = "UPDATE #__point SET point = point - $point WHERE customerId = $customerId AND businessId = $businessId";
			$db->setQuery($q);
			$db->execute();
			
			$q = "SELECT point FROM #__point WHERE customerId = $customerId AND businessId = $businessId";
			$db->setQuery($q);
			$newPoint = $db->loadResult();
			
			$db->setQuery("SELECT type, latitude, longitude FROM #__business WHERE id = $businessId");
			$business = $db->loadObject();
			
			$return['result'] = 1;
			$return['error'] = "";
			$return['newPoint'] = $newPoint;
			$return['push'] = $this->pushNotification($customerId, $businessName. " har nu trukket ".$point." point fra dig.", 2, $businessId, $business->type, $business->latitude, $business->longitude);
		} else {
			$return['result'] = 0;
			$return['error'] = "Redeem point fail";
		}
		die(json_encode($return));
	}
	
	public function giveStamp(){
		$customerId = JRequest::getVar("customerId");
		$businessId = JRequest::getVar("businessId");
		$businessName = JRequest::getVar("businessName");
		$promotionId = JRequest::getVar("promotionId");
		
		$db = JFactory::getDBO();
		$q = "INSERT INTO #__log_stamp(businessId, customerId, promotionId, type, numStamp, createdAt) VALUES ($businessId, $customerId, $promotionId, 1, 1, '".time()."')";
		$db->setQuery($q);
		if($db->execute()){
			$q = "SELECT id FROM #__stamp WHERE customerId = $customerId AND businessId = $businessId AND promotionId = $promotionId";
			$db->setQuery($q);
			$id = $db->loadResult();
			if($id){
				$q = "UPDATE #__stamp SET numStamp = numStamp + 1 WHERE id = ".$id;
				$db->setQuery($q);
				$db->execute();
			} else {
				$q = "INSERT INTO #__stamp(promotionId, customerId, businessId, numStamp) VALUES ($promotionId, $customerId, $businessId, 1)";
				$db->setQuery($q);
				$db->execute();
			}
			$q = "SELECT numStamp FROM #__stamp WHERE customerId = $customerId AND businessId = $businessId AND promotionId = $promotionId";
			$db->setQuery($q);
			$newNumStamp = $db->loadResult();
			
			$db->setQuery("SELECT type, latitude, longitude FROM #__business WHERE id = $businessId");
			$business = $db->loadObject();
			
			$return['result'] = 1;
			$return['error'] = "";
			$return['newNumStamp'] = $newNumStamp;
			$return['push'] = $this->pushNotification($customerId, "Du har nu modtaget 1 stempel fra ".$businessName, 2, $businessId, $business->type, $business->latitude, $business->longitude);
		} else {
			$return['result'] = 0;
			$return['error'] = "Give stamp fail";
		}
		
		die(json_encode($return));
	}
	
	public function takeBackStamp(){
		$customerId = JRequest::getVar("customerId");
		$businessId = JRequest::getVar("businessId");
		$businessName = JRequest::getVar("businessName");
		$promotionId = JRequest::getVar("promotionId");
		
		$db = JFactory::getDBO();
		$q = "INSERT INTO #__log_stamp(businessId, customerId, promotionId, type, numStamp, createdAt) VALUES ($businessId, $customerId, $promotionId, 1, 2, '".time()."')";
		$db->setQuery($q);
		if($db->execute()){
			$q = "SELECT id FROM #__stamp WHERE customerId = $customerId AND businessId = $businessId AND promotionId = $promotionId";
			$db->setQuery($q);
			$id = $db->loadResult();
			
			$q = "UPDATE #__stamp SET numStamp = numStamp - 1 WHERE id = ".$id;
			$db->setQuery($q);
			$db->execute();
			
			$q = "SELECT numStamp FROM #__stamp WHERE customerId = $customerId AND businessId = $businessId AND promotionId = $promotionId";
			$db->setQuery($q);
			$newNumStamp = $db->loadResult();
			
			$db->setQuery("SELECT type, latitude, longitude FROM #__business WHERE id = $businessId");
			$business = $db->loadObject();
			
			$return['result'] = 1;
			$return['error'] = "";
			$return['newNumStamp'] = $newNumStamp;
			$return['push'] = $this->pushNotification($customerId, $businessName." har trukket 1 stempel fra dig.", 2, $businessId, $business->type, $business->latitude, $business->longitude);
		} else {
			$return['result'] = 0;
			$return['error'] = "Take back stamp fail";
		}
		
		die(json_encode($return));
	}
	
	public function redeemStamp(){
		$customerId = JRequest::getVar("customerId");
		$businessId = JRequest::getVar("businessId");
		$businessName = JRequest::getVar("businessName");
		$promotionId = JRequest::getVar("promotionId");
		$promotionStamp = JRequest::getVar("promotionStamp");
		
		$db = JFactory::getDBO();
		$q = "INSERT INTO #__log_stamp(customerId, businessId, promotionId, type, numStamp, createdAt) VALUES ($customerId, $businessId, $promotionId, 2, $promotionStamp, '".time()."')";
		$db->setQuery($q);
		if($db->execute()){
			$q = "UPDATE #__stamp SET numStamp = numStamp - $promotionStamp WHERE customerId = $customerId AND businessId = $businessId AND promotionId = $promotionId";
			$db->setQuery($q);
			$db->execute();
			
			$q = "SELECT numStamp FROM #__stamp WHERE customerId = $customerId AND businessId = $businessId AND promotionId = $promotionId";
			$db->setQuery($q);
			$newNumStamp = $db->loadResult();
			
			$db->setQuery("SELECT type, latitude, longitude FROM #__business WHERE id = $businessId");
			$business = $db->loadObject();
			
			$return['result'] = 1;
			$return['error'] = "";
			$return['newNumStamp'] = $newNumStamp;
			$return['push'] = $this->pushNotification($customerId, $businessName. " har trukket ".$promotionStamp." stempler fra dig.", 2, $businessId, $business->type, $business->latitude, $business->longitude);
		} else {
			$return['result'] = 0;
			$return['error'] = "Redeem stamp fail";
		}
		die(json_encode($return));
	}
	
	public function pushNotification($userId, $msg, $pushType, $businessId, $businessType, $lat = NULL, $long = NULL){
		$data['pushType'] = $pushType;
		$data['businessId'] = $businessId;
		$data['businessType'] = $businessType;
		$data['lat'] = $lat;
		$data['long'] = $long;
		$data = json_encode($data);
		$customData['custom'] = $data;
		$customData = json_encode($customData);
		
		$url = 'https://cp.pushwoosh.com/json/1.3/createTargetedMessage';
		$send['request'] = array('auth' => 'C4jIJrQCJLlubwb7pPvBDsdcA9SdGSIkRynZC2vZ0J4y7jkEuUiq6GjDK7LFVMeifC72FuSVtRqjzDqXpEYX', 'send_date'=>'now', 'content'=>$msg, 'devices_filter'=>'A("9727D-054A0") * (T("userId", EQ, '.$userId.') + T("userId", EQ, '.$userId.'))', 'data'=>$data);

		$request = json_encode($send);
	 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	 
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		return $response;
		//print "[PW] request: $request\n";
        //print "[PW] response: $response\n";
        //print "[PW] info: " . print_r($info, true);
	}
	
	public function getDeals(){
		$lat = JRequest::getVar("lat");
		$long = JRequest::getVar("long");
		
		$db = JFactory::getDBO();
		$q = "SELECT id as businessId, businessName, icon, address, latitude, longitude, ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$long.") ) + sin( radians(".$lat.") ) * sin( radians( latitude ) ) ) ) AS distance FROM #__business HAVING distance < 10000 ORDER BY distance;";
		$db->setQuery($q);
		$stores = $db->loadAssocList();
		
		if($stores){
			$data = array();
			$i = 0;
			foreach($stores as $store){
				$q = "SELECT * FROM #__deals WHERE businessId = ".$store["businessId"]." AND countDownTime > ".time()." AND status = 0";
				$db->setQuery($q);
				$deals = $db->loadAssocList();
				if($deals){
					foreach($deals as $deal){
						$data[$i]['businessId'] = $store['businessId'];
						$data[$i]['businessName'] = $store['businessName'];
						$data[$i]['icon'] = JURI::base()."images/business/".$store['icon'];
						$data[$i]['address'] = $store['address'];
						$data[$i]['latitude'] = $store['latitude'];
						$data[$i]['longitude'] = $store['longitude'];
						$data[$i]['distance'] = $store['distance'];
						$data[$i]['expire'] = date("d/m/Y", $deal["endDate"]);
						$data[$i]['title'] = $deal["title"];
						$data[$i]['content'] = $deal["content"];
						$data[$i]['countDownTime'] = $deal["countDownTime"];
						
						$i++;
					}
				}
			}
			$return['result'] = 1;
			$return['error'] = "";
			$return['data'] = $data;
		} else {
			$return['result'] = 0;
			$return['error'] = "Butikker blev ikke fundet. Klik her for at opdatere.";
		}
		die(json_encode($return));
	}
	
	public function testSendForDeal(){
		$businessId = JRequest::getVar("businessId");
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT DISTINCT(customerId) FROM #__checkin WHERE businessId = $businessId");
		$ids = $db->loadColumn();
		foreach($ids as $id){
			$arr[] = 'T("userId", EQ, '.$id.')';
		}
		$str = implode("+", $arr);
		
		$data['pushType'] = 3;
		$data['businessId'] = $businessId;
		$data['businessType'] = 0;
		$data = json_encode($data);
		
		$url = 'https://cp.pushwoosh.com/json/1.3/createTargetedMessage';
		$send['request'] = array('auth' => 'C4jIJrQCJLlubwb7pPvBDsdcA9SdGSIkRynZC2vZ0J4y7jkEuUiq6GjDK7LFVMeifC72FuSVtRqjzDqXpEYX', 'send_date'=>'now', 'content'=>'Testing to send group notification when create a deal', 'devices_filter'=>'A("9727D-054A0") * ('.$str.')', 'data'=>$data);

		$request = json_encode($send);
	 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	 
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		print_r($response);exit;
	}
	
	public function getBestCustomer(){
		$businessId = JRequest::getVar("businessId");
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT COUNT(*) as num FROM #__checkin WHERE businessId = $businessId GROUP BY customerId , businessId");
		$all = $db->loadObjectList();
		
		$amount = count($all);
		$limit = round($amount*0.1);
		$db->setQuery("SELECT COUNT(*) as num , customerId FROM #__checkin WHERE businessId = $businessId GROUP BY customerId , businessId ORDER BY num DESC LIMIT $limit");
		$userIds = $db->loadObjectList();
		foreach($userIds as $userId){
			$arr[] = $userId->customerId;
		}
		return $arr;
	}
	
	public function setFirstTime(){
		$userId = JRequest::getVar("userId");
		
		$db = JFactory::getDBO();
		$db->setQuery("UPDATE #__users SET firstTime = 1 WHERE id = $userId");
		$db->execute();
		
		$return['result'] = 1;
		$return['error'] = "";
		die(json_encode($return));
	}
	
	public function getMoney(){
		$db = JFactory::getDBO();
		$time = time()+86400;
		$q = "SELECT * FROM #__business WHERE timeExpired < '".$time."' AND subscriptionid <> ''";
		$db->setQuery($q);
		$businessList = $db->loadObjectList();
		
		foreach($businessList as $business){
			if($business->numMonthPayment == 1){
				$expired = strtotime('+1 month', $business->timeExpired );
				$productName = "Måndligt abonnement";
				$productPrice = 349;
			} else {
				$expired = strtotime('+1 year', $business->timeExpired );
				$productName = "Årligt abonnement";
				$productPrice = 3490;
			}
			
			$epay_params = array();
			$epay_params['merchantnumber'] = "6284736";
			$epay_params['subscriptionid'] = $business->subscriptionid;
			$epay_params['orderid'] = "ML".time();
			$epay_params['amount'] = ($productPrice+($productPrice*0.25))*100;
			$epay_params['currency'] = "208";
			$epay_params['instantcapture'] = "0";
			$epay_params['fraud'] = "0";
			$epay_params['transactionid'] = "-1";
			$epay_params['pbsresponse'] = "-1";
			$epay_params['epayresponse'] = "-1";
			
			$client = new SoapClient('https://ssl.ditonlinebetalingssystem.dk/remote/subscription.asmx?WSDL');
			
			$result = $client->authorize($epay_params);
			
			if($result->authorizeResult == true)
			{
				$db = JFactory:: getDBO();
				$db->setQuery("UPDATE #__business SET timeExpired = '".$expired."', transactionPayment = '".$result->transactionid."' WHERE id = ".$business->id);
				$db->execute();
				
				$db->setQuery("SELECT email, name FROM #__users WHERE id = ".$business->userId);
				$user = $db->loadObject();
				
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
									<td width="20%" style="padding: 5px; border: 1px solid #ccc;"><strong>Ordrenummer:</strong></td>
									<td style="padding: 5px; border: 1px solid #ccc;"><strong>'.sprintf("%'.05d\n", $business->userId).'</strong></td>
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
										<td style="text-align: right; padding: 5px; border: 1px solid #ccc;">Heraf moms:</td>
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
										<th style="text-align: left; padding: 10px;">Årlig abonnemenet gældende fra og til: '.date("d/m/Y", $business->timeExpired).' - '.date("d/m/Y", $expired).'</th>
										<th style="padding: 10px;">Ordrenummer: '.sprintf("%'.05d\n", $business->userId).'</th>
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
						$mail->setSubject('Bekræftet ordre '.sprintf("%'.05d\n", $business->userId));
						$mail->isHTML(true);
						$mail->setBody($html);
						$sent = $mail->Send();
			}
			else
			{
				print_r($result);exit;
			}
		}
	}
	
	function testMailchimp(){
		$apikey = '025754d9255be8adab8fbc40796313ea-us12';
		$auth = base64_encode( 'user:'.$apikey );
		
		$data = array(
			'apikey'        => $apikey,
			'email_address' => "trung@mywebcreations.dk",
			'status'        => 'subscribed',
			'merge_fields'  => array(
				'FNAME' => "trung",
				'LNAME' => "nguyen",
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
		print_r($result);exit;
	}
	
	public function setNewIdCustomer(){
		$db = JFactory::getDBO();
		
		$q = "SELECT u.id FROM #__users u INNER JOIN #__user_usergroup_map um ON u.id = um.user_id WHERE um.group_id = 3";
		$db->setQuery($q);
		$ids = $db->loadColumn();
		
		foreach($ids as $id){
			$newId = 100000000 + $id;
		
			$q = "UPDATE #__users SET newId = $newId WHERE id =".$id;
			$db->setQuery($q);
			$db->execute();
		}
		print_r($ids);exit;
	}
	
	function testPdf(){
		
		$content = "
		<page>
			<h1>Exemple d'utilisation</h1>
			<br>
			Ceci est un <b>exemple d'utilisation</b>
			de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
		</page>";
	
		require_once 'html2pdf.class.php';die('fghfgh');
		$html2pdf = new HTML2PDF('P','A4','fr');
		$html2pdf->WriteHTML($content);
		if($html2pdf->Output(JPATH_BASE.DIRECTORY_SEPARATOR.'exemple.pdf')){
			die('ok');
		} else {
			die('fail');
		}
	}
}
