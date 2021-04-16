<?php
include("../admin_api/config.php");
$funcName = $_REQUEST['funcName'];
if($funcName == 'check_subscription_validity')
{
	$returnArray= array();
	$sql ="select * from message_subscription where user_id ='".$_REQUEST['user_id']."' order by create_date desc limit 0, 1";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	$row = mysqli_fetch_array($res);
	if(!$num)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "No subscription found";
	}
	else
	{
		$validity_date = strtotime($row['create_date'] . ' + ' . $row['validity'] . ' days');
		$today = time();
		$difference = floor(($validity_date - $today)/86400);
		$returnArray['difference'] = $difference;
		if($difference > 0)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Subscription found";
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Subscription ends";	
		}
	}
	echo json_encode($returnArray);
}
elseif($funcName=="temp_subscribe")
{
	$returnArray= array();
	if($_REQUEST['sid'] == 1) 
	{
		$amount =20;
		$validity=30;
	}
	else
	{
		$amount =200;
		$validity=365;	
	}
	$sql = "insert message_subscription set 
				user_id ='".$_REQUEST['user_id']."', 
				sid='".$_REQUEST['sid']."',
				create_date=NOW(),
				amount='$amount',
				validity='$validity' ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Subscription success";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Subscription fail";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "save_message")
{
	$chat = array();
	$returnArray= array();

	$sql = "select image from users where id='".$_REQUEST['user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$uimage = $row['image'];
	if(!$uimage) $uimage = "avatar.png";

	$sql = "select image from doctor where id='".$_REQUEST['doc_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$dimage = $row['image'];
	if(!$dimage) $dimage = "no-doctor.png";

	$file_name = "../chat_messages/chat_".$_REQUEST['user_id']."_".$_REQUEST['doc_id'].".json";

	if(file_exists($file_name))
	{
		$chat = file_get_contents($file_name, true);
		$chat = json_decode ($chat);


		$chat_msg = array();
		$chat_msg['type'] = $_REQUEST['type'];
		$chat_msg['msg'] = addslashes(trim($_REQUEST['msg']));
		$chat_msg['time'] = time();
		$chat_msg['read'] = 0;

		array_push($chat, $chat_msg);

		$returnArray['success'] = true;
		$returnArray['msg'] = "Chat append successful.";
		$returnArray['chat'] = $chat;

		$returnArray['dimage'] = $dimage;
		$returnArray['uimage'] = $uimage;

		$chat = json_encode($chat);

		if(file_put_contents ( $file_name , $chat ))
		{
			echo json_encode($returnArray);
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Chat append unsuccessful.";
			echo json_encode($returnArray);
		}

	}
	else
	{
		$chat_msg = array();
		$chat_msg['type'] = $_REQUEST['type'];
		$chat_msg['msg'] = addslashes(trim($_REQUEST['msg']));
		$chat_msg['time'] = time();
		$chat_msg['read'] = 0;
		array_push($chat, $chat_msg);

		$returnArray['success'] = true;
		$returnArray['msg'] = "Chat write successful.";
		$returnArray['chat'] = $chat;

		$returnArray['dimage'] = $dimage;
		$returnArray['uimage'] = $uimage;
		
		$chat = json_encode($chat);

		if(file_put_contents ( $file_name , $chat ))
		{
			echo json_encode($returnArray);
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Chat write unsuccessful. ";
			echo json_encode($returnArray);
		}

	}
}
elseif($funcName == "get_message")
{
	$chat = array();
	$returnArray= array();

	$sql = "select image from users where id='".$_REQUEST['user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$uimage = $row['image'];
	if(!$uimage) $uimage = "avatar.png";

	$sql = "select image from doctor where id='".$_REQUEST['doc_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$dimage = $row['image'];
	if(!$dimage) $dimage = "no-doctor.png";



	$file_name = "../chat_messages/chat_".$_REQUEST['user_id']."_".$_REQUEST['doc_id'].".json";

	if(file_exists($file_name))
	{
		$chat = file_get_contents($file_name, true);
		$chat = json_decode ($chat);

		foreach($chat as $value)
		{
			if($_REQUEST['logged_user_type'] == 'd')
			{
				if($value->type == 'u')
				{
					$value->read = 1;
				}
			}
			else
			{
				if($value->type == 'd')
				{
					$value->read = 1;
				}
			}			
		}

		if($_REQUEST['call_time'] == '1st_time')
			file_put_contents ( $file_name , json_encode($chat) );
	}


	$returnArray['success'] = true;
	$returnArray['msg'] = "Chat read successful.";
	$returnArray['chat'] = $chat;
	$returnArray['dimage'] = $dimage;
	$returnArray['uimage'] = $uimage;
	echo json_encode($returnArray);
}
elseif($funcName == "get_doc_chat_users")
{
	$returnArray= array();

	$files = array_slice(scandir('../chat_messages/'), 2);
	usort($files, create_function('$a,$b', 'return filemtime("../chat_messages/".$a)>filemtime("../chat_messages/".$b);'));
	// print_r($files);

	$userIds = array();

	foreach($files as $file)
	{
		$path_parts = pathinfo($file);
		$file_name = $path_parts['filename'];

		$file_name = explode("_",$file_name);
		$doc_id= $file_name[2];
		
		if($doc_id == $_REQUEST['doc_id'])
		{	
			array_push($userIds, $file_name[1]);
		}
	}

	$sql = "select user_id from share_health_info where doc_id='". $_REQUEST['doc_id'] ."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($res))
	{
		array_push($userIds, $row['user_id']);
	}

	$userIds = array_unique($userIds);

	// print_r($userIds);
	if(count($userIds) > 0)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Data found";
		$returnArray['data'] = array();

		$userIds = implode(",", $userIds);	
		$sql = "select * from users where id in($userIds) and is_active='1'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		while($row = mysqli_fetch_array($res))
		{
			if(!$row['image']) $row['image'] = "avatar.png";
			$row['age'] = date('Y') - date('Y',strtotime($row['dob']));
			if($row['sex'] == 'm') $row['gender'] = "Male";
			else $row['gender'] = "Female";

			// check count of unread message
			$file_name = "../chat_messages/chat_".$row['id']."_".$_REQUEST['doc_id'].".json";
			$unreadCount=0;

			if(file_exists($file_name))
			{
				$chat = file_get_contents($file_name, true);
				$chat = json_decode ($chat);			
				foreach($chat as $value)
				{
					if($value->type == 'u' && $value->read == 0)
					{
						$unreadCount++;
					}
				}
			}

			$row['unread_count'] = $unreadCount;

			array_push($returnArray['data'], $row);			
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "No chat record found!!!";
		$returnArray['data'] = array();
	}
// echo "<pre>";
// 	print_r($returnArray);
	echo json_encode($returnArray);
}
elseif($funcName == "get_chat_timimg_by_doc_id")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "No chat timimg found.";

	$sql ="select * from doctor_chat_timing where doc_id = '".$_REQUEST['doc_id']."'";
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
		$returnArray['msg'] = "Chat timimg found.";

		$i++;
	}
	echo json_encode($returnArray);

}
elseif($funcName == 'save_chat_time')
{
	$returnArray= array();
	$sql = "insert doctor_chat_timing set
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
		$returnArray['msg'] = " Chat timimg is successfully saved.";	
	}

	echo json_encode($returnArray);
}
?>