<?php
include("config.php");

$returnArray= array();

//print_r($_POST);
//print_r($_FILES);
if($_POST['func']=='list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from  sd_categories 
			 where category_parent_id =  '0' order by  category_name";
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
		
		$j=0; 
		$sql = "select * from  sd_categories 
			 where category_parent_id =  '".$row['category_id']."' order by  category_name";
		$resInner = mysqli_query($link, $sql) or die(mysqli_error($link));	
		while($rowInner = mysqli_fetch_array($resInner))
		{
			$returnArray['data'][$i]['child'][$j]['category_id'] = $rowInner['category_id'];
			$returnArray['data'][$i]['child'][$j]['category_name'] = stripslashes($rowInner['category_name']);
			$returnArray['data'][$i]['child'][$j]['category_image'] = stripslashes($rowInner['category_image']);
			$returnArray['data'][$i]['child'][$j]['category_parent_id'] = stripslashes($rowInner['category_parent_id']);
			$returnArray['data'][$i]['child'][$j]['created_on'] = stripslashes($rowInner['created_on']);
			$returnArray['data'][$i]['child'][$j]['is_active'] = $rowInner['is_active'];
			
			$k=0; 
			$sql = "select * from  sd_categories 
				 where category_parent_id =  '".$rowInner['category_id']."' order by  category_name";
			$resInner1 = mysqli_query($link, $sql) or die(mysqli_error($link));	
			while($rowInner1 = mysqli_fetch_array($resInner1))
			{
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_id'] = $rowInner1['category_id'];
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_name'] = stripslashes($rowInner1['category_name']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_image'] = stripslashes($rowInner1['category_image']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_parent_id'] = stripslashes($rowInner1['category_parent_id']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['created_on'] = stripslashes($rowInner1['created_on']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['is_active'] = $rowInner1['is_active'];	
				$k++;
			}
			
				
			$j++;
		}
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func']=='active_list')
{
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found.";
	$sql = "select * from  sd_categories 
			 where category_parent_id =  '0' and is_active='1' order by  category_name";
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
		$returnArray['data'][$i]['child'] =array();		
		
		$j=0; 
		$sql = "select * from  sd_categories 
			 where category_parent_id =  '".$row['category_id']."' and is_active='1' order by  category_name";
		$resInner = mysqli_query($link, $sql) or die(mysqli_error($link));	
		while($rowInner = mysqli_fetch_array($resInner))
		{
			$returnArray['data'][$i]['child'][$j]['category_id'] = $rowInner['category_id'];
			$returnArray['data'][$i]['child'][$j]['category_name'] = stripslashes($rowInner['category_name']);
			$returnArray['data'][$i]['child'][$j]['category_image'] = stripslashes($rowInner['category_image']);
			$returnArray['data'][$i]['child'][$j]['category_parent_id'] = stripslashes($rowInner['category_parent_id']);
			$returnArray['data'][$i]['child'][$j]['created_on'] = stripslashes($rowInner['created_on']);
			$returnArray['data'][$i]['child'][$j]['is_active'] = $rowInner['is_active'];
			$returnArray['data'][$i]['child'][$j]['child'] =array();	
			$k=0; 
			$sql = "select * from  sd_categories 
				 where category_parent_id =  '".$rowInner['category_id']."' and is_active='1' order by  category_name";
			$resInner1 = mysqli_query($link, $sql) or die(mysqli_error($link));	
			while($rowInner1 = mysqli_fetch_array($resInner1))
			{
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_id'] = $rowInner1['category_id'];
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_name'] = stripslashes($rowInner1['category_name']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_image'] = stripslashes($rowInner1['category_image']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['category_parent_id'] = stripslashes($rowInner1['category_parent_id']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['created_on'] = stripslashes($rowInner1['created_on']);
				$returnArray['data'][$i]['child'][$j]['child'][$k]['is_active'] = $rowInner1['is_active'];	
				$k++;
			}
			
				
			$j++;
		}
		$i++;
	}
	echo json_encode($returnArray);
}
elseif($_POST['func'] == "status_change")
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Category status change is not successful.";
	
	$sql = "update sd_categories set is_active = if(is_active = '1','0', '1' ) where category_id='".$_POST['id']."'";	
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));
	
	if($res)
	{
		$sql = "select is_active from sd_categories where category_id='".$_POST['id']."'";
		$res_inner = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_inner = mysqli_fetch_array($res_inner);
		
		$returnArray['success'] = true;
		$returnArray['msg'] = " Category status has been successfully changed.";
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
elseif($_POST['func'] == "add")
{
	if($_FILES['category_image']['tmp_name'] != "")
	{		
	    $category_image = "cat_".time().$_FILES['category_image']['name'];
		
		include('../api/inc/s3_config.php');
		if($s3->putObjectFile($_FILES['category_image']['tmp_name'], 'sd-prod-101-category', $category_image, S3::ACL_PUBLIC_READ) )
		{
			$sql = "insert sd_categories set 
						category_name='".addslashes(trim($_POST['category_name']))."',
						category_image='".$category_image."',
						category_parent_id='".$_POST['category_parent_id']."',
						created_on=NOW(),
						is_active='1'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));
			
			$returnArray['success'] = true;
			$returnArray['msg'] = "Category has been added successfully.";
			
		}else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Error in uploading Category Image.";
		}
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please select a valid file.";
	}
	echo json_encode($returnArray);
	
}
elseif($_POST['func'] == 'delete')
{
	$returnArray['success'] = false;
	$returnArray['msg'] = "Category deletion is not successful.";
	$sql = "select delete_p 
			from sd_role_module_permission 
			join sd_admin on (sd_admin.admin_role = sd_role_module_permission.role_id)
			where admin_id='".$_POST['admin_id']."' and module_id='7'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link)); 
	$row = mysqli_fetch_array($res);
	if($row['delete_p'])
	{
		$sql = "select category_image from sd_categories where category_id='".$_POST['id']."' ";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$num_row = mysqli_num_rows($res);
		$row = mysqli_fetch_array($res);
		if($num_row > 0)
		{
			if($row['category_image'] != '')
			{
				include('../api/inc/s3_config.php');
				$s3->deleteObject('sd-prod-101-category',  $row['category_image']);
				
				$sql = "delete from sd_categories where category_id='".$_POST['id']."'";
				$res = mysqli_query($link, $sql) or die(mysqli_error($link));
				if($res)
				{
					$returnArray['success'] = true;
					$returnArray['msg'] = "Category deletion is successful.";
				}
				else
				{
					$returnArray['success'] = false;
					$returnArray['msg'] = "category deletion is not successful.";
				}
			}
			else
			{
				$sql = "delete from sd_categories where category_id='".$_POST['id']."'";
				$res = mysqli_query($link, $sql) or die(mysqli_error($link));
				if($res)
				{
					$returnArray['success'] = true;
					$returnArray['msg'] = "Category deletion is successful.";
				}
				else
				{
					$returnArray['success'] = false;
					$returnArray['msg'] = "category deletion is not successful.";
				}
			}
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "No category found. Please refresh the page and try again.";
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
	$parentIdL1=0;
	$parentIdL2=0;
	
	$sql ="select * from sd_categories where category_id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$row = mysqli_fetch_array($res);
	$returnArray['data']['category_id'] = $row['category_id'];
	$returnArray['data']['category_name'] = stripslashes($row['category_name']);
	$returnArray['data']['category_image'] = stripslashes($row['category_image']);
	$returnArray['data']['category_parent_id'] = stripslashes($row['category_parent_id']);
		
	if($row['category_parent_id'] != 0)
	{
		$returnArray['data']['tree'] = $row['category_parent_id'].",". $row['category_id'];
		
		$sql ="select category_parent_id from sd_categories where category_id='".$row['category_parent_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
		$row = mysqli_fetch_array($res);
		
		if($row['category_parent_id'] != 0)
		{
			$returnArray['data']['tree'] = $row['category_parent_id'].",". $returnArray['data']['tree'];	
			
			$sql ="select category_parent_id from sd_categories where category_id='".$row['category_parent_id']."'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
			$row = mysqli_fetch_array($res);
			
			if($row['category_parent_id'] != 0)
			{
				$returnArray['data']['tree'] = $row['category_parent_id'].",".$returnArray['data']['tree'];	
			}
			else
			{
				$returnArray['data']['tree'] =  "0,".	$returnArray['data']['tree'];
			}
		}
		else
		{
			$returnArray['data']['tree'] = "0,".	$returnArray['data']['tree'];
		}
	}
	else
	{
		$returnArray['data']['tree'] = "0,". $row['category_id'];
	}
	
	$sql = "select * from  sd_categories 
			 where category_parent_id =  '0' and is_active='1' order by  category_name";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link));	
	$i=0;
	while($row = mysqli_fetch_array($res))
	{
		$returnArray['data']['cat_list'][$i]['category_id'] = $row['category_id'];
		$returnArray['data']['cat_list'][$i]['category_name'] = stripslashes($row['category_name']);
		
		
		$j=0; 
		$sql = "select * from  sd_categories 
			 where category_parent_id =  '".$row['category_id']."' and is_active='1' order by  category_name";
		$resInner = mysqli_query($link, $sql) or die(mysqli_error($link));	
		while($rowInner = mysqli_fetch_array($resInner))
		{
			$returnArray['data']['cat_list'][$i]['child'][$j]['category_id'] = $rowInner['category_id'];
			$returnArray['data']['cat_list'][$i]['child'][$j]['category_name'] = stripslashes($rowInner['category_name']);
			
			
			$k=0; 
			$sql = "select * from  sd_categories 
				 where category_parent_id =  '".$rowInner['category_id']."' and is_active='1' order by  category_name";
			$resInner1 = mysqli_query($link, $sql) or die(mysqli_error($link));	
			while($rowInner1 = mysqli_fetch_array($resInner1))
			{
				$returnArray['data']['cat_list'][$i]['child'][$j]['child'][$k]['category_id'] = $rowInner1['category_id'];
				$returnArray['data']['cat_list'][$i]['child'][$j]['child'][$k]['category_name'] = stripslashes($rowInner1['category_name']);
			
				$k++;
			}
			
				
			$j++;
		}
		$i++;
	}
	
	
	
		
	$returnArray['success'] = true;
	$returnArray['msg'] = "Data Found..";
	
	echo json_encode($returnArray);

}
elseif($_POST['func'] == 'edit_save')
{
	if($_FILES['category_image']['name'] != '')
	{
		include('../api/inc/s3_config.php');
		
		$sql = "select category_image from sd_categories where category_id='".$_POST['edit_id']."' ";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		$num_row = mysqli_num_rows($res);
		$row = mysqli_fetch_array($res);
		if($row['category_image'] != '')
		{			
			$s3->deleteObject('sd-prod-101-category',  $row['category_image']);
		}
		
		$category_image = "cat_".time().$_FILES['category_image']['name'];
		if($s3->putObjectFile($_FILES['category_image']['tmp_name'], 'sd-prod-101-category', $category_image, S3::ACL_PUBLIC_READ) )
		{
			$sql = "update sd_categories set 
						category_name='".addslashes(trim($_POST['category_name']))."',
						category_image='".$category_image."',
						category_parent_id='".$_POST['category_parent_id']."'
						where category_id='".$_POST['edit_id']."'";
			$res = mysqli_query($link, $sql) or die(mysqli_error($link));
			
			$returnArray['success'] = true;
			$returnArray['msg'] = "Category has been updated successfully.";
			
		}else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Error in uploading Category Image.";
		}
		
	}
	else
	{
		$sql = "update sd_categories set 
					category_name='".addslashes(trim($_POST['category_name']))."',						
					category_parent_id='".$_POST['category_parent_id']."'
					where category_id='".$_POST['edit_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link));
		if($res)	
		{
			$returnArray['success'] = true;
			$returnArray['msg'] = "Category has been updated successfully.";			
		}
		else
		{
			$returnArray['success'] = false;
			$returnArray['msg'] = "Error in uploadating Category.";
		}
	}
	
	echo json_encode($returnArray);
}
?>