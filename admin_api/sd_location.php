<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
	$locationSql = "";
	$citySql = "";
	
	if(trim($_POST['search_location_name']))
	$locationSql = " and location like '%".trim($_POST['search_location_name'])."%'";
	
	if($_POST['search_city_id'])
	$citySql = " and location.city_id='".$_POST['search_city_id']."'";
	
	$sql = "select * from location 
			join city on (city.city_id = location.city_id)
			join states on (city.state_id = states.state_id) where states.is_active =  '1' and city.is_active =  '1' $locationSql $citySql order by  city, location";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['location_id'] = $row['location_id'];
		$returnArray['data'][$i]['location'] = stripslashes($row['location']);
		$returnArray['data'][$i]['location_pin_code'] = stripslashes($row['location_pin_code']);
		$returnArray['data'][$i]['city'] = stripslashes($row['city']);
		$returnArray['data'][$i]['is_active'] = $row['is_active']; 
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Location status change is not successful.";
	
	$sql = "update location set is_active = if(is_active = '1','0', '1' ) where location_id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from location where location_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Location status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{
	$sql = "insert location set
			location='".addslashes(trim($_POST['location_name']))."',
			location_pin_code='".addslashes(trim($_POST['location_pin_code']))."',
			city_id='".$_POST['city_id']."',
			is_active='1'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Location has been added successfully";
		
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
	$returnArray['msg'] = "Location deletion is not successful.";
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='5'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from location where location_id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Location deletion is successful.";
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
	$sql ="select * from location where location_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['city_id'] = $row['city_id'];
	$returnArray['data']['location_id'] = $row['location_id'];
	$returnArray['data']['location_name'] = $row['location'];
	$returnArray['data']['location_pin_code'] = stripslashes($row['location_pin_code']);
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	$sql = "update location set
			location='".addslashes(trim($_POST['location_name']))."',
			location_pin_code='".addslashes(trim($_POST['location_pin_code']))."',
			city_id='".$_POST['city_id']."'
			where location_id='".$_POST['id']."'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Location has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'active_list_by_city')
{
	$sql = "select * from location where city_id='".$_POST['id']."' and is_active='1' order by location";
	//echo $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['location_id'] = $row['location_id'];
		$returnArray['data'][$i]['location_name'] = stripslashes($row['location']);
		$i++;
	}
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	echo json_encode($returnArray);
}
?>