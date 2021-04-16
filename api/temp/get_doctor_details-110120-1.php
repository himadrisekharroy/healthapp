<?php
include("../admin_api/config.php");

$funcName = $_REQUEST['funcName'];

if($funcName == 'doctor_login')
{
	$returnArray= array();
	$sql = "select * from doctor where email_id='".trim($_REQUEST['user_login_email'])."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	$row = mysqli_fetch_array($res);
	if($num == 1)
	{
		if($row['is_active'] == 1)
		{
			if(verifyPasswordHash($_REQUEST['user_login_password'],$row['password']))
			{
				$image = $row['image'];
				if(!$image) 
				{
					if($row['type'] == 'jn')
						$image = "no-nurse.png";
					elseif($row['type'] == 'sn')
						$image = "no-nurse.png";	
					else
						$image = "no-doctor.png";
				}

				$returnArray['success'] = true;
				$returnArray['id'] = $row["id"];
				$returnArray['f_name'] = stripslashes($row["f_name"]);
				$returnArray['l_name'] = stripslashes($row["l_name"]);
				$returnArray['mobile'] = stripslashes($row["mobile"]);
				$returnArray['sex'] = stripslashes($row["sex"]);
				$returnArray['dob'] = stripslashes($row["dob"]);
				$returnArray['image'] = $image;
				$returnArray['type']= $row['type'];
				$returnArray['msg'] = "Login Successful. Please Wait...";
			}
			else
			{
				$returnArray['success'] = false;
				$returnArray['msg'] = "Wrong password. Try again...";	
			}
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Account has been blocked, Please contact Actora admin...";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Username not found...";
	}
	echo json_encode($returnArray);
}

elseif($funcName == 'get_specialization')
{
	$returnArray= array();
	$sql = "select * from specialization where is_active = '1' ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$returnArray['success'] = true;
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['title'] = stripslashes($row['title']);
		$i++;
	}
	$returnArray['msg'] = "Data Found";

	echo json_encode($returnArray);
}
elseif($funcName == "get_specialist_doctor")
{

	$returnArray= array();

	$sql ="select * from specialization where id='". $_REQUEST['specialization_id'] ."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);
	$returnArray['per_visit_change'] = $row['per_visit_change'];

	$sql = "select * from doctor where is_active = '1' and specialization_id='".$_REQUEST['specialization_id']."' and type in ('d','jd')";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$returnArray['success'] = false;
	$returnArray['msg'] = "No specialist found";
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "no-doctor.png";

		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = stripslashes($row['dob']);
		$returnArray['data'][$i]['sex'] = stripslashes($row['sex']);
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['designation'] = stripslashes($row['designation']);
		$returnArray['data'][$i]['certificate'] = stripslashes($row['certificate']);
		$returnArray['data'][$i]['phy_id'] = stripslashes($row['phy_id']);
		$returnArray['data'][$i]['specialization_id'] = stripslashes($row['specialization_id']);

		$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$row['id']."' and review.is_active='1'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$j=0;
		$returnArray['data'][$i]['review']= array();
		while($row2 = mysqli_fetch_array($res2))
		{
			$returnArray['data'][$i]['review'][$j]['rating']=$row2['rating'];

			$returnArray['data'][$i]['review'][$j]['f_name']=stripslashes($row2['f_name']);
			$returnArray['data'][$i]['review'][$j]['l_name']=stripslashes($row2['l_name']);
			$returnArray['data'][$i]['review'][$j]['comments']=stripslashes($row2['comments']);
			$j++;
		}

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
		$returnArray['success'] = true;
		$returnArray['msg'] = "specialist found";
	}
	
	echo json_encode($returnArray);
}
elseif($funcName == "get_specialist_nurse")
{

	$returnArray= array();

	$sql ="select * from specialization where id='". $_REQUEST['specialization_id'] ."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);
	$returnArray['per_visit_change'] = $row['per_visit_change'];

	$sql = "select * from doctor where is_active = '1' and specialization_id='".$_REQUEST['specialization_id']."' and type in ('jn','sn')";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$returnArray['success'] = false;
	$returnArray['msg'] = "No nurse found";
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "no-doctor.png";

		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = stripslashes($row['dob']);
		$returnArray['data'][$i]['sex'] = stripslashes($row['sex']);
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['designation'] = stripslashes($row['designation']);
		$returnArray['data'][$i]['certificate'] = stripslashes($row['certificate']);
		$returnArray['data'][$i]['phy_id'] = stripslashes($row['phy_id']);
		$returnArray['data'][$i]['specialization_id'] = stripslashes($row['specialization_id']);

		$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$row['id']."' and review.is_active='1'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$j=0;
		$returnArray['data'][$i]['review']= array();
		while($row2 = mysqli_fetch_array($res2))
		{
			$returnArray['data'][$i]['review'][$j]['rating']=$row2['rating'];

			$returnArray['data'][$i]['review'][$j]['f_name']=stripslashes($row2['f_name']);
			$returnArray['data'][$i]['review'][$j]['l_name']=stripslashes($row2['l_name']);
			$returnArray['data'][$i]['review'][$j]['comments']=stripslashes($row2['comments']);
			$j++;
		}

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
		$returnArray['success'] = true;
		$returnArray['msg'] = "specialist found";
	}
	
	echo json_encode($returnArray);
}
elseif($funcName == "search_doctor")
{
	$returnArray= array();
	$sql = "select * 
			from doctor 
			where is_active = '1' and type in ('d','jd') and 
				( f_name like '%".$_REQUEST['search_text']."%' or 
				l_name like'%".$_REQUEST['search_text']."%' or 
				mobile like '%".$_REQUEST['search_text']."%' or 
				email_id like'%".$_REQUEST['search_text']."%')";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$returnArray['success'] = false;
	$returnArray['sql'] = $sql;
	$returnArray['msg'] = "No specialist found";
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "no-doctor.png";
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = stripslashes($row['dob']);
		$returnArray['data'][$i]['sex'] = stripslashes($row['sex']);
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['designation'] = stripslashes($row['designation']);
		$returnArray['data'][$i]['certificate'] = stripslashes($row['certificate']);
		$returnArray['data'][$i]['phy_id'] = stripslashes($row['phy_id']);
		$returnArray['data'][$i]['specialization_id'] = stripslashes($row['specialization_id']);
		

		$sql ="select * from specialization where id='". $row['specialization_id'] ."'";
		$res1 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$row1 = mysqli_fetch_array($res1);
		$returnArray['data'][$i]['per_visit_change'] = $row1['per_visit_change'];

		$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$row['id']."' and review.is_active='1'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$j=0;
		$returnArray['data'][$i]['review']= array();
		while($row2 = mysqli_fetch_array($res2))
		{
			$returnArray['data'][$i]['review'][$j]['rating']=$row2['rating'];

			$returnArray['data'][$i]['review'][$j]['f_name']=stripslashes($row2['f_name']);
			$returnArray['data'][$i]['review'][$j]['l_name']=stripslashes($row2['l_name']);
			$returnArray['data'][$i]['review'][$j]['comments']=stripslashes($row2['comments']);
			$j++;
		}

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
		$returnArray['success'] = true;
		$returnArray['msg'] = "specialist found";
	}
	
	echo json_encode($returnArray);
}
elseif($funcName == "search_nurse")
{

	$returnArray= array();
	$sql = "select * 
			from doctor 
			where is_active = '1' and type in ('jn','sn') and 
				( f_name like '%".$_REQUEST['search_text']."%' or 
				l_name like'%".$_REQUEST['search_text']."%' or 
				mobile like '%".$_REQUEST['search_text']."%' or 
				email_id like'%".$_REQUEST['search_text']."%')";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$returnArray['success'] = false;
	$returnArray['sql'] = $sql;
	$returnArray['msg'] = "No specialist found";
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "no-doctor.png";
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = stripslashes($row['dob']);
		$returnArray['data'][$i]['sex'] = stripslashes($row['sex']);
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['designation'] = stripslashes($row['designation']);
		$returnArray['data'][$i]['certificate'] = stripslashes($row['certificate']);
		$returnArray['data'][$i]['phy_id'] = stripslashes($row['phy_id']);
		$returnArray['data'][$i]['specialization_id'] = stripslashes($row['specialization_id']);
		

		$sql ="select * from specialization where id='". $row['specialization_id'] ."'";
		$res1 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$row1 = mysqli_fetch_array($res1);
		$returnArray['data'][$i]['per_visit_change'] = $row1['per_visit_change'];

		$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$row['id']."' and review.is_active='1'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$j=0;
		$returnArray['data'][$i]['review']= array();
		while($row2 = mysqli_fetch_array($res2))
		{
			$returnArray['data'][$i]['review'][$j]['rating']=$row2['rating'];

			$returnArray['data'][$i]['review'][$j]['f_name']=stripslashes($row2['f_name']);
			$returnArray['data'][$i]['review'][$j]['l_name']=stripslashes($row2['l_name']);
			$returnArray['data'][$i]['review'][$j]['comments']=stripslashes($row2['comments']);
			$j++;
		}

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
		$returnArray['success'] = true;
		$returnArray['msg'] = "specialist found";
	}
	
	echo json_encode($returnArray);
}
elseif($funcName == "get_doctor_data_by_id")
{//print_r($_REQUEST);
	$returnArray= array();
	$sql = "select * from doctor where id='".$_REQUEST['doc_id']."'";

	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	
	$returnArray['data']['id'] = $row['id'];
	$returnArray['data']['f_name'] = stripslashes($row['f_name']);
	$returnArray['data']['l_name'] = stripslashes($row['l_name']);
	$returnArray['data']['mobile'] = stripslashes($row['mobile']);
	$returnArray['data']['email_id'] = stripslashes($row['email_id']);
	$returnArray['data']['dob'] = stripslashes($row['dob']);
	$returnArray['data']['sex'] = stripslashes($row['sex']);
	$returnArray['data']['image'] = stripslashes($row['image']);
	$returnArray['data']['designation'] = stripslashes($row['designation']);
	$returnArray['data']['certificate'] = stripslashes($row['certificate']);
	$returnArray['data']['phy_id'] = stripslashes($row['phy_id']);
	$returnArray['data']['about'] = stripslashes($row['about']);
	$returnArray['data']['specialization_id'] = stripslashes($row['specialization_id']);
	$returnArray['data']['type']= stripslashes($row['type']);
	$returnArray['data']['service_id'] = stripslashes($row['service_id']);
	$returnArray['data']['age'] = date('Y') - date('Y',strtotime($row['dob']));

	$sql = "select * from nurse_fees where nurse_id = '". $_REQUEST['doc_id'] ."'";
	$resy = mysqli_query($link, $sql) or die(mysqli_error($link));
	$rowy = mysqli_fetch_array($resy);
	$returnArray['data']['fees'] = $rowy['fees'];

	$selected_service_ids = array();
	$sql = "select service_id from nurse_service_selected where nurse_id='".$_REQUEST['doc_id']."'";
	$resx = mysqli_query($link, $sql) or die(mysqli_error($link));
	while($rowx = mysqli_fetch_array($resx))
	{
		array_push($selected_service_ids, $rowx['service_id']);
	}
	$returnArray['data']['selected_service_ids'] = $selected_service_ids;

	$selected_service_titles = array();
	foreach ($selected_service_ids as $selected_service_id) {
		# code...
		$sql = "select * from nurse_provided_services where id='$selected_service_id'";
		$resx = mysqli_query($link, $sql) or die(mysqli_error($link));
		$rowx = mysqli_fetch_array($resx);
		array_push($selected_service_titles, stripslashes($rowx['title']))	;	
	}
	$returnArray['data']['nurse_provided_service'] = $selected_service_titles;


	$returnArray['data']['language_known_ids'] = "";
	$lang_array = "Not Specified.";
	if($row['language_known'])
	{
		$lang = json_decode($row['language_known']);
		$lang = implode(",", $lang);	

		$returnArray['data']['language_known_ids'] = $lang;

		$sql = "select * from doctor_language_known where id in ($lang)" ;
		$res3 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$lang_array = array();
		while($row3 = mysqli_fetch_array($res3))
		{
			array_push($lang_array, $row3['title']); 
		}
		$lang_array = implode(", ", $lang_array);
	}
	$returnArray['data']['language_known'] = stripslashes($lang_array);

	$sql = "select * from specialization where id='".$row['specialization_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['data']['specialization_title'] = stripslashes($row['title']);
	$returnArray['data']['per_visit_change'] = stripslashes($row['per_visit_change']);

	$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$_REQUEST['doc_id']."' and review.is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	$returnArray['data']['review']= array();
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['review'][$i]['rating']=$row['rating'];

		$returnArray['data']['review'][$i]['f_name']=stripslashes($row['f_name']);
		$returnArray['data']['review'][$i]['l_name']=stripslashes($row['l_name']);
		$returnArray['data']['review'][$i]['comments']=stripslashes($row['comments']);
		$i++;
	}

	$returnArray['success'] = true;
	$returnArray['msg'] = "Specialist found";
	
	echo json_encode($returnArray);
}
elseif($funcName == "doctor_registration")
{
	$returnArray= array();

	$sql = "select id from users where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_u = mysqli_num_rows($res);

	$sql = "select id from doctor where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_d = mysqli_num_rows($res);
	
	if(($num_u + $num_d) > 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email is already been registerd.";
	}
	else
	{
		$certificate = "";
		if($_FILES['certificate']['name'])
		{		
			$certificate = time().$_FILES['certificate']['name'];
		
			move_uploaded_file($_FILES['certificate']['tmp_name'],"../certificate/".$certificate);
		}

		$sql = "insert doctor set
			f_name='".addslashes(trim($_REQUEST['f_name']))."',
			l_name='".addslashes(trim($_REQUEST['l_name']))."',
			mobile='".addslashes(trim($_REQUEST['mobile']))."',
			email_id='".addslashes(trim($_REQUEST['email']))."',
			dob='".addslashes(trim($_REQUEST['dob']))."',
			sex='".addslashes(trim($_REQUEST['sex']))."',
			password='".createHashAndSalt($_REQUEST['password'])."',
			phy_id = '".addslashes(trim($_REQUEST['physician_id']))."',
			about = '".addslashes(trim($_REQUEST['about']))."',
			designation = '".addslashes(trim($_REQUEST['designation']))."',
			specialization_id = '".$_REQUEST['specialization_id']."',			
			image = '',
			certificate = '$certificate',
			language_known = '".json_encode($_REQUEST['lang'])."',
			create_date=NOW(),
			is_active='0'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));			

		if($res)
		{
			$insert_doctor_id = mysqli_insert_id($link);
			$digits = 4;
			$code =  rand(pow(10, $digits-1), pow(10, $digits)-1);
			$sql = "select value_text from site_info where id='16'";
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
			$row = mysqli_fetch_array($res2);
			$playstore_link = $row['value_text'];
			$to = $_REQUEST['email'];
			$subject = "Welcome Dr. ".$_REQUEST['f_name'].", Actora is willing to verify your identity!";
			$content = $content = '<html>
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
      <b>Dear Dr. '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'].'</b><br/>      
