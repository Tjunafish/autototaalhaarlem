<?
// Unless we are on localhost / testbyte, make sure the domain starts with www.
if($_SERVER['SERVER_NAME'] != "localhost" && !preg_match('/testbyte/',$_SERVER['SERVER_NAME']) && !strncmp($_SERVER['SERVER_NAME'],'www.',4))
	header('Location: '.
           (@$_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://').
           'www.'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

// Load dependencies
require 'config.php';
require 'loader.php';
?>
<!DOCTYPE html>

<html>
<head>

    <meta charset="utf-8" />
    
    <title><?= core::$TITLE.' - '.(core::$USER ? core::$PAGE->TITLE : 'Inloggen') ?></title>    
    
    <link rel="icon" href="images/favicon.ico" />
    
    <!-- JAVASCRIPT -->
    
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js">		</script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js">	</script>
	<script type="text/javascript" src="js/functions.js">													</script>
	<script type="text/javascript" src="js/jquery.lightbox-0.5.js">											</script>	
	<script type="text/javascript" src="js/nicEdit.js">														</script>    
    <script type="text/javascript" src="js/jscolor/jscolor.js">												</script>	
    <script type="text/javascript" src="js/jquery.sb.min.js">												</script>
	
	<!-- CSS -->
		
	<link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.6.custom.css" />
	<link rel="stylesheet" type="text/css" href="js/jquery.lightbox-0.5.css" />    
	<link rel="stylesheet" type="text/css" href="js/jquery.sb.css" />
    
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="css/ie.css" />
    <![endif]-->
    
</head>

<body>

<div id="wrapper">

    <div id="active_page"><?=core::$PAGE->TABLE?></div>
    
    <!-- HEADER -->
    <div id="header">
    
        <?
        if(core::$USER != false){
        ?>

        <div id="header_info">
        
            Welkom, <?= core::$USER['displayname']; ?><br/>
            <?= date("d-m-Y / H:i"); ?>
        
        </div>
        
        <div id="header_nav_left">
        
            <? core::print_navigation(); ?>
        
        </div>
    
        <div id="header_nav_right">
		
			<a href="index.php?logout=true">Uitloggen</a>
			
		</div>
        
        <? 
		} 
		?>
    
    </div>
    
    <? core::print_subnavigation(); ?>
        
    <!-- CONTENT -->
    <? 
    if(!core::$USER){
    ?>	
    <div id="content">
    
        <form id="login_form" action="index.php" method="post">
                
            <label>Gebruikersnaam:</label>
            <input type="text" name="login_name" />
            
			<br/>
            
			<label>Wachtwoord:</label>
            <input type="password" name="login_password" />
            
            <input type="submit" id="login_submit" value="LOGIN" />
            
            <span id="forget_pass">Wachtwoord vergeten?</span>
            <span id="login_error"><?=$login_error?></span>
        
        </form>
    
    </div>
    <?
    }else
        require 'content.php';
    ?>

    <!-- FOOTER -->
    <div id="footer">
    
        <div id="footer_top"></div>

        Created by <a href="http://www.codecreators.nl">CodeCreators</a> - 
		Donauweg 10 - 
		1043 AJ Amsterdam - 
		06 14 28 68 54 - 
		<a href="mailto:michael@codecreators.nl">michael@codecreators.nl</a> - 
		<a href="http://www.codecreators.nl/">www.codecreators.nl</a>

    </div>
	                
</div>

<!-- Javascript DIVS -->
<div id="workspace"></div>
<div id="full_overlay"></div>
<div id="notice"></div>
<div id="processing">Even geduld a.u.b.</div>
<div id="alert"><div id="wrap"><div><h1></h1><p></p></div><img id="exit" src="images/notice_cross.png" alt="remove notice" /></div></div>

<script type="text/javascript">
$(function(){
        
    <?
    // Create lightboxes
    for($i=0;$i<core::$PAGE->LIGHTBOX;$i++)
        echo '$(".lightbox_'.$i.'").lightBox();';	
    ?>

	// Catch F5 and manually reload so no accidental double submits can occur
	/////////////////////////////////////////////////////////////////////////
	
	$('html').keydown(function(event){
		
		if(event.keyCode == 116){
			
			event.preventDefault();
			location.href = 'http://<?= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].($_POST['page'] ? '?page='.$_POST['page'] : '')?>';
			
		}
		
	});
	<?
	if(is_array(core::$PAGE->SELECTFILTERS))
		foreach(core::$PAGE->SELECTFILTERS as $f){
		?>
		$('#input_form select[name^="<?=$f['field']?>"]').live('change',function(){
		
			$(this).parents('.form_block')
			       .find('.row:not(:has(select[name^="<?=$f['field']?>"]))')
				   .hide()
				   .next('.form_divider')
				   .hide();
				   
			$(this).parents('.form_block')
				   .find('.row.filter_'+$(this).val())
				   .show()
				   .next('.form_divider')
				   .show();
			
		});
		
		$('#input_form select[name^="<?=$f['field']?>"]').trigger('change');
		<?	
		}
	
	if(core::$NOTIFICATION)
		echo "$(function(){ cmsalert('".core::$NOTIFICATION."'); })";
		
	echo core::$END_SCRIPT;
	?>

});
</script>

</body>
</html>