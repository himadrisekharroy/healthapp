<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='admin_user_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";

	$sql = "select * from admin join admin_role on (admin.admin_role = admin_role.role_id) order by admin_name";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	

	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['admin_id'] = $row['admin_id'];
		$returnArray['data'][$i]['admin_email'] = stripslashes($row['admin_email']);
		$returnArray['data'][$i]['admin_name'] = stripslashes($row['admin_name']);
		$returnArray['data'][$i]['admin_status'] = $row['admin_status'];
		$returnArray['data'][$i]['created_on'] = date("jS M' y", strtotime($row['created_on']));
		$returnArray['data'][$i]['role_title'] = stripslashes($row['role_title']);
		
		$sql = "select admin_name from admin where admin_id='". $row['parent_admin_id']. "'";
		$resInner = mysqli_query($link, $sql) or die(mysqli_error($link));	
		$rowInner = mysqli_fetch_array($resInner);
		
		$returnArray['data'][$i]['parent_admin_name'] = stripslashes($rowInner['admin_name']);
		$i++;
	}
	
	echo json_encode($returnArray);
}

elseif($_POST['func'] == 'status_change')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Admin User status change is not successful.";
	
	$sql = "update admin set admin_status = if(admin_status = '1','0', '1' ) where admin_id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select admin_status from admin where admin_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Admin User status has been successfully changed.";
		$returnArray['status'] = $row_inner['admin_status'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Admin User deletion is not successful.";

	$sql = "select delete_p 
			from role_module_permission 
			join admin on (admin.admin_role = role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='2'"; 	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from admin where admin_id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Admin User deletion is successful.";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'get_role_admin_user')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Data Found.";
	
	$sql = "select * from  admin join admin_role on (admin_role.parent_role_id = admin.admin_role) where role_id='".$_POST['id']."' and admin_status='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['admin_id'] = $row['admin_id'];
		$returnArray['data'][$i]['admin_email'] = stripslashes($row['admin_email']);
		$returnArray['data'][$i]['admin_name'] = stripslashes($row['admin_name']);
		$i++;
	}
	
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'add_admin_user')
{
	$admin_password = createHashAndSalt($_POST['admin_password']);
	
	$sql = "insert  admin set 
				admin_name = '".addslashes(trim($_POST['admin_name']))."',
				admin_email = '".addslashes(trim($_POST['admin_email']))."',
				admin_mobile = '".addslashes(trim($_POST['admin_mobile']))."',
				admin_password = '$admin_password',
				admin_role = '".$_POST['admin_role']."',
				parent_admin_id = '".$_POST['parent_admin_id']."',
				admin_status ='1',
				created_on = NOW()";
				
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Admin User has been added successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'get_data')
{
	$sql ="select * from admin where admin_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['admin_id'] = $row['admin_id'];
	$returnArray['data']['admin_email'] = stripslashes($row['admin_email']);
	$returnArray['data']['admin_mobile'] = stripslashes($row['admin_mobile']);
	$returnArray['data']['admin_name'] = stripslashes($row['admin_name']);
	$returnArray['data']['admin_role'] = $row['admin_role'];
	$returnArray['data']['parent_admin_id'] = $row['parent_admin_id'];
	$returnArray['data']['admin_password'] = "XXXXXXXXXXXXXXXXXXXX";
	
	$sql = "select admin_id, admin_name from admin where admin_role = (select parent_role_id from admin_role where role_id='".$row['admin_role']."')";
	$resInner = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($rowInner = mysqli_fetch_array($resInner))
	{
		$returnArray['data']['parent_list'][$i]['admin_id'] = stripslashes($rowInner['admin_id']);
		$returnArray['data']['parent_list'][$i]['admin_name'] = stripslashes($rowInner['admin_name']);
		$i++;
	}
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'edit')
{
	
	$sql = "update  admin set 
				admin_name = '".addslashes(trim($_POST['admin_name']))."',
				admin_email = '".addslashes(trim($_POST['admin_email']))."',
				admin_mobile = '".addslashes(trim($_POST['admin_mobile']))."',				
				admin_role = '".$_POST['admin_role']."',
				parent_admin_id = '".$_POST['parent_admin_id']."' 
				where admin_id='".$_POST['id']."'";
				
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Admin User has been updated successfully";
		
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