​       You have requested on line access to our App. We have generated a One-Time Pass-code for you which will verify that you have requested access. This One-Time pass-code is time sensitive and valid for a single use. On subsequent login, you will not need to enter this One-Time Pass code.​
<br/>
<br/>
  <b>Your One-Time Passcode is '.$code.'</b>
  <br/>
  <br/>
  Please enter this code into that you have accessed and thank you fopr utilizing our service.
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
			$headers[] = 'To: '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'] .' <'.$_REQUEST['email'].'>';
			$headers[] = 'From: Actora registration <no-reply@actora.us>';
			//$headers[] = 'Cc: birthdayarchive@example.com';			
			$headers[] = 'Bcc: sksafdar@gmail.com';

			// Mail it
			mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;
			$returnArray['code'] = $code;
			$returnArray['insert_doctor_id'] = $insert_doctor_id;
			$returnArray['msg'] = "Please check your email for One Time Password (OTP).";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later.";
		}		
	}
	echo json_encode($returnArray);
	
}
elseif($funcName == "activate_doctor")
{
	$returnArray= array();
	$sql = "update doctor set is_active='1' where id='".$_REQUEST['doctor_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Activation Successful.";

		$sql = "select value_text from site_info where id='16'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res2);
		$playstore_link = $row['value_text'];

		$sql = "select * from doctor where id='".$_REQUEST['doctor_id']."'";
		$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row3 = mysqli_fetch_array($res3);


		$to = $row3['email_id'];
		$subject = "Welcome Dr. ".$row3['f_name'].", your Actora registration is Successful!";
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
  Thank you for creating your account at <a href="https://www.actora.us">Actora</a>. Please use following Email for login. ​
<br/><b>'. $row3['email_id'] .'</b>
<br/>  
  <br/>
If you have any questions regarding your account, please contact us. We will be happy to help you.  
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
		$headers[] = 'From: Actora Registration<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Activation not Successful.";
	}
	echo json_encode($returnArray);

}
elseif($funcName == 'specialization_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from specialization where is_active='1' order by title asc ";
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
elseif($funcName=='nurse_provided_service')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from nurse_provided_services where is_active='1' order by title asc ";
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
elseif($funcName == "get_service_nurse")
{

	$returnArray= array();

	//$sql ="select * from nurse_provided_services where id='". $_REQUEST['service_id'] ."'";
	//$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	//$row = mysqli_fetch_array($res);
	$returnArray['per_visit_change'] = "TO BE WORKED ON";

	$searched_nurse_id = array();
	$sql = "select nurse_id from nurse_service_selected where service_id = '".$_REQUEST['service_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($res))
	{
		array_push($searched_nurse_id, $row['nurse_id']);
	}

	$searched_nurse_id = "( ".implode(", ", $searched_nurse_id)." )";

	$sql = "select * from doctor where is_active = '1' and id in $searched_nurse_id and type in ('jn','sn')";
	$returnArray['sql']=$sql; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$returnArray['success'] = false;
	$returnArray['msg'] = "No nurse found";
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "no-nurse.png";

		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = stripslashes($row['dob']);
		$returnArray['data'][$i]['sex'] = stripslashes($row['sex']);
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['designation'] = stripslashes($row['designation']);
		$returnArray['data'][$i]['certificate'] = stripslashes($row['certificate']);
		$returnArray['data'][$i]['phy_id'] = stripslashes($row['phy_id']);
		$returnArray['data'][$i]['specialization_id'] = stripslashes($row['specialization_id']);

		$sql = "select * from nurse_fees where nurse_id='".$row['id']."'";
		$resx = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$rowx = mysqli_fetch_array($resx);
		$returnArray['data'][$i]['fees'] = $rowx['fees'];


		$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$row['id']."' and review.is_active='1'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$j=0;
		$returnArray['data'][$i]['review']= array();
		while($row2 = mysqli_fetch_array($res2))
		{
			$returnArray['data'][$i]['review'][$j]['rating']=$row2['rating'];

			$returnArray['data'][$i]['review'][$j]['f_name']=stripslashes($row2['f_name']);
			$returnArray['data'][$i]['review'][$j]['l_name']=stripslashes($row2['l_name']);
			$returnArray['data'][$i]['review'][$j]['comments']=stripslashes($row2['comments']);
			$j++;
		}

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
		$returnArray['success'] = true;
		$returnArray['msg'] = "specialist found";
	}
	
	echo json_encode($returnArray);
}
elseif($funcName == "get_service_nurse_day_avail")
{

	$returnArray= array();

	//$sql ="select * from nurse_provided_services where id='". $_REQUEST['service_id'] ."'";
	//$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	//$row = mysqli_fetch_array($res);
	$returnArray['per_visit_change'] = "TO BE WORKED ON";

	$nurse_id_as_day = array();
	$sql = "select doc_id from doctor_video_timing where day_id='". $_REQUEST['day_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($res))
	{
		array_push($nurse_id_as_day, $row['doc_id']);
	}
	$returnArray['nurse_id_as_day'] =$nurse_id_as_day;

	$searched_nurse_id = array();
	$sql = "select nurse_id from nurse_service_selected where service_id = '".$_REQUEST['service_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($res))
	{
		array_push($searched_nurse_id, $row['nurse_id']);
	}	

	$returnArray['searched_nurse_id'] =$searched_nurse_id;

	$result = array_intersect($nurse_id_as_day, $searched_nurse_id);
	if(count($result) > 0)
	$result = implode(", ", $result);
	else $result = 0;
	$sql = "select * from doctor where is_active = '1' and type in ('jn','sn') and id in ($result)";
	$returnArray['sql']=$sql; 

	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$returnArray['success'] = false;
	$returnArray['msg'] = "No nurse found";
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "no-nurse.png";

		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = stripslashes($row['dob']);
		$returnArray['data'][$i]['sex'] = stripslashes($row['sex']);
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['designation'] = stripslashes($row['designation']);
		$returnArray['data'][$i]['certificate'] = stripslashes($row['certificate']);
		$returnArray['data'][$i]['phy_id'] = stripslashes($row['phy_id']);
		$returnArray['data'][$i]['specialization_id'] = stripslashes($row['specialization_id']);

		$sql = "select * from nurse_fees where nurse_id='".$row['id']."'";
		$resx = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$rowx = mysqli_fetch_array($resx);
		$returnArray['data'][$i]['fees'] = $rowx['fees'];


		$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$row['id']."' and review.is_active='1'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$j=0;
		$returnArray['data'][$i]['review']= array();
		while($row2 = mysqli_fetch_array($res2))
		{
			$returnArray['data'][$i]['review'][$j]['rating']=$row2['rating'];

			$returnArray['data'][$i]['review'][$j]['f_name']=stripslashes($row2['f_name']);
			$returnArray['data'][$i]['review'][$j]['l_name']=stripslashes($row2['l_name']);
			$returnArray['data'][$i]['review'][$j]['comments']=stripslashes($row2['comments']);
			$j++;
		}

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
		$returnArray['success'] = true;
		$returnArray['msg'] = "specialist found";
	}
	
	echo json_encode($returnArray);
}
elseif($funcName == 'language_known')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from doctor_language_known where is_active='1' order by title asc ";
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
elseif($funcName == 'submit_review')
{
	$returnArray= array();
	$sql = "insert review set
			doctor_id='".$_REQUEST['doctor_id']."',
			user_id='".$_REQUEST['user_id']."',
			rating='".$_REQUEST['rating']."',
			comments='".addslashes(trim($_REQUEST['comments']))."',
			is_active='1',
			created_date=NOW()";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Thanks for provide a review.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "view_review")
{
	$returnArray= array();
	$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$_REQUEST['doc_id']."' and review.is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['rating']=$row['rating'];
		$returnArray['data'][$i]['image']=stripslashes($row['image']);
		$returnArray['data'][$i]['f_name']=stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name']=stripslashes($row['l_name']);
		$returnArray['data'][$i]['comments']=nl2br(stripslashes($row['comments']));
		$i++;
	}
	$returnArray['success'] = true;
	$returnArray['msg'] = "Specialist found";
	
	echo json_encode($returnArray);
}

elseif($funcName == "get_chat_users")
{

}

elseif($funcName == "change_password")
{
	$returnArray = array();
	if(trim($_REQUEST['new_password']) == trim($_REQUEST['re_password']))
	{
		$sql = "select * from users where id='".trim($_REQUEST['user_id'])."'";
		//echo $sql;
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res);

		$check = verifyPasswordHash($_REQUEST['old_password'],$row['password']);
		$returnArray['check'] = $check;
		if( $check)
		{
			$sql = "update users 
						set password='".createHashAndSalt(trim($_REQUEST['new_password']))."'
						where id='".trim($_REQUEST['user_id'])."'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
			if($res)
			{
				$returnArray['success'] = true;				
				$returnArray['msg'] = "Password has been changed successfully.";
			}
			else
			{
				$returnArray['success'] = false;
				$returnArray['msg'] = "Please try again later.";
			}
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Incorrect current password.";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Your password and confirmation password do not match.";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "doctor_reset_password")
{
	$returnArray = array();

	$sql = "select * from doctor where email_id='".$_REQUEST['doctor_reset_email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$digits = 8;
		$generated_pw =  rand(pow(10, $digits-1), pow(10, $digits)-1);
		$generated_pw_enc = createHashAndSalt($generated_pw);

		$sql = "update doctor set password='$generated_pw_enc' where id='".$row['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));

		if($res)
		{
			$to = $_REQUEST['doctor_reset_email'];
			$subject = "Welcome Dr. ".$row['f_name'].", Actora is resetting your password!";
			$content = $content = '<html>
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
      <b>Dear Dr. '.$row['f_name'].' '.$row['l_name'].'</b><br/>      
​       You have requested on line access to our App. We have generated a One-Time Pass-code for you which will verify that you have requested access. This One-Time pass-code is time sensitive. Please chenge the password after first login .​
<br/>
<br/>
  <b>Your Password is '.$generated_pw.'</b>
  <br/>
  <br/>
  Please enter this password into that you have accessed and thank you fopr utilizing our service.
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
			$headers[] = 'To: '.$row['f_name'].' '.$row['l_name'] .' <'.$_REQUEST['doctor_reset_email'].'>';
			$headers[] = 'From: Actora Reset password <no-reply@actora.us>';
			//$headers[] = 'Cc: birthdayarchive@example.com';			
			$headers[] = 'Bcc: himadri.roy@rediffmail.com';

			// Mail it
			mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;
			$returnArray['msg'] = "Please check your mail!";
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Server error, Please try again later!!!";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email not found!!!";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "nurse_reset_password")
{
	$returnArray = array();

	$sql = "select * from doctor where email_id='".$_REQUEST['doctor_reset_email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$digits = 8;
		$generated_pw =  rand(pow(10, $digits-1), pow(10, $digits)-1);
		$generated_pw_enc = createHashAndSalt($generated_pw);

		$sql = "update doctor set password='$generated_pw_enc' where id='".$row['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));

		if($res)
		{
			$to = $_REQUEST['doctor_reset_email'];
			$subject = "Welcome ".$row['f_name'].", Actora is resetting your password!";
			$content = $content = '<html>
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
      <b>Dear '.$row['f_name'].' '.$row['l_name'].'</b><br/>      
​       You have requested on line access to our App. We have generated a One-Time Pass-code for you which will verify that you have requested access. This One-Time pass-code is time sensitive. Please chenge the password after first login .​
<br/>
<br/>
  <b>Your Password is '.$generated_pw.'</b>
  <br/>
  <br/>
  Please enter this password into that you have accessed and thank you fopr utilizing our service.
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
			$headers[] = 'To: '.$row['f_name'].' '.$row['l_name'] .' <'.$_REQUEST['doctor_reset_email'].'>';
			$headers[] = 'From: Actora Reset password <no-reply@actora.us>';
			//$headers[] = 'Cc: birthdayarchive@example.com';			
			$headers[] = 'Bcc: himadri.roy@rediffmail.com';

			// Mail it
			mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;
			$returnArray['msg'] = "Please check your mail!";
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Server error, Please try again later!!!";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email not found!!!";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "doctor_update_profile")
{
	$returnArray= array();
	//print_r($_REQUEST);

	$certificateSql = "";
	if($_FILES['certificate']['name'])
	{		
		$certificate = time().$_FILES['certificate']['name'];
	
		move_uploaded_file($_FILES['certificate']['tmp_name'],"../certificate/".$certificate);
		$certificateSql = "certificate = '$certificate',";
	}

	$sql = "update doctor set
		f_name='".addslashes(trim($_REQUEST['f_name']))."',
		l_name='".addslashes(trim($_REQUEST['l_name']))."',
		mobile='".addslashes(trim($_REQUEST['mobile']))."',
		email_id='".addslashes(trim($_REQUEST['email']))."',
		dob='".addslashes(trim($_REQUEST['dob']))."',
		sex='".addslashes(trim($_REQUEST['sex']))."',
		password='".createHashAndSalt($_REQUEST['password'])."',
		phy_id = '".addslashes(trim($_REQUEST['physician_id']))."',
		about = '".addslashes(trim($_REQUEST['about']))."',
		type = '".addslashes(trim($_REQUEST['nurse_type']))."',		
		designation = '".addslashes(trim($_REQUEST['designation']))."',
		specialization_id = '".$_REQUEST['specialization_id']."',
		$certificateSql
		language_known = '".json_encode($_REQUEST['lang'])."' where id='".$_REQUEST['doctor_id']."'";

		//service_id = '".addslashes(trim($_REQUEST['service_id']))."',
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);			

	if($res)
	{
		if(isset($_REQUEST['nurse_type']))
		{
			$sql = "delete from nurse_fees where nurse_id='".$_REQUEST['doctor_id']."'";
			mysqli_query($link, $sql) or die(mysqli_error($link).$sql);


			//======nurse fees entry========//
			$sql = "SELECT * FROM `nurse_fees_structure`";
			$res_n = mysqli_query($link, $sql) or die(mysqli_error($link));
			while($row_n = mysqli_fetch_array($res_n))
			{
				$sql = "insert nurse_fees set
					nurse_id='".$_REQUEST['doctor_id']."',
					book_min='".$row_n['book_min']."',
					fees='".$row_n['fees']."'";
				mysqli_query($link, $sql) or die(mysqli_error($link));
			}
			//===============================//
		}

		$returnArray['success'] = true;
		$returnArray['msg'] = "Profile has been successfully updated.";

		if(count($_REQUEST['service_ids']) > 0)
		{
			$sql = "delete from nurse_service_selected where nurse_id='".$_REQUEST['doctor_id']."'";
			mysqli_query($link, $sql) or die(mysqli_error($link));

			foreach($_REQUEST['service_ids'] as $service_id)
			{
				$sql = "insert nurse_service_selected set
				service_id='$service_id', 
				nurse_id='".$_REQUEST['doctor_id']."'";
				mysqli_query($link, $sql) or die(mysqli_error($link));
			}
		}
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";
	}		
	echo json_encode($returnArray);	
}
elseif($funcName == "user_change_image")
{
	$returnArray= array();

	$image = "";
	if($_FILES['file']['name'])
	{		
		$image = time().".jpg";
	
		move_uploaded_file($_FILES['file']['tmp_name'],"../images/doctor_image/".$image);
		$returnArray['image']= $image;
	}

	$sql = "update doctor set image ='".$image ."' where id='".$_REQUEST['user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Profile has been successfully updated.";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";
	}		
	echo json_encode($returnArray);	

}

elseif($funcName == "get_doctor_data_by_appt_id")
{//print_r($_REQUEST);
	$returnArray= array();



	$sql = "select doctor.* from doctor
			join doctor_appointment on (doctor_appointment.doc_id = doctor.id) where doctor_appointment.id='".$_REQUEST['appt_id']."'";

	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	
	$returnArray['data']['id'] = $row['id'];
	$returnArray['data']['f_name'] = stripslashes($row['f_name']);
	$returnArray['data']['l_name'] = stripslashes($row['l_name']);
	$returnArray['data']['mobile'] = stripslashes($row['mobile']);
	$returnArray['data']['email_id'] = stripslashes($row['email_id']);
	$returnArray['data']['dob'] = stripslashes($row['dob']);
	$returnArray['data']['sex'] = stripslashes($row['sex']);
	$returnArray['data']['image'] = stripslashes($row['image']);
	$returnArray['data']['designation'] = stripslashes($row['designation']);
	$returnArray['data']['certificate'] = stripslashes($row['certificate']);
	$returnArray['data']['phy_id'] = stripslashes($row['phy_id']);
	$returnArray['data']['about'] = stripslashes($row['about']);
	$returnArray['data']['type'] = stripslashes($row['type']);
	$returnArray['data']['specialization_id'] = stripslashes($row['specialization_id']);
	$returnArray['data']['age'] = date('Y') - date('Y',strtotime($row['dob']));

	$returnArray['data']['language_known_ids'] = "";
	$lang_array = "Not Specified.";
	if($row['language_known'])
	{
		$lang = json_decode($row['language_known']);
		$lang = implode(",", $lang);	

		$returnArray['data']['language_known_ids'] = $lang;

		$sql = "select * from doctor_language_known where id in ($lang)" ;
		$res3 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$lang_array = array();
		while($row3 = mysqli_fetch_array($res3))
		{
			array_push($lang_array, $row3['title']); 
		}
		$lang_array = implode(", ", $lang_array);
	}
	$returnArray['data']['language_known'] = stripslashes($lang_array);

	$sql = "select * from specialization where id='".$row['specialization_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['data']['specialization_title'] = stripslashes($row['title']);
	$returnArray['data']['per_visit_change'] = stripslashes($row['per_visit_change']);

	$sql = "select * from review join users on (review.user_id= users.id) where doctor_id='".$_REQUEST['doc_id']."' and review.is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	$returnArray['data']['review']= array();
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['review'][$i]['rating']=$row['rating'];

		$returnArray['data']['review'][$i]['f_name']=stripslashes($row['f_name']);
		$returnArray['data']['review'][$i]['l_name']=stripslashes($row['l_name']);
		$returnArray['data']['review'][$i]['comments']=stripslashes($row['comments']);
		$i++;
	}

	$returnArray['success'] = true;
	$returnArray['msg'] = "Specialist found";
	
	echo json_encode($returnArray);
}

elseif($funcName == "jr_doc_registration")
{
	$returnArray= array();

	$sql = "select id from users where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_u = mysqli_num_rows($res);

	$sql = "select id from doctor where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_d = mysqli_num_rows($res);
	
	if(($num_u + $num_d) > 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email is already been registerd.";
	}
	else
	{
		$certificate = "";
		if($_FILES['certificate']['name'])
		{		
			$certificate = time().$_FILES['certificate']['name'];
		
			move_uploaded_file($_FILES['certificate']['tmp_name'],"../certificate/".$certificate);
		}

		$digits = 6;
		$code =  rand(pow(10, $digits-1), pow(10, $digits)-1);

		$sql = "insert doctor set
			f_name='".addslashes(trim($_REQUEST['f_name']))."',
			l_name='".addslashes(trim($_REQUEST['l_name']))."',
			mobile='".addslashes(trim($_REQUEST['mobile']))."',
			email_id='".addslashes(trim($_REQUEST['email']))."',
			dob='".addslashes(trim($_REQUEST['dob']))."',
			sex='".addslashes(trim($_REQUEST['sex']))."',
			password='".createHashAndSalt($code)."',
			phy_id = '".addslashes(trim($_REQUEST['physician_id']))."',
			about = '".addslashes(trim($_REQUEST['about']))."',
			designation = '".addslashes(trim($_REQUEST['designation']))."',
			specialization_id = '".$_REQUEST['specialization_id']."',			
			image = '',
			certificate = '$certificate',
			language_known = '".json_encode($_REQUEST['lang'])."',
			refered_by='".$_REQUEST['refered_by']."',
			create_date=NOW(),
			type='jd',
			is_active='1'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));			

		if($res)
		{
			$insert_doctor_id = mysqli_insert_id($link);

			$sql = "select value_text from site_info where id='16'";
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
			$row = mysqli_fetch_array($res2);
			$playstore_link = $row['value_text'];

			$to = $_REQUEST['email'];
			$subject = "Welcome Dr. ".$_REQUEST['f_name'].", Actora is willing to add you in Actora Family!";
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
      <b>Dear Dr. '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'].'</b><br/>      
  You have been invited to join at <a href="https://www.actora.us">Actora</a>. Family Please use current Email and following Password for login. ​
<br/><b>'. $code .'</b>
<br/>  
  <br/>
If you have any questions regarding your account, please contact us. We will be happy to help you.  
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
			$headers[] = 'To: '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'] .' <'.$_REQUEST['email'].'>';
			$headers[] = 'From: Actora registration <no-reply@actora.us>';
			//$headers[] = 'Cc: birthdayarchive@example.com';			
			$headers[] = 'Bcc: himadri.roy@rediffmail.com';

			// Mail it
			mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;			
			$returnArray['insert_doctor_id'] = $insert_doctor_id;
			$returnArray['msg'] = "Thank you for adding a Junor Physician.";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later.";
		}		
	}
	echo json_encode($returnArray);
	
}

elseif($funcName == "nurse_registration")
{
	$returnArray= array();

	$sql = "select id from users where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_u = mysqli_num_rows($res);

	$sql = "select id from doctor where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_d = mysqli_num_rows($res);
	
	if(($num_u + $num_d) > 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email is already been registerd.";
	}
	else
	{
		$certificate = "";
		if($_FILES['certificate']['name'])
		{		
			$certificate = time().$_FILES['certificate']['name'];
		
			move_uploaded_file($_FILES['certificate']['tmp_name'],"../certificate/".$certificate);
		}

		$digits = 6;
		$code =  rand(pow(10, $digits-1), pow(10, $digits)-1);

		$sql = "insert doctor set
			f_name='".addslashes(trim($_REQUEST['f_name']))."',
			l_name='".addslashes(trim($_REQUEST['l_name']))."',
			mobile='".addslashes(trim($_REQUEST['mobile']))."',
			email_id='".addslashes(trim($_REQUEST['email']))."',
			dob='".addslashes(trim($_REQUEST['dob']))."',
			sex='".addslashes(trim($_REQUEST['sex']))."',
			password='".createHashAndSalt($code)."',
			phy_id = '".addslashes(trim($_REQUEST['physician_id']))."',
			about = '".addslashes(trim($_REQUEST['about']))."',
			designation = '".addslashes(trim($_REQUEST['designation']))."',
			specialization_id = '".$_REQUEST['specialization_id']."',			
			image = '',
			certificate = '$certificate',
			language_known = '".json_encode($_REQUEST['lang'])."',
			refered_by='".$_REQUEST['refered_by']."',
			create_date=NOW(),
			type = '".$_REQUEST['nurse_type']."',
			service_id = '".$_REQUEST['service_id']."',
			is_active = '1'";

		$res = mysqli_query($link, $sql) or die(mysqli_error($link));			

		if($res)
		{
			$insert_doctor_id = mysqli_insert_id($link);

			//======nurse fees entry========//
			$sql = "SELECT * FROM `nurse_fees_structure`";
			$res_n = mysqli_query($link, $sql) or die(mysqli_error($link));
			while($row_n = mysqli_fetch_array($res_n))
			{
				$sql = "insert nurse_fees set
					nurse_id='$insert_doctor_id',
					book_min='".$row_n['book_min']."',
					fees='".$row_n['fees']."'";
				mysqli_query($link, $sql) or die(mysqli_error($link));
			}
			//===============================//

			$sql = "select value_text from site_info where id='16'";
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
			$row = mysqli_fetch_array($res2);
			$playstore_link = $row['value_text'];

			$to = $_REQUEST['email'];
			$subject = "Welcome ".$_REQUEST['f_name'].", Actora is willing to add you in Actora Family!";
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
      <b>Dear . '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'].'</b><br/>      
  You have been invited to join at <a href="https://www.actora.us">Actora</a>. Family Please use current Email and following Password for login. ​
<br/><b>'. $code .'</b>
<br/>  
  <br/>
If you have any questions regarding your account, please contact us. We will be happy to help you.  
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
			$headers[] = 'To: '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'] .' <'.$_REQUEST['email'].'>';
			$headers[] = 'From: Actora registration <no-reply@actora.us>';
			//$headers[] = 'Cc: birthdayarchive@example.com';			
			$headers[] = 'Bcc: himadri.roy@rediffmail.com';

			// Mail it
			mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;
			$returnArray['insert_doctor_id'] = $insert_doctor_id;
			$returnArray['msg'] = "Thank you for adding a Nurse.";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later.";
		}		
	}
	echo json_encode($returnArray);
	
}
elseif($funcName == "nurse_registration_non_refer")
{
	$returnArray= array();

	$sql = "select id from users where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_u = mysqli_num_rows($res);

	$sql = "select id from doctor where email_id='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_d = mysqli_num_rows($res);
	
	if(($num_u + $num_d) > 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email is already been registerd.";
	}
	else
	{
		$certificate = "";
		if($_FILES['certificate']['name'])
		{		
			$certificate = time().$_FILES['certificate']['name'];
		
			move_uploaded_file($_FILES['certificate']['tmp_name'],"../certificate/".$certificate);
		}

		$sql = "insert doctor set
			f_name='".addslashes(trim($_REQUEST['f_name']))."',
			l_name='".addslashes(trim($_REQUEST['l_name']))."',
			mobile='".addslashes(trim($_REQUEST['mobile']))."',
			email_id='".addslashes(trim($_REQUEST['email']))."',
			dob='".addslashes(trim($_REQUEST['dob']))."',
			sex='".addslashes(trim($_REQUEST['sex']))."',
			password='".createHashAndSalt($_REQUEST['password'])."',
			phy_id = '".addslashes(trim($_REQUEST['physician_id']))."',
			about = '".addslashes(trim($_REQUEST['about']))."',
			designation = '".addslashes(trim($_REQUEST['designation']))."',
			specialization_id = '".$_REQUEST['specialization_id']."',			
			image = '',
			certificate = '$certificate',
			language_known = '".json_encode($_REQUEST['lang'])."',
			create_date=NOW(),
			type = '".$_REQUEST['nurse_type']."',			
			is_active='0'";
			//service_id = '".$_REQUEST['service_id']."',
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));			

		if($res)
		{
			$insert_doctor_id = mysqli_insert_id($link);

			//======nurse fees entry========//
			$sql = "SELECT * FROM `nurse_fees_structure`";
			$res_n = mysqli_query($link, $sql) or die(mysqli_error($link));
			while($row_n = mysqli_fetch_array($res_n))
			{
				$sql = "insert nurse_fees set
					nurse_id='$insert_doctor_id',
					book_min='".$row_n['book_min']."',
					fees='".$row_n['fees']."'";
				mysqli_query($link, $sql) or die(mysqli_error($link));
			}
			//===============================//
			//========== nurse service entry =============//
			foreach($_REQUEST['service_ids'] as $service_id)
			{
				$sql = "insert nurse_service_selected set
				service_id= '$service_id', 
				nurse_id= '$insert_doctor_id'";
				mysqli_query($link, $sql) or die(mysqli_error($link));
			}

			//===========================================//
			$digits = 4;
			$code =  rand(pow(10, $digits-1), pow(10, $digits)-1);

			$sql = "select value_text from site_info where id='16'";
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
			$row = mysqli_fetch_array($res2);
			$playstore_link = $row['value_text'];

			$to = $_REQUEST['email'];
			$subject = "Welcome ".$_REQUEST['f_name'].", Actora is willing to verify your identity!";
			$content = $content = '<html>
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
      <b>Dear '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'].'</b><br/>      
​       You have requested on line access to our App. We have generated a One-Time Pass-code for you which will verify that you have requested access. This One-Time pass-code is time sensitive and valid for a single use. On subsequent login, you will not need to enter this One-Time Pass code.​
<br/>
<br/>
  <b>Your One-Time Passcode is '.$code.'</b>
  <br/>
  <br/>
  Please enter this code into that you have accessed and thank you fopr utilizing our service.
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
			$headers[] = 'To: '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'] .' <'.$_REQUEST['email'].'>';
			$headers[] = 'From: Actora registration <no-reply@actora.us>';
			//$headers[] = 'Cc: birthdayarchive@example.com';			
			$headers[] = 'Bcc: himadri.roy@rediffmail.com';

			// Mail it
			mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;
			$returnArray['code'] = $code;
			$returnArray['insert_doctor_id'] = $insert_doctor_id;
			$returnArray['msg'] = "Please check your email for One Time Password (OTP).";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later.";
		}		
	}
	echo json_encode($returnArray);
	
}
elseif($funcName == "activate_nurse")
{
	$returnArray= array();
	$sql = "update doctor set is_active='1' where id='".$_REQUEST['doctor_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Activation Successful.";

		$sql = "select value_text from site_info where id='16'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res2);
		$playstore_link = $row['value_text'];

		$sql = "select * from doctor where id='".$_REQUEST['doctor_id']."'";
		$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row3 = mysqli_fetch_array($res3);


		$to = $row3['email_id'];
		$subject = "Welcome ".$row3['f_name'].", your Actora registration is Successful!";
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
      <b>Dear '.$row3['f_name'].' '.$row3['l_name'].'</b><br/>      
  Thank you for creating your account at <a href="https://www.actora.us">Actora</a>. Please use following Email for login. ​
<br/><b>'. $row3['email_id'] .'</b>
<br/>  
  <br/>
If you have any questions regarding your account, please contact us. We will be happy to help you.  
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
		$headers[] = 'From: Actora Registration<no-reply@actora.us>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadri.roy@rediffmail.com';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Activation not Successful.";
	}
	echo json_encode($returnArray);

}
function createHashAndSalt($user_provided_password)
{
	
	$options = array(		
		'cost' => 11
	);

	$hash = password_hash($user_provided_password, PASSWORD_BCRYPT,$options);

	return $hash;
}

function verifyPasswordHash($password,$hash_and_salt)
{
	if (password_verify($password, $hash_and_salt))
	{		
		return TRUE;
	}
	else
	{
		return FALSE;
	}
		
}
?>