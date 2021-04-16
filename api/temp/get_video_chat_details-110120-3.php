<?php
require "../vendor/autoload.php";
include("../admin_api/config.php");
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

include("../admin_api/config.php");
$funcName = $_REQUEST['funcName'];
if($funcName == "get_chat_timimg_by_doc_id")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "No appointment timimg found.";

	$sql ="select * from doctor_video_timing where doc_id = '".$_REQUEST['doc_id']."'";
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
	$start_time    = strtotime ($_REQUEST['start_time']); //change to strtotime
	$end_time      = strtotime ($_REQUEST['end_time']); //change to strtotime

	if($start_time < $end_time)
	{
		$sql = "insert doctor_video_timing set
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
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "End timing  must be greater  than Start Time."; 
	}	

	echo json_encode($returnArray);
}

elseif($funcName == "get_slot_by_doc_id")
{
	$returnArray = array();
	$duration = 15;

	$returnArray['success'] = false;
	$returnArray['msg'] = "No chat timimg found.";

	$bookedTime = array();
	$sql = "select time 
			from doctor_appointment 
			where			
			doc_id='".$_REQUEST['doc_id']."' and
			year='".$_REQUEST['year']."' and
			month='".$_REQUEST['month']."' and
			day='".$_REQUEST['date']."' and status='1'";

			//user_id='".$_REQUEST['user_id']."' and
	$returnArray['sql'] = $sql;
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	while($row = mysqli_fetch_array($res))
	{
		array_push($bookedTime, $row['time']);
	}

	$sql ="select * from doctor_video_timing where doc_id = '".$_REQUEST['doc_id']."' and  day_id='".$_REQUEST['day_id']."'";
	$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$i=0;
		$array_of_time = array ();
		while($row = mysqli_fetch_array($res))
		{
			$start_time    = strtotime ($row['start_time']); //change to strtotime
			$end_time      = strtotime ($row['end_time']); //change to strtotime
			
			$add_mins  = $duration * 60;


			while ($start_time <= $end_time) // loop between time
			{
				$temp['time'] = date ("H:i", $start_time);
				if($start_time + $add_mins < $end_time)
					$temp['time_f'] = date ("H:i", $start_time + $add_mins);
				else
					$temp['time_f'] = date ("H:i", $end_time);
				if(in_array($temp['time'], $bookedTime))
				{
					$temp['booked'] = true; 						
				}
				else
				{
					$temp['booked'] = false; 						
				}
				

			   	$array_of_time[] = $temp;
			   	$start_time += $add_mins; // to check endtie=me
			}
			
			$returnArray['success'] = true;
			$returnArray['msg'] = "Chat timimg found.";

		$i++;
		}
		$returnArray['data'] = $array_of_time;
	}

	echo json_encode($returnArray);

}
elseif($funcName == "get_slot_by_nurse_id")
{
	$returnArray = array();
	$duration = 15;

	$returnArray['success'] = false;
	$returnArray['msg'] = "No chat timimg found.";

	$bookedTime = array();
	$sql = "select time 
			from doctor_appointment 
			where			
			doc_id='".$_REQUEST['doc_id']."' and
			year='".$_REQUEST['year']."' and
			month='".$_REQUEST['month']."' and
			day='".$_REQUEST['date']."' and status='1' and relase_nurse='1'";

			//user_id='".$_REQUEST['user_id']."' and

	$returnArray['sql'] = $sql;
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	while($row = mysqli_fetch_array($res))
	{
		array_push($bookedTime, $row['time']);
	}

	$sql ="select * from doctor_video_timing where doc_id = '".$_REQUEST['doc_id']."' and  day_id='".$_REQUEST['day_id']."'";
	$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$i=0;
		$array_of_time = array ();
		while($row = mysqli_fetch_array($res))
		{
			$start_time    = strtotime ($row['start_time']); //change to strtotime
			$end_time      = strtotime ($row['end_time']); //change to strtotime
			
			$add_mins  = $duration * 60;

			$temp['time'] = date ("H:i", $start_time);
			$temp['time_f'] = date ("H:i", $end_time);
			if(in_array($temp['time'], $bookedTime))
			{
				$temp['booked'] = true; 						
			}
			else
			{
				$temp['booked'] = false; 						
			}

			$array_of_time[] = $temp;
			
			$returnArray['success'] = true;
			$returnArray['msg'] = "Chat timimg found.";

		$i++;
		}
		$returnArray['data'] = $array_of_time;
	}

	echo json_encode($returnArray);

}
elseif($funcName == 'get_user_appointments')
{
	$returnArray = array();
	$returnArray['upcomming']['success'] = false;
	$returnArray['upcomming']['msg'] = "No upcoming appointment found.";

	$returnArray['previous']['success'] = false;
	$returnArray['previous']['msg'] = "No past appointment found.";

	//$today = explode("/",$_REQUEST['today']);
	$today = $_REQUEST['today'];
	// $today_month = intval($today[0]);
	// if($today_month < 10) $today_month = "0" . $today_month;

	// $today_day = intval($today[1]);
	// if($today_day < 10) $today_day = "0" . $today_day;

	// $today_year = $today[2];
	// $today = $today_year ."-".$today_month."-". $today_day;

	$sql = "SELECT 
				doctor_appointment.*, 
				doctor.f_name, 
				doctor.l_name, 
				doctor.type, 
				doctor.image, 
				doctor.designation, 
				specialization.title,
				specialization.per_visit_change, 
				nurse_provided_services.title as nps_title
			FROM `doctor_appointment` 
			join doctor ON (doctor.id = doctor_appointment.doc_id) 
			join specialization on (specialization.id = doctor.specialization_id) 
			left join nurse_provided_services on(nurse_provided_services.id = doctor.service_id)
			WHERE user_id='".$_REQUEST['user_id']."' and status='1' and relase_nurse='0' and end_call='0' and TIMESTAMP(ADDTIME(app_date_time,'00:15:00')) > TIMESTAMP('$today') and status = '1' order by app_date_time asc";

			$returnArray['upcomming']['sql'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$num = mysqli_num_rows($res);
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$sql = "select * from nurse_fees where nurse_id='".$row['doc_id']."'";
		$resx = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$rowx = mysqli_fetch_array($resx);
		

		if(!$row['image']) 
		{
			if($row['type'] == 'd')
				$row['image'] = "no-doctor.png";
			elseif($row['type'] == 'jd')
				$row['image'] = "no-doctor.png";
			elseif($row['type'] == 'jn')
				$row['image'] = "no-nurse.png";
			elseif($row['type'] == 'sn')
				$row['image'] = "no-nurse.png";
		}

		$returnArray['upcomming']['data'][$i] = $row;
		$returnArray['upcomming']['data'][$i]['fees'] = $rowx['fees'];
		$returnArray['upcomming']['success'] = true;
		$returnArray['upcomming']['msg'] = "Appointment found.";

		$lang_array = "Not Specified.";
		if($row['language_known'])
		{
			$lang = json_decode($row['language_known']);
			$lang = implode(",", $lang);	

			$sql = "select * from doctor_language_known where id in ($lang)" ;
			$res3 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
			$lang_array = array();
			while($row3 = mysqli_fetch_array($res3))
			{
				array_push($lang_array, $row3['title']); 
			}
			$lang_array = implode(", ", $lang_array);
		}
		$returnArray['data'][$i]['language_known'] = stripslashes($lang_array);
		$i++;
	}


	$sql = "SELECT 
				doctor_appointment.*, 
				doctor.f_name, 
				doctor.l_name, 
				doctor.type, 
				doctor.image, 
				doctor.designation, 
				specialization.title,
				specialization.per_visit_change,
				prescription.id as pres_id,
				prescription.file ,
				nurse_provided_services.title as nps_title
			FROM `doctor_appointment` 
			left join prescription on(prescription. app_id = doctor_appointment.id)
			join doctor ON (doctor.id = doctor_appointment.doc_id) 
			join specialization on (specialization.id = doctor.specialization_id) 
			left join nurse_provided_services on(nurse_provided_services.id = doctor.service_id)
			WHERE (user_id='".$_REQUEST['user_id']."' and status='1' and relase_nurse='1') 
				or
				(user_id='".$_REQUEST['user_id']."' and status='1' and end_call='1')
				or
				( user_id='".$_REQUEST['user_id']."' and status='1' and TIMESTAMP(ADDTIME(app_date_time,'00:15:00')) < TIMESTAMP('$today'))  order by app_date_time desc";
			$returnArray['previous']['sql'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$num = mysqli_num_rows($res);
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		if(!$row['image']) 
		{
			if($row['type'] == 'd')
				$row['image'] = "no-doctor.png";
			elseif($row['type'] == 'jd')
				$row['image'] = "no-doctor.png";
			elseif($row['type'] == 'jn')
				$row['image'] = "no-nurse.png";
			elseif($row['type'] == 'sn')
				$row['image'] = "no-nurse.png";
		}

		$sql = "select * from nurse_fees where nurse_id='".$row['doc_id']."'";
		$resx = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$rowx = mysqli_fetch_array($resx);

		$returnArray['previous']['data'][$i] = $row;
		$returnArray['previous']['data'][$i]['fees'] = $rowx['fees'];

		if($row['file'])
		{
			$returnArray['previous']['data'][$i]['pres'] = base64_encode($row['pres_id']."_".$row['id']."_".$row['file']);
		}
		else
			$returnArray['previous']['data'][$i]['pres'] = "";
		
		$returnArray['previous']['success'] = true;
		$returnArray['previous']['msg'] = "Appointment found.";

		$lang_array = "Not Specified.";
		if($row['language_known'])
		{
			$lang = json_decode($row['language_known']);
			$lang = implode(",", $lang);	

			$sql = "select * from doctor_language_known where id in ($lang)" ;
			$res3 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
			$lang_array = array();
			while($row3 = mysqli_fetch_array($res3))
			{
				array_push($lang_array, $row3['title']); 
			}
			$lang_array = implode(", ", $lang_array);
		}
		$returnArray['data'][$i]['language_known'] = stripslashes($lang_array);


		$i++;
	}
	echo json_encode($returnArray);
}
elseif($funcName == 'get_doctor_appointments')
{
	$returnArray = array();
	$returnArray['upcomming']['success'] = false;
	$returnArray['upcomming']['msg'] = "No appointment found.";

	$returnArray['previous']['success'] = false;
	$returnArray['previous']['msg'] = "No appointment found.";
	
	$today = $_REQUEST['today'];
	/*today calc begin 
	$today_month = intval($today[0]);
	echo $today_month; exit();
	if($today_month < 10) $today_month = "0" . $today_month;

	 $today_day = intval($today[1]);
	 if($today_day < 10) $today_day = "0" . $today_day;

	$today_year = $today[2];
	$today = $today_year ."-".$today_month."-". $today_day;
	//today calc end*/
	$sql = "SELECT 
				doctor_appointment.*, 
				users.f_name, 
				users.l_name, 
				users.image, 
				users.mobile, 
				users.dob,
				users.sex,
				users.PDID,
				doctor.DDID
			FROM `doctor_appointment` 
			join users ON (users.id = doctor_appointment.user_id)
			join doctor on (doctor.id = doctor_appointment.doc_id)
			WHERE doc_id='".$_REQUEST['doc_id']."' 
				and status = '1' 
				and relase_nurse='0' 
				and end_call='0' 
				and TIMESTAMP(ADDTIME(app_date_time,'00:15:00')) > TIMESTAMP('$today')  
			order by app_date_time asc";
			$returnArray['upcomming']['sql'];
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$num = mysqli_num_rows($res);
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		if(!$row['image']) $row['image'] = "avatar.png";
		$row['age'] = date('Y') - date('Y',strtotime($row['dob']));
		if($row['sex'] == 'm') $row['gender'] = "Male";
		else $row['gender'] = "Female";
	
		$returnArray['upcomming']['data'][$i] = $row;
		$returnArray['upcomming']['success'] = true;
		$returnArray['upcomming']['msg'] = "Appointment found.";		
		$i++;
	}
	$sql = "SELECT 
				doctor_appointment.*, 
				users.f_name, 
				users.l_name, 
				users.image, 
				users.mobile, 
				users.dob,
				users.sex,
				users.PDID
			FROM `doctor_appointment` 
			join users ON (users.id = doctor_appointment.user_id)			
			WHERE (doc_id='".$_REQUEST['doc_id']."' 
					and status='1' and relase_nurse='1')
				or
				(doc_id='".$_REQUEST['doc_id']."' 
					and status='1' and end_call='1')
				or
				(doc_id='".$_REQUEST['doc_id']."' 
					and status='1'
					and TIMESTAMP(ADDTIME(app_date_time,'00:15:00')) < TIMESTAMP('$today')) 
			 order by app_date_time desc";
			 
			$returnArray['previous']['sql'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$num = mysqli_num_rows($res);
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		if(!$row['image']) $row['image'] = "avatar.png";
		$row['age'] = date('Y') - date('Y',strtotime($row['dob']));
		if($row['sex'] == 'm') $row['gender'] = "Male";
		else $row['gender'] = "Female";
		$returnArray['previous']['data'][$i] = $row;
		$returnArray['previous']['success'] = true;
		$returnArray['previous']['msg'] = "Appointment found.";		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($funcName == 'temp_subscribe')
{
	$returnArray = array();
	$apiKey = "46477402";
	$apiSecret = "279d3b56b5c221c0ab25fcd2ba48008ac96974ef";
	$opentok = new OpenTok($apiKey, $apiSecret);
	$session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));
	$sessionId = $session->getSessionId();
	$token = $session->generateToken(array(
		'role'       => Role::PUBLISHER,
		'expireTime' => time()+(365 * 24 * 60 * 60), // in one week
		'data'       => '',
		'initialLayoutClassList' => array('focus')
	));
	$mnth = $_REQUEST['app_month'];
	$dt = $_REQUEST['app_date'];
	$app_date_time = $_REQUEST['app_year']."-".$mnth."-".$dt." ".$_REQUEST['app_time'];
	$nssql = "";
	if($_REQUEST['app_nurse_schedule']) $nssql = "nurse_schedule_time='".$_REQUEST['app_nurse_schedule']."', ";
	$sql = "insert doctor_appointment set
				user_id='".$_REQUEST['user_id']."',
				doc_id='".$_REQUEST['app_doc_id']."',
				year='".$_REQUEST['app_year']."',
				month='".$_REQUEST['app_month']."',
				day='".$_REQUEST['app_date']."',
				time ='".$_REQUEST['app_time']."', 
				app_date_time='$app_date_time',
				opentok_api_key='$apiKey',
				opentok_api_secret='$apiSecret',
				opentok_session_id='$sessionId',
				opentok_token='$token',
				create_date=NOW()";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if($res)
	{
		$returnArray['appt_id'] = mysqli_insert_id($link);
		$returnArray['success'] = true;
		$returnArray['msg'] = "Appointment booking is successful.";
		//=====================Booking confirmation mail to user ===========================//
		$sql = "select value_text from site_info where id='16'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res2);
		$playstore_link = $row['value_text'];

		$sql = "select * from doctor where id='".$_REQUEST['app_doc_id']."'";
		$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row3 = mysqli_fetch_array($res3);

		$sql = "select * from users where id='".$_REQUEST['user_id']."'";
		$res4 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row4 = mysqli_fetch_array($res4);

		$to = $row4['email_id'];
		$subject = "Welcome ".$row4['f_name'].", your appointment booking is Successful!";
		$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="https://www.actora.us/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Actora</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear '.$row4['f_name'].' '.$row4['l_name'].'</b><br/>  
      The <a href="https://www.actora.us">Actora</a> appointment with Dr. '.$row3['f_name'].' '.$row3['l_name'].' is scheduled on '.$_REQUEST['app_date'].'/ '.($_REQUEST['app_month']+1).'/'.$_REQUEST['app_year'].' at '.$_REQUEST['app_time'].'. you can also reschedule this appointment at least 30 minutes prior to this appointment.<br/>

We are interested in your health care and hope to hear from you soon. If you have any question, please feel free to contact us.
<br/>  
  <br/>
If you have any questions regarding your appointment, please contact us. We will be happy to help you.  
  <br/>
  <br/>
    </td>   
  </tr>
  <tr valign="top" align="center">
    <td valign="top">Download the app<br/> <br>
      <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Download</a>
    </td>
  </tr>
  
  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/bulb.png" alt=""><br>
      <strong>Make Knowledgeable choices</strong><br>
      Research doctors with verified patient reviews.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
     <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Book An Appointment</a>    
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <b>Healthy Living!</b>
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     Your Team at Actora!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="https://www.actora.us/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Actorac does not provide medical guidance, diagnosis,<br>
      or treatment. Please discuss all medical questions and<br>
      concerns with your Doctor. 
    </td>
  </tr>
</table>
</body>
</html>';

			

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		$headers[] = 'To: '.$row4['f_name'].' '.$row4['l_name'] .' <'.$row4['email_id'].'>';
		$headers[] = 'From: Actora Appointment<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));
		//==========================================================================//
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
		//=====================Confirmation mail to Doctor =========================//

		$to = $row3['email_id'];
		$subject = "Welcome Dr. ".$row3['f_name'].", New appointment has been booked!";
		$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="https://www.actora.us/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Actora</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear Dr. '.$row3['f_name'].' '.$row3['l_name'].'</b><br/>  
      New <a href="https://www.actora.us">Actora</a> appointment is booked. Here are the details...<br/> <br/> 
      	<b>Patient Name: <b>'.$row4['f_name'].' '.$row4['l_name'].'<br/>      	
      	 <b>Date: </b> '.$_REQUEST['app_date'].'/ '.($_REQUEST['app_month']+1).'/'.$_REQUEST['app_year'].' <br/>
      	 <b>Time: </b>'.$_REQUEST['app_time'].'<br/><br/>
 If you have any question, please feel free to contact us.
<br/>  
  <br/>
If you have any questions regarding your appointment, please contact us. We will be happy to help you.  
  <br/>
  <br/>
    </td>   
  </tr>
  <tr valign="top" align="center">
    <td valign="top">Download the app<br/> <br>
      <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Download</a>
    </td>
  </tr>
  
  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/bulb.png" alt=""><br>
      <strong>Make Knowledgeable choices</strong><br>
      Research doctors with verified patient reviews.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
     <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Book An Appointment</a>    
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <b>Healthy Living!</b>
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     Your Team at Actora!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="https://www.actora.us/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Actorac does not provide medical guidance, diagnosis,<br>
      or treatment. Please discuss all medical questions and<br>
      concerns with your Doctor. 
    </td>
  </tr>
</table>
</body>
</html>';

			

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		$headers[] = 'To: '.$row3['f_name'].' '.$row3['l_name'] .' <'.$row3['email_id'].'>';
		$headers[] = 'From: Actora Appointment<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));

		//==========================================================================//
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Appointment booking is not successful";
	}
	echo json_encode($returnArray);
}
elseif($funcName == 'reschedule_appt')
{
	$returnArray = array();
	$mnth = $_REQUEST['app_month'] < 10 ? '0'.$_REQUEST['app_month']+1 : $_REQUEST['app_month']+1;
	$dt = $_REQUEST['app_date'] < 10 ? '0'.$_REQUEST['app_date'] : $_REQUEST['app_date'];

	$app_date_time = $_REQUEST['app_year']."-".$mnth."-".$dt." ".$_REQUEST['app_time'].":00";

	$sql = "update doctor_appointment set
				year='".$_REQUEST['app_year']."',
				month='".$_REQUEST['app_month']."',
				day='".$_REQUEST['app_date']."',
				time ='".$_REQUEST['app_time']."',
				app_date_time = '$app_date_time' 
			where id='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Appointment Rescheduling is successful.";

		//=====================Booking confirmation mail to user ===========================//
		$sql = "select value_text from site_info where id='16'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res2);
		$playstore_link = $row['value_text'];

		$sql = "select user_id, doc_id from doctor_appointment where id='".$_REQUEST['appt_id']."'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row2 = mysqli_fetch_array($res2);

		$sql = "select * from doctor where id='".$row2['doc_id']."'";
		$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row3 = mysqli_fetch_array($res3);

		$sql = "select * from users where id='".$row2['user_id']."'";
		$res4 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row4 = mysqli_fetch_array($res4);

		$to = $row4['email_id'];
		$subject = "Welcome ".$row4['f_name'].", your appointment Rescheduling is Successful!";
		$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="https://www.actora.us/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Actora</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear '.$row4['f_name'].' '.$row4['l_name'].'</b><br/>  
      The <a href="https://www.actora.us">Actora</a> appointment with Dr. '.$row3['f_name'].' '.$row3['l_name'].' is rescheduled on '.$_REQUEST['app_date'].'/ '.($_REQUEST['app_month']+1).'/'.$_REQUEST['app_year'].' at '.$_REQUEST['app_time'].'. you can also reschedule this appointment at least 30 minutes prior to this appointment.<br/>

We are interested in your health care and hope to hear from you soon. If you have any question, please feel free to contact us.
<br/>  
  <br/>
If you have any questions regarding your appointment, please contact us. We will be happy to help you.  
  <br/>
  <br/>
    </td>   
  </tr>
  <tr valign="top" align="center">
    <td valign="top">Download the app<br/> <br>
      <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Download</a>
    </td>
  </tr>
  
  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/bulb.png" alt=""><br>
      <strong>Make Knowledgeable choices</strong><br>
      Research doctors with verified patient reviews.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
     <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Book An Appointment</a>    
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <b>Healthy Living!</b>
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     Your Team at Actora!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="https://www.actora.us/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Actorac does not provide medical guidance, diagnosis,<br>
      or treatment. Please discuss all medical questions and<br>
      concerns with your Doctor. 
    </td>
  </tr>
</table>
</body>
</html>';

			

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		$headers[] = 'To: '.$row4['f_name'].' '.$row4['l_name'] .' <'.$row4['email_id'].'>';
		$headers[] = 'From: Actora Appointment<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));
		//==========================================================================//
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
		//=====================Confirmation mail to Doctor =========================//

		$to = $row3['email_id'];
		$subject = "Welcome Dr. ".$row3['f_name'].", One appointment has been rescheduled!";
		$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="https://www.actora.us/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Actora</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear Dr. '.$row3['f_name'].' '.$row3['l_name'].'</b><br/>  
      An <a href="https://www.actora.us">Actora</a> appointment is rescheduled. Here are the details...<br/> <br/> 
      	<b>Patient Name: <b>'.$row4['f_name'].' '.$row4['l_name'].'<br/>      	
      	 <b>Date: </b> '.$_REQUEST['app_date'].'/ '.($_REQUEST['app_month']+1).'/'.$_REQUEST['app_year'].' <br/>
      	 <b>Time: </b>'.$_REQUEST['app_time'].'<br/><br/>
 If you have any question, please feel free to contact us.
<br/>  
  <br/>
If you have any questions regarding your appointment, please contact us. We will be happy to help you.  
  <br/>
  <br/>
    </td>   
  </tr>
  <tr valign="top" align="center">
    <td valign="top">Download the app<br/> <br>
      <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Download</a>
    </td>
  </tr>
  
  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/bulb.png" alt=""><br>
      <strong>Make Knowledgeable choices</strong><br>
      Research doctors with verified patient reviews.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
     <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Book An Appointment</a>    
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <b>Healthy Living!</b>
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     Your Team at Actora!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="https://www.actora.us/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Actorac does not provide medical guidance, diagnosis,<br>
      or treatment. Please discuss all medical questions and<br>
      concerns with your Doctor. 
    </td>
  </tr>
</table>
</body>
</html>';

			

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		$headers[] = 'To: '.$row3['f_name'].' '.$row3['l_name'] .' <'.$row3['email_id'].'>';
		$headers[] = 'From: Actora Appointment<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));

		//==========================================================================//

	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Appointment Rescheduling is not successful";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "user_initiate_chat")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "User end chat initiation failed.";

	$sql ="insert initiate_chat set user_initiation='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "User end chat initiation success.";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "check_user_initiate_chat")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "User not initiate chat.";

	$sql = "select id from initiate_chat where user_initiation='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "User initiate chat.";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "get_opentok_credentials")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "Credentials not found.";

	$sql = "select opentok_api_key, opentok_api_secret, opentok_session_id, opentok_token 
			from doctor_appointment 
			where id='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);
	if($row['opentok_api_key'])	
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Credentials found.";		
		$returnArray['data'] = $row;
	}
	echo json_encode($returnArray);
}
elseif($funcName == "get_appt_details")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "No appointment details found.";
	$sql = "SELECT * FROM `doctor_appointment` 
	join doctor on (doctor.id = doctor_appointment.doc_id) 
	join specialization on (doctor.specialization_id = specialization.id)
	WHERE doctor_appointment.id='". $_REQUEST['appt_id']."' ";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);

	$returnArray['success'] = true;
	$returnArray['msg'] = "Appointment details found.";		
	$returnArray['data'] = $row;
	echo json_encode($returnArray);
}
elseif($funcName == "save_past_appt_note")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "<strong>Error!</strong> Note submission. Try again later";
	$sql = "update doctor_appointment set `note`= '".addslashes(trim($_REQUEST['note_text']))."' where id='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "<strong>Success!</strong> Note submission.";	
	}
	echo json_encode($returnArray);
}
elseif($funcName == "show_past_appt_note")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "<strong>Error!</strong> No Note found.";
	$sql = "select `note` from doctor_appointment where id='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res); 
	if($row['note'])
	{
		$returnArray['success'] = true;
		$returnArray['note'] = addslashes($row['note']);
		$returnArray['msg'] = "<strong>Success!</strong> Note Found.";	
	}
	echo json_encode($returnArray);
}
elseif($funcName == "get_user_details_by_app_id")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "<strong>Error!</strong> No Note found.";

	$sql= "select *, prescription.id as pres_id from users 
			join doctor_appointment on(doctor_appointment.user_id = users.id) 
			left join prescription on(prescription. app_id = doctor_appointment.id)
			where doctor_appointment.id='".$_REQUEST['app_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res); 		

	if(!$row['image']) $row['image']= 'avatar.png'; 
	$row['age'] = date('Y') - date('Y',strtotime($row['dob']));
	if($row['sex'] == 'm') $row['gender'] = "Male";
	else $row['gender'] = "Female";

	if(!$row['file']) 
	{
		$row['file'] = 0;

	}
	else
	{
		$file_link = $row['pres_id']."_".$_REQUEST['app_id'].'_'.$row['file'];
		$file_link = base64_encode($file_link);
		$row['file_link'] = $file_link;
	}
	

	$returnArray['data'] = $row;
	$returnArray['success'] = true;
	$returnArray['msg'] = "<strong>Success!</strong> Data Found.";	
	echo json_encode($returnArray);
}
elseif($funcName == 'save_prescription')
{
	$returnArray= array();

	$image = "";
	$returnArray['file_name']= $_FILES['file']['name'];

	if($_FILES['file']['name'])
	{	
		$ext = pathinfo($_REQUEST['orgFile'], PATHINFO_EXTENSION);
		$image = time().".".$ext;
	
		move_uploaded_file($_FILES['file']['tmp_name'],"../prescription_file/".$image);
		//$returnArray['prescription']= $image;
	}

	$sql = "delete from prescription where app_id = '".$_REQUEST['app_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	

	$sql = "insert prescription set app_id ='".$_REQUEST['app_id'] ."',file='". $image ."', create_date=NOW()";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$id = mysqli_insert_id($link);
		$file_link = $id."_".$_REQUEST['app_id'].'_'.$image;
		$file_link = base64_encode($file_link);

		$returnArray['file_link'] = $file_link;
		$returnArray['success'] = true;
		$returnArray['msg'] = "Updation Successful.";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";
	}		
	echo json_encode($returnArray);
}
elseif($funcName == "relase_nurse")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "Release nurse is not successful. Please try again later.";
	$sql = "update doctor_appointment set relase_nurse='1' where id='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Release nurse is successful.";
	}
	echo json_encode($returnArray);
}

