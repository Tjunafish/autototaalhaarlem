<?
require_once('../config.php');

core::checkLogin();

if(core::$USER == false)
	die();
	
$tmp 				= explode("|",$_POST['req']);
$maxnum 			= $tmp[0];	
$target				= $tmp[1];

if($maxnum == 0)
	$maxnum = 999999999;

if($_POST['type'] == "img"){
	
	$extra				= $tmp[2];	
	list($type,$coords) = explode(":",$extra);
	list($x,$y)			= explode("-",$coords);

}

$extensions = ($_POST['type'] == "img")? "jpg,jpeg,png" : $tmp[2];
?>
<script type="text/javascript">
var unique = '';
$('#notice .img_block').die();
</script>
<h1>Bestandsbeheer</h1>
<div id="popup_content" class="inside">

	<div id="uploader">
		
        <h3>
        
        	<div class="floatleft">
            
                <b class="fixed">Aantal:</b> <?= $maxnum != 999999999 ? $maxnum : 'Geen limiet' ?><br/>
                <b class="fixed">Types:</b>  <?= $extensions ?>
                
            </div>
            
            <? if($_POST['type'] == "img"){ ?>
            <div class="floatright">
            
            	<b class="fixed"><?= ($type == "max")? 'Max ' : 'Doel'; ?>breedte:</b> <span class="fixed"><?= (is_numeric($x))? $x.'px' : 'n.v.t.';?></span><br/>
            	<b class="fixed"><?= ($type == "max")? 'Max ' : 'Doel'; ?>hoogte:</b>  <span class="fixed"><?= (is_numeric($y))? $y.'px' : 'n.v.t.';?></span>
        	
            </div>
            <? } ?>
            
            <div class="clear"></div>
            
        </h3>
        
        <div id="uploaded_files">
            
            <div id="warning">
                
                Rode bestanden moeten nog aangepast worden voor je kunt opslaan.
                
            </div>
            
            <ul id="uploaded"></ul>

        </div>
        
        <div id="step_progress">
            
            <h2>Uploading <i></i></h2>
            
            <img src="images/loading.gif" />
            
            <!--<div id="progressbar">
        
                <p>0%</p>
                <div id="progress"></div>
            
            </div>-->
        
        </div>
        
        <div id="step_upload">
        
            <div id="browse_file">
            
            	<form id="upload_form" action="ajax/upload_handle.php" target="upload_target" method="post" enctype="multipart/form-data">
                
                	<input type="hidden" name="extensions"	value="<?=$extensions?>" />
                	<input type="hidden" name="upload_form" value="true" />
                	<input type="hidden" id="tmp_name" 		name="tmpname" value="" />
                    <input type="hidden" name="req" 		value="<?= $type."|".$x."|".$y ?>" />
                    <input type="hidden" name="type"		value="<?=$_POST['type']?>" />
                    <input type="hidden" name="path" 		value="<?= $target ?>" />
                
                    <div id="file_holder"><input id="file_upload" name="uploaded_file" type="file" /></div>
                    
                    <div class="text">Kies een bestand om up te loaden</div>
                
                    <span id="browse_button">Bladeren...</span>
                
                </form>
                
            </div>
            
            <a id="upload_button" class="inactive">Uploaden</a>
        
        	<iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;"></iframe>
            
            <a id="old_file_button">Of klik hier om een bestaand bestand te kiezen</a>
            
            <div id="choose_file"></div>
        
        </div>
    
    	<a class="button" id="cancel_all">annuleren</a>    
    	<a class="button" id="save_all">opslaan</a>
    
    </div>
    
    <div id="editor"></div>
    
    <input type="hidden" id="current_field" value="<?=$_POST['current_field']?>" />
    <input type="hidden" id="upload_data" value="" />
    
</div>

<script type="text/javascript">

function updateLocation(){

	$(function(){
		
		$('#full_overlay').css('height',$('html').height());
		//$('#notice').center();
	
	});
}

