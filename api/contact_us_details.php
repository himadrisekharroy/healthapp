<?php
include("../admin_api/config.php");

$funcName = $_REQUEST['func'];

if($funcName == 'contact_submit')
{
	$returnArray= array();

	$sql = "insert contact_us set 
			name='".addslashes(trim($_REQUEST['contact_us_name']))."',
			email='".addslashes(trim($_REQUEST['contact_us_email']))."',
			ph='".addslashes(trim($_REQUEST['contact_us_ph']))."',
			msg='".addslashes(trim($_REQUEST['contact_us_comments']))."',
			create_date=NOW()";

	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Thank you for contacting us. We'll get back to you soon.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";
	}
	echo json_encode($returnArray);
}
?>