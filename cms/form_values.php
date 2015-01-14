<?
$ids = array();

if(!isset($_GET['search']) && method_exists(core::$PAGE,'printModules'))
	core::$PAGE->printModules("right_top");

if(core::$PAGE->FORM_SUCCESS == true || (isset($_POST['check_type']) && $_POST['check_type'] == "mod" && count($_POST) < 3)){
	
	if(core::$PAGE->CAN_ADD == true)
		core::$PAGE->printForm("add");
	
}elseif((isset($_POST['check_type']) && $_POST['check_type'] == "mod") || (isset($_POST['form_type']) && $_POST['form_type'] == "mult_edit") && core::$PAGE->CAN_MOD == true){
	
	if(isset($_POST['form_type']))					
		foreach($_POST as $key => $value){
			
			$tmp = explode("-",$key);
			$id  = array_pop($tmp);
			
			if(!is_numeric($id))
				$id = array_pop($tmp);
			
			if(isset($id) && !$ids[$id])
				$ids[$id] = $id;
			
		}					
	else					
		foreach($_POST as $key => $value){
	
			list($check,$id) = explode("_",$key);
			if($check == "check")			
				if(sql::exists(core::$PAGE->TABLE,array("id"=>$id)))
					$ids[] = $id;
			
		}
	
	core::$PAGE->printForm("mult_edit",$ids);

}elseif(!isset($_GET['search']))
	if((isset($_GET['action']) && $_GET['action'] == "edit") || (isset($_POST['form_type']) && $_POST['form_type'] == "edit") && core::$PAGE->FORM_SUCCESS != true)		
		if(isset($_GET['id']))
			core::$PAGE->printForm("edit",$_GET['id']);
		else{
			
			foreach($_POST as $key => $value)
				$tmp[] = $key;
			
			$tmp = explode("-",$tmp[2]);
			$num = array_pop($tmp);
			
			if(!is_numeric($num))
				$num = array_pop($tmp);
			
			core::$PAGE->printForm("edit",$num);
		
		}		
	else				
		if(core::$PAGE->CAN_ADD == true)
			core::$PAGE->printForm("add");

if(!isset($_GET['search']) && method_exists(core::$PAGE,'printModules'))
	core::$PAGE->printModules("right_mid");

if(core::$PAGE->CUSTOM_ORDER) 
	echo '<div id="custom_order_notice"><div id="custom_order_icon"></div>U kunt door te slepen de volgorde van de rijen aanpassen</div>';	

if(core::$PAGE->CAN_VIEW == true){
	
	core::$PAGE->printCols();	
	core::$PAGE->printValues();

}

if(method_exists(core::$PAGE,'draw'))
	core::$PAGE->draw();

if(!isset($_GET['search']) && method_exists(core::$PAGE,'printModules'))
	core::$PAGE->printModules("right_bot");
	
if(method_exists(core::$PAGE,'draw_page'))	
	core::$PAGE->draw_page();

if(core::$PAGE->CAN_ADD == false && core::$PAGE->CAN_VIEW == false && !(isset($_GET['action']) && $_GET['action'] == "edit"))
	echo '<script type="text/javascript">$("._special").each(function(){ $(this).css("display","none") });</script>';
	
?>