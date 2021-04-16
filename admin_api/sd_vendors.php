<?php
include("config.php");

$returnArray= array();
//print_r($_POST);
if($_POST['func']=='list')
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
	else if(isset($_POST['search_city_id']) && $_POST['search_city_id'] != "")
	{
		$citySql = " and location.city_id='".$_POST['search_city_id']."'";
	}
	
	$mobileSql = "";
	if(isset($_POST['search_mobile']) && $_POST['search_mobile'] != "")
	$mobileSql = " and vendor_mobile like '%".trim($_POST['search_mobile'])."%'";
	
	$shopSql = "";
	if(isset($_POST['search_shop_name']) && $_POST['search_shop_name'] != "")
	$shopSql = " and shop_name like '%".trim($_POST['search_shop_name'])."%'";
	
	$sql = "select count(vendor_id) as total_count from sd_vendors where 1 $shopSql $mobileSql $locationSql $citySql  ";
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
				vendor_id,
				vendor_fname,
				vendor_lname,
				vendor_mobile,
				shop_name,
				shop_address1,
				shop_address2,
				created_on,
				sd_vendors.is_active,
				location.location,
				city.city
			from  sd_vendors 
			left join location on (sd_vendors.location_id =location.location_id )
			left join city on (city.city_id = location.city_id) 
			where 1 $shopSql $mobileSql $locationSql $citySql
			order by `created_on` desc 
			limit $startingLimit, $length";
			//echo $sql;
			$returnArray['sql2'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{		
		$returnArray['data'][$i]['vendor_id'] = $row['vendor_id'];
		$returnArray['data'][$i]['vendor_fname'] = stripslashes($row['vendor_fname']? $row['vendor_fname']:" -- ");
		$returnArray['data'][$i]['vendor_lname'] = stripslashes($row['vendor_lname']? $row['vendor_lname']:"  ");
		$returnArray['data'][$i]['vendor_mobile'] = stripslashes($row['vendor_mobile']? $row['vendor_mobile']: " -- ");
		$returnArray['data'][$i]['shop_name'] = stripslashes($row['shop_name']?$row['shop_name']:" -- ");
		$returnArray['data'][$i]['shop_address1'] = stripslashes($row['shop_address1']? $row['shop_address1']: " -- ");		
		$returnArray['data'][$i]['shop_address2'] = stripslashes($row['shop_address2']? $row['shop_address2']: "  ");
		$returnArray['data'][$i]['created_on'] = date("d-m-Y", strtotime($row['created_on']));
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		$returnArray['data'][$i]['location'] = stripslashes($row['location']);
		$returnArray['data'][$i]['city'] = stripslashes($row['city']);
		
		$sql = "select photo_name from sd_photos where vendor_id='".$row['vendor_id']."' limit 0,1";
		$resInner = mysqli_query($link, $sql) or die(mysqli_error($link));	
		$rowInner = mysqli_fetch_array($resInner);
		$numrowInner = mysqli_num_rows($resInner);
		if($numrowInner == 0)
		{
			$returnArray['data'][$i]['photo_name'] ="";
		}
		else
		{
			$returnArray['data'][$i]['photo_name'] =$rowInner['photo_name'];
		}
		

		$sql = "select amount, validity from sd_billing where vendor_id='".$row['vendor_id']."' order by created_date desc limit 0,1";
		$res1 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
		$row1 = mysqli_fetch_array($res1);
		$num_row1 = mysqli_num_rows($res1);
		//print_r($row1);
		if($num_row1 == 0 )
		{
			$returnArray['data'][$i]['billing'] = "--";
		}
		else
		{
			$amt = $row1['amount']  ? "Rs. ". $row1['amount']." /-" : "-";
			$validity = $row1['validity'] ? '<br/>Valid till: '.date("d-m-Y", strtotime($row1['validity'])) : "-";

			$returnArray['data'][$i]['amt'] = $row1['amount'];
			$returnArray['data'][$i]['billing'] = $amt. $validity;	
		}
		$i++;
	}
	//print_r($returnArray);
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Vendor status change is not successful.";
	
	$sql = "update sd_vendors set is_active = if(is_active = '1','0', '1' ) where vendor_id='".$_POST['id']."'";	
	$returnArray['sql'] = $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$returnArray['res'] = $res;
	if($res)
	{
		$sql = "select is_active from sd_vendors where vendor_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Vendor status has been successfully changed.";
		$returnArray['status'] = $row_inner['is_active'];
		
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "active_parent_list")
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	
	$sql = "select * from sd_categories where category_parent_id='".$_POST['parent_id']."' order by category_name asc";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;	
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data'][$i]['category_id'] = $row['category_id'];
		$returnArray['data'][$i]['category_name'] = stripslashes($row['category_name']);
		$returnArray['data'][$i]['category_image'] = stripslashes($row['category_image']);
		$returnArray['data'][$i]['category_parent_id'] = stripslashes($row['category_parent_id']);
		$returnArray['data'][$i]['created_on'] = stripslashes($row['created_on']);
		$returnArray['data'][$i]['is_active'] = $row['is_active'];
		$i++;
	}
	if($i==0)
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Data Not Found.";
	}
	echo json_encode($returnArray);
}


