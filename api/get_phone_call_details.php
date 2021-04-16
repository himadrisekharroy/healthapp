<?php
include("../admin_api/config.php");

$funcName = $_REQUEST['func'];

if($funcName == 'save_chat_time')
{
	$returnArray= array();
	$start_time    = strtotime ($_REQUEST['start_time']); //change to strtotime
	$end_time      = strtotime ($_REQUEST['end_time']); //change to strtotime

	if($start_time < $end_time)
	{
		$sql = "insert doctor_ph_cl_timing set
			doc_id='".$_REQUEST['doc_id']."',
			day_id='".$_REQUEST['day_id']."',
			start_time='".$_REQUEST['start_time']."',
			end_time = '".$_REQUEST['end_time']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link. $sql));

		if(!$res)
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Data not saved";
		}
		else
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = " Phone Call timimg is successfully saved.";	
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "End timing  must be greater  than Start Time."; 
	}	

	echo json_encode($returnArray);
}
elseif($funcName == "get_chat_timimg_by_doc_id")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "No appointment timimg found.";

	$sql ="select * from doctor_ph_cl_timing where doc_id = '".$_REQUEST['doc_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$start_end_time = array();
		$start_end_time['start_time'] = $row['start_time'];
		$start_end_time['end_time'] = $row['end_time'];

		if(is_array($returnArray['data'][$row['day_id']]))
		{
			array_push($returnArray['data'][$row['day_id']], $start_end_time);
		}
		else
		{
			$returnArray['data'][$row['day_id']] =array();
			array_push($returnArray['data'][$row['day_id']], $start_end_time);	
		}	

		$returnArray['success'] = true;
		$returnArray['msg'] = "Phone Call timimg found.";

		$i++;
	}
	echo json_encode($returnArray);

}
?>