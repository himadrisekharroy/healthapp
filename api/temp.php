<?php
include("../admin_api/config.php");
$funcName = $_REQUEST['func'];
// Test - Image Upload
if($funcName == 'Presc-Upload')
{
	$returnArray= array();
	
	/*if($_SERVER['REQUEST_METHOD'] == 'POST')
	{*/
		$DefaultId = 0; 
		$ImageData = $_REQUEST['image_data']; 
		$ImageName = $_REQUEST['image_tag'];
		//$ImagePath = "s1/$ImageName.jpg";
		$ImagePath = "s1/$ImageName";
		$ServerURL = "s2/$ImageName.jpg"; 
		$InsertSQL = "INSERT INTO imageupload (image_path,image_name) values('$ServerURL','$ImageName')";
 
		if(mysqli_query($link, $InsertSQL))
		{
			file_put_contents($ImagePath,base64_decode($ImageData));
			//$returnArray['msg'] = "Your Image Has Been Uploaded.";
			//$returnArray['data'] =$ImageData;
			echo "Your Image Has Been Uploaded.";
		}
		else
    	{
    		echo  "Please Try Again";
    	}
	/*}
	else
	{
		$returnArray['msg'] = "Please Try Again";
	}*/
	//$returnArray['data'] =$ImageData;
	//echo json_encode($returnArray);
	//echo $ImageData;
}
// End of Test - Image Upload
?>