<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from sd_billing_payment_method order by method_name";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['payment_method_id'] = $row['payment_method_id'];
		$returnArray['data'][$i]['method_name'] = stripslashes($row['method_name']);
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='active_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from sd_billing_payment_method where is_active='1' order by method_name";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['payment_method_id'] = $row['payment_method_id'];
		$returnArray['data'][$i]['method_name'] = stripslashes($row['method_name']);
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Payment Method status change is not successful.";
	
	$sql = "update sd_billing_payment_method set is_active = if(is_active = '1','0', '1' ) where payment_method_id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from sd_billing_payment_method where payment_method_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Payment Method status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{
	$sql = "select add_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='13'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['add_p'])
	{
		$sql = "insert sd_billing_payment_method set
				method_name='".addslashes(trim($_POST['method_name']))."',
				is_active='1'";
		
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Payment Method has been added successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Payment Method deletion is not successful.";
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='13'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from sd_billing_payment_method where payment_method_id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Payment Method deletion is successful.";
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
	$sql ="select * from sd_billing_payment_method where payment_method_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['payment_method_id'] = $row['payment_method_id'];
	$returnArray['data']['method_name'] = stripslashes($row['method_name']);
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	$sql = "select edit_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='13'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['edit_p'])
	{
		$sql = "update sd_billing_payment_method set
				method_name='".addslashes(trim($_POST['method_name']))."'
				where payment_method_id='".$_POST['id']."'";
		
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Payment Method has been updated successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		} 
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";
	}
	echo json_encode($returnArray);
}
?>