elseif($_POST['func'] == "form1_add")
{
	$sql = "insert sd_vendors set
				vendor_fname='".addslashes(trim($_POST['vendor_fname']))."',
				vendor_lname='".addslashes(trim($_POST['vendor_lname']))."',
				vendor_mobile='".addslashes(trim($_POST['vendor_mobile']))."',
				have_facebook='".$_POST['have_facebook']."',
				have_whatsapp='".$_POST['have_whatsapp']."',
				have_shartphone='".$_POST['have_shartphone']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$returnArray['inserted_id']= mysqli_insert_id($link) ;
		$returnArray['success'] = true;
		$returnArray['msg'] = "Vendor Information has been added successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "form1_edit")
{
	
	$sql = "update sd_vendors set
				vendor_fname='".addslashes(trim($_POST['vendor_fname']))."',
				vendor_lname='".addslashes(trim($_POST['vendor_lname']))."',
				vendor_mobile='".addslashes(trim($_POST['vendor_mobile']))."',
				have_facebook='".$_POST['have_facebook']."',
				have_whatsapp='".$_POST['have_whatsapp']."',
				have_shartphone='".$_POST['have_shartphone']."' 
			where vendor_id='".$_POST['edit_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$returnArray['inserted_id']= $_POST['edit_id'] ;
		$returnArray['success'] = true;
		$returnArray['msg'] = "Vendor Information has been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form2_add")
{
	if($_FILES['vendor_sign']['tmp_name'] != "")
	{		
	    $vendor_sign = "sign_".time().$_FILES['vendor_sign']['name'];
		
		include('../api/inc/s3_config.php');
		if($s3->putObjectFile($_FILES['vendor_sign']['tmp_name'], 'sd-prod-101-signature', $vendor_sign, S3::ACL_PUBLIC_READ) )
		{
			$sql = "insert sd_vendors set
				vendor_sign='". $vendor_sign."'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));
			
			if($res)
			{
				$returnArray['inserted_id']= mysqli_insert_id($link) ;
				$returnArray['vendor_sign'] = $vendor_sign;
				$returnArray['success'] = true;
				$returnArray['msg'] = "Vendor sign been added successfully";
				
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
			$returnArray['msg'] = "Vendor sign not uploaded";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please upload a valid Vendor sign";
	}	
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "form2_edit")
{
	
	if($_FILES['vendor_sign']['tmp_name'] != "")
	{	
	
		$sql = "select vendor_sign from sd_vendors where vendor_id='".$_POST['edit_id']."' ";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$num_row = mysqli_num_rows($res);
		$row = mysqli_fetch_array($res);
		if($row['vendor_sign'] != '')
		{			
			$s3->deleteObject('sd-prod-101-category',  $row['category_image']);
		}
		
	    $vendor_sign = "sign_".time().$_FILES['vendor_sign']['name'];
		
		include('../api/inc/s3_config.php');
		if($s3->putObjectFile($_FILES['vendor_sign']['tmp_name'], 'sd-prod-101-signature', $vendor_sign, S3::ACL_PUBLIC_READ) )
		{
			$sql = "update sd_vendors set
				vendor_sign='". $vendor_sign."' where vendor_id='".$_POST['edit_id']."'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));
			
			if($res)
			{
				$returnArray['inserted_id']= $_POST['edit_id'] ;
				$returnArray['vendor_sign'] = $vendor_sign;
				$returnArray['success'] = true;
				$returnArray['msg'] = "Vendor sign been updated successfully";
				
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
			$returnArray['msg'] = "Vendor sign not uploaded";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please upload a valid Vendor sign";
	}
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form3_add")
{	
	$shop_off_days = "N/A";
	if(isset($_POST['shop_off_days']))
	{
		if(count($_POST['shop_off_days']) > 0)
		{
			$shop_off_days = array();
			foreach ($_POST['shop_off_days'] as $shop_off_day)
			{ 
				array_push($shop_off_days, $shop_off_day);
			}
			$shop_off_days = implode(",", $shop_off_days);
		}
	}
	
	
	$sql = "insert sd_vendors set
				shop_name='".addslashes(trim($_POST['shop_name']))."',
				description='".addslashes(trim($_POST['description']))."',
				shop_years='".addslashes(trim($_POST['shop_years']))."',
				shop_address1='".addslashes(trim($_POST['shop_address1']))."',
				shop_open_time='".$_POST['shop_open_time']."',
				shop_close_time='".$_POST['shop_close_time']."',
				shop_off_days='".$shop_off_days."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$returnArray['inserted_id']= mysqli_insert_id($link) ;
		$returnArray['success'] = true;
		$returnArray['msg'] = "Shop Information has been added successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "form3_edit")
{
	$shop_off_days = "N/A";
	if(isset($_POST['shop_off_days']))
	{
		if(count($_POST['shop_off_days']) > 0)
		{
			$shop_off_days = array();
			foreach ($_POST['shop_off_days'] as $shop_off_day)
			{ 
				array_push($shop_off_days, $shop_off_day);
			}
			$shop_off_days = implode(",", $shop_off_days);
		}
	}

	$sql = "update sd_vendors set
				shop_name='".addslashes(trim($_POST['shop_name']))."',
				shop_years='".addslashes(trim($_POST['shop_years']))."',
				description='".addslashes(trim($_POST['description']))."',
				shop_address1='".addslashes(trim($_POST['shop_address1']))."',
				shop_address2='".addslashes(trim($_POST['shop_address2']))."',
				shop_open_time='".$_POST['shop_open_time']."',
				shop_close_time='".$_POST['shop_close_time']."',
				shop_off_days='".$shop_off_days."' 
			where vendor_id='".$_POST['edit_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$returnArray['inserted_id']= $_POST['edit_id'] ;
		$returnArray['success'] = true;
		$returnArray['msg'] = "Shop Information been updated successfully";
		
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later";
	}
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form4_add") // shop image .. so always add no edit
{
	if($_FILES['shop_image']['tmp_name'] != "")
	{

		if($_POST['edit_id'] == "")
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please submit vendor details first. Then try to upload Image.";
		}
		else
		{
			$shop_image = "photo_".time().$_FILES['shop_image']['name'];
		
			include('../api/inc/s3_config.php');
			if($s3->putObjectFile($_FILES['shop_image']['tmp_name'], 'sd-prod-101-img', $shop_image, S3::ACL_PUBLIC_READ) )
			{
				$sql = "insert sd_photos set
					photo_name='". $shop_image."',
					vendor_id='".$_POST['edit_id']."',
					created_on=NOW()";
				$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
				
				if($res)
				{		
					$returnArray['inserted_id']= $_POST['edit_id'] ;			
					$returnArray['success'] = true;
					$returnArray['photo_name'] = $shop_image;
					$returnArray['msg'] = "Image been added successfully";
					
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
				$returnArray['msg'] = "Image not uploaded";
			}			
		}	    
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please upload a valid Image";
	}	
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form5_add") // shop Video .. so always edit no add
{	
	if($_POST['edit_id'] == "")
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please submit vendor details first. Then try to upload video URL.";
	}
	else
	{		
		$sql = "insert sd_videos set
			video_name='". trim($_POST['video_name'])."',
			vendor_id='".$_POST['edit_id']."',
			created_on=NOW(),
			flag='1'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		
		if($res)
		{	
			$returnArray['inserted_id']= $_POST['edit_id'] ;				
			$returnArray['success'] = true;
			$returnArray['msg'] = "Video URL has been added successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		}
				
	}	    
		
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form6_edit") // Other information .. so always edit no add
{	
	if($_POST['edit_id'] == "")
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please submit vendor details first. Then try to update other informations.";
	}
	else
	{		
		$sql = "update sd_vendors set
					price_id='". $_POST['price_id']."',
					pricing_policy='".$_POST['pricing_policy']."',
					warranty ='".$_POST['warranty']."',
					exchange ='".$_POST['exchange']."',
					home_delivery ='".$_POST['home_delivery']."',
					parking ='".$_POST['parking']."',
					washroom ='".$_POST['washroom']."'
				where vendor_id='".$_POST['edit_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		
		if($res)
		{	
			$returnArray['inserted_id']= $_POST['edit_id'] ;				
			$returnArray['success'] = true;
			$returnArray['msg'] = "other informations has been added successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		}
				
	}	    
		
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form7_edit") // area information .. so always edit no add
{	
	if($_POST['edit_id'] == "")
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please submit vendor details first. Then try to update area information.";
	}
	else
	{		
		$sql = "update sd_vendors set
					location_id='". $_POST['location_id']."'					
				where vendor_id='".$_POST['edit_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		
		if($res)
		{	
			$returnArray['inserted_id']= $_POST['edit_id'] ;				
			$returnArray['success'] = true;
			$returnArray['msg'] = "Area information has been added successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		}
				
	}	    
		
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form8_add") // Product image .. so always add no edit
{
	if($_FILES['product_image']['tmp_name'] != "")
	{

		if($_POST['edit_id'] == "")
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please submit vendor details first. Then try to upload Image.";
		}
		else
		{
			$product_image = "photo_".time().$_FILES['product_image']['name'];
		
			include('../api/inc/s3_config.php');
			if($s3->putObjectFile($_FILES['product_image']['tmp_name'], 'sd-prod-101-img', $product_image, S3::ACL_PUBLIC_READ) )
			{
				$sql = "insert sd_product_image set
					product_image='". $product_image."',
					product_name='".addslashes(trim($_POST['product_name']))."',
					product_id='".addslashes(trim($_POST['product_id']))."',
					product_price='".addslashes(trim($_POST['product_price']))."',
					vendor_id='".$_POST['edit_id']."',
					created_on=NOW()";
				$res = mysqli_query($link, $sql) or die(mysqli_error($link));
				
				if($res)
				{
					$returnArray['inserted_id']= $_POST['edit_id'] ;
					$returnArray['product_image']= $product_image ;	
					$returnArray['product_name']= $_POST['product_name'] ;	
					$returnArray['product_id']= $_POST['product_id'] ;
					$returnArray['product_price']= $_POST['product_price'] ;					
					$returnArray['success'] = true;
					$returnArray['msg'] = "Image has been added successfully";
					
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
				$returnArray['msg'] = "Image not uploaded";
			}			
		}	    
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please upload a valid Image";
	}	
	echo json_encode($returnArray);
}

elseif($_POST['func'] == "form9_add") // category .. so always edit no add
{	
	
	if($_POST['edit_id'] == "")
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please submit vendor details first. Then try to update other informations.";
	}
	else
	{	
		$succ = true;
		
		$sql = "delete from sd_vendors_category where vendor_id='".$_POST['edit_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			foreach($_POST['category_id'] as $category_id)
			{
				$sql = "insert sd_vendors_category 
						set vendor_id='".$_POST['edit_id']."',
						category_id='$category_id'";

			$res = mysqli_query($link, $sql) or die(mysqli_error($link));
			if(!$res)
			{
				$succ = false;
			}
		}
		
		}
		if($succ)
		{					
			$returnArray['inserted_id']= $_POST['edit_id'] ;
			$returnArray['success'] = true;
			$returnArray['msg'] = "Category has been added successfully";
			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Please try again later";
		}
				
	}	    
		
	echo json_encode($returnArray);
}

elseif($_POST['func'] == 'delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Vendor deletion is not successful.";
	
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='8'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['delete_p'] = $row['delete_p'];
	if($row['delete_p'])
	{
		$sql = "delete from sd_vendors where vendor_id='".$_POST['id']."'";
		$returnArray['sql'] = $sql;
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Vendor deletion is successful.";
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
	$sql ="select * from sd_vendors where vendor_id='".$_POST['id']."'";
	//echo $sql;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['vendor_id'] = $row['vendor_id'];
	$returnArray['data']['vendor_fname'] = stripslashes($row['vendor_fname']);
	$returnArray['data']['vendor_lname'] = stripslashes($row['vendor_lname']);
	$returnArray['data']['vendor_mobile'] = stripslashes($row['vendor_mobile']);
	$returnArray['data']['shop_name'] = stripslashes($row['shop_name']);
	$returnArray['data']['description'] = stripslashes($row['description']);
	$returnArray['data']['shop_address1'] = stripslashes($row['shop_address1']);
	$returnArray['data']['shop_address2'] = stripslashes($row['shop_address2']);
	$returnArray['data']['shop_open_time'] = stripslashes($row['shop_open_time']);
	$returnArray['data']['shop_close_time'] = stripslashes($row['shop_close_time']);
	$returnArray['data']['shop_off_days'] = stripslashes($row['shop_off_days']);
	$returnArray['data']['have_shartphone'] = stripslashes($row['have_shartphone']);
	$returnArray['data']['location_id'] = stripslashes($row['location_id']);
	$returnArray['data']['price_id'] = stripslashes($row['price_id']);
	$returnArray['data']['warranty'] = stripslashes($row['warranty']);
	$returnArray['data']['exchange'] = stripslashes($row['exchange']);
	$returnArray['data']['home_delivery'] = stripslashes($row['home_delivery']);
	$returnArray['data']['parking'] = stripslashes($row['parking']);
	$returnArray['data']['washroom'] = stripslashes($row['washroom']);
	$returnArray['data']['vendor_sign'] = stripslashes($row['vendor_sign']);
	$returnArray['data']['shop_years'] = stripslashes($row['shop_years']);
	$returnArray['data']['pricing_policy'] = stripslashes($row['pricing_policy']);
	$returnArray['data']['have_facebook'] = stripslashes($row['have_facebook']);
	$returnArray['data']['have_whatsapp'] = stripslashes($row['have_whatsapp']);
	
	$sql = "select video_name from sd_videos where vendor_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['video_name'] = $row['video_name'];

	$returnArray['data']['photos'] = array();
	$sql = "select * from sd_photos where vendor_id='".$_POST['id']."'"	;
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;	
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['photos'][$i]['photo_name'] =$row['photo_name'];
		$returnArray['data']['photos'][$i]['id'] =$row['photo_id'];
		$i++;
	}

	$sql = "select city_id from location where location_id = '".$returnArray['data']['location_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['data']['city_id'] = $row['city_id'];

	$sql = "select state_id from city where city_id='".$returnArray['data']['city_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['data']['state_id'] = $row['state_id'];

	$returnArray['data']['city_list'] = array();
	$sql = "select * from city where state_id='".$returnArray['data']['state_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['city_list'][$i]['city_name'] = stripslashes($row['city']);
		$returnArray['data']['city_list'][$i]['city_id'] = stripslashes($row['city_id']);
		$i++;
	}

	$returnArray['data']['location_list'] = array();
	$sql = "select * from location where city_id='".$returnArray['data']['city_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['location_list'][$i]['location_name'] = stripslashes($row['location']);
		$returnArray['data']['location_list'][$i]['location_id'] = stripslashes($row['location_id']);
		$i++;
	}

	$returnArray['data']['product_photos']= array();
	$sql = "select * from sd_product_image where vendor_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;	
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['product_photos'][$i]['product_image'] =$row['product_image'];
		$returnArray['data']['product_photos'][$i]['product_image_id'] =$row['product_image_id'];
		
		$returnArray['data']['product_photos'][$i]['product_name']= $row['product_name'] ;
		$returnArray['data']['product_photos'][$i]['product_id']= $row['product_id'] ;	
		$returnArray['data']['product_photos'][$i]['product_price']= $row['product_price'] ;
		$i++;
	}

	$returnArray['data']['product_category'] = array();
	$sql = "select * from  sd_vendors_category where vendor_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$i=0;	
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['product_category'][$i] = $row['category_id'];
		$i++;
	}

	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'img_delete'){
	$returnArray['success'] = false;
	$returnArray['msg'] = "Image deletion is not successful.";
	
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='8'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['delete_p'] = $row['delete_p'];
	if($row['delete_p'])
	{	
		$sql = "select vendor_id from sd_photos where photo_id ='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res);
		$vendor_id= $row['vendor_id'];
		
		$sql = "delete from sd_photos where photo_id ='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		
		$returnArray['data']['photos'] = array();
		$sql = "select * from sd_photos where vendor_id='$vendor_id'"	;
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$i=0;	
		while($row = mysqli_fetch_array($res))
		{
			$returnArray['data']['photos'][$i]['photo_name'] =$row['photo_name'];
			$returnArray['data']['photos'][$i]['id'] =$row['photo_id'];
			$i++;
		}
		$returnArray['success'] = true;
		$returnArray['msg'] = "Image deletion is successful.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";	
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'prd_delete'){
	$returnArray['success'] = false;
	$returnArray['msg'] = "Image deletion is not successful.";

	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='8'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['delete_p'] = $row['delete_p'];
	if($row['delete_p'])
	{
		$sql = "select vendor_id from sd_product_image where product_image_id ='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($res);
		$vendor_id= $row['vendor_id'];

		$sql = "delete from sd_product_image where product_image_id ='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		
		$returnArray['data']['product_photos']= array();
		$sql = "select * from sd_product_image where vendor_id='$vendor_id'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$i=0;	
		while($row = mysqli_fetch_array($res))
		{
			$returnArray['data']['product_photos'][$i]['product_image'] =$row['product_image'];
			$returnArray['data']['product_photos'][$i]['product_image_id'] =$row['product_image_id'];
			
			$returnArray['data']['product_photos'][$i]['product_name']= $row['product_name'] ;	
			$returnArray['data']['product_photos'][$i]['product_id']= $row['product_id'] ;
			$returnArray['data']['product_photos'][$i]['product_price']= $row['product_price'] ;
			$i++;
		}
		
		$returnArray['success'] = true;
		$returnArray['msg'] = "Product Image deletion is successful.";
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";	
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == 'video_delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Video deletion is not successful.";

	//$res=0;
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='8'"; 
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row = mysqli_fetch_array($res);
	$returnArray['delete_p'] = $row['delete_p'];
	if($row['delete_p'])
	{
		$sql = "delete from sd_videos where vendor_id ='".$_POST['id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Vendor video deletion is successful.";

		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Vendor video deletion is not successful.";
			$returnArray['sql'] = $sql;
		}	
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Permission Denied.";	
	}
	echo json_encode($returnArray);
}

?>