<?php
include("../admin_api/config.php");
$funcName = $_REQUEST['func'];
if($funcName == 'Pharmacy_registration')
{
	$returnArray= array();
	$sql = "select PharmId from pharmacy where email='".$_REQUEST['email']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_lab = mysqli_num_rows($res);
	if($num_lab > 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Pharmacy with given eMaild id is already registered";
	}
	else
	{
		$sql = "insert pharmacy set
			name='".addslashes(trim($_REQUEST['name']))."',
			address='".addslashes(trim($_REQUEST['address']))."',
			mobile='".addslashes(trim($_REQUEST['mobile']))."',
			email='".addslashes(trim($_REQUEST['email']))."',
			validity='".addslashes(trim($_REQUEST['validity']))."',
			PinCode='".addslashes(trim($_REQUEST['PinCode']))."',
			password='".createHashAndSalt($_REQUEST['password'])."',
			License = '".addslashes(trim($_REQUEST['License']))."',
			Telephone = '".addslashes(trim($_REQUEST['Telephone']))."',
			CreateDate =NOW(),
			status='1',
			is_active='1'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));			

		if($res)
		{
			$insert_pharm_id = mysqli_insert_id($link);
			$digits = 4;
			$code =  rand(pow(10, $digits-1), pow(10, $digits)-1);
			$sql = "select value_text from site_info where id='16'";
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
			$row = mysqli_fetch_array($res2);
			$playstore_link = $row['value_text'];
			$to = $_REQUEST['email'];
			$subject = "Welcome Pharmacy Management - ".$_REQUEST['name'].", Remote Health is willing to verify your identity!";
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
      <b>Dear Supervisor, Pharmacy - '.$_REQUEST['name'].'</b><br/>      
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
			$headers[] = 'To: '.$_REQUEST['name'] .' <'.$_REQUEST['email'].'>';
			$headers[] = 'From: Remote Health registration <no-reply@remotehealth.org>';
			$headers[] = 'Bcc: sksafdar@gmail.com';

			// Mail it
			mail($to, $subject, $content, implode("\r\n", $headers));

			$returnArray['success'] = true;
			$returnArray['code'] = $code;
			$returnArray['insert_pharm_id'] = $insert_pharm_id;
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
// Pharmacy login
elseif($funcName == 'pharmacy_login')
{
	$returnArray= array();
	$sql = "select * from pharmacy where email='".trim($_REQUEST['pharmacy_login_email'])."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	$row = mysqli_fetch_array($res);
	if($num == 1)
	{
		if($row['is_active'] == 1)
		{
			if(verifyPasswordHash($_REQUEST['pharmacy_login_password'],$row['password']))
			{
				$returnArray['success'] = true;
				$returnArray['PharmacyId'] = $row["PharmId"];
				$returnArray['Name'] = stripslashes($row["Name"]);
				$returnArray['Address'] = stripslashes($row["Address"]);
				$returnArray['PinCode'] = stripslashes($row["PinCode"]);
				$returnArray['Telephone'] = stripslashes($row["Telephone"]);
				$returnArray['License'] = stripslashes($row["License"]);
				$returnArray['Validity'] = stripslashes($row["Validity"]);
				$returnArray['Status'] = stripslashes($row["Status"]);
				$returnArray['CreateDate'] = stripslashes($row["CreateDate"]);
				$returnArray['msg'] = "Login Successful. Please Wait...";
				
			}
			else
			{
				$returnArray['success'] = false;
				$returnArray['msg'] = "Wrong password. Please try again...";	
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
		$returnArray['msg'] = "Username not found...";
	}
	echo json_encode($returnArray);
}
// End of Pharmacy login

// Pharmacy transactions
elseif($funcName == 'pharmacy_trans')
{
	$returnArray= array();
	$sql = "SELECT p.id as prescId, p.file as file, p.create_date as UpTime,a.user_id as UserId,a.doc_id as DocId, u.f_name as userName, d.f_name as docName FROM prescription p JOIN doctor_appointment a on p.app_id=a.id join users u ON u.id=a.user_id JOIN doctor d on d.id=a.doc_id WHERE p.PharmId='".trim($_REQUEST['PharmId'])."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$returnArray['success'] = true;
		$i=0;
		while($row = mysqli_fetch_array($res))
		{
			
			$returnArray['data'][$i]['prescId'] = $row['prescId'];
			$returnArray['data'][$i]['FileName'] = stripslashes($row["file"]);
			$returnArray['data'][$i]['UploadedTime'] = stripslashes($row["UpTime"]);
			$returnArray['data'][$i]['UserId'] = stripslashes($row["UserId"]);
			$returnArray['data'][$i]['DocId'] = stripslashes($row["DocId"]);
			$returnArray['data'][$i]['userName'] = stripslashes($row["userName"]);
			$returnArray['data'][$i]['docName'] = stripslashes($row["docName"]);
			$i++;
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Something went wrong, please contact our technical team";
	}
	echo json_encode($returnArray);
}
// End of Pharmacy transactions

// Patient Prescriptions
elseif($funcName == 'PatientPrescs')
{
	$returnArray= array();
	$sql = "SELECT p.id, p.app_id as appointmentId,p.file, p.PharmId, p.LabId, p.create_date, a.doc_id, d.f_name FROM prescription p join doctor_appointment a on p.app_id=a.id join doctor d on a.doc_id=d.id WHERE a.user_id='".trim($_REQUEST['UserId'])."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$returnArray['success'] = true;
		$i=0;
		while($row = mysqli_fetch_array($res))
		{
			
			$returnArray['data'][$i]['prescId'] = $row['id'];
			$returnArray['data'][$i]['FileName'] = stripslashes($row["file"]);
			$returnArray['data'][$i]['appointmentId'] = stripslashes($row["appointmentId"]);
			$returnArray['data'][$i]['PharmId'] = stripslashes($row["PharmId"]);
			$returnArray['data'][$i]['LabId'] = stripslashes($row["LabId"]);
			$returnArray['data'][$i]['DocId'] = stripslashes($row["DocId"]);
			$returnArray['data'][$i]['docName'] = stripslashes($row["docName"]);
			$returnArray['data'][$i]['UploadDate'] = stripslashes($row["create_date"]);
			$i++;
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Something went wrong, please contact our technical team";
	}
	echo json_encode($returnArray);
}
// End of Patient Prescriptions

// List of Pharmacies
elseif($funcName == 'getPharmacyList')
{
	$returnArray= array();
	$sql = "SELECT PharmId, Name, Address, PinCode FROM pharmacy";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num = mysqli_num_rows($res);
	if($num > 0)
	{
		$returnArray['success'] = true;
		$i=0;
		while($row = mysqli_fetch_array($res))
		{
			
			$returnArray['data'][$i]['PharmId'] = $row['PharmId'];
			$returnArray['data'][$i]['Name'] = stripslashes($row["Name"]);
			$returnArray['data'][$i]['Address'] = stripslashes($row["Address"]);
			$returnArray['data'][$i]['PinCode'] = stripslashes($row["PinCode"]);
			$i++;
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Something went wrong, please contact our technical team";
	}
	echo json_encode($returnArray);
}
// End of Patient Prescriptions


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