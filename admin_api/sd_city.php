<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
	$searchCitySql = "";
	$searchStateSql = "";
	if(trim($_POST['searchedCityName']))
	$searchCitySql = " and city like '%".trim($_POST['searchedCityName'])."%'";
	
	if($_POST['searchedStateId'])
	$searchStateSql = " and city.state_id = '".$_POST['searchedStateId']."'";
	
	$sql = "select * from city join states on (city.state_id = states.state_id) WHERE states.is_active =  '1' $searchCitySql $searchStateSql order by state_name, city";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['city_id'] = $row['city_id'];
		$returnArray['data'][$i]['city'] = stripslashes($row['city']);
		$returnArray['data'][$i]['state_id'] = stripslashes($row['state_id']);
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
	$sql = "select * from city join states on (city.state_id = states.state_id) where city.is_active='1'  order by state_name, city";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['city_id'] = $row['city_id'];
		$returnArray['data'][$i]['city'] = stripslashes($row['city']);
		$returnArray['data'][$i]['state_id'] = stripslashes($row['state_id']);
		$returnArray['data'][$i]['state_name'] = stripslashes($row['state_name']);
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "City status change is not successful.";
	
	$sql = "update city set is_active = if(is_active = '1','0', '1' ) where city_id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from city where city_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " City status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{
	$sql = "insert city set
			city='".addslashes(trim($_POST['city_name']))."',
			state_id='".$_POST['state_id']."',
			is_active='1'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "City has been added successfully";
		
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
	$returnArray['msg'] = "City deletion is not successful.";
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='4'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from city where city_id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "City deletion is successful.";
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
	$sql ="select * from city where city_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['state_id'] = $row['state_id'];
	$returnArray['data']['city_id'] = $row['city_id'];
	$returnArray['data']['city_name'] = stripslashes($row['city']);
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'active_list_by_state')
{
	$sql ="select * from city where state_id='".$_POST['id']."' and is_active='1' order by city";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{	
		$returnArray['data'][$i]['city_id'] = $row['city_id'];
		$returnArray['data'][$i]['city_name'] = stripslashes($row['city']);
		$i++;
	}
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'edit_save')
{
	$sql = "update city set
			city='".addslashes(trim($_POST['city_name']))."',
			state_id='".$_POST['state_id']."'
			where city_id='".$_POST['id']."'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "City has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
?>