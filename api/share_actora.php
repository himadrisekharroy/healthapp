<?php
include("../admin_api/config.php");

$funcName = $_REQUEST['func'];
if($funcName == "via_email")
{
	$returnArray = array();
	$returnArray['success'] = false;
	$returnArray['msg'] = "Share is not successful.";
	$sql = "insert share_details set 
			share_name = '".addslashes(trim($_POST['share_name']))."',
			share_email = '".addslashes(trim($_POST['share_email']))."',
			user_id = '".$_POST['user_id']."',
			user_type ='".$_POST['logged_user_type']."',
			create_time = NOW()";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Share successful.";

		$to = $row4['email_id'];
		$subject = "Welcome ".$_POST['share_name'].", Remote Health is ready to provide access to doctors now!!";
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
    <b>The start of access to improved access to care and better experience with physicians.</b>
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
     <a href="'.SITE_URL.'privacy" style="color: #9b113a">Privacy</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'terms-condition" style="color: #9b113a">Terms</a> &nbsp; | &nbsp; <a href="'.SITE_URL.'unsubscribe" style="color: #9b113a">Unsuscribe</a>
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
		$headers[] = 'To: '.$row4['f_name'].' '.$row4['l_name'] .' <'.$row4['email_id'].'>';
		$headers[] = 'From: Remote Health <no-reply@remotehealth.org>';
		//$headers[] = 'Cc: birthdayarchive@example.com';
		$headers[] = 'Bcc: himadrisekharroy.cse.rs@jadavpuruniversity.in';

		// Mail it
		mail($to, $subject, $content, implode("\r\n", $headers));
	}
  echo json_encode($returnArray);
}
?>