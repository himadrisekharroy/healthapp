<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from slider ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['text1'] = stripslashes($row['text1']);
		$returnArray['data'][$i]['text2'] = stripslashes($row['text2']);
		$returnArray['data'][$i]['text3'] = stripslashes($row['text3']);
		$returnArray['data'][$i]['text4'] = stripslashes($row['text4']);
		$returnArray['data'][$i]['link1'] = stripslashes($row['link1']);
		$returnArray['data'][$i]['link2'] = stripslashes($row['link2']);
		$returnArray['data'][$i]['link1_l'] = stripslashes($row['link_1_l']);
		$returnArray['data'][$i]['link2_l'] = stripslashes($row['link2_l']);
		$returnArray['data'][$i]['image'] = stripslashes($row['image']);
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
	$returnArray['msg'] = "Slider status change is not successful.";
	
	$sql = "update slider set is_active = if(is_active = '1','0', '1' ) where id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from slider where id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Slider status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{
	$image = time().$_FILES['slider_image']['name'];
	
	move_uploaded_file($_FILES['slider_image']['tmp_name'],"../images/slider_images/".$image);

	$sql = "insert slider set
			text1='".addslashes(trim($_POST['text_1']))."',
			text2='".addslashes(trim($_POST['text_2']))."',
			text3='".addslashes(trim($_POST['text_3']))."',
			text4='".addslashes(trim($_POST['text_4']))."',
			link1='".addslashes(trim($_POST['link_1']))."',
			link2='".addslashes(trim($_POST['link_2']))."',
			link1_l='".addslashes(trim($_POST['link_1_l']))."',
			link2_l='".addslashes(trim($_POST['link_2_l']))."',
			image = '$image',
			is_active='1'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Slider has been added successfully";
		
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
	$returnArray['msg'] = "Slider deletion is not successful.";
	$sql = "select delete_p 
			from role_module_permission 
			join admin on (admin.admin_role = role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='23'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from slider where id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Slider deletion is successful.";
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
	$sql ="select * from slider where id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['id'] = $row['id'];
	$returnArray['data']['text1'] = stripslashes($row['text1']);
	$returnArray['data']['text2'] = stripslashes($row['text2']);
	$returnArray['data']['text3'] = stripslashes($row['text3']);
	$returnArray['data']['text4'] = stripslashes($row['text4']);
	$returnArray['data']['link1'] = stripslashes($row['link1']);
	$returnArray['data']['link2'] = stripslashes($row['link2']);
	$returnArray['data']['link1_l'] = stripslashes($row['link1_l']);
	$returnArray['data']['link2_l'] = stripslashes($row['link2_l']);
	$returnArray['data']['image'] = stripslashes($row['image']);
	
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	$sql = "";
	if($_FILES['slider_image']['name'])
	{
		$image = time().$_FILES['slider_image']['name'];
		move_uploaded_file($_FILES['slider_image']['tmp_name'],"../images/slider_images/".$image);
		$sql = "update slider set
			text1='".addslashes(trim($_POST['text_1']))."',
			text2='".addslashes(trim($_POST['text_2']))."',
			text3='".addslashes(trim($_POST['text_3']))."',
			text4='".addslashes(trim($_POST['text_4']))."',
			link1='".addslashes(trim($_POST['link_1']))."',
			link2='".addslashes(trim($_POST['link_2']))."',
			link1_l='".addslashes(trim($_POST['link_1_l']))."',
			link2_l='".addslashes(trim($_POST['link_2_l']))."',
			image = '$image'
			where id='".$_POST['edit_id']."'";
	
		
	}
	else
	{
		$sql = "update slider set
			text1='".addslashes(trim($_POST['text_1']))."',
			text2='".addslashes(trim($_POST['text_2']))."',
			text3='".addslashes(trim($_POST['text_3']))."',
			text4='".addslashes(trim($_POST['text_4']))."',
			link1='".addslashes(trim($_POST['link_1']))."',
			link2='".addslashes(trim($_POST['link_2']))."',
			link1_l='".addslashes(trim($_POST['link_1_l']))."',
			link2_l='".addslashes(trim($_POST['link_2_l']))."'			
			where id='".$_POST['edit_id']."'";
	}

	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Slider has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
?>