<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from specialization order by title";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['title'] = stripslashes($row['title']);
		
		$returnArray['data'][$i]['per_visit_change'] = stripslashes($row['per_visit_change']);
		$returnArray['data'][$i]['provider_percentage'] = stripslashes($row['provider_percentage']);	
		$returnArray['data'][$i]['free_period_days'] = stripslashes($row['free_period_days']);	
		$returnArray['data'][$i]['subscription_charge'] = stripslashes($row['subscription_charge']);	
		$returnArray['data'][$i]['subscription_charge_year'] = stripslashes($row['subscription_charge_year']);	
		$returnArray['data'][$i]['session_timing'] = stripslashes($row['session_timing']);

		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='active_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from specialization where is_active='1' order by title";
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
	$returnArray['msg'] = "Specialization status change is not successful.";
	
	$sql = "update specialization set is_active = if(is_active = '1','0', '1' ) where id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from specialization where id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Specialization status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{
	$sql = "insert specialization set
				title='".addslashes(trim($_POST['title']))."',
				per_visit_change='".addslashes(trim($_POST['per_visit_change']))."',
				provider_percentage='".addslashes(trim($_POST['provider_percentage']))."',
				free_period_days='".addslashes(trim($_POST['free_period_days']))."',
				subscription_charge='".addslashes(trim($_POST['subscription_charge']))."',
				subscription_charge_year='".addslashes(trim($_POST['subscription_charge_year']))."',
				session_timing='".addslashes(trim($_POST['subscription_charge']))."',		
				is_active='1',
			created_on=NOW()";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Specialization has been added successfully";
		
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
	$returnArray['msg'] = "Specialization deletion is not successful.";
	$sql = "select delete_p 
			from role_module_permission 
			join admin on (admin.admin_role = role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='26'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from specialization where id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Specialization deletion is successful.";
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
	$sql ="select * from specialization where id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['id'] = $row['id'];
	$returnArray['data']['title'] = stripslashes($row['title']);
	$returnArray['data']['per_visit_change'] = stripslashes($row['per_visit_change']);
	$returnArray['data']['provider_percentage'] = stripslashes($row['provider_percentage']);
	$returnArray['data']['free_period_days'] = stripslashes($row['free_period_days']);	
	$returnArray['data']['subscription_charge'] = stripslashes($row['subscription_charge']);
	$returnArray['data']['subscription_charge_year'] = stripslashes($row['subscription_charge_year']);
	$returnArray['data']['session_timing'] = stripslashes($row['session_timing']);
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	$sql = "update `specialization` set
				title='".addslashes(trim($_POST['title']))."',
				per_visit_change='".addslashes(trim($_POST['per_visit_change']))."',
				provider_percentage='".addslashes(trim($_POST['provider_percentage']))."',
				free_period_days='".addslashes(trim($_POST['free_period_days']))."',
				subscription_charge='".addslashes(trim($_POST['subscription_charge']))."',
				subscription_charge_year='".addslashes(trim($_POST['subscription_charge_year']))."',
				session_timing='".addslashes(trim($_POST['subscription_charge']))."'
			where id='".$_POST['id']."'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Specialization has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
?>