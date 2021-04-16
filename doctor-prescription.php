<?php 
include("telemed_admin_api/config.php");
$info = "";
//echo "<pre>";

//print_r($_REQUEST);
foreach ($_GET as $key => $value) {
	$info = $key;
	break;
}
$info = base64_decode($info);
//$info = explode("_", $info);
//echo $info; 
$ext = pathinfo($info, PATHINFO_EXTENSION);
$ext = strtolower($ext);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Actora:: Doctor prescription</title>
</head>
<body>
	<?php
	if($ext == "pdf")
	{	
	?>
	<object id="view_container" data="<?php echo SITE_URL?>prescription_file/<?php echo $info?>" type="application/pdf" width="100%" style="height: 100vh">
	       alt : <a href="prescription_file/<?php echo $info?>"><?php echo $info ?></a>
	   </object>
	<?php
	}
	elseif($ext == 'txt'){
	?>
	<object id="view_container" data="<?php echo SITE_URL?>prescription_file/<?php echo $info?>" type="text/plain" width="100%" style="height: 100vh">
	       alt : <a href="<?php echo SITE_URL?>prescription_file/<?php echo $info?>"><?php echo $info ?></a>
	</object>
	<?php
	}
	elseif($ext == 'jpg')
	{
		?>
		<img src="<?php echo SITE_URL?>prescription_file/<?php echo $info?>" style="width: 100%">
		<?php
	}
	elseif ($ext == 'jpeg') {
		?>
		<img src="<?php echo SITE_URL?>prescription_file/<?php echo $info?>" style="width: 100%">
		<?php
	}
	elseif ($ext == 'png') {
		?>
		<img src="<?php echo SITE_URL?>prescription_file/<?php echo $info?>" style="width: 100%">
		<?php
	}
	?>
	</body>

</html>