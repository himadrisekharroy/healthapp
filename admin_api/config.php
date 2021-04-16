<?php
//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
//ini_set('display_errors', 1);
date_default_timezone_set('Asia/Kolkata');

ob_start();
session_start();
define("SITE_URL","http://www.demo.ritzcybernetics.com/health_app/");
define("ADMIN_URL","http://www.demo.ritzcybernetics.com/health_app/admin/");
define("ABS_PATH",$_SERVER['DOCUMENT_ROOT'].'/'); 

define('BASEPATH', SITE_URL); // for use the database details from CI
define('ENVIRONMENT','production'); // for use the database details from CI

include("database.php");

$link=mysqli_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password']);
if($link)
{
	mysqli_select_db($link,$db['default']['database']) or die(mysqli_error($link));
}

?>