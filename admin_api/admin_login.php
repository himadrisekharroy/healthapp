<?php
include("config.php");
include("sd_send_email.php");

if($_POST['func'] == "login")
{	
	$sql = "select admin_id, admin_status, admin_password, admin_name, admin_mobile, otp from admin where admin_email='".trim(addslashes($_POST['userName']))."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	$row = mysqli_fetch_array($res);
	
	$returnArray= array();
	
	if($num == 1)
	{
		if($row['admin_status'] == 1)
		{
			if(verifyPasswordHash($_POST['password'],$row['admin_password']))
			{
				$returnArray['success'] = true;
				$returnArray['admin_id'] = $row["admin_id"];
				$returnArray['admin_name'] = stripslashes($row["admin_name"]);
				$returnArray['msg'] = "Login Successful. Please Wait...";
			}		
			else
			{
				$returnArray['success'] = false;
				$returnArray['msg'] = "Wrong password. Try again...";	
			}	
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Account has been blocked, Please contact admin...";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Username not found...";
	}
	echo json_encode($returnArray);
}

function createHashAndSalt($user_provided_password)
	{
		
		$options = array(		
			'cost' => 11,
			'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
		);

		$hash = password_hash($user_provided_password, PASSWORD_BCRYPT,$options);

		return $hash;
	}
function verifyPasswordHash($password,$hash_and_salt)
{
	if (password_verify($password, $hash_and_salt))
	{		
		return TRUE;
	}
	else
	{
		return FALSE;
	}
		
}
?>