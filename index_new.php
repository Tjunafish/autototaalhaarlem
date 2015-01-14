<?php

$useragent = $_SERVER['HTTP_USER_AGENT'];

require 'php/config.php';

/*if(core::$cur_page['id'] == 1 && preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	header('Location: http://autoservicehaarlem.nl/m/');*/
?>
<!DOCTYPE html PUBLIC>
<html>

<head>

	<meta http-equiv="X-UA-Compatible"  content="IE=Edge"/>
	<meta http-equiv="Content-Type"     content="text/html;charset=utf-8" />

	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600' rel='stylesheet' type='text/css'>

	<base href="<?= ROOT ?>" />

	<?
	core::draw_metadata();
	?>

	<link	rel="alternate"  type="application/rss+xml" title="Auto Service Haarlem - Voorraad RSS" href="<?= ROOT ?>rss/" />

	<!-- Mootools-->
	<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js"></script>
	<script type="text/javascript" src="js/more.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

	<!-- StyleSheet -->
	<link 	rel="stylesheet" href="css/default-new.css"/>

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

</head>

<body>
	<div class="container">
		<div class="header">
			<div class="inner">
				<a href="index_new.php">
					<div class="logo-box">
						<img src="img/new/logo.png">
					</div>
				</a>
				<div class="nav">
					<a class="active" href="index_new.php">Home</a>
					<a href="voorraad">Aanbod</a>
					<a href="milieubewust">Best green deals</a>
					<a href="garantiepakketten">Service</a>
					<a href="contact">contact</a>
				</div>
				<div class="lang">
					<select class="sb" id="langpicker">
						<option>Nederlands</option>
						<option>Engels</option>
					</select>
				</div>
			</div>
		</div>
		<div class="splash">
			<div class="fader">
				<div style="background-image: url(img/new/splash1.jpg)"></div>
				<div style="background-image: url(img/new/splash2.jpg); opacity: 0;"></div>
				<div style="background-image: url(img/new/splash3.jpg); opacity: 0;"></div>
			</div>
			<form class="controls">
				<select class="sb" name="brand">
					<option>Selecteer merk</option>
					<option value="3">BMW</option>
				</select>
				<select class="sb" name="model">
					<option>Selecteer model</option>
					<option value="3">3 Serie</option>
					<option value="4">5 Serie</option>
					<option value="7">7 Serie</option>
				</select>
				<select class="sb" name="price">
					<option>selecteer prijs</option>
					<option value="0,5000">&euro; 0,-  &euro; 5.000,-</option>
					<option value="5000,10000">&euro; 5.000,- tot &euro; 10.000,-</option>
					<option value="10000,15000">&euro; 10.000,- tot &euro; 15.000,-</option>
				</select>
				<select class="sb" name="fuel">
					<option>Selecteer brandstof</option>
					<option value="gas">Gas</option>
				</select>
				<label for="hs1" class="submit">zoeken</label>
				<input id="#hs1" class="hidden-submit" type="submit" value="zoeken">
			</form>
			<div class="splash-footer">
				<div class="inner">
					<?php echo str_replace('|', '&nbsp;|&nbsp;', 'Zijlweg 294  |  2015 CN Haarlem  |  T. 023 53 920 24  |   <a href="mailto:info@autoservicehaarlem.nl">info@autoservicehaarlem.nl</a>'); ?>
					<div class="flr">
						<a href="rdw"><img src="img/new/rdw-logo.png"></a>
						<a href="nap"><img src="img/new/nap-logo.png"></a>
					</div>
				</div>
			</div>
		</div>
		<div class="car-slider-container">
			<div class="inner">
				<div class="filters">
					<a class="active" href="#Nieuw-binnen">Nieuw binnen</a><a href="#Aanbiedingen">Aanbiedingen</a><a href="#Meest-Populair">Meest Populair</a>
				</div>
				<div class="car-container">
					<a href="#left"><div class="arrow"></div></a><a href="#right"><div class="arrow"></div></a>
					<div class="inner-container">
						<div class="inner-width">
					<?php 
						$cars = sql::fetch("array","cars","WHERE `showticker` = 1 && `active` = 1 ORDER BY `created_at` DESC LIMIT 10");
						foreach($cars as $car) {
							include 'partials/car.php';
						} 
					?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="inner950">
			<div class="double-coloumn fl">
				<div>
					<div class="good-good">
						<h2><span>Top deals</span></h2>
						<p>Met de stijgende bezineprijzen en
						toenemende zorg over ons milieu 
						hechten steeds meer mensen waarde 
						aan auto’s die zuinig zijn.</p>
						<ul>
							<li>A, B of C label</li>
							<li>Slechts 14% bijtelling</li>
							<li>Minder CO2 uitstoot</li>
						</ul>
						<p class="slogan"><span>Green Deals,</span><br>
						een milieu bewuste keuze!</p>
					</div>
					<div class="best-day-deal">
						<?php
							$title = 'Best Day Deal';
							list($car) = sql::fetch("array","cars","WHERE `best_day_deal` = 1 && `active` = 1 ORDER BY `created_at` LIMIT 1");
							list($thumb) = explode(',', $car[afbeeldingen]);
							include 'partials/car.php';
						?>
					</div>
				</div>
				<div class="taxatie">
					<div class="front">
						<h2>Je auto inruilen?</h2>
						<h3>Vraag dan hier gratis een taxatie aan!</h3><br>
						<input type="text" placeholder="Je kenteken"><br>
						<label for="hs1" class="submit">Taxatie aanvragen</label><input type="submit" id="hs1" class="hidden-submit">
					</div>
					<div class="back">
						<input type="text" name="kenteken" placeholder="Je kenteken"><br>
						<input type="text" name="name" placeholder="Je naam"><br>
						<input type="text" name="email" placeholder="Je e-mailadres"><br>
						<input type="text" name="phone" placeholder="Uw telefoonnummer"><br>
						<label for="hs2" class="submit">Taxatie aanvragen</label><input type="submit" id="hs2" class="hidden-submit">
					</div>
				</div>
				<div class="special-buttons">
					<a href="aanbod">Aanbod</a>
					<a href="service">Service</a>
				</div>
			</div>
			<div class="colomn fr">
				<form method="post" action="ajax/wijbellenjou.php" class="border-form fr">
					<h2><span>Vragen?</span> Wij bellen jou!</h2>
					<input name="name" type="text" placeholder="Je naam">
					<input name="phone" type="text" placeholder="Je telefoonnummer">
					<label for="hs3" class="flr submit">Verzenden</label><input type="submit" id="hs3" class="hidden-submit">
				</form>
				<div class="mobile-version fr">
					<h2>Bezoek ook eens onze <span>mobiele website..</span></h2>
					<img src="img/new/mobile_version.png" alt="iPhones">
				</div>
				<div class="facebook-social fr">
					<h2><span>Like ons</span> op Facebook</h2>
					<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fautoservicehaarlem&amp;width=240&amp;height=235&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;show_border=false&amp;header=false&amp;appId=132096160224620" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:240px; height:235px;" allowTransparency="true"></iframe>
				</div>
			</div>
		</div>
		<div class="footer">
			<div class="column">
				<h3>Contact</h3>
				<p>
					Auto Service Haarlem<br>
					Zijlweg 294<br>
					2015 CN Haarlem<br>
					<br>
					T. 023-5392024<br>
					F. 023-5269898<br>
					<a href="mailto:info@autoservicehaarlem.nl">info@autoservicehaarlem.nl</a>
				</p>
			</div><div class="column">
				<h3>Openingstijden</h3>
				<p>
					Maandag t/m vrijdag<br>
					Van 9.00 tot 18:00 uur<br>
					<br>
					Zaterdags<br>
					Van 9.00 tot 17.00 uur<br>
					<br>
					Zondag<br>
					Van 12.00 tot 16.00 uur
				</p>
			</div><form method="post" action="ajax/newsletter.php" class="column">
				<h3>Nieuwsbrief</h3>
				<input type="text" name="name" placeholder="Je naam">
				<input type="text" name="email" placeholder="Je e-mailadres">
				<label for="hs4" class="flr submit">vertstuur</label><input type="submit" id="hs4" class="hidden-submit">
			</form><div class="column center">
				<h3>Aangesloten bij</h3>
				<a href="rdw"><img src="img/new/rdw.png"></a>
				<a href="rdw"><img src="img/new/nap.png"></a>
			</div>
		</div>
	</div>
</body>
</html>