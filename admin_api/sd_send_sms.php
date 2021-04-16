<?php
function sendSMS($user_mob,$msg,$sendor_id){


	$authKey = "126412AsLZSmdHsk57e8d64e";
	$mobileNumber = "+91".$user_mob;
	$senderId = $sendor_id;
	$message = urlencode($msg);
	$route = "4";//"default";  (1 -Promotional 4 - Transactional)

	$postData = array(
	    'authkey' => $authKey,
	    'mobiles' => $mobileNumber,
	    'message' => $message,
	    'sender' => $senderId,
	    'route' => $route
	);
	$url="https://control.msg91.com/api/sendhttp.php";

	$ch = curl_init();
	curl_setopt_array($ch, array(
	    CURLOPT_URL => $url,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => $postData
	    //,CURLOPT_FOLLOWLOCATION => true
	));

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$output = curl_exec($ch);
	$returnArray['success'] = true;
	$returnArray['msg'] = "Sending Message is successful.".$output;

	$txt = $senderId . " / " . $mobileNumber . " / " . $message." @ ". date("d-m-y h:i:s")."\r\n";

	if(curl_errno($ch))
	{
	    $txt = 'error: ' . curl_error($ch) . " == " .$txt ;

	    $returnArray['success'] = false;
	    $returnArray['msg'] = "Error in sending Message". curl_error($ch);
	}
	
	curl_close($ch);
	
	
 	$myfile = file_put_contents('sms_log', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
	
	return $returnArray;
}

?>

