<?
if(!is_object('core')){

	require __DIR__.'/../php/config.php';
	
	if($_POST[type] != 'rotator')
		require __DIR__.'/../php/cars_settings.php';
		
}

if($_POST[type] == 'rotator'){

	$cars = sql::fetch("array","cars","WHERE    `created_at` < '".sql::escape($_POST[start])."' 
									   &&  		`showticker` = 1 
									   ORDER BY `created_at` DESC 
									   LIMIT 1");
									   
	if(count($cars) == 0)
		$cars = sql::fetch("array","cars","WHERE `showticker` = 1 ORDER BY `created_at` DESC LIMIT 1");
									   	
	ob_start();
	core::draw_cars(1,$cars,'grid');
	$html = ob_get_clean();
	
	echo json_encode(array("start" => $cars[0][created_at], "html" => $html));
	exit;

}

$sort  = implode(" ".$search[sort][dir].", ",explode(",",$search[sortsql]));
$cars  = sql::fetch("array","cars",$filtersql." ORDER BY ".$sort." LIMIT ".$search[page][limit]);



if($search[view] == 'list'){
?>
<div class="content">

	<?
	core::draw_cars(1,$cars);
	?>
	
</div>
<script type="text/javascript" src="js/tiptip.js"></script>
<?
}else{

?>
<div class="content right">
	
	<?
	core::draw_cars(3,$cars);
	?>
	<div class="clear"></div>
	
</div>

<div class="clear"></div>
<?
}
?>
<script>
	function fit_text(){
	
		$('.textfit').each(function(){
					
			while($(this).width() > $(this).attr('rel')){
			
				var dFontsize = parseFloat($(this).css("font-size"), 10);
				$(this).css("font-size", dFontsize - 1);
				
			}
			
			$(this).removeClass('textfit');
			
		});	
	
	}
	fit_text();

</script>