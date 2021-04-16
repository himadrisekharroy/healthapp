<?php
include("config.php");

$returnArray= array();

if($_POST['func'] == "migrate")
{
	$sql = "select * from billing_table";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	while($row = mysqli_fetch_array($res))
	{
		$plan_id = 0;
		if($row['vendor_plan_name'] == 'Chota') $plan_id= 1;
		elseif($row['vendor_plan_name'] == 'Thora') $plan_id= 2;

		$billing_cycle_id = 0;
		if($row['billing_type'] == 'Weekly') $billing_cycle_id = 1;
		if($row['billing_type'] == 'Monthly') $billing_cycle_id = 2;
		if($row['billing_type'] == 'Quarterly') $billing_cycle_id = 3;
		if($row['billing_type'] == 'Yearly') $billing_cycle_id = 4;

		$payment_method_id = 0;
		if($row['financial_instrument'] == 'Cash') $payment_method_id = 1;
		if($row['financial_instrument'] == 'Cheque') $payment_method_id = 2;
		if($row['financial_instrument'] == 'Paytm') $payment_method_id = 3;

		$sql = "insert sd_billing set 
				bill_id 			='".$row['bill_id']."', 	
				vendor_id			='".$row['vendor_id']."',
				plan_id				='$plan_id',
				amount				='".$row['total_amount']."',
				validity			='".$row['valid_till']."',
				billing_cycle_id	='$billing_cycle_id',
				payment_method_id	='$payment_method_id',
				bank_id				='',
				ifsc				='".$row['ifsc_code']."',
				acc_no				='".$row['account_no']."',
				cheque_no			='".$row['cheque_number']."',
				aadhar				='".$row['adhaar_no']."',
				created_date		='".$row['collection_date']."',
				created_by_id		='1',
				agent_name			='".$row['agent_name']."'";
		$res1 = mysqli_query($link, $sql) or die(mysqli_error($link));

		$types = explode(",",$row['vendor_plan_type']);
		foreach($types as $type)
		{
			$plan_type_id = 0;
			if($type == "Youtube") $plan_type_id = 1;
			elseif($type == "FreeListing") $plan_type_id = 2;
			elseif($type == "Facebook") $plan_type_id = 3;
			elseif($type == "Twitter") $plan_type_id = 4;
			$sql = "insert sd_billing_plan_type_rel set billing_id='".$row['bill_id']."', plan_type_id='$plan_type_id'";
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		}
	}
}
elseif($_POST['func']=='list')
{
	$returnArray['req'] = $_POST;
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
	$startingLimit = $_POST['starting'];
	$length = $_POST['length'];
	
	$citySql ="";
	$locationSql = "";

	if(isset($_POST['search_location_id']) && $_POST['search_location_id'] != "")
	$locationSql = " and sd_vendors.location_id='".$_POST['search_location_id'] ."'";
	
	$mobileSql = "";
	if(isset($_POST['search_mobile']) && $_POST['search_mobile'] != "")
	$mobileSql = " and vendor_mobile like '%".trim($_POST['search_mobile'])."%'";
	
	$shopSql = "";
	if(isset($_POST['search_shop_name']) && $_POST['search_shop_name'] != "")
	$shopSql = " and shop_name like '%".trim($_POST['search_shop_name'])."%'";
	
	$dateRangeSql ="";
	if(isset($_POST['search_date_from']) && $_POST['search_date_from'] != "" && isset($_POST['search_date_to']) && $_POST['search_date_to'] != "" )
	{
		$fromDate = explode("/",trim($_POST['search_date_from']));
		$toDate = explode("/",trim($_POST['search_date_to']));

		$dateRangeSql =" and sd_billing.created_date between 
					'".$fromDate[2]."-".$fromDate[0]."-".$fromDate[1]."' and 
					'".$toDate[2]."-".$toDate[0]."-".$toDate[1]."'";
	}

	$sql = "select count(bill_id) as total_count 
			from sd_billing 
			left join sd_vendors on (sd_billing.vendor_id = sd_vendors.vendor_id) 
			where 1 $dateRangeSql $shopSql $mobileSql $locationSql $citySql  ";
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
				bill_id, 
				vendor_fname, 
				vendor_lname, 
				vendor_mobile, 
				shop_name, 
				sd_billing_plan.plan_name, 
				amount, 
				validity, 
				invoice_id,
				sd_billing_cycle_type.billing_cycle_name, 
				sd_billing.created_date, 
				sd_billing_payment_method.method_name 
			from sd_billing 
			left join sd_vendors on (sd_billing.vendor_id = sd_vendors.vendor_id) 
			left join sd_billing_plan on (sd_billing_plan.plan_id = sd_billing.plan_id) 
			left join sd_billing_cycle_type on (sd_billing.billing_cycle_id = sd_billing_cycle_type.billing_cycle_id) 
			LEFT JOIN sd_billing_payment_method on (sd_billing.payment_method_id = sd_billing_payment_method.payment_method_id)
			left join location on (sd_vendors.location_id = location.location_id)
			where 1 $dateRangeSql $shopSql $mobileSql $locationSql
			order by sd_billing.created_date desc 
			limit $startingLimit, $length";
			//echo $sql;
			$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{		
		$returnArray['data'][$i]['bill_id'] = $row['bill_id'];
		$returnArray['data'][$i]['vendor_fname'] = stripslashes($row['vendor_fname']? $row['vendor_fname']:" -- ");
		$returnArray['data'][$i]['vendor_lname'] = stripslashes($row['vendor_lname']? $row['vendor_lname']:"  ");
		$returnArray['data'][$i]['vendor_mobile'] = stripslashes($row['vendor_mobile']? $row['vendor_mobile']: " -- ");
		$returnArray['data'][$i]['shop_name'] = stripslashes($row['shop_name']?$row['shop_name']:" -- ");
		$returnArray['data'][$i]['plan_name'] = stripslashes($row['plan_name']? $row['plan_name']: " -- ");		
		$returnArray['data'][$i]['amount'] = stripslashes($row['amount']? $row['amount']: "");
		$returnArray['data'][$i]['validity'] = date("d-m-Y", strtotime($row['validity']));
		$returnArray['data'][$i]['billing_cycle_name'] = $row['billing_cycle_name'];
		$returnArray['data'][$i]['created_date'] = date("d-m-Y", strtotime($row['created_date']));
		$returnArray['data'][$i]['method_name'] = stripslashes($row['method_name']);
		$returnArray['data'][$i]['invoice_id'] = $row['invoice_id'];
		$i++;
	}
	//print_r($returnArray);
	echo json_encode($returnArray);
}
elseif($_POST['func']=='add')
{
	$sql = "select add_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='16'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['add_p'])
	{
		$validity = "";
		if($_POST['validity'])
		{
			$validity = explode("/", $_POST['validity']);
			$temp = $validity[2]."-".$validity[0]."-".$validity[1]." 00:00:00";
			$validity = $temp;
		}
		

		$sql = "insert sd_billing set
					vendor_id 			= '".$_POST['vendor_id']."',
					plan_id 			= '".$_POST['plan_id']."',
					amount 				= '".trim($_POST['amount'])."',
					validity 			= '$validity.',
					billing_cycle_id	= '".$_POST['billing_cycle_id']."',
					payment_method_id	= '".$_POST['payment_method_id']."',
					bank_id				= '".$_POST['bank_id']."',
					ifsc 				= '".trim($_POST['ifsc'])."',
					acc_no 				= '".trim($_POST['acc_no'])."',
					cheque_no 			= '".trim($_POST['cheque_no'])."',
					aadhar 				= '".trim($_POST['aadhar'])."',
					gst_no 				= '".trim($_POST['gst_no'])."',
					created_date 		= '".date('Y-m-d h:i:s')."',
					created_by_id 		= '".$_POST['created_by_id']."',
					agent_name 			= '".addslashes(trim($_POST['agent_name']))."'";
		
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$billing_id = mysqli_insert_id($link);

		foreach($_POST['plan_type_ids'] as $plan_type_id)
		{
			$sql = "insert sd_billing_plan_type_rel set
						billing_id='$billing_id', 
						plan_type_id='$plan_type_id'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		}	
		if($billing_id )
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Bill has been added successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='get_data')
{
	$sql ="select * from sd_billing where bill_id='".$_POST['id']."'";
	//echo $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['vendor_id'] = $row['vendor_id'];
	$returnArray['data']['plan_id'] = $row['plan_id'];
	$returnArray['data']['amount'] = $row['amount'];
	$returnArray['data']['validity'] = date("m/d/Y",strtotime($row['validity']));
	$returnArray['data']['billing_cycle_id'] = $row['billing_cycle_id'];
	$returnArray['data']['payment_method_id'] = $row['payment_method_id'];
	$returnArray['data']['bank_id'] = $row['bank_id'];
	$returnArray['data']['ifsc'] = $row['ifsc'];
	$returnArray['data']['acc_no'] = $row['acc_no'];
	$returnArray['data']['cheque_no'] = $row['cheque_no'];
	$returnArray['data']['aadhar'] = $row['aadhar'];
	$returnArray['data']['gst_no'] = $row['gst_no'];
	$returnArray['data']['created_by_id'] = stripslashes($row['created_by_id']);
	$returnArray['data']['agent_name'] = stripslashes($row['agent_name']);
	
	$sql = "select plan_type_id from sd_billing_plan_type_rel where billing_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['plan_type_id'][$i] = $row['plan_type_id'];	
		$i++;
	}

	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);
}
elseif($_POST['func']=='edit_save')
{
	$sql = "select edit_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='16'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['edit_p'])
	{
		$validity = "";
		if($_POST['validity'])
		{
			$validity = explode("/", $_POST['validity']);
			$temp = $validity[2]."-".$validity[0]."-".$validity[1]." 00:00:00";
			$validity = $temp;
		}
		
		$sql = "select invoice_id from sd_billing where bill_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		if($row_inner['invoice_id'] == 0)
		{

			
			$sql = "update sd_billing set					
						plan_id 			= '".$_POST['plan_id']."',
						amount 				= '".trim($_POST['amount'])."',
						validity 			= '$validity.',
						billing_cycle_id	= '".$_POST['billing_cycle_id']."',
						payment_method_id	= '".$_POST['payment_method_id']."',
						bank_id				= '".$_POST['bank_id']."',
						ifsc 				= '".trim($_POST['ifsc'])."',
						acc_no 				= '".trim($_POST['acc_no'])."',
						cheque_no 			= '".trim($_POST['cheque_no'])."',
						aadhar 				= '".trim($_POST['aadhar'])."',
						gst_no 				= '".trim($_POST['gst_no'])."',
						agent_name 			= '".addslashes(trim($_POST['agent_name']))."'
						where bill_id='".$_POST['id']."'";
			
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));
			
			$sql ="delete from sd_billing_plan_type_rel where billing_id='".$_POST['id']."'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));

			foreach($_POST['plan_type_ids'] as $plan_type_id)
			{
				$sql = "insert sd_billing_plan_type_rel set
							billing_id='".$_POST['id']."', 
							plan_type_id='$plan_type_id'";
				$res = mysqli_query($link, $sql) or die(mysqli_error($link));
			}

			$returnArray['success'] = true;
			$returnArray['msg'] = "Bill has been updated successfully";
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Invoice already generated. Bill updation failed.";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'get_data_for_invoice')
{
	$max_invoice_id = 0;
	$sql ="select max(id) as max_id from sd_invoice";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	if($row['max_id']) $max_invoice_id = $row['max_id']+1;
	else $max_invoice_id = 1; 

	$sql ="select * from sd_billing where bill_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);

	$returnArray['data']['plan_id'] = $row['plan_id'];
	$returnArray['data']['amount'] = $row['amount']; 
	$returnArray['data']['gst'] = $row['gst_no']; 
	$returnArray['data']['created_date'] = date("jS M, Y", strtotime($row['created_date'])); 
	$returnArray['data']['invoice_id'] = $row['invoice_id'];
	$returnArray['data']['visible_invoice_id'] = "SD".date("Ymd").$_POST['id'].$max_invoice_id;

	$vendor_id = $row['vendor_id'];

	$sql = "select * from sd_billing_plan where is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['plan'][$i]['id'] = $row['plan_id'];
		$returnArray['data']['plan'][$i]['plan_name'] = $row['plan_name'];
		$i++;
	}

	$plan_type = array();
	$sql = "select plan_type_name 
			from sd_billing_plan_type 
			join sd_billing_plan_type_rel on (sd_billing_plan_type_rel.plan_type_id = sd_billing_plan_type.plan_type_id)
			where billing_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	while($row = mysqli_fetch_array($res))
	{
		array_push($plan_type, $row['plan_type_name']);
	}
	$plan_type = implode(", ", $plan_type);
	$returnArray['data']['description'] = $plan_type;

	$sql = "select vendor_fname, vendor_lname, vendor_mobile, location_id from sd_vendors where vendor_id='$vendor_id' ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['vendor_fname'] = stripslashes($row['vendor_fname']);
	$returnArray['data']['vendor_lname'] = stripslashes($row['vendor_lname']);
	$returnArray['data']['vendor_mobile'] = $row['vendor_mobile'];

	$location_id = $row['location_id'];

	$sql = "select location , city_id from location where location_id='$location_id'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['location'] = stripslashes($row['location']);

	$city_id = $row['city_id'];

	$sql = "select city from city where city_id='$city_id'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['city'] = stripslashes($row['city']);	

	echo json_encode($returnArray);
}
?>