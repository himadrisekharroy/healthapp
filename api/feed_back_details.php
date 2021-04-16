<?php
include("../admin_api/config.php");

$funcName = $_REQUEST['func'];

if($funcName == 'feedback_submit')
{
	$returnArray= array();

	$sql = "insert feedback set 
			subject='".addslashes(trim($_REQUEST['feedback_subject']))."',
			comments='".addslashes(trim($_REQUEST['feedback_comments']))."',
			user_type='".$_REQUEST['user_type']."',
			user_id='".$_REQUEST['user_id']."',
			create_date=NOW()";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Thank you for your valuable feedback. We'll get back to you soon.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";
	}
	echo json_encode($returnArray);
}
?>