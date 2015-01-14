<?
$useragent = $_SERVER['HTTP_USER_AGENT'];

require 'php/config.php';

/*if(core::$cur_page['id'] == 1 && preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
    header('Location: http://autototaalhaarlem.nl/m/');
*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="X-UA-Compatible"  content="IE=Edge"/>
    <meta http-equiv="Content-Type"     content="text/html;charset=utf-8" />
    <base href="<?= ROOT ?>" />
        
    <?
    core::draw_metadata();
    ?>
    
    <link	rel="alternate"  type="application/rss+xml" title="Auto Totaal Haarlem - Voorraad RSS" href="<?= ROOT ?>rss/" />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <link 	rel="stylesheet" href="css/default.css"/>
    <link 	rel="stylesheet" href="css/print.css"  media="print" />
    
    <link 	rel="stylesheet" href="css/mobile.css" media="screen and (orientation: portrait) or (orientation: landscape) or (max-device-width: 480px)" />
    <link 	rel="stylesheet" href="css/mobile.css" media="handheld" />
	
  
    <!--[if ie]>
    <link	rel="stylesheet" href="css/ie.css" />
    <![endif]-->
    
    <!--[if lte ie 7]>
    <link	rel="stylesheet" href="css/ie7.css" />
    <![endif]-->
    
    <?
    core::draw_js_libs();
    ?>
    
    <script type="text/javascript">
    
		var collection_page = true;	
			
		if(navigator.platform == 'MacIntel')  		
			if(window.devicePixelRatio)
				document.write('<link rel="stylesheet" type="text/css" href="media/css/mac.css" />');
			
	</script>
    
    <script type="text/javascript" src="js/jquery.outside-events.js"></script>
    <script type="text/javascript" src="js/codeslide.js"></script>
    <script type="text/javascript" src="js/functions.js?4"></script>
    
    <!-- jQuery SB Plugin -->
    <link   rel="stylesheet" 	   href="css/jquery.sb.css" />
    <script type="text/javascript" src="js/jquery.sb.min.js"></script>
    
    <!-- jQuery TipTip Plugin -->
    <link	rel="stylesheet" 	   href="css/jquery.tiptip.css" />
    <script type="text/javascript" src="js/jquery.tiptip.min.js"></script>
    
	<?
	if(core::$cur_page['bg_color'] || core::$cur_page['bg_image'] ||  core::$cur_page['id'] == 9){
	
		if(core::$cur_page['id'] == 9){
		
			$domain = str_replace('http://','',$_SERVER[HTTP_REFERER]);
			$domain = str_replace('www.',	'',$domain);
			$domain = str_replace('/',		'',$domain);
		
			if(sql::exists("landing",array("domain"=>$domain)))
				list($landing) = sql::fetch("array","landing","WHERE `domain` = '".sql::escape($domain)."'");
				
			core::$cur_page['bg_color'] = $landing['bg_color'];
			core::$cur_page['bg_image'] = $landing['bg_image'];
		
		}	
		?>	
		<style>
		body {
			<?
			if(core::$cur_page[bg_color]) 
				echo 'background-color: 	#'.core::$cur_page[bg_color]."\n";
			
			if(core::$cur_page[bg_image])
				echo 'background-image: 	url(\''.core::$cur_page[bg_image].'\');
					  background-repeat:	no-repeat;
					  background-position:	center top;';
			?>
		}
		</style>
		<?
		
	}
	?>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-50598762-1', 'autototaalhaarlem.nl');
        ga('send', 'pageview');

    </script>

</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/nl_NL/all.js#xfbml=1&appId=1408813742720787";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="header">
	<div class="wrapper-container">
		<a href="/"><h1>Auto Service Haarlem<img src="img/logo_ash.png" alt="Auto Service Haarlem" /></h1></a>
	
		<ul id="nav">
			
		<?				
			foreach(core::$pages as $page)
				if(preg_match('/top/',$page['position']))
				echo '<li '.($page == core::$cur_page ? 'class="active"' : '').'>
					  <a href="'.$page['slug'].'" target="_top">'.$page['title'].'</a></li>';
		?>

		</ul>
		
		<form id="search" method="post" action="<?= core::page_url('id',2) ?>" target="_top">
					
			<input <?= core::$cur_page['file'] == 'sub/voorraad.php' && !$_GET['item'] && core::$cur_page['id'] != 3 ? 'id="search_ajax"' : '' ?> type="text"  name="string" value="<?= $_POST[string] ? $_POST[string] : ($_SESSION[string] && SESSION_MATCH ? $_SESSION[string] : 'vind hier je nieuwe auto'); ?>" class="clickclear" />
			<input type="image" src="img/search.jpg" />
					
		</form>
	</div>
	<div class="shadow"></div>	
</div>
<?php

if(core::$cur_page['id'] != 5 && core::$cur_page['id'] != 10) {


    ?>
<ul id="slider">
	
	<?
	$slides = sql::fetch("array","banners","WHERE `active` = '1' ORDER BY `order` ASC");
	
	foreach($slides as $slide)
		echo '<li><a href="'.$slide['link'].'"><img src="'.$slide['img'].'" alt="'.$slide['text'].'" /><p>'.$slide['text'].'</p></a></li>';
	?>
	
	<div class="slider_pages"></div>

</ul>

<script type="text/javascript">
$('#slider').codeslide({buttonholder:	'#slider .slider_pages', 
						buttonclass: 	'page', 
						buttonactive: 	'active'});
</script>

<div class="label orange">
	<div class="wrapper-container">
		<p class="left arrow_down"><a href="<?= core::page_url('file','sub/voorraad.php'); ?>">Nieuw binnen</a></p>
		<p class="right">	<a href="<?= core::page_url('file','sub/voorraad.php') ?>" class="arrow_right">Bekijk totale voorraad</a></p>
	</div>
	<div class="shadow"></div>
</div>

<?php
}
?>




<?
if(core::$cur_page['id'] == 9){ // Landingspagina

	$domain = str_replace('http://','',$_SERVER[HTTP_REFERER]);
	$domain = str_replace('www.',	'',$domain);
	$domain = str_replace('/',		'',$domain);
		
	list($landing) = sql::fetch("array","landing","WHERE `domain` = '".sql::escape($domain)."'");
	
}
?>



<div <?= core::$cur_page['id'] == 4 || $landing['fuel'] == 'H' ? 'class="green"' : '' ?>>



	<div <?= core::$cur_page['id'] == 1 ? 'style="padding-bottom:0;"' : '' ?>>
	
		
		
		<?		
		if($_GET['item'] && core::$cur_page['item_file'] && file_exists(core::$cur_page['item_file']))
			require core::$cur_page['item_file'];
		elseif(core::$cur_page['file'] && file_exists(core::$cur_page['file']))
			require core::$cur_page['file'];
		?>
		
	</div>
	
	

</div>
	
	



<div id="footer">
	<div class="wrapper-container">
		<? if(core::$cur_page['id'] != 1){ ?>
		<div class="label darkgray">
		
			<!--<div class="left">
				<div class="corner"></div>
			</div>-->
			
			<!--<p>
				volg ons
				<a target="_blank" href="<?/*= core::$social[twitter] */?>">	<img src="img/twitter.png" 	alt="Twitter" />	</a>
				<a target="_blank" href="<?/*= core::$social[facebook] */?>">	<img src="img/facebook.png" alt="Facebook" />	</a>
				<a target="_blank" href="<?/*= core::$social[youtube] */?>">	<img src="img/youtube.png" 	alt="Youtube" />	</a>
			</p>-->
			
			<!--<div class="shadow"></div>-->
				
		</div>
		
		<!--<div id="footer_info">
			
			ASH  |  Zijlweg 294  |  2015 CN Haarlem  |  T. 023-5392024  |  F. 023-5269898  |   <a href="mailto:info@autoservicehaarlem.nl">info@autoservicehaarlem.nl</a>
			
		</div>-->
		<? } ?>
		
		<div class="col">
			
			<h2>Sitemap</h2>
	
			<?
			foreach(core::$pages as $page)
				if(preg_match('/bottom/',$page['position']))
					echo '<a '.($page == core::$cur_page ? 'class="active"' : '').' href="'.$page['slug'].'" target="_top">'.$page['title'].'</a>';
			?>
			<a href="http://www.santander.nl/" target="_blank">Financieringen</a>
			<a href="<?= core::page_url('id',10) ?>">Favorieten</a>
				
			<img style="position:static;margin-top:5px;" src="img/workson.png" alt="Works on iPhone and iPad" />
				
			<p id="credit">CREATED BY <a href="http://www.merqwaardig.com/" target="_blank">MERQWAARDIG</a></p>
			
		</div>
		
		<div class="col">
			<h2>Contact</h2>
			
			<div class="vcard">
				<div class="org">Auto Totaal Haarlem</div>
				<div class="adr">
					<div class="street-address">Zijlweg 293</div>
					<div><span  class="postal-code">2015 CN</span> <span class="locality">Haarlem</span></div>
				</div>
				<div class="tel">T. 023-5392024</div>
				<div class="fax">F. 023-5269898</div>
				<a href="mailto:info@autototaalhaarlem.nl" class="email">info@autototaalhaarlem.nl</a>
			</div>
			
		</div>
			
		<div class="colFacebook">
			
			<h2>Volg ons op Facebook</h2>
			<div class="fb-like-box" data-href="https://www.facebook.com/autototaalhaarlem" data-width="210" data-height="215" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
			<!--<p>-->
			<?
			/*foreach(sql::fetch("array","landing","ORDER BY `domain` ASC") as $page)
				if($page[domain] != 'hybrideoccasions.com')
					echo '<a href="http://'.$page[domain].'/" target="_blank">'.$page[domain].'</a>';*/
			?>
			<!--</p>-->
			
		</div>
			
		<div class="col last">
			
			<h2>Aangesloten bij</h2>
				
			<a href="http://www.autopas.nl/" 	target="_blank"><img src="img/other/nap.png" class="static" alt="Nationale Auto Pas" width="150"/></a>
			<a href="http://www.rdw.nl/" 		target="_blank"><img src="img/other/rdw.png" class="static" alt="RDW Erkend" /></a>
			
		</div>
	</div>
</div>

<script type="text/javascript" src="js/tiptip.js"></script>

</body>
</html>
