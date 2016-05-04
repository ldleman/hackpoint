<?php 
	session_start('hackpoint-session');
	mb_internal_encoding('UTF-8');
	
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'constant.php');
	require_once(__ROOT__.'function.php');
	date_default_timezone_set(TIME_ZONE); 

	set_error_handler('errorToException');
	spl_autoload_register('app_autoloader');
	
	if(!file_exists(__ROOT__.DATABASE_PATH)) header('location:install.php');

	global $myUser,$conf,$_,$success;
	
	$_ = array_map('secure_user_vars',array_merge($_POST,$_GET));
	$myUser = new User();
	$conf = new Configuration();
	$conf->getAll();
	$page = basename($_SERVER['PHP_SELF']);

	if(isset($_SESSION['currentUser']))
		$myUser =unserialize($_SESSION['currentUser']);
	
	
	

?>