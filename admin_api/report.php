<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='msg_list')
{
	$returnArray['req'] = $_POST;
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
		
	$dateRangeSql ="";
	if(isset($_POST['search_date_from']) && $_POST['search_date_from'] != "" && isset($_POST['search_date_to']) && $_POST['search_date_to'] != "" )
	{
		$fromDate = explode("/",trim($_POST['search_date_from']));
		$toDate = explode("/",trim($_POST['search_date_to']));

		$dateRangeSql =" and message_subscription.create_date between 
					'".$fromDate[2]."-".$fromDate[0]."-".$fromDate[1]." 00:00:00' and 
					'".$toDate[2]."-".$toDate[0]."-".$toDate[1]." 23:59:59'";
	}

	
	$sql = "select * 				
			from message_subscription 
			join users on(users.id=message_subscription.user_id)
			where 1 $dateRangeSql 
			order by message_subscription.create_date desc ";
			//echo $sql;
			$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{		
		$returnArray['data'][$i]['bill_id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']? $row['f_name']:" -- ");
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']? $row['l_name']:"  ");
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']? $row['mobile']: " -- ");
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']?$row['email_id']:" -- ");
		
		$returnArray['data'][$i]['amount'] = stripslashes($row['amount']? $row['amount']: "");
		$returnArray['data'][$i]['validity'] = $row['validity'];

		$returnArray['data'][$i]['create_date'] = date("d-m-Y", strtotime($row['create_date']));
		$i++;
	}
	//print_r($returnArray);
	echo json_encode($returnArray);
}
elseif($_POST['func']=='app_date_list')
{
	$returnArray['req'] = $_POST;
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
		
	$dateRangeSql ="";
	if(isset($_POST['search_date_from']) && $_POST['search_date_from'] != "" && isset($_POST['search_date_to']) && $_POST['search_date_to'] != "" )
	{
		$fromDate = explode("/",trim($_POST['search_date_from']));
		$toDate = explode("/",trim($_POST['search_date_to']));

		$dateRangeSql =" and doctor_appointment.app_date_time between 
					'".$fromDate[2]."-".$fromDate[0]."-".$fromDate[1]." 00:00:00' and 
					'".$toDate[2]."-".$toDate[0]."-".$toDate[1]." 23:59:59'";
	}

	
	$sql = "select doctor_appointment.*, 
			concat_ws(' ',doctor.f_name , doctor.l_name) as doc_name, 

			concat_ws(' ', users.f_name, users.l_name) as user_name,

			specialization.per_visit_change,
			(100-specialization.provider_percentage) as actora_percentage

			from doctor_appointment 
			join users on(users.id=doctor_appointment.user_id)
			join doctor on(doctor.id=doctor_appointment.doc_id)
			join specialization on (specialization.id = doctor.specialization_id)
			where 1 $dateRangeSql 
			order by doctor_appointment.app_date_time desc ";
			//echo $sql;
			$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{		
		$returnArray['data'][$i] = $row;
		$returnArray['data'][$i]['app_date'] = date("d-m-Y", strtotime($row['app_date_time']));
		$returnArray['data'][$i]['create_on'] = date("d-m-Y", strtotime($row['create_date']));
		$returnArray['data'][$i]['amount'] = $row['per_visit_change']* $row['actora_percentage']/100;
		$i++;
	}
	//print_r($returnArray);
	echo json_encode($returnArray);
}
elseif($_POST['func']=='create_date_list')
{
	$returnArray['req'] = $_POST;
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
		
	$dateRangeSql ="";
	if(isset($_POST['search_date_from']) && $_POST['search_date_from'] != "" && isset($_POST['search_date_to']) && $_POST['search_date_to'] != "" )
	{
		$fromDate = explode("/",trim($_POST['search_date_from']));
		$toDate = explode("/",trim($_POST['search_date_to']));

		$dateRangeSql =" and doctor_appointment.create_date between 
					'".$fromDate[2]."-".$fromDate[0]."-".$fromDate[1]." 00:00:00' and 
					'".$toDate[2]."-".$toDate[0]."-".$toDate[1]." 23:59:59'";
	}

	
	$sql = "select doctor_appointment.*, 
			concat_ws(' ',doctor.f_name , doctor.l_name) as doc_name, 

			concat_ws(' ', users.f_name, users.l_name) as user_name,

			specialization.per_visit_change,
			(100-specialization.provider_percentage) as actora_percentage

			from doctor_appointment 
			join users on(users.id=doctor_appointment.user_id)
			join doctor on(doctor.id=doctor_appointment.doc_id)
			join specialization on (specialization.id = doctor.specialization_id)
			where 1 $dateRangeSql 
			order by doctor_appointment.app_date_time desc ";
			//echo $sql;
			$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{		
		$returnArray['data'][$i] = $row;
		$returnArray['data'][$i]['app_date'] = date("d-m-Y", strtotime($row['app_date_time']));
		$returnArray['data'][$i]['create_on'] = date("d-m-Y", strtotime($row['create_date']));
		$returnArray['data'][$i]['amount'] = $row['per_visit_change']* $row['actora_percentage']/100;
		$i++;
	}
	//print_r($returnArray);
	echo json_encode($returnArray);
}
elseif($_POST['func']=='create_csv')
{
	$dateRangeSql ="";
	if(isset($_POST['search_date_from']) && $_POST['search_date_from'] != "" && isset($_POST['search_date_to']) && $_POST['search_date_to'] != "" )
	{
		$fromDate = explode("/",trim($_POST['search_date_from']));
		$toDate = explode("/",trim($_POST['search_date_to']));

		$dateRangeSql =" and sd_billing.created_date between 
					'".$fromDate[2]."-".$fromDate[0]."-".$fromDate[1]." 00:00:00' and 
					'".$toDate[2]."-".$toDate[0]."-".$toDate[1]." 23:59:59'";
	}
	$sql = "select
				sd_billing.bill_id,
				sd_invoice.id,
				sd_invoice.name, 
				sd_invoice.mobile, 
				shop_name,
				shop_address1,
				shop_address2,
				sd_invoice.location,
				sd_billing_plan.plan_name,
				sd_billing.created_date as plan_started_date,
				sd_billing.validity,
				sd_billing_payment_method.method_name,
				sd_billing_cycle_type.billing_cycle_name,
				sd_billing.amount  
			from sd_billing 
			left join sd_invoice on (sd_billing.invoice_id = sd_invoice.id)
			left join sd_vendors on (sd_billing.vendor_id = sd_vendors.vendor_id)
			left join sd_billing_plan on (sd_billing_plan.plan_id = sd_billing.plan_id) 
			LEFT JOIN sd_billing_payment_method on (sd_billing.payment_method_id = sd_billing_payment_method.payment_method_id)
			left join sd_billing_cycle_type on (sd_billing.billing_cycle_id = sd_billing_cycle_type.billing_cycle_id) 
			where 1 $dateRangeSql 
			order by sd_billing.created_date desc ";

	$file_name = "sd_report_".$fromDate[2]."-".$fromDate[0]."-".$fromDate[1]."_to_".$toDate[2]."-".$toDate[0]."-".$toDate[1].".csv";
	$file_path = "../report_csv/".$file_name;
	$output = fopen($file_path, "w");

	$titleRow = array(
					"SL", 
					"Invoice ID", 
					"Bill ID",
					"Merchant Name", 
					"Merchant Mobile No.",
					"Shop Name", 
					"Shop Address", 
					"Location", 
					"Plan",
					"Plan Started On", 
					"Plan Validity Till", 
					"Payment Mode", 
					"Payment Cycle", 
					"Amount (Rs)");
	fputcsv($output, $titleRow);

	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	$i=1;
	$totalAmount =0;
	while($row = mysqli_fetch_array($res))
	{
		if($row['id'])
			$invId = "SD2018/".$row['id'];
		else
			$invId ="Not Generated";
		$tempRow = array(
						$i, 
						$invId, 
						$row['bill_id'],
						$row['name'], 
						$row['mobile'], 
						$row['shop_name'],
						$row['shop_address1']." ". $row['shop_address2'], 
						$row['location'],
						$row['plan_name'],
						date("d/m/Y",strtotime($row['plan_started_date'])),
						date("d/m/Y",strtotime($row['validity'])),
						$row['method_name'],
						$row['billing_cycle_name'],
						$row['amount']." " );
		fputcsv($output, $tempRow);
		$i++;
		$amount = (int)$row['amount'];
		$totalAmount = $totalAmount + $amount;
	}
		
	$blankRow = array("","", "", "", "", "", "", "", "", "", "","", "");
	$totalRow = array("","", "", "", "", "", "", "", "", "", "","Total Amount", $totalAmount);
	fputcsv($output, $blankRow);
	fputcsv($output, $totalRow);

	fclose($output);

	$returnArray['success'] = true;
	$returnArray['file_name'] = $file_name;
	echo json_encode($returnArray);
}
