<?php
include("../admin_api/config.php");
include("phone_verification.php");


$funcName = $_REQUEST['func'];

if($funcName == 'user_login')
{
	//$PDID=$_REQUEST['PDID'];
	
	$returnArray= array();
	$sql = "select * from users where mobile='".trim($_REQUEST['user_login_email'])."'";
	$sql2 = "update users set PDID='".trim($_REQUEST['PDID'])."' where mobile='".trim($_REQUEST['user_login_email'])."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$res2 = mysqli_query($link, $sql2) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	$row = mysqli_fetch_array($res);
	if($num == 1)
	{
		if($row['is_active'] == 1)
		{
			if(verifyPasswordHash($_REQUEST['user_login_password'],$row['password']))
			{
				$image = $row['image'];
				if(!$image) $image = "avatar.png";
				$returnArray['success'] = true;
				$returnArray['id'] = $row["id"];
				$returnArray['f_name'] = stripslashes($row["f_name"]);
				$returnArray['l_name'] = stripslashes($row["l_name"]);
				$returnArray['email_id'] = stripslashes($row["email_id"]);
				$returnArray['mobile'] = stripslashes($row["mobile"]);
				$returnArray['sex'] = stripslashes($row["sex"]);
				$returnArray['dob'] = stripslashes($row["dob"]);
				$returnArray['image'] = $image;
				$returnArray['msg'] = "Login Successful. Please Wait...";
				$returnArray['PDID'] = stripslashes($row["PDID"]);
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
			$returnArray['msg'] = "Account has been blocked, Please contact Remote Health admin...";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Mobile number not found...";
	}
	echo json_encode($returnArray);
}
elseif($funcName == 'user_registration')
{
	$returnArray= array();
	$sql = "select id from users where mobile='".$_REQUEST['mobile']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_u = mysqli_num_rows($res);

	$sql = "select id from doctor where mobile='".$_REQUEST['mobile']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_d = mysqli_num_rows($res);

	if(($num_u + $num_d) > 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Mobile Number is already been registerd.";
	}
	else
	{
	    $sql = "insert users set
				f_name='".addslashes(trim($_REQUEST['f_name']))."',
				l_name='".addslashes(trim($_REQUEST['l_name']))."',
				mobile='".addslashes(trim($_REQUEST['mobile']))."',
				email_id='".addslashes(trim($_REQUEST['email']))."',
				dob='".addslashes(trim($_REQUEST['dob']))."',
				sex='".addslashes(trim($_REQUEST['sex']))."',
				password='".createHashAndSalt($_REQUEST['password'])."',			
				image = '$image',
				create_date=NOW(),
				is_active='0'"; // if opt send case change to 0
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
		
		if($res)
		{
			$insert_user_id = mysqli_insert_id($link); 
			
			$phoneVerification = new PhoneVerification();
			$otp_generate_reply = $phoneVerification -> send(trim($_REQUEST['mobile']));
			
			//$digits = 4;
			//$code =  rand(pow(10, $digits-1), pow(10, $digits)-1);

			$sql = "select value_text from site_info where id='16'";
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
			$row = mysqli_fetch_array($res2);
			$playstore_link = $row['value_text'];

			$to = $_REQUEST['email'];
			$subject = "Welcome ".$_REQUEST['f_name'].", Remote Health is willing to verify your identity!";
			$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="http://remotehealth.org/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Remote Health</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear '.$_REQUEST['f_name'].' '.$_REQUEST['l_name'].'</b><br/>      
       You have requested on line access to our App. We have generated a One-Time Pass-code for you which will verify that you have requested access. This One-Time pass-code is time sensitive and valid for a single use. On subsequent login, you will not need to enter this One-Time Pass code.​
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
      <img width="96" src="http://remotehealth.org/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="http://remotehealth.org/images/email_template_images/bulb.png" alt=""><br>
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
     Your Team at Remote Health!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="http://remotehealth.org/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a>  &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Remote Health does not provide medical guidance, diagnosis,<br>
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
			$headers[] = 'From: Remote Health Registration<no-reply@remotehealth.org>';
			//$headers[] = 'Cc: birthdayarchive@example.com';
			$headers[] = 'Bcc: himadrisekharroy.cse.rs@jadavpuruniversity.in';

			// Mail it
			//if($_REQUEST['email'])
			//	mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;
			$returnArray['code'] = $code;
			$returnArray['insert_user_id'] = $insert_user_id;
			$returnArray['otp_generate_reply'] = $otp_generate_reply;
			$returnArray['msg'] = "Please check your mobile inbox for One Time Password (OTP).";
			//$returnArray['msg'] = "You have successfully registered as member. Please login.";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later.";
		}
	}
	echo json_encode($returnArray);
	
}
elseif($funcName == "validate_otp")
{
	//print_r($_REQUEST);
	$returnArray= array();
	$otp_details = $_REQUEST['otp_details'];
	
	$phoneVerification = new PhoneVerification();
	$otp_reply = $phoneVerification -> verify(trim($otp_details), trim($_REQUEST['otp']));
	
	if($otp_reply->Status == "Success")
	{
		$sql = "update users set is_active='1' where id='".$_REQUEST['user_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['otp_reply'] = $otp_reply;
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['otp_reply'] = $otp_reply;
		}
	
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['otp_reply'] = $otp_reply;
	}
	echo json_encode($returnArray);
}
elseif($funcName == "activate_user")
{
	$returnArray= array();
	$sql = "update users set is_active='1' where id='".$_REQUEST['user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Activation Successful.";
        $sql = "select value_text from site_info where id='16'";
		$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res2);
		$playstore_link = $row['value_text'];

		$sql = "select * from users where id='".$_REQUEST['user_id']."'";
		$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row3 = mysqli_fetch_array($res3);


		$to = $row3['email_id'];
		$subject = "Welcome ".$row3['f_name'].", your Remote Health registration is Successful!";
		$content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="http://remotehealth.org/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Remote Health</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear '.$row3['f_name'].' '.$row3['l_name'].'</b><br/>      
​       Thank you for creating your account at <a href="https://www.remotehealth.org">Remote Health</a>. Please use following Email for login. ​
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
      <img width="96" src="http://remotehealth.org/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="http://remotehealth.org/images/email_template_images/bulb.png" alt=""><br>
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
     Your Team at Remote Health!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="http://remotehealth.org/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a>  &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Remote Health does not provide medical guidance, diagnosis,<br>
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
		$headers[] = 'From: Remote Health Registration<no-reply@remotehealth.org>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadrisekharroy.cse.rs@jadavpuruniversity.in';

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
elseif($funcName == "get_personal_health_qn")
{
	$returnArray= array(); 
	$sql = "select * from site_info where id='15'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);

	$returnArray['success'] = true;
	$returnArray['data'] = json_decode($row['value_text'], true);
	$returnArray['msg'] = "Data found";

	$sql = "select personal_health_details from users where id='".$_REQUEST['logged_user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);
	if($row['personal_health_details'])
	{
		$returnArray['data_selected'] = json_decode($row['personal_health_details'], true);
	}
	else
	{
		$returnArray['data_selected']=array();
	}
	
	echo json_encode($returnArray);
}
elseif($funcName == "save_personal_health_data")
{
	$returnArray= array(); 
	$answer = array();
	foreach($_REQUEST as $key=>$value)
	{
	    $remove_element = ["general_feel","funcName","logger_user_id"];
	    if(in_array($key, $remove_element) )
	        continue;
		$key = str_replace("q","",$key);
		$key = explode("_", $key);
	    if(!isset($answer[$key[0]]))
	        $answer[$key[0]] = array();  
	    array_push($answer[$key[0]],$value );
	}
	$answer["general_feel"]= $_REQUEST["general_feel"];
	$answer = json_encode($answer);
	//print_r($answer);
	//exit;
	$sql = "update users 
			set personal_health_details = '$answer'
			where id='".$_REQUEST['logger_user_id']."'";
	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Personal Health data has been successfully submitted.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";
	}
	echo json_encode($returnArray);
}
elseif($funcName == "get_personal_health_data")
{
	$returnArray= array(); 

	$sql = "select personal_health_details from users where id='".$_REQUEST['logger_user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);
	if($row['personal_health_details'])
	{
		$returnArray['success'] = true;
		$returnArray['data'] = json_decode($row['personal_health_details'], true);
		$returnArray['msg'] = "Data found";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['data'] = array();
		$returnArray['msg'] = "Data not found"; 
	}
	echo json_encode($returnArray);
}
elseif($funcName == "user_change_profile")
{
	if($_REQUEST['is_image_changed'])
	{
		$image = "";
		if($_FILES['file']['name'])
		{		
			$image = time().".jpg";
		
			move_uploaded_file($_FILES['file']['tmp_name'],"../images/user_images/".$image);
			$returnArray['image']= $image;
		}

		$sql = "update users set
					f_name='".addslashes(trim($_REQUEST['f_name']))."',
					l_name='".addslashes(trim($_REQUEST['l_name']))."',
					mobile='".addslashes(trim($_REQUEST['mobile']))."',					
					dob='".addslashes(trim($_REQUEST['dob']))."',
					sex='".addslashes(trim($_REQUEST['sex']))."',
					image='".$image."'
				where id='".$_REQUEST['user_id']."'";	
	}
	else
	{
		$sql = "update users set
					f_name='".addslashes(trim($_REQUEST['f_name']))."',
					l_name='".addslashes(trim($_REQUEST['l_name']))."',
					mobile='".addslashes(trim($_REQUEST['mobile']))."',
					dob='".addslashes(trim($_REQUEST['dob']))."',
					sex='".addslashes(trim($_REQUEST['sex']))."'
				where id='".$_REQUEST['user_id']."'";

	}

	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Profile has been successfully updated.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Try again later.";
	}
	echo json_encode($returnArray);
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

		if(verifyPasswordHash($_REQUEST['old_password'],$row['password']))
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
elseif($funcName == "get_user_details_by_id")
{
	$returnArray= array(); 

	$sql = "select * from users where id='".$_REQUEST['user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);

	$returnArray['success'] = true;
	$returnArray['msg'] = "Data found.";
	
	if(!$row['image']) $row['image']= 'avatar.png'; 
	if($row['dob'] != "0000-00-00")
		$row['age'] = date('Y') - date('Y',strtotime($row['dob']));
	else $row['age'] = "";
	
	if($row['sex'] == 'm') $row['gender'] = "Male";
	elseif($row['sex'] == 'f') $row['gender'] = "Female";
	elseif($row['sex'] == 'o')  $row['gender'] = "Other";
	else $row['gender'] = "";

	
	$heath_info_share_doc_ids = array();

	$sql = "select doc_id from share_health_info where user_id='". $_REQUEST['user_id'] ."'";
	$res2 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	while($row2 = mysqli_fetch_array($res2))
	{
		array_push($heath_info_share_doc_ids, $row2['doc_id']);
	}

	$row['heath_info_share_doc_ids']  = $heath_info_share_doc_ids;

	$returnArray['data'] = $row;
	
	echo json_encode($returnArray); 

}
elseif($funcName == 'share_health_info') 
{
	$returnArray = array();

	//$returnArray['data'] = $_REQUEST;

	$sql = "delete from share_health_info where user_id='". $_REQUEST['user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));


	foreach($_REQUEST['selected_doctor_ids'] as $doc_id)
	{
		$sql = "insert share_health_info set doc_id='$doc_id', user_id='". $_REQUEST['user_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	}

	$returnArray['success'] = true;
	$returnArray['msg'] = "Share successfull.";

	echo json_encode($returnArray); 
}
elseif($funcName == 'share_health_info_doc_ids') 
{
	$returnArray = array();
	$returnArray['success'] = true;

	$sql = "select * from share_health_info where user_id='". $_REQUEST['user_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i] = $row['doc_id'];		
		$i++;
	}
	$returnArray['sql'] = $sql;
	$returnArray['msg'] = "Data Found";
	echo json_encode($returnArray); 
}
elseif($funcName == "user_reset_password")
{
	$returnArray = array();
	$sql = "select * from users where email_id='".$_REQUEST['user_reset_email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$digits = 8;
		$generated_pw =  rand(pow(10, $digits-1), pow(10, $digits)-1);
		$generated_pw_enc = createHashAndSalt($generated_pw);
		$sql = "update users set password='$generated_pw_enc' where id='".$row['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));

		if($res)
		{
			$to = $_REQUEST['user_reset_email'];
			$subject = "Welcome ".$row['f_name'].", Remote Health is resetting your password!";
			$content = $content = '<html>
<head>
  <title></title>
</head>
<body style="font-family: \'Montserrat\', sans-serif; color: #9b113a;">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <th valign="top">
      <img width="65" height="65" src="http://remotehealth.org/images/email_template_images/logo.png">
    </th>
  </tr>
  <tr>
    <th colspan="2" valign="top"><strong>Welcome to  Remote Health</strong></th>
  </tr>  
  <tr>
    <td valign="top" >
      <b>Dear  '.$row['f_name'].' '.$row['l_name'].'</b><br/>      
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
      <img width="96" src="http://remotehealth.org/images/email_template_images/search_icon.png"> 
      <br>
      <strong>Find a Physician instantly for you and your family</strong>
      <br>
      Filter quickly by specialty, and location.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/calender.png" alt="Daily calendar"><br>
      <strong>See the caregiver&rsquo;s calendar on real-time</strong>
      <br>
      Book an appointment online anytime, 24/7.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>
  
  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/lightning.png" alt=""><br>
      <strong>Ontime Notifications</strong><br>
      Receive email and text reminders - and manage your appointment<br>
      if something changes.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96"  src="http://remotehealth.org/images/email_template_images/taxi.png" alt=""><br>
      <strong>No Travel  or wait times</strong><br>
      RSpend less time travelling and no waiting times in the traffic and in the office.
    </td>
  </tr>

  <tr> <td>&nbsp;</td></tr>

  <tr>
    <td valign="top" align="center">
      <img width="96" src="http://remotehealth.org/images/email_template_images/bulb.png" alt=""><br>
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
     Your Team at Remote Health!
    </td>
  </tr>

   <tr> <td>&nbsp;</td></tr>
   <tr> <td>&nbsp;</td></tr>
  <tr> <td>&nbsp;</td></tr>
  <tr>
    <td valign="top" align="center">
     <img  src="http://remotehealth.org/images/email_template_images/icon.png">
     <div style="font-size: 28px; font-weight: 500; color: #9b113a; ">Bridge the gap in care</div>
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a>  &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
     <br/>
      Remote Health does not provide medical guidance, diagnosis,<br>
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
			$headers[] = 'To: '.$row['f_name'].' '.$row['l_name'] .' <'.$_REQUEST['user_reset_email'].'>';
			$headers[] = 'From: Remote Health Reset password <no-reply@remotehealth.org>';
			//$headers[] = 'Cc: birthdayarchive@example.com';			
			$headers[] = 'Bcc: himadrisekharroy.cse.rs@jadavpuruniversity.in';

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