function updateItems(){
		
	$(function(){
			
		if(!$('ul#uploaded li').length){
		
			$('#save_all').unbind('click');
			$('#save_all').addClass('inactive');							
			$('#uploaded_files').hide();
			$('#browse_file .text').html('Kies een bestand om up te loaden');
		
		}
		
		if(!$('ul#uploaded li.invalid').length){
			
			$('#warning').hide();
			$('#save_all').removeClass('inactive');
		
		}
		
		if($('ul#uploaded li').length >= <?=$maxnum?>)		
			$('#step_upload').hide();	
		else
			$('#step_upload').show();
	
	});
	
}

function progress(){
	
	var tmp = $('#file_upload').val().split(".");
	var ext = tmp[(tmp.length-1)];
	var extra = '';
	<? 
	if(substr($target,-1) != "/")
		$target .= "/";
	?>
	$.post('ajax/get_progress.php?anticache='+new Date().getTime(),{ 	tmpfile:  '../tmp/'+$('#tmp_name').val()+'.'+ext,
																	 	realfile: '../../<?=$target?>'+$('#browse_file .text').html(),
																		secure:   '<?=sha1(md5(date("Y-m-d")."code"))?>'
																	},function(data){
		
		if(data == "false")
			setTimeout("progress()",500);
		else{
		
			<? if($_POST['type'] == "img"){ ?>
			if(data == "real")
				var rel = '<?=$target?>'+$('#browse_file .text').html();
			else
				var rel = $('#browse_file .text').html()+'|tmp/'+$('#tmp_name').val()+'.'+ext;
				
			var links = '<a class="img_del" rel="'+rel+'" rev="'+unique+'"><img src="images/delete.png"></a>'+
						'<a class="img_edit" rel="'+rel+'" rev="'+unique+'"><img src="images/image_edit.png"></a>';
						
			if(data == "tmp"){
				
				extra = ' class="invalid"'; 
				$('#warning').show();
				$('#save_all').addClass('inactive');
				links += '<a class="img_auto_edit" 	rel="'+rel+'" rev="'+unique+'"><img src="images/resize.png"></a>';			
			
			}
			links 	  += '<a class="img_preview" rel="'+rel+'" rev="'+unique+'"><img src="images/eye.png"></a>';
			<? }else{ ?>			
			var links = '<a class="img_del" rel="<?=$target?>'+$('#browse_file .text').html()+'" rev="'+unique+'"><img src="images/delete.png"></a>';
			<? } ?>
			$('#step_upload').show();
			$('#step_progress').hide();
			
			$('#uploaded').append('<li'+extra+' id="'+unique+'">'+$('#browse_file .text').html()+links+'</li>');
			
			$('#browse_file .text').html('Kies nog een bestand om up te loaden');
			$('#upload_button').addClass('inactive');
			$('#upload_button').unbind('click');
			
			$('#uploaded_files').show();
			
			updateLocation();
			updateItems();
			
		}
		
	});
	
}
			
