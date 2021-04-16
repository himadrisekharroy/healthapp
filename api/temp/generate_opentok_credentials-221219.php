<?php
require "../vendor/autoload.php";
include("../admin_api/config.php");

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

$returnArray = array();
$returnArray['success'] = false;

$apiKey = "46266712";
$apiSecret = "c936cf3e4456dcc988df8c8be02611f0a3a9576f";

$opentok = new OpenTok($apiKey, $apiSecret);

$session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));

$sessionId = $session->getSessionId();

$token = $session->generateToken(array(
    'role'       => Role::PUBLISHER,
    'expireTime' => time()+(60 * 60), // in one week
    'data'       => '',
    'initialLayoutClassList' => array('focus')
));

if($token)
{
	$returnArray['success'] = true;
	$returnArray['sessionId'] = $sessionId;
	$returnArray['token'] = $token;

	$sql = "update doctor_appointment set 
			opentok_api_key='$apiKey',
			opentok_api_secret ='$apiSecret',
			opentok_session_id='$sessionId', 
			opentok_token='$token' 

			where id='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Successfully generated all creadentials.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Database error. ";
	}
}
else
{
	$returnArray['success'] = false;
	$returnArray['sessionId'] = $sessionId;
	$returnArray['token'] = $token;
	$returnArray['msg'] = "Token or session id not generated.";
}

echo json_encode($returnArray);
?>