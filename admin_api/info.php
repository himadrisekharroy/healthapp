<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from states order by state_name";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['state_id'] = $row['state_id'];
		$returnArray['data'][$i]['state_name'] = stripslashes($row['state_name']);
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='active_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from states where is_active='1' order by state_name";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['state_id'] = $row['state_id'];
		$returnArray['data'][$i]['state_name'] = stripslashes($row['state_name']);
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "State status change is not successful.";
	
	$sql = "update states set is_active = if(is_active = '1','0', '1' ) where state_id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from states where state_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " State status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{
	$sql = "insert states set
			state_name='".addslashes(trim($_POST['state_name']))."',
			is_active='1',
			created_on=NOW()";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "State has been added successfully";
		
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
	$returnArray['msg'] = "State deletion is not successful.";
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='3'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from states where state_id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "State deletion is successful.";
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
	$sql ="select * from site_info ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{	
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['variable_text'] = stripslashes($row['variable_text']);
		$returnArray['data'][$i]['value_text'] = stripslashes($row['value_text']);		
		$i++;
	}
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	foreach($_POST as $key => $value)
	{
		$id = str_replace("info_","",$key) ;
		$sql = "update site_info set value_text = '".addslashes($value)."' where id='$id'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	}
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Site info has been updated successfully.";
	echo json_encode($returnArray);

/*
	$sql = "update states set
			state_name='".addslashes(trim($_POST['state_name']))."'
			where state_id='".$_POST['id']."'";
	
	//$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "State has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);*/
}
?>