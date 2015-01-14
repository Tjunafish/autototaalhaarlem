<?
if(!$templates || !$name || !$_POST['id']) 								exit;
if(!$templates[$name]) 													exit;
if(!sql::exists($templates[$name]['table'],array("id"=>$_POST['id']))) 	exit;

$cond 	  = "WHERE `template` = '".sql::escape($name)."' && `letter` = '".sql::escape($_POST['id'])."'";

// Calculate start and end date
///////////////////////////////

if($_POST['start_date'])
	$mindate = strtotime($_POST['start_date']);
else{
	
	$mindate = strtotime('-1 day',strtotime(sql::fetch("object",VIEWS_TABLE,$cond." ORDER BY `date` ASC LIMIT 1")->date));
		
	if(date('Y',$mindate) < 2000)
		$mindate = strtotime('-1 day');
			
}

$maxdate = strtotime(($_POST['end_date'])? $_POST['end_date'] : '+1 day');
	
if($mindate == $maxdate)
	$mindate = strtotime('-1 day',$mindate);

$views    = sql::fetch("array",VIEWS_TABLE,$cond." && `date` BETWEEN '".date('Y-m-d H:i:s',$mindate)."' AND '".date('Y-m-d H:i:s',$maxdate)."'");

$cond 	 .= " && `datetime` BETWEEN '".date('Y-m-d H:i:s',$mindate)."' AND '".date('Y-m-d H:i:s',$maxdate)."'";

// Populate defined viewcount
/////////////////////////////

$totalviews 		= 0;
$totalonlineviews 	= 0;
$viewcount  		= array();

foreach($views as $tmp){
	
	if($tmp['type'] == "online")
		$totalonlineviews 	+= $tmp['count'];
	else
		$totalviews			+= $tmp['count'];
	
	$viewcount[$tmp['type']][$tmp['date']] = $tmp['count'];

}

// Fill empty days with a value of 0
////////////////////////////////////

$diff 	  = abs($mindate-$maxdate);
$daydiff  = floor($diff/(60*60*24));

$viewcoords = '';

for($i=1;$i<=$daydiff;$i++){

	$today_ts	 = strtotime('+'.$i.' days',$mindate);
	$today 		 = date('Y-m-d',$today_ts);
	
	$viewcoords .= "data.setValue(".$i.",0,'".date("j F y",strtotime($today))."');\n".
		 		   "data.setValue(".$i.",1,".(($viewcount['email'][$today])? 	$viewcount['email'][$today] 	: 0).");\n";
				   "data.setValue(".$i.",2,".(($viewcount['online'][$today])? 	$viewcount['online'][$today]	: 0).");\n";
	
}

$mindate = date("M d, Y",$mindate);
$maxdate = date("M d, Y",$maxdate);

// Calculate viewing percentage
///////////////////////////////

$extra = "email";

$tot_unread = sql::num(LOG_TABLE,$cond." && `readcount_".$extra."` = 0 GROUP BY `email`"); 
$tot_sent 	= sql::num(LOG_TABLE,$cond." GROUP BY `email`");

if($tot_sent != 0)
	$read_perc = round((sql::num(LOG_TABLE,$cond." && `readcount_".$extra."` > 0 GROUP BY `email`")/ $tot_sent * 100),2);

