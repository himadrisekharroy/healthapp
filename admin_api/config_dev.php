<?php
error_reporting(E_ALL& ~E_NOTICE);
//ini_set('display_errors', 1);
date_default_timezone_set('Asia/Kolkata');

ob_start();
session_start();
define("SITE_URL","https://www.actora.us/");
define("ADMIN_URL","https://www.actora.us/admin/");
define("ABS_PATH",$_SERVER['DOCUMENT_ROOT'].'/'); 

define('BASEPATH', SITE_URL); // for use the database details from CI
define('ENVIRONMENT','production'); // for use the database details from CI

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'thesanam_actora',
	'password' => 'thesanam_actora',
	'database' => 'thesanam_actora_dev',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$link=mysqli_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password']);
if($link)
{
	mysqli_select_db($link,$db['default']['database']) or die(mysqli_error($link));
}

?>