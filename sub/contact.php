<?
$error = array();

if($_POST){

	$checks 	  	= array();
	$checks[name] 	= array($_POST[name],	'empty',		'voornaam');
	$checks[email]	= array($_POST[email],	'empty email');
	$checks[msg]	= array($_POST[msg],	'empty',		'vraag of opmerking');
	
	$error = core::validate($checks);
	
	if(count($error) == 0)	
		core::send_mail(core::$mail_from,'Nieuw bericht via Autoservicehaarlem.nl',
					   '<b>Naam: </b>'.$_POST[name].' '.$_POST[lastname].'<br/>'.
			   		   ($_POST[phone] ? '<b>Telefoonnummer:</b> '.$_POST[phone].'<br/>' : '').
			   		   '<b>E-mailadres: </b>'.$_POST[email].'<br/>'.
			   		   '<b>Bericht:</b><br/>'.$_POST[msg]);
	
}
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true"></script>

<div id="map"></div>

<div class="label orange">
	<div class="wrapper-container">
		<p class="left arrow_down">Neem contact op</p>
		<p class="right">	<a target="_blank" href="http://maps.google.com/maps?daddr=Zijlweg+294,+2015+CN+Zijlweg-West,+Haarlem,+Nederland&hl=en&sll=37.0625,-95.677068&sspn=55.455479,135.263672&vpsrc=0&mra=ls&t=m&z=16" class="arrow_right">Plan hier uw route</a></p>
	</div>
	<div class="shadow"></div>
	
</div>

<div id="ipad_fix">
	<div id="wrapper">
		<div id="container">
	
			<div id="contact">
			
				<?
				if($_POST && count($error) == 0){
				?>
				Uw bericht is verstuurd naar Auto Service Haarlem, u ontvangt zo spoedig mogelijk een reactie.
				<?
				}else{
				?>
				<form method="POST">
			
					<input class="clickclear <?= $error[name]  ? 'error' : '' ?>" type="text" name="name"  value="<?= $_POST[name]  ? $_POST[name]  : 'voornaam'    ?>"   />			
					<input class="clickclear" type="text" name="lastname" 	value="<?= $_POST[lastname] ? $_POST[lastname] 	: 'achternaam' 		?>" />
					<input class="clickclear" type="text" name="phone" 		value="<?= $_POST[phone] 	? $_POST[phone] 	: 'telefoonnummer' 	?>" />	
					<input class="clickclear <?= $error[email] ? 'error' : '' ?>" type="text" name="email" value="<?= $_POST[email] ? $_POST[email] : 'e-mailadres'	?>" />
					
					<textarea class="clickclear <?= $error[msg] ? 'error': '' ?>" name="msg"><?= $_POST[msg] ? $_POST[msg] : 'vraag of opmerking' ?></textarea>
					
					<input type="submit" value="verstuur bericht" />
				
				</form>
				<?
				}
				?>
			
			</div>
			
			<div id="contact_right">
			
				<h2>Auto Totaal Haarlem</h2>
				Zijlweg 294<br />
				2015 CN Haarlem<br />
				T. 023-5392024<br />
				F. 023-5269898<br />
				<a href="mailto:info@autototaalhaarlem.nl">info@autototaalhaarlem.nl</a><br />
				<br />
				
				<h2>Openingstijden</h2>
				MA t/m VRIJ van 9.00 tot 18.00 uur<br />
				Zaterdags van 9.00 tot 17.00 uur<br />
				Zondag gesloten
			
			</div>
			
			<div class="clear"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function(){

	var latLng = new google.maps.LatLng(52.387067,4.611873);

	var myOptions = {
	  zoom: 			14,
	  center: 			new google.maps.LatLng(52.386431,4.61724),
	  mapTypeId: 		google.maps.MapTypeId.ROADMAP,
	  disableDefaultUI: true
	};	

	var map = new google.maps.Map(document.getElementById('map'),myOptions);
	
	marker 	= new google.maps.Marker({
		position: 	latLng,
		map:		map	
	});

});
</script>