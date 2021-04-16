<?php
include("config.php");

$returnArray= array();

if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from doctor 
			where type in ('n') 
			order by create_date desc";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$image = $row['image'];
		if(!$image) $image = "no-doctor.png";
		$certificate ="";
		if($row['certificate'])  $certificate = $row['certificate'];		

		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['specialization_id'] = stripslashes($row['specialization_id']);
		$returnArray['data'][$i]['phy_id'] = stripslashes($row['phy_id']);
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		$returnArray['data'][$i]['designation'] = stripslashes($row['designation']);
		
		$returnArray['data'][$i]['mobile'] = stripslashes($row['mobile']);
		$returnArray['data'][$i]['email_id'] = stripslashes($row['email_id']);
		$returnArray['data'][$i]['dob'] = $row['dob'];
		$returnArray['data'][$i]['sex'] = $row['sex'];
		$returnArray['data'][$i]['type'] = $row['type'];
		$returnArray['data'][$i]['image'] = $image;
		$returnArray['data'][$i]['certificate'] = $certificate;
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		if(!$row['refered_by'])
			$returnArray['data'][$i]['referred'] = "None";
		else
		{
			$sql = "select f_name, l_name 
					from doctor 
					where id='".$row['refered_by']."'";
			$returnArray['data'][$i]['sql'] = $sql;
			$res2 = mysqli_query($link, $sql) or die(mysqli_error($link));
			$row2 = mysqli_fetch_array($res2);
			$returnArray['data'][$i]['referred'] = "Dr. ".stripslashes($row2['f_name']). " " .stripslashes($row2['l_name']);
		}
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='set_ref')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Not Updated..";
	$sql = "update doctor set refered_by='".$_POST['ref_id']."' where id='".$_POST['doc_id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$returnArray['success'] = ture;
		$returnArray['msg'] = "Updated..";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='specialization_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from specialization order by title asc ";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['title'] = stripslashes($row['title']);
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='nurse_provided_services')
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
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='active_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from doctor  where  type in ('n') and is_active='1'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['id'] = $row['id'];
		$returnArray['data'][$i]['f_name'] = stripslashes($row['f_name']);
		$returnArray['data'][$i]['l_name'] = stripslashes($row['l_name']);
		
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Doctor status change is not successful.";
	
	$sql = "update doctor set is_active = if(is_active = '1','0', '1' ) where id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from doctor where id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Doctor status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "add")
{


	$sql = "select id from users where email_id='".$_POST['email_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_u = mysqli_num_rows($res);

	$sql = "select id from doctor where email_id='".$_POST['email_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$num_d = mysqli_num_rows($res);
	
	if(($num_u + $num_d) > 0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Email is already been registerd.";
	}
	else
	{
		//print_r($_FILES);
		$image = "";
		if($_FILES['doctor_image']['name'])
		{		
			$image = time().$_FILES['doctor_image']['name'];
		
			move_uploaded_file($_FILES['doctor_image']['tmp_name'],"../images/doctor_image/".$image);
		}

		$certificate = "";
		if($_FILES['doctor_certificate']['name'])
		{		
			$certificate = time().$_FILES['doctor_certificate']['name'];
		
			move_uploaded_file($_FILES['doctor_certificate']['tmp_name'],"../certificate/".$certificate);
		}

		$sql = "insert doctor set
				phy_id = '".addslashes(trim($_POST['phy_id']))."',
				f_name='".addslashes(trim($_POST['f_name']))."',
				l_name='".addslashes(trim($_POST['l_name']))."',
				designation = '".addslashes(trim($_POST['designation']))."',
				mobile='".addslashes(trim($_POST['mobile']))."',
				email_id='".addslashes(trim($_POST['email_id']))."',
				dob='".addslashes(trim($_POST['dob']))."',
				sex='".addslashes(trim($_POST['sex']))."',
				about='".addslashes(trim($_POST['about']))."',
				password='".createHashAndSalt($_POST['password'])."',
				specialization_id = '".$_POST['specialization_id']."',			
				image = '$image',
				certificate = '$certificate',
				language_known = '".json_encode($_POST['lang'])."',
				type = '".$_POST['type']."',
				create_date=NOW(),
				is_active='1'";
		
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
			foreach($_POST['service_ids'] as $service_id)
			{
				$sql = "insert nurse_service_selected set
				service_id= '$service_id', 
				nurse_id= '$insert_doctor_id'";
				mysqli_query($link, $sql) or die(mysqli_error($link));
			}

			//===========================================//

			$returnArray['success'] = true;
			$returnArray['msg'] = "Nurse has been added successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		}
	}	
	$returnArray['postdata'] =$_POST;

	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Doctor deletion is not successful.";
	$sql = "select delete_p 
			from role_module_permission 
			join admin on (admin.admin_role = role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='24'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "delete from doctor where id='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Doctor deletion is successful.";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'get_data')
{
	$sql ="select * from doctor where id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	
	$image = $row['image'];
		if(!$image) $image = "no-doctor.png";

	$returnArray['data']['id'] = $row['id'];
	$returnArray['data']['specialization_id'] = stripslashes($row['specialization_id']);
	$returnArray['data']['phy_id'] = stripslashes($row['phy_id']);
	$returnArray['data']['f_name'] = stripslashes($row['f_name']);
	$returnArray['data']['l_name'] = stripslashes($row['l_name']);
	$returnArray['data']['designation'] = stripslashes($row['designation']);
	$returnArray['data']['mobile'] = stripslashes($row['mobile']);
	$returnArray['data']['about'] = stripslashes($row['about']);
	$returnArray['data']['email_id'] = stripslashes($row['email_id']);
	$returnArray['data']['dob'] = $row['dob'];
	$returnArray['data']['sex'] = $row['sex'];
	$returnArray['data']['image'] = $image;
	$returnArray['data']['certificate'] = $row['certificate'];;
	$returnArray['data']['type'] = $row['type'];;
	$returnArray['data']['service_id'] = $row['service_id'];;
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	$selected_service_ids = array();
	$sql = "select service_id from nurse_service_selected where nurse_id='".$_POST['id']."'";
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

	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	$sql = "";
	$image_sql = "";
	$certificate_sql = "";

	$image = "";
	if($_FILES['doctor_image']['name'])
	{
		$image = time().$_FILES['doctor_image']['name'];
		move_uploaded_file($_FILES['doctor_image']['tmp_name'],"../images/doctor_image/".$image);
		$image_sql = "image = '$image',";
	}

	$certificate ="";
	if($_FILES['doctor_certificate']['name'])
	{
		$certificate = time().$_FILES['doctor_certificate']['name'];
		move_uploaded_file($_FILES['doctor_certificate']['tmp_name'],"../certificate/".$certificate);
		$certificate_sql = "certificate = '$certificate',";
	}


		$sql = "update doctor set
			phy_id = '".addslashes(trim($_POST['phy_id']))."',
			f_name='".addslashes(trim($_POST['f_name']))."',
			l_name='".addslashes(trim($_POST['l_name']))."',
			designation='".addslashes(trim($_POST['designation']))."',
			mobile='".addslashes(trim($_POST['mobile']))."',
			email_id='".addslashes(trim($_POST['email_id']))."',
			about='".addslashes(trim($_POST['about']))."',
			dob='".addslashes(trim($_POST['dob']))."',
			sex='".addslashes(trim($_POST['sex']))."',
			$image_sql $certificate_sql
			specialization_id = '".$_POST['specialization_id']."',
			language_known = '".json_encode($_POST['lang'])."'
			where id='".$_POST['edit_id']."'";


		if(count($_POST['service_ids']) > 0)
		{
			$sql = "delete from nurse_service_selected where nurse_id='".$_POST['edit_id']."'";
			mysqli_query($link, $sql) or die(mysqli_error($link));

			foreach($_POST['service_ids'] as $service_id)
			{
				$sql = "insert nurse_service_selected set
				service_id='$service_id', 
				nurse_id='".$_POST['edit_id']."'";
				mysqli_query($link, $sql) or die(mysqli_error($link));
			}
		}
	

	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Doctor has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']  == 'language_known')
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

function createHashAndSalt($user_provided_password)
	{
		
		$options = array(		
		'cost' => 11
	);

	$hash = password_hash($user_provided_password, PASSWORD_BCRYPT,$options);

		return $hash;
	}
?>