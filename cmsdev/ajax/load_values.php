<?
if(!$_POST['start'])
	exit;
	
if(!is_numeric($_POST['start']))
	exit;
	
require '../config.php';

if(isset($_GET['logout']) && $_GET['logout'] == true)
	core::logout();
	
if(isset($_POST['forgot_pass']))
	$login_error = core::forgotPass($_POST['login_name']);
else{
	
	if((isset($_POST['login_name']) && isset($_POST['login_password'])) && core::$USER == false)
		$login_error = core::login($_POST['login_name'],$_POST['login_password']);
	else
		core::checkLogin();
		
	if(!core::$PAGE)	
		core::handlePage($_POST['page']);

	core::$PAGE->LIMIT = sql::escape($_POST['start']).",50";

	core::$PAGE->handleSelector();
	
	core::$PAGE->FIRST_CALC = 0;
	core::$PAGE->calculate();
	
	core::$PAGE->printValues(true);
		
}
?>