<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";

	$sql = "select count(id) as cv from  doctor where is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);

	$returnArray['cv'] = $row['cv'];

	$sql = "select count(id) as cu from users where is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);

	$returnArray['cu'] = $row['cu'];

	$sql = "select sum(amount) as rc from message_subscription";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);	
	$returnArray['rc'] = $row['rc'];

	$total_ra = 0;
	$sql = "select sum(specialization.per_visit_change * (100-specialization.provider_percentage)/100)  as ra
	from doctor_appointment
	join doctor on (doctor.id = doctor_appointment.doc_id) 
	join specialization on(specialization.id = doctor.specialization_id)
	where doctor_appointment.status='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	
	$returnArray['ra'] = $row['ra'];
	//$returnArray['ra'] = 0;
	
	echo json_encode($returnArray);	
}

?>