list($avg_readcount) = mysql_fetch_array(mysql_query("SELECT AVG(`readcount_".$extra."`) 
														FROM `".LOG_TABLE."` ".$cond));
														
// Calculate online viewing percentage
//////////////////////////////////////

$extra = "online";

$tot_online	= sql::num(LOG_TABLE,$cond." && `readcount_".$extra."` > 0 GROUP BY `email`");

if($tot_sent != 0)
	$perc_readonline = $tot_online / $tot_sent * 100;

list($avg_onlinereadcount) = mysql_fetch_array(mysql_query("SELECT AVG(`readcount_".$extra."`) 
																FROM `".LOG_TABLE."` ".$cond));

// Calculate unread / read / online percentage		
//////////////////////////////////////////////	

if($tot_sent != 0){
	
	$perc_unread 		= round($tot_unread / $tot_sent * 100,2);
	$perc_read	 		= round((100-$perc_unread) - $perc_readonline,2);
	$perc_readonline 	= round($perc_readonline,2);
	
	if($perc_read < 0)
		$perc_read = 0;
		
	if($perc_unread == 100)
		$perc_readonline = 0;
	
}else{

	$perc_unread = 100;
	$perc_read 	 = $perc_readonline = $read_perc = 0;	
	
}
?>
<script type="text/javascript" src="//www.google.com/jsapi">  			</script>
<script type="text/javascript" src="js/jquery.js">						</script>
<script type="text/javascript" src="js/jquery-ui-1.8.6.custom.min.js">	</script>
<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.6.custom.css" />

<link 	type="text/css" 		href="js/jquery.jqplot.css" rel="stylesheet" />

<div id="input_form" style="border:0;margin:0;display:block;">

<span class="row">

    <div class="left" style="width:auto !important;">Van:</div>
    <div class="right" style="width:auto;"><input id="start_date" type="text" class="datepicker" value="<?=date('j F Y',strtotime($mindate))?>" style="width:auto;" /></div>

</span>

<span class="row">

    <div class="left" style="width:auto !important;">Tot:</div>
    <div class="right" style="width:auto;"><input id="end_date" type="text" class="datepicker" value="<?=date('j F Y',strtotime($maxdate))?>" style="width:auto;" /></div>
	<img src="images/refresh.png" alt="Verversen" id="stats_refresh"/>

</span>


<div class="form_divider"></div>

<?
if(sql::num(LOG_TABLE,$cond." GROUP BY `email`") == 0)
	die('<div style="padding:10px;">Deze nieuwsbrief is / was op deze data nog niet verstuurd</div>');
?>

<span class="row">

    <div class="left">Aantal impressies:</div>
    <div class="right"><?=(int)$totalviews?></div>

</span>

<span class="row">

    <div class="left">Leespercentage:</div>
    <div class="right"><?=$read_perc?>%</div>

</span>

<span class="row">

    <div class="left">Unieke ontvangers:</div>
    <div class="right"><?=$tot_sent?></div>

</span>

<span class="row">

    <div class="left">Gemiddeld gelezen:</div>
    <div class="right"><?=round($avg_readcount,2)?> keer</div>

</span>

<div class="form_divider"></div>

<span class="row">

    <div class="left">Aantal online impressies:</div>
    <div class="right"><?=(int)$totalonlineviews?></div>

</span>

<span class="row">

    <div class="left">Deel online bekeken:</div>
    <div class="right"><?=$perc_readonline?>%</div>

</span>

<span class="row">

    <div class="left">Unieke online bekijkers:</div>
    <div class="right"><?=$tot_online?></div>

</span>

<span class="row">

    <div class="left">Gemiddeld online gelezen:</div>
    <div class="right"><?=round($avg_onlinereadcount,2)?> keer</div>

</span>

<div class="form_divider"></div>

<div id="view_chart" 	style="margin:15px;margin-right:0;float:left;"></div>
<div id="view_pie" 		style="margin:15px;float:right;"></div>

<div style="clear:both;"></div>

<div class="form_divider"></div>

<span class="row">

    <div class="left">Aantal uitgeschreven:</div>
    <div class="right"><?=sql::num('','',"SELECT * FROM `".UNSUBSCRIBE_TABLE."` WHERE `template` = '".sql::escape($name)."' && `letter` = '".sql::escape($_POST['id'])."' GROUP BY `email`");?></div>

</span>

</div>
<script type="text/javascript">

function loadGoogle() {
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart'], 'callback' : drawCharts});

}

// Callback that creates and populates a data table, 
// instantiates the pie chart, passes in the data and
// draws it.
function drawCharts() {

	// Line graph
	/////////////
	var data = new google.visualization.DataTable();	
	data.addColumn('string', 	'Datum');
	data.addColumn('number', 	'E-mail');
	data.addColumn('number',	'Online');
	data.addRows(<?=$daydiff+1?>);
	<?=$viewcoords?>

	var chart = new google.visualization.LineChart(document.getElementById('view_chart'));
	chart.draw(data,{
					width:			475, 
					height: 		350, 
					title: 			'Impressies per dag',
					chartArea:		{	
									left:		40,
									top:		50,
									width:		"80%",
									height:		"70%"
									},
					backgroundColor:{
									strokeWidth:1,
									stroke:		'#ccc'
									},
					colors:			['#222','#ccc'],
					legend:			'none',
					hAxis:			{
									slantedText:true
									}
					});

	// Pie chart
	////////////
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Data');
	data.addColumn('number', 'Percentage');
	data.addRows([
		['Niet bekeken', 		<?=$perc_unread?>],
		['In e-mail bekeken', 	<?=$perc_read?>],
		['Online bekeken',		<?=$perc_readonline?>]
	]);
	
	var chart = new google.visualization.PieChart(document.getElementById('view_pie'));
	chart.draw(data,{
					width: 		460, 
					height: 	350, 
					title: 		'Percentage bekeken e-mails',
					chartArea:		{	
									left:		50,
									top:		40,
									width:		"80%",
									height:		"80%"
									},
					backgroundColor:{
									strokeWidth:1,
									stroke:		'#ccc'
									},
					colors:			['#ccc','#222','#555'],
					legend:			'none'
					});
	}
	
$(function(){
	
	loadGoogle();
	
	$( ".datepicker" ).datepicker({
				
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		dateFormat: 'd MM yy'
		
	});
	
	$('#stats_refresh').click(function(event){
		
		event.preventDefault();
		
		$.post('newsletter/ajax.php',{
									  action: 		'load_stats', 
									  template: 	$('#select_template').val(), 
									  id: 			$('#select_newsletter').val(), 
									  start_date: 	$('#start_date').val(), 
									  end_date: 	$('#end_date').val()
									  },function(data){
			
			$('#newsletter_stats').html(data);
			
		});
		
	});

});
</script>