<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from users order by create_date desc ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "avatar.png";
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = $row['dob'];
		$returnArray['data'][$i]['sex'] = $row['sex'];
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='active_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from slider where is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['title'] = stripslashes($row['title']);
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "User status change is not successful.";
	
	$sql = "update users set is_active = if(is_active = '1','0', '1' ) where id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from users where id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " User status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{
	$image = "";
	if($_FILES['user_image']['name'])
	{		
		$image = time().$_FILES['user_image']['name'];
	
		move_uploaded_file($_FILES['user_image']['tmp_name'],"../images/user_images/".$image);
	}

	$sql = "insert users set
			f_name='".addslashes(trim($_POST['f_name']))."',
			l_name='".addslashes(trim($_POST['l_name']))."',
			mobile='".addslashes(trim($_POST['mobile']))."',
			email_id='".addslashes(trim($_POST['email_id']))."',
			dob='".addslashes(trim($_POST['dob']))."',
			sex='".addslashes(trim($_POST['sex']))."',
			password='".createHashAndSalt($_POST['password'])."',			
			image = '$image',
			create_date=NOW(),
			is_active='1'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "User has been added successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "User deletion is not successful.";
	$sql = "select delete_p 
			from role_module_permission 
			join admin on (admin.admin_role = role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='9'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from users where id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "User deletion is successful.";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'get_data')
{
	$sql ="select * from users where id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['id'] = $row['id'];
	$returnArray['data']['f_name'] = stripslashes($row['f_name']);
	$returnArray['data']['l_name'] = stripslashes($row['l_name']);
	$returnArray['data']['mobile'] = stripslashes($row['mobile']);
	$returnArray['data']['email_id'] = stripslashes($row['email_id']);
	$returnArray['data']['dob'] = $row['dob'];
	$returnArray['data']['sex'] = $row['sex'];
	$returnArray['data']['image'] = $row['image'];;
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	$sql = "";
	if($_FILES['user_image']['name'])
	{
		$image = time().$_FILES['user_image']['name'];
		move_uploaded_file($_FILES['user_image']['tmp_name'],"../images/user_images/".$image);
		$sql = "update users set
			f_name='".addslashes(trim($_POST['f_name']))."',
			l_name='".addslashes(trim($_POST['l_name']))."',
			mobile='".addslashes(trim($_POST['mobile']))."',
			email_id='".addslashes(trim($_POST['email_id']))."',
			dob='".addslashes(trim($_POST['dob']))."',
			sex='".addslashes(trim($_POST['sex']))."',
			image = '$image'
			where id='".$_POST['edit_id']."'";
	
		
	}
	else
	{
		$sql = "update users set
			f_name='".addslashes(trim($_POST['f_name']))."',
			l_name='".addslashes(trim($_POST['l_name']))."',
			mobile='".addslashes(trim($_POST['mobile']))."',
			email_id='".addslashes(trim($_POST['email_id']))."',
			dob='".addslashes(trim($_POST['dob']))."',
			sex='".addslashes(trim($_POST['sex']))."',
			where id='".$_POST['edit_id']."'";
	}

	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "User has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
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