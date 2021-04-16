<?php
include("config.php");

if($_POST['func'] == "forgot_admin")
{
	$returnArray= array();
	$sql = "select admin_id from sd_admin where admin_email='".trim(addslashes($_POST['email']))."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	if($num == 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email Not found.";
	}
	else
	{
		///mail
		$returnArray['success'] = true;
		$returnArray['msg'] = "Please check your E-mail for password reset link.";
	}
	echo json_encode($returnArray);
}
?>