elseif($funcName == "get_nurse_schedule")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "No Schedule found.";

	$sql = "SELECT * FROM `nurse_fees_structure`";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['nstruc'][$i]['book_min']= $row['book_min'];
		$returnArray['nstruc'][$i]['fees'] = $row['fees'];
		$i++;
		$returnArray['success'] = true;
		$returnArray['msg'] = "Nurse Schedule found..";
	} 	
	
	
	
	echo json_encode($returnArray);
}
elseif($funcName == "end_call")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$sql = "update doctor_appointment set end_call='1' where id='".$_REQUEST['appt_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Call ended successfully.";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "appt_cancel")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "No appointment details found.";
	
	$sql = "update doctor_appointment set status='0' where id='". $_REQUEST['appt_id']."' ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);

	$sql = "select value_text from site_info where id='16'";
	$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res2);
	$playstore_link = $row['value_text'];

	$sql = "select * FROM `doctor_appointment` 
	join doctor on (doctor.id = doctor_appointment.doc_id) 
	join specialization on (doctor.specialization_id = specialization.id)
	WHERE doctor_appointment.id='". $_REQUEST['appt_id']."' ";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);

	$sql = "select `user_id`, `doc_id` from doctor_appointment where id='".$_REQUEST['appt_id']."'";
	$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($res2);

	$sql = "select * from doctor where id='".$row2['doc_id']."'";
	$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row3 = mysqli_fetch_array($res3);

	$sql = "select * from users where id='".$row2['user_id']."'";
	$res4 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row4 = mysqli_fetch_array($res4);

		$to = $row4['email_id'];
		$subject = "Welcome ".$row4['f_name'].", your appointment cancellation is successful!";
		$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="https://www.actora.us/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Actora</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear '.$row4['f_name'].' '.$row4['l_name'].'</b><br/>  
      The <a href="https://www.actora.us">Actora</a> appointment with Dr. '.$row3['f_name'].' '.$row3['l_name'].' on '.$row['day'].'/ '.($row['month']+1).'/'.$row['year'].' at '.$row['time'].' is cancelled. <br/>