$(function(){
	
	// Old file add functions
	$('#notice .img_block').live('click',function(){

		unique = new Date().getTime();
		
		$('#notice #choose_file').slideUp();
		$('#notice #old_file_button').show();
		
		var tmp = $(this).attr('rel').split(".");
		var ext = tmp[(tmp.length-1)];
		var extra = '';
		<? 
		if(substr($target,-1) != "/")
			$target .= "/";
			
		if($_POST['type'] == "img"){ 
		?>
		var links = '<a class="img_del" rel="'+$(this).attr('rel')+'" rev="'+unique+'"><img src="images/delete.png"></a>';
		<? }else{ ?>
		var links = '<a class="img_del" rel="<?=$target?>'+$(this).attr('rev')+'" rev="'+unique+'"><img src="images/delete.png"></a>';
		<? } ?>
		
		<? if($_POST['type'] == "img"){ ?>
		tmp = $(this).attr('info-size').split("|");
		
		var x = parseInt(tmp[0]);
		var y = parseInt(tmp[1]);
		
		links += '<a class="img_edit" 		oldfile=true rel="'+$(this).attr('rel')+'" rev="'+unique+'"><img src="images/image_edit.png"></a>';
		if((!isNaN(x) && x > <?=(is_numeric($x))? $x : '9999999999999999'?>) || (!isNaN(y) && y > <?=(is_numeric($y))? $y : '9999999999999999'?>)) {
		
			extra = ' class="invalid"'; 
			$('#warning').show();
			$('#save_all').addClass('inactive');
			links += '<a class="img_auto_edit" 	oldfile=true rel="'+$(this).attr('rel')+'" rev="'+unique+'"><img src="images/resize.png"></a>';
		
		}
		links 	  += '<a class="img_preview" rel="'+$(this).attr('rel')+'" rev="'+unique+'"><img src="images/eye.png"></a>';
		<? } ?>
		
		$('#step_upload').show();
		$('#step_progress').hide();
		
		$('#uploaded').append('<li'+extra+' id="'+unique+'">'+$(this).attr('rev')+links+'</li>');
		
		$('#browse_file .text').html('Kies nog een bestand om up te loaden');
		$('#upload_button').addClass('inactive');
		$('#upload_button').unbind('click');
		
		$('#uploaded_files').show();
		
		updateLocation();
		updateItems();
		
	});
	
	// Old file button
	$('#notice #old_file_button').live('click',function(){
		
		$.post('ajax/old_files.php?anticache='+new Date().getTime(),{target: 	'<?=$target?>',
																	   ext:		'<?=$extensions?>',
																	   secure:	'<?=sha1(md5($target."code".$extensions))?>',
																	   type:	'<?=$_POST['type']?>'},function(data){
			
			$('#notice #choose_file').html(data);
			$('#notice #choose_file').slideDown();
			$('#notice #old_file_button').hide();
			
		});
		
		
	});
		
	// Catch files that are already defined
	if($('#upload_data_'+$('#current_field').val()).val() != ""){
	
		var images = $('#upload_data_'+$('#current_field').val()).val().split("|");
		$('#upload_data_'+$('#current_field').val()).html('');	
	
		for(var i in images){
		
			unique = new Date().getTime();
			$('#upload_data').html($('#upload_data').html()+'|'+images[i]);
			
			var image = images[i].split("/");
			image = image[(image.length-1)];
			
			$('#uploaded').append('<li id="'+unique+'">'+image+'<a class="img_del" rel="'+images[i]+'" rev="'+unique+'"><img src="images/delete.png"></a>'+
								  '<a class="img_edit" oldfile=true rel="'+images[i]+'" rev="'+unique+'"><img src="images/image_edit.png"></a>'+
								  '<a class="img_preview" rel="'+images[i]+'" rev="'+unique+'"><img src="images/eye.png"></a></li>');
			
		}
		
		$('#step_upload').show();
		$('#step_progress').hide();
			
		$('#uploaded_files').show();
		updateLocation();
		
	}
	
	// Update the uploadform when a file is selected
	$('#browse_file input[type=file]').change(function(){
		
		unique = new Date().getTime();
		
		$(function(){
			
			$('#tmp_name').val(unique);
			
		});
		
		var value = $(this).val().replace('C:\\fakepath\\','');
		var tmp   = value.split("\\");
		value = tmp[tmp.length-1]; 
		
		$('#browse_file .text').html(value);
		$('#upload_button.inactive').removeClass('inactive');

		$('#upload_button').unbind('click');
		$('#upload_button').click(function(){
			
			$.post('ajax/upload_handle.php?anticache='+new Date().getTime(),{
																				action: 	'checkext',
																				extensions:	'<?=$extensions?>',
																				file:		value
																			},function(data){
			
				if(data == "invalid")					
					alert('Je mag alleen de volgende bestandtypes uploaden: <?=$extensions?>');						
				else{
					
					$('#upload_form').submit();
					$('#step_progress h2 i').html(value);
					$('#step_upload').hide();
					$('#step_progress').show();
					$('#notice #choose_file').hide();
					$('#notice #old_file_button').show();
					
					updateLocation();
			
					setTimeout("progress()",500);
					
				}
																				
			});
			
		});
		
	});
	
	// Ajax call for editing the image
	$('.img_edit').live('click',function(event){
		
		event.preventDefault();
		
		if($(this).attr('oldfile') == "true")
			var old = true;
		else
			var old = false;
		
		$.post('ajax/editor.php?anticache='+new Date().getTime(),{	img: 	$(this).attr('rel'), 
																	x: 		'<?=$x?>', 
																	y: 		'<?=$y?>', 
																	type: 	'<?=$type?>', 
																	target: '<?=$target?>', 
																	unique: $(this).attr('rev'),
																	oldfile: old,
																	secure:	'<?=sha1(md5($x.$y."code".$target))?>'
																	},function(data){
			
			if(data == "error")
				alert('Er is een fout opgetreden, contacteer de webmaster.');
			else{
				
				$('h1').html('Bestand aanpassen');				
				$('#uploader').hide();
				$('#editor').html(data);
				$('#editor').show();
				updateLocation();
			
			}
			
		});
		
	});
	
	// Ajax call for auto-editing the image
	$('.img_auto_edit').live('click',function(event){
		
		event.preventDefault();
		
		if($(this).attr('oldfile') == "true")
			var old = true;
		else
			var old = false;

		var parent = $(this).parents('li');
		
		$.post('ajax/upload_handle.php?anticache='+new Date().getTime(),{	
																	img: 	$(this).attr('rel'), 
																	x: 		'<?=$x?>', 
																	y: 		'<?=$y?>', 
																	type: 	'<?=$type?>',
																	target: '<?=$target?>', 	
																	action: 'autosize',
																	oldfile: old,
																	secure:	'<?=sha1(md5($x.$y."code".$target))?>'
																	},function(data){
			
			$(parent).removeClass('invalid');
			updateItems();
			$(parent).find('.img_edit').remove();
			$(parent).find('.img_auto_edit').remove();
			
			if(data){				
			
				$(parent).find('.img_del').attr('rel',data);
				$(parent).find('.img_preview').attr('rel',data);
				$(parent).find('.img_preview').attr('truepath','true');
			}
			
		});
		
	});
	
	// Ajax call for removing the image
	$('.img_del').live('click',function(event){
		
		event.preventDefault();
		
		$('#'+$(this).attr('rev')).remove();
		updateItems();

	});
	
	// Event for previewing image
	$('.img_preview').live('click',function(event){

		var img = $(this).attr('rel').split("|");
		
		if(img.length == 1){
			
			if($(this).attr('truepath') == 'true')
				img = img[0]
			else
				img = "../"+img[0];
				
		}else
			img = img[1];
			
		fb.loadAnchor(img, 'doAnimations:false scrolling:no');
		
	});
	
	function kill(){
		
		$(function(){
						
			$('#save_all').die();
			$('#cancel_all').die();
			$('.img_del').die();
			$('.img_auto_edit').die();
			$('.img_edit').die();
			$('#notice .img_block').die();
			$('.img_preview').die();
			
			$('#uploaded').html('');
			$('#upload_data').val('');
			$('#full_overlay').hide();
			$('#notice').hide();
			$('#notice').html('');
			$(window).unbind('beforeunload');

		});
		
	}
	
	// Ajax call for canceling the upload process
	$('#cancel_all').live('click',function(event){
		
		event.preventDefault();
		
		if(!$('ul#uploaded li').length){
			
			$('#full_overlay').hide();
			$('#notice').hide();
			$('#notice').html('');
			
		}else{
		
			var answer = confirm("Weet je zeker dat je wilt annuleren?");
			
			if(answer)								
				kill();
			
		}
		
	});
	
	// Ajax call for saving the uploads
	$('#save_all').live('click',function(event){
		
		if(!$(this).hasClass('inactive')){
		
			var upload_data = new Array();

			$('#uploaded li').each(function(){
				
				upload_data.push($(this).find('.img_del').attr('rel'));
				
			});
			
			var count 			= 0;
			var new_data 		= new Array();

			for(var i in upload_data){
			
				if(upload_data[i] != "" && upload_data[i] != null){
					
					if(upload_data[i].substr(0,6) == "../../")
						new_data[count] = upload_data[i].substr(6);	
					else
						new_data[count] = upload_data[i];
						
					count++;			
				
				}
				
			}			

			new_data = new_data.join("|");
			
			$('#upload_data_'+$('#current_field').val()	).val(new_data);
			
			if(count == 1)
				$('#files_uploaded_'+$('#current_field').val()+' span').html('1 bestand');
			else
				$('#files_uploaded_'+$('#current_field').val()+' span').html(count+' bestanden');
				
			kill();
			
		}
		
	});
	
	updateItems();
	updateLocation();
	
});

</script>