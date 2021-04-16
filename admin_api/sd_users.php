<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from  `users` 
			  order by  create_date";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['name'] = stripslashes($row['name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['user_id'] = stripslashes($row['user_id']);
		$returnArray['data'][$i]['created_on'] = stripslashes($row['create_date']);
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
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
{}
elseif($_POST['func'] == 'delete')
{ 
	$returnArray['success'] = false;
	$returnArray['msg'] = "User deletion is not successful.";
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
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
{}
elseif($_POST['func'] == 'edit_save')
{}
?>