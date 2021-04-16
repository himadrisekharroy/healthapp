<?php
include("../admin_api/config.php");

$funcName = $_REQUEST['func'];

if($funcName == 'faq')
{
	$returnArray= array();
	$sql = "select * from `page` where id='4'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);

	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found";
	$returnArray['title'] = stripslashes($row['title']) ;
	$returnArray['content'] = stripslashes($row['content']) ;
	echo json_encode($returnArray);
}
elseif($funcName == 'pp')
{
	$returnArray= array();
	$sql = "select * from `page` where id='3'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);

	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found";
	$returnArray['title'] = stripslashes($row['title']) ;
	$returnArray['content'] = stripslashes($row['content']) ;
	echo json_encode($returnArray);
}
elseif($funcName == 'tc')
{
	$returnArray= array();
	$sql = "select * from `page` where id='2'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);

	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found";
	$returnArray['title'] = stripslashes($row['title']) ;
	$returnArray['content'] = stripslashes($row['content']) ;
	echo json_encode($returnArray);
}