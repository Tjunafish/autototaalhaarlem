<?
require 'php/config.php';
?>
<!DOCTYPE HTML>
<html>

<head>

    <meta charset="utf-8">
	
<?
$browser = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
if ($browser == true) 
	echo '<meta name="viewport" content="width=device-width; initial-scale=0.48; user-scalable=0;" />';
else {
	echo '<meta name="viewport" content="670, initial-scale=0.56; user-scalable=1">';
	echo '<link rel="stylesheet" href="css/select_box.css" />';
}
?>
	
	
	



    
        
    <?
    mobile_core::draw_metadata();
    ?>
	<?/*
	<base href="<?= ROOT ?>" />
    <link	rel="alternate"  type="application/rss+xml" title="Auto Service Haarlem - Voorraad RSS" href="<?= ROOT ?>rss/" />

    <link 	rel="stylesheet" href="../css/default.css"/>
    <link 	rel="stylesheet" href="../css/print.css"  media="print" />
    
    <link 	rel="stylesheet" href="../css/mobile.css" media="screen and (orientation: portrait) or (orientation: landscape) or (max-device-width: 480px)" />
    <link 	rel="stylesheet" href="../css/mobile.css" media="handheld" />
	*/
	//echo '<link	rel="stylesheet" href="../css/default.css" />';
	?>
	
	<base href="<?= ROOT ?>" />
	
	<?
    mobile_core::draw_js_libs();
    ?>
	
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-18278644-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
	
	<script type="text/javascript">
		if ('standalone' in navigator && !navigator.standalone && (/iphone|ipod|ipad/gi).test(navigator.platform) && (/Safari/i).test(navigator.appVersion)) {
			document.write('<link rel="stylesheet" href="m_develop\/css\/add2home.css">');
			document.write('<script type="application\/javascript" src="m_develop\/js\/add2home.js" charset="utf-8"><\/s' + 'cript>');
		}
	</script>
		
	<link	rel="stylesheet" href="m/css/default.css" />
	
	<link	rel="stylesheet" href="m/css/fontface.css" />
	
	<script type="text/javascript" src="js/jquery.outside-events.js"></script>
	
	<!-- jQuery SB Plugin -->
    <link	rel="stylesheet" 		href="m/css/jquery.sb.css" />
    <script type="text/javascript" 	src="m/js/jquery.sb.min.js"></script>
	
	<script type="text/javascript" src="m/js/functions.js"></script>
	
<body>

<div id="ipad_fix">

<div id="wrapper">
	
	<div id="header">
		
		<a href="/m/"><h1>Auto Service Haarlem<img src="img/logo_ash.png" alt="Auto Service Haarlem" /></h1></a>
		
		<form id="search" method="post" action="m_develop/" target="_top">
					
			<input type="text"  name="string" value="<?= $_POST[string] ? $_POST[string] : 'vind hier je nieuwe auto' ?>" class="clickclear" />
			<input type="image" src="img/search.jpg" />
				
		</form>
		
		<div class="clear"></div>
		
	</div>
	
<?
	include('sub/voorraad.php');
?>
	
	<div id="footer">
	
		<div class="label darkgray">
	
			<p>
			volg ons
			<a target="_blank" href="<?= mobile_core::$social[twitter] ?>">	<img src="img/twitter.png" 	alt="Twitter" />	</a>
			<a target="_blank" href="<?= mobile_core::$social[facebook] ?>">	<img src="img/facebook.png" alt="Facebook" />	</a>
			<a target="_blank" href="<?= mobile_core::$social[youtube] ?>">	<img src="img/youtube.png" 	alt="Youtube" />	</a>
			</p>
		
			<div class="shadow"></div>
			
		</div>
		
		<div id="footer_info">
		
			ASH  |  Zijlweg 294  |  2015 CN Haarlem  |  T. 023-5392024  <br>F. 023-5269898  |   <a href="mailto:info@autoservicehaarlem.nl">info@autoservicehaarlem.nl</a>
		
		</div>
		
		<div class="clear"></div>
	
	</div>

</div>

<?
if ($browser == true)
	echo '
	<script type="text/javascript"> 
		$(\'#search_adv select\').sb();
	</script>		
	';


?>
	