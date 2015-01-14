<?
unset($_SESSION['browser_secure']);

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
	
	if(isset($_GET['action']) && $_GET['action'] == "del" && core::$USER != false && core::$PAGE->CAN_DEL == true)
		if(sql::exists(core::$PAGE->TABLE,array("id"=>$_GET['id'])))
			sql::del(core::$PAGE->TABLE,array("id"=>$_GET['id']));		
			
	if(isset($_POST['check_type']) && $_POST['check_type'] == "del" && core::$PAGE->CAN_DEL == true){
	
		foreach($_POST as $key => $value){
			
			list($check,$id) = explode("_",$key);
			if($check == "check")			
				if(sql::exists(core::$PAGE->TABLE,array("id"=>$id)))
					sql::del(core::$PAGE->TABLE,array("id"=>$id));
			
		}
	
	}
	
	if(method_exists(core::$PAGE,'handleSelector'))
		core::$PAGE->handleSelector();

	if(method_exists(core::$PAGE,'calculate'))
		core::$PAGE->calculate();

	if(method_exists(core::$PAGE,'handleForm'))
		core::$PAGE->handleForm();

}
?>