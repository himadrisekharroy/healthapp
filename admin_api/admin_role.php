<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='role_list')
{
	$sql = "SELECT A.role_id, A.role_title, B.role_title AS parent_role_title, A.status
			FROM admin_role A
			LEFT JOIN admin_role B ON A.parent_role_id = B.role_id
			ORDER BY A.role_title ASC";
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";

	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['role_id'] = $row['role_id'];
		$returnArray['data'][$i]['role_title'] = stripslashes($row['role_title']);
		$returnArray['data'][$i]['parent_role_title'] = stripslashes($row['parent_role_title']);
		$returnArray['data'][$i]['status'] = $row['status'];
		$i++;
	}
	
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'status_change')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Admin status change is not successful.";
	
	$sql = "update admin_role set status = if(status = '1','0', '1' ) where role_id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select status from admin_role where role_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Admin status has been successfully changed.";
		$returnArray['status'] = $row_inner['status'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Admin deletion is not successful.";
	
	$sql = "select delete_p 
			from role_module_permission 
			join admin on (admin.admin_role = role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='1'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from admin_role where role_id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Admin deletion is successful.";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";	
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='active_role_list')
{
	$sql = "SELECT role_id,role_title
			FROM admin_role 
			where status = 1
			ORDER BY role_title ASC";
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";

	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['role_id'] = $row['role_id'];
		$returnArray['data'][$i]['role_title'] = stripslashes($row['role_title']);		
		$i++;
	}
	
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'add_role')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "";
		
	$sql = "insert admin_role set role_title='".addslashes(trim($_POST['role_tile']))."', parent_role_id='".$_POST['parent_role_id']."', status='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Admin designation has been added successfully";	
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
	$sql = "select * from admin_role where role_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['success'] = true;
	$returnArray['msg'] = "";
	$returnArray['data']['role_title'] =stripslashes(trim($row['role_title']));
	$returnArray['data']['parent_role_id'] =$row['parent_role_id'];
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'edit_role')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "";
		
	$sql = "update admin_role set role_title='".addslashes(trim($_POST['role_tile']))."', parent_role_id='".$_POST['parent_role_id']."' where role_id='".$_POST['role_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Admin designation has been added updated";	
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "permission")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "";
	
	$sql ="select * from modules ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$sql ="select * from role_module_permission where role_id='".$_POST['role_id']."' and module_id='".$row['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		$returnArray['data']['modules'][$i]['id'] = $row['id'];
		$returnArray['data']['modules'][$i]['name'] = stripslashes($row['name']);
		$returnArray['data']['modules'][$i]['view'] = "";
		$returnArray['data']['modules'][$i]['add'] = "";
		$returnArray['data']['modules'][$i]['edit'] = "";
		$returnArray['data']['modules'][$i]['delete'] = "";
		if($row_inner['view_p']) $returnArray['data']['modules'][$i]['view'] = 1;
		if($row_inner['add_p']) $returnArray['data']['modules'][$i]['add'] = 1;
		if($row_inner['edit_p']) $returnArray['data']['modules'][$i]['edit'] = 1;
		if($row_inner['delete_p']) $returnArray['data']['modules'][$i]['delete'] = 1;
		$i++;
	}
	
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "permission_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Role permission change is not successful.";
	
	$sql = "select id from role_module_permission where role_id='".$_POST['role_id']."' and module_id='".$_POST['module_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	$returnArray['sql_1'] = $sql;
	$returnArray['num'] = $num;
	if($num > 0)
	{
		if($_POST['type'] == 'view') 
		$sql = "update role_module_permission set view_p = if(view_p = '1','0', '1' ) where role_id='".$_POST['role_id']."' and module_id='".$_POST['module_id']."'";
		if($_POST['type'] == 'add') 
		$sql = "update role_module_permission set add_p = if(add_p = '1','0', '1' ) where role_id='".$_POST['role_id']."' and module_id='".$_POST['module_id']."'";
		elseif($_POST['type'] == 'edit') 
		$sql = "update role_module_permission set edit_p = if(edit_p = '1','0', '1' ) where role_id='".$_POST['role_id']."' and module_id='".$_POST['module_id']."'";
		elseif($_POST['type'] == 'delete') 
		$sql = "update role_module_permission set delete_p = if(delete_p = '1','0', '1' ) where role_id='".$_POST['role_id']."' and module_id='".$_POST['module_id']."'";
	}
	else
	{
		if($_POST['type'] == 'view') 
		$sql = "insert role_module_permission set view_p = '1', role_id='".$_POST['role_id']."' , module_id='".$_POST['module_id']."'";
		if($_POST['type'] == 'add') 
		$sql = "insert role_module_permission set add_p = '1', role_id='".$_POST['role_id']."' , module_id='".$_POST['module_id']."'";
		elseif($_POST['type'] == 'edit') 
		$sql = "insert role_module_permission set edit_p = '1', role_id='".$_POST['role_id']."' , module_id='".$_POST['module_id']."'";
		elseif($_POST['type'] == 'delete') 
		$sql = "insert role_module_permission set delete_p = '1' , role_id='".$_POST['role_id']."' , module_id='".$_POST['module_id']."'";
	}
	$returnArray['sql'] = $sql;
	$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));	
		
	if($res_inner)
	{
		$type = $_POST['type']."_p";
		$sql = "select $type from role_module_permission where role_id='".$_POST['role_id']."' and module_id='".$_POST['module_id']."'";
		$res_inner2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner2 = mysqli_fetch_array($res_inner2);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Role permission has been successfully changed.";
		$returnArray['status'] = $row_inner2[$type];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "check_permission")
{
	$sql = "select view_p, add_p, edit_p, delete_p 
			from role_module_permission 
			join admin on (admin.admin_role = role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='".$_POST['module_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data found";
	if($_POST['role_type'] == 'add')
	{
		$returnArray['permission'] = false;
		if($row['add_p'])	$returnArray['permission'] = true;		
	}
	elseif($_POST['role_type'] == 'edit')
	{
		$returnArray['permission'] = false;
		if($row['edit_p'])	$returnArray['permission'] = true;		
	}
	elseif($_POST['role_type'] == 'delete')
	{
		$returnArray['permission'] = false;
		if($row['delete_p'])	$returnArray['permission'] = true;		
	}
	elseif($_POST['role_type'] == 'view')
	{
		$returnArray['permission'] = false;
		if($row['view_p'])	$returnArray['permission'] = true;		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "role_all_permission_list")
{
	$sql = "select * from modules";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$sql = "select view_p, add_p, edit_p, delete_p 
				from role_module_permission 
				join admin on (admin.admin_role = role_module_permission.role_id)
				where admin_id='".$_POST['admin_id']."' and module_id='".$row['id']."'";
		$resInner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$rowInner = mysqli_fetch_array($resInner);
		$returnArray['permission_list'][$i]["module_id"] = $row['id'];
		$returnArray['permission_list'][$i]["module_name"] = stripslashes($row['name']);
		
		$returnArray['permission_list'][$i]["view"] = false;
		$returnArray['permission_list'][$i]["add"] = false;
		$returnArray['permission_list'][$i]["edit"] = false;
		$returnArray['permission_list'][$i]["delete"] = false;
		
		if($rowInner['view_p'])	$returnArray['permission_list'][$i]["view"] = true;
		if($rowInner['add_p'])	$returnArray['permission_list'][$i]["add"] = true;
		if($rowInner['edit_p'])	$returnArray['permission_list'][$i]["edit"] = true;
		if($rowInner['delete_p'])	$returnArray['permission_list'][$i]["delete"] = true;
		$i++;
	}
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data found";
	echo json_encode($returnArray);
}
?>