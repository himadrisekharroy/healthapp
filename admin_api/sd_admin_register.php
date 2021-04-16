<?php
include("config.php");

if($_POST['func'] == "register_by_user")
{
	$returnArray= array();
	$sql = "select admin_id from sd_admin where admin_email='".trim(addslashes($_POST['register_email']))."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	if($num > 0 )
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "You are already registered.";	
	}
	else
	{
		$sql = "insert sd_admin set 
				parent_admin_id=0, 
				admin_email='".trim(addslashes($_POST['register_email'])). "', 
				admin_password='".createHashAndSalt($_POST['register_password'])."', 
				admin_name='".trim(addslashes($_POST['register_username']))."',
				admin_role='0',
				admin_status='0',
				created_on=NOW()";
				
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Registration is successful. Please wait for admin approval.";	
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "some error occurred, please try again later.";
		}
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

?>
