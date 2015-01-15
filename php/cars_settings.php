<?
if(!is_object('core'))
	require dirname(__FILE__).'/config.php';
	
$page					= $_POST['page'] ? $_POST['page'] : ($_SESSION['page'] && SESSION_MATCH ? $_SESSION['page'] : 1);
if(core::$cur_page['id'] == 2)
	$_SESSION['special'] = 'voorraad';
elseif(core::$cur_page['id'] == 3) // Aanbiedingen pagina
    $_SESSION['special'] = 'actieprijs';
elseif(core::$cur_page['id'] == 4) // Milieubewust pagina
    $_SESSION['special'] = 'milieu_bewust';  
else    
    $_SESSION['special'] = $_SESSION['special'] ?: 'voorraad';

if(substr($_POST['curr'],0,4) != 'page' && !empty($_POST['prev']) && !empty($_POST['curr']))
	$page = 1;
    
$_SESSION['page']         = $page;

$search 				= array();

$search['sort']['type']		= $_POST['sort']   ? $_POST['sort']   
										 : ($_SESSION['sort'] && SESSION_MATCH ? $_SESSION['sort'] : 'name');
                                         
$_SESSION['sort']         = $search['sort']['type'];
										 
$search['view']			= $_POST['view']   ? $_POST[view]   
										 : ($_SESSION[view] && SESSION_MATCH ? $_SESSION['view'] : 'list');
										 
$_SESSION['view']         = $search['view'];                                         
                                         
$search[string]			= $_POST[string] ? sql::escape($_POST[string]) 
										 : ($_SESSION[string] && SESSION_MATCH ? $_SESSION[string] : false);


if($search[string] == 'vind hier je nieuwe auto')
	$search[string] = false;
    
$_SESSION[string]       = $search[string];

// Write SQL Conditional from filters
/////////////////////////////////////

$tmp 					= array();
$values 				= array();
$filter					= array();
$filters 				= array('brand|str'			=> "`merk`  					= 	'--input--'",
								'model|str'			=> "`model` 					= 	'--input--'",
								'minprice|int'		=> "`verkoopprijs_particulier` 	>= 	--input--",
								'maxprice|int'		=> "`verkoopprijs_particulier` 	<= 	--input--",
								'minkm|int'			=> "`tellerstand` 				>= 	--input--",	
								'maxkm|int'			=> "`tellerstand` 				<= 	--input--",
								'minyear|int'		=> "`bouwjaar` 					>= 	--input--",
								'maxyear|int'		=> "`bouwjaar` 					<= 	--input--",
								//'seats|int'		=> "`aantal_zitplaatsen` 		= 	--input--",
								'transmission|str'  => "`transmissie`		 		= 	'--input--'",
								'doors|int'			=> "`aantal_deuren` 			= 	--input--",	
								'fuel|str'			=> "`brandstof` 				= 	'--input--'");

$nomodel = false;

if(isset($_POST['brand']) && isset($_POST['model']))
    if(!sql::exists("cars",array("merk"=>$_POST['brand'],"model"=>$_POST['model'])))
        $nomodel = true;
        
if(!$nomodel && isset($_SESSION['brand']) && isset($_SESSION[model]))
    if(!sql::exists("cars",array("merk"=>$_SESSION['brand'],"model"=>$_SESSION['model'])))
        $nomodel = true;

foreach($filters as $f => $sql){

	list($name,$type) = explode("|",$f);
    

    
	$val			  = isset($_POST[$name]) ? $_POST[$name] : ($_SESSION[$name] && SESSION_MATCH ? $_SESSION[$name] : '');
	$values[$name]	  =	$val;
    $_SESSION[$name]  = $val;
	
	if(empty($val))
		continue;

	if($type == 'int' && !is_numeric($val))
		continue;
			
    if($name == 'model' && $nomodel){
	    //do nothing
    } else {
		$val 			= sql::escape($val);			    
		$tmp[] 			= str_replace('--input--',$val,$sql);
		$filter[$name]  = $val;
    }
			

	
}

$tmp[] = '`active` = 1';

if(core::$cur_page['id'] == 3) // Aanbiedingen pagina
	$tmp[] = '`actieprijs` > 0';
elseif(core::$cur_page['id'] == 4) // Milieubewust pagina
	$tmp[] = "`milieu_bewust` = '1'";

if($search['string'])
	$tmp[] = "(`sortname` LIKE '%".$search['string']."%' || `merk` LIKE '%".$search['string']."%' || `model` LIKE '%".$search['string']."%' || `type` LIKE '%".$search['string']."%' || concat(`merk`,' ',`model`,' ',`type`) LIKE '%".$search['string']."%')";

if(count($tmp) > 0)
	$filtersql = "WHERE ".implode(" && ",$tmp);
	
$_SESSION['values']		= $values;
	
// Set all variables
////////////////////

if($_POST['sort_dir'] != "asc" && $_POST['sort_dir'] != "desc")
	unset($_POST['sort_dir']);
    
if($_POST['sort_dir'])
    $_SESSION['sort_dir'] = $_POST['sort_dir'];
else
    unset($_SESSION['sort_dir']);
	
$search['sorting']['naam']['fields'] 	= '`sortname`';
$search['sorting']['naam']['dir']		= $_POST['sort_dir'] ? $_POST['sort_dir'] : ($_SESSION['sort_dir'] && SESSION_MATCH ? $_SESSION['sort_dir'] : 'ASC');
$search['sorting']['date']['fields'] 	= '`created_at`';
$search['sorting']['date']['dir']		= $_POST[sort_dir] ? $_POST['sort_dir'] : ($_SESSION['sort_dir'] && SESSION_MATCH ? $_SESSION['sort_dir'] : 'DESC');
$search['sorting']['price']['fields']	= '`verkoopprijs_particulier`';
$search['sorting']['price']['dir']	= $_POST[sort_dir] ? $_POST['sort_dir'] : ($_SESSION['sort_dir'] && SESSION_MATCH ? $_SESSION['sort_dir'] : 'ASC');

$search['sortsql']		= $search['sorting'][$search['sort']['type']] ? $search['sorting'][$search['sort']['type']] : reset($search['sorting']);
$search['sort']['dir']		= $search['sortsql']['dir'];
$search['sortsql']		= $search['sortsql']['fields']." ".$search['sortsql']['dir'];
$search['total'] 			= sql::num("cars",$filtersql);
$search['limit']			= 51; //$search[view] == "grid" ? 12 : 10;

if($landing)
	$search['limit'] 		= 999999999;
	
$search['page']			= core::calc_pages($page,$search['limit'],$search['total'],5);
?>