<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select *,review.id as review_id,review.is_active as review_is_active  from review join users on (review.user_id= users.id) where doctor_id='".$_POST['doc_id']."'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "avatar.png";
		
		$returnArray['data'][$i]['id'] = $row['review_id'];

		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['sex'] = strtoupper(stripslashes($row['sex']));
		$returnArray['data'][$i]['rating'] = stripslashes($row['rating']);		
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['comments'] = nl2br(stripslashes($row['comments']));
		$returnArray['data'][$i]['is_active'] = $row['review_is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Review status change is not successful.";
	
	$sql = "update review set is_active = if(is_active = '1','0', '1' ) where id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from review where id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Review status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}






?>