We are interested in your health care and hope to hear from you soon. If you have any question, please feel free to contact us.
<br/>  
  <br/>
If you have any questions regarding your appointment, please contact us. We will be happy to help you.  
  <br/>
  <br/>
    </td>   
  </tr>
  <tr valign="top" align="center">
    <td valign="top">Download the app<br/> <br>
      <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Download</a>
    </td>
  </tr>
  
  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/bulb.png" alt=""><br>
      <strong>Make Knowledgeable choices</strong><br>
      Research doctors with verified patient reviews.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
     <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Book An Appointment</a>    
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <b>Healthy Living!</b>
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     Your Team at Actora!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="https://www.actora.us/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a>  &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Actorac does not provide medical guidance, diagnosis,<br>
      or treatment. Please discuss all medical questions and<br>
      concerns with your Doctor. 
    </td>
  </tr>
</table>
</body>
</html>';

			

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		$headers[] = 'To: '.$row4['f_name'].' '.$row4['l_name'] .' <'.$row4['email_id'].'>';
		$headers[] = 'From: Actora Appointment<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));
		//==========================================================================//
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
		//=====================Confirmation mail to Doctor =========================//

		$to = $row3['email_id'];
		$subject = "Welcome Dr. ".$row3['f_name'].", One appointment has been cancelled!";
		$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="https://www.actora.us/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Actora</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear Dr. '.$row3['f_name'].' '.$row3['l_name'].'</b><br/>  
      An <a href="https://www.actora.us">Actora</a> appointment is cancelled. Here are the details...<br/> <br/> 
      	<b>Patient Name: <b>'.$row4['f_name'].' '.$row4['l_name'].'<br/>      	
      	 <b>Date: </b> '.$row['day'].'/ '.($row['month']+1).'/'.$row['year'].' <br/>
      	 <b>Time</b>'.$row['time'].'<br/><br/>
 If you have any question, please feel free to contact us.
<br/>  
  <br/>
If you have any questions regarding your appointment, please contact us. We will be happy to help you.  
  <br/>
  <br/>
    </td>   
  </tr>
  <tr valign="top" align="center">
    <td valign="top">Download the app<br/> <br>
      <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Download</a>
    </td>
  </tr>
  
  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="https://www.actora.us/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="https://www.actora.us/images/email_template_images/bulb.png" alt=""><br>
      <strong>Make Knowledgeable choices</strong><br>
      Research doctors with verified patient reviews.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
     <a href="'.$playstore_link.'" style="background-color: #9b113a;color: #fff; padding: 5px 10px; text-decoration: none;">Book An Appointment</a>    
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <b>Healthy Living!</b>
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     Your Team at Actora!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="https://www.actora.us/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a>  &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Actorac does not provide medical guidance, diagnosis,<br>
      or treatment. Please discuss all medical questions and<br>
      concerns with your Doctor. 
    </td>
  </tr>
</table>
</body>
</html>';

			

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		$headers[] = 'To: '.$row3['f_name'].' '.$row3['l_name'] .' <'.$row3['email_id'].'>';
		$headers[] = 'From: Actora Appointment<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));

		//==========================================================================// 



	$returnArray['success'] = true;
	$returnArray['msg'] = "Appointment details found.";		
	$returnArray['data'] = $row;
	echo json_encode($returnArray);
}

?>