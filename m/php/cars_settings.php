<?
if(!is_object('mobile_core'))
	require __DIR__.'/config.php';

$page					= $_POST[page] ? $_POST[page] : 1;

if(substr($_POST[curr],0,4) != 'page' && !empty($_POST[prev]) && !empty($_POST[curr]))
	$page = 1;

$search 				= array();

$search[sort][type]		= $_POST[sort]   ? $_POST[sort]   
										 : 'name';
                                         
$_SESSION[sort]         = $search[sort][type];
										 
$search[view]			= $_POST[view]   ? $_POST[view]   
										 : 'list';
										 
$search[string]			= $_POST[string] ? sql::escape($_POST[string]) 
										 : false;

if($search[string] == 'trefwoord...')
	$search[string] = false;

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

if(isset($_POST[brand]) && isset($_POST[model]))
    if(!sql::exists("cars",array("merk"=>$_POST[brand],"model"=>$_POST[model])))
        $nomodel = true;        
    
foreach($filters as $f => $sql){

	list($name,$type) = explode("|",$f);
    
    if($name == 'model' && $nomodel)
        continue;
    
	$val			  = $_POST[$name] ? $_POST[$name] : '';
	$values[$name]	  =	$val;
	
	if(empty($val))
		continue;

	if($type == 'int' && !is_numeric($val))
		continue;
			
	$val 			= sql::escape($val);		
	$tmp[] 			= str_replace('--input--',$val,$sql);
	$filter[$name]  = $val;
	
}

$tmp[] = '`active` = 1';

if(mobile_core::$cur_page[id] == 3) // Aanbiedingen pagina
	$tmp[] = '`actieprijs` > 0';
elseif(mobile_core::$cur_page[id] == 4) // Milieubewust pagina
	$tmp[] = "`milieu_bewust` = '1'";

if($search[string])
	$tmp[] = "(`sortname` LIKE '%".$search[string]."%' || `merk` LIKE '%".$search[string]."%' || `model` LIKE '%".$search[string]."%' || `type` LIKE '%".$search[string]."%' || concat(`merk`,' ',`model`,' ',`type`) LIKE '%".$search[string]."%')";

if(count($tmp) > 0)
	$filtersql = "WHERE ".implode(" && ",$tmp);
	
$_SESSION[values]		= $values;
	
// Set all variables
////////////////////

if($_POST[sort_dir] != "asc" && $_POST[sort_dir] != "desc")
	unset($_POST[sort_dir]);
    
if($_POST[sort_dir])
    $_SESSION[sort_dir] = $_POST[sort_dir];
else
    unset($_SESSION[sort_dir]);
	
$search[sorting][naam][fields] 	= '`sortname`';
$search[sorting][naam][dir]		= $_POST[sort_dir] ? $_POST[sort_dir] : 'ASC';
$search[sorting][date][fields] 	= '`created_at`';
$search[sorting][date][dir]		= $_POST[sort_dir] ? $_POST[sort_dir] : 'DESC';
$search[sorting][price][fields]	= '`verkoopprijs_particulier`';
$search[sorting][price][dir]	= $_POST[sort_dir] ? $_POST[sort_dir] : 'ASC';

$search[sortsql]		= $search[sorting][$search[sort][type]] ? $search[sorting][$search[sort][type]] : reset($search[sorting]);
$search[sort][dir]		= $search[sortsql][dir];
$search[sortsql]		= $search[sortsql][fields]." ".$search[sortsql][dir];
$search[total] 			= sql::num("cars",$filtersql);
$search[limit]			= 10; //$search[view] == "grid" ? 12 : 10;

if($landing)
	$search[limit] 		= 999999999;
	
$search[page]			= mobile_core::calc_pages($page,$search[limit],$search[total],5);
?>