<?php
include("config.php");
include("sd_send_sms.php");

if($_POST['func'] == "send_sms")
{
$sql = "select admin_mobile from sd_admin where admin_id='1'";
$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));
$row3 = mysqli_fetch_array($res3);

$admin_mobile = $row3['admin_mobile'];
$user_mob_no =$_POST['user_mob_no'];
$vendor_mob_no =$_POST['vendor_mob_no'];

$adminSms = $user_mob_no ." is interested on your product! Please call him / her as early as possible from the number ".$vendor_mob_no;

$userSms = "Your message has been delivered on your request to the merchants having mobile number ". $user_mob_no ."! you may get a call from that merchant with number ". $vendor_mob_no;

$vendorSms = $user_mob_no ." is interested on your product! Please call him / her as early as possible from the number ".$vendor_mob_no;
//echo $admin_mobile."===".$adminSms."++++";
//echo $user_mob_no."===".$userSms."++++";;
//echo $vendor_mob_no."===".$vendorSms."++++";;

sendSMS($admin_mobile, $adminSms,"SDECRT");
sendSMS($user_mob_no, $userSms,"SDECRT");
sendSMS($vendor_mob_no, $vendorSms,"SDECRT");

$sql = "insert sd_ecart_contact set 	
		vendor_id = '".$_POST['vendor_id']."',
		product_id	= '".$_POST['product_id']."',
		user_mob_no	= '".$_POST['user_mob_no']."',
		created_date=NOW()";
mysqli_query($link, $sql) or die(mysqli_error($link));

}
elseif($_POST['func'] == "send_otp")
{
	$otp = rand(1000, 9999);
	$user_mob_no = $_POST['user_mob_no'];

	$sms = "$otp is the High Security Passcode for Street Delight. Do not share with anyone.";
	sendSMS($user_mob_no, $sms, "SDECRT");

	$returnArray['generated_otp'] = $otp;
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "list")
{
	$returnArray['req'] = $_POST;
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
	$startingLimit = $_POST['starting'];
	$length = $_POST['length'];

	$dateRangeSql ="";
	if(isset($_POST['search_date_from']) && $_POST['search_date_from'] != "" && isset($_POST['search_date_to']) && $_POST['search_date_to'] != "" )
	{
		$fromDate = explode("/",trim($_POST['search_date_from']));
		$toDate = explode("/",trim($_POST['search_date_to']));

		$dateRangeSql =" and sd_ecart_contact.created_date between 
					'".$fromDate[2]."-".$fromDate[0]."-".$fromDate[1]."' and 
					'".$toDate[2]."-".$toDate[0]."-".$toDate[1]."'";
	}

	$mobileSql = "";
	if(isset($_POST['search_mobile']) && $_POST['search_mobile'] != "")
	$mobileSql = " and sd_ecart_contact.user_mob_no, like '%".trim($_POST['search_mobile'])."%'";
	
	$shopSql = "";
	if(isset($_POST['search_shop_name']) && $_POST['search_shop_name'] != "")
	$shopSql = " and (sd_vendors.shop_name like '%".trim($_POST['search_shop_name'])."%' or 
						sd_vendors.vendor_fname like '%".trim($_POST['search_shop_name'])."%' or
						sd_vendors.vendor_lname like '%".trim($_POST['search_shop_name'])."%' or 
						sd_vendors.vendor_mobile like '%".trim($_POST['search_shop_name'])."%')";

	$sql = "select count(id) as total_count 
			from sd_ecart_contact 
			 join sd_vendors on (sd_ecart_contact.vendor_id = sd_vendors.vendor_id)
			 join sd_product_image on (sd_ecart_contact.product_id = sd_product_image.product_image_id)
			where 1 $dateRangeSql ";
	$returnArray['sql1'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);
	$returnArray['total_count'] = $row['total_count'];
	
	$lastPage = $row['total_count']/50;
	if(($row['total_count']%50) > 0) $lastPage = floor($lastPage)+1;
	
	
	$returnArray['lastPage'] = $lastPage;
	$returnArray['startingLimit'] = $startingLimit;
	$returnArray['length'] = $length;
	$returnArray['currentPage'] =  $_POST['page'];	
	
	
	$sql = "select 
				sd_ecart_contact.id,
				sd_ecart_contact.created_date,
				sd_ecart_contact.user_mob_no,
				sd_product_image.product_name,
				sd_product_image.product_image,
				sd_product_image.product_price,
				sd_vendors.vendor_fname,
				sd_vendors.vendor_lname,
				sd_vendors.vendor_mobile,
				sd_vendors.shop_name
			from sd_ecart_contact 
			join sd_vendors on (sd_ecart_contact.vendor_id = sd_vendors.vendor_id)
			join sd_product_image on (sd_ecart_contact.product_id = sd_product_image.product_image_id)
			where 1 $dateRangeSql 
			order by sd_ecart_contact.created_date desc 
			limit $startingLimit, $length";
			//echo $sql;
			$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{		
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['created_date'] = date("d-m-Y", strtotime($row['created_date']));
		$returnArray['data'][$i]['user_mob_no'] = $row['user_mob_no'];
		$returnArray['data'][$i]['product_name'] = stripslashes($row['product_name']);
		$returnArray['data'][$i]['product_image'] = $row['product_image'];
		$returnArray['data'][$i]['product_price'] = $row['product_price'];
		$returnArray['data'][$i]['vendor_fname'] = stripslashes($row['vendor_fname']? $row['vendor_fname']:" -- ");
		$returnArray['data'][$i]['vendor_lname'] = stripslashes($row['vendor_lname']? $row['vendor_lname']:"  ");
		$returnArray['data'][$i]['vendor_mobile'] = stripslashes($row['vendor_mobile']? $row['vendor_mobile']: " -- ");
		$returnArray['data'][$i]['shop_name'] = stripslashes($row['shop_name']?$row['shop_name']:" -- ");
			
		$i++;
	}
	//print_r($returnArray);
	echo json_encode($returnArray);
}
?>