<?
require 'php/cars_settings.php';
?>

<?
$cars = sql::fetch("array","cars","WHERE `showticker` = 1 && `active` = 1 ORDER BY `created_at` DESC LIMIT 0,4");
$cars2 = sql::fetch("array","cars","WHERE `showticker` = 1 && `active` = 1 ORDER BY `created_at` DESC LIMIT 5,4");
$last = end($cars);
?>
<div id="ipad_fix">
	<div id="wrapper">
		<div id="container">
	
			<div id="rotate_contain" style="height: 180px">
				<div id="cars_rotate" data-start="<?= $last[created_at] ?>">
				
					<?	
					core::draw_cars(4,$cars);
					?>
					
					<div class="clear"></div>
					
				</div>
			</div>
			<div id="rotate_contain" style="height: 180px;margin-bottom: 60px;margin-top:-30px">
				<div id="cars_rotate" data-start="<?= $last[created_at] ?>">
			
					<?
					core::draw_cars(4,$cars2);
					?>
			
					<div class="clear"></div>
			
				</div>
			</div>
			
			<div class="relative">
			
				<div class="bigblock left">
				
					<form class="newsletter ajax" action="ajax/newsletter.php">
					
						<div id="img_mail"></div>
					
						<p>Nieuwsbrief</p>
						
						<input type="text" name="name"  value="Je naam"			class="clickclear" />
						<input type="text" name="email" value="Je e-mailadres"	class="clickclear" />
					
						<div class="label darkgray">
								
							<input type="submit" class="full left" value="Verstuur" />
						
							<div class="shadow"></div>
							
						</div>
					
					</form>
					
					<div class="shadow"></div>
				
				</div>
				
				<div class="bigblock center">
				
					<p>
					Je auto inruilen?<br />
					Vraag dan hier<br />
					gratis een taxatie aan!
					</p>
					
					<div class="label darkgray">
					
						<p><a class="tax_toggle">Taxatie aanvragen</a></p>
						
						<a href="#" class="tax_toggle full"></a>
					
						<div class="shadow"></div>
						
					</div>
					
					<form class="taxatie ajax" action="ajax/taxatie.php">
										
						<h2>Vraag een gratis taxatie aan!</h2>
						
						<input type="text" name="name"  class="clickclear" value="Je naam" />
						<input type="text" name="phone" class="clickclear" value="Je telefoonnummer" /><br />
						<input type="text" name="email" class="clickclear" value="Je e-mailadres" />
						<input type="text" name="plate" class="clickclear" value="Kenteken" /><br />
						
						<textarea name="msg" class="clickclear">Persoonlijk bericht</textarea><br />
						
						<input type="submit" value="Verstuur bericht" />
									
					</form>
					
					<div class="shadow"></div>
				
				</div>
				
				<div class="bigblock right">
				
					<form class="contact ajax" action="ajax/callback.php">
					
						<div id="img_support"></div>
					
						<p>Vragen? Wij bellen jou!</p>
						
						<input type="text" name="name"  value="Je naam"				class="clickclear" />
						<input type="text" name="phone" value="Je telefoonnummer"	class="clickclear" />
					
						<div class="label darkgray">
					
							<input type="submit" class="full right" value="Verstuur" />
					
					
							<div class="shadow"></div>
							
						</div>
					
					</form>
					
					<div class="shadow"></div>
				
				</div>
				
			</div>
			
			
			
			<!--<div class="left grid">-->
			
				<?
				//require __DIR__.'/../includes/search_form.php';
				?>
			
			<!--</div>-->
			
			<div id="spotlight-list">
			
				<?
				list($car)   = sql::fetch("array","cars","WHERE `best_day_deal` = 1 && `active` = 1 ORDER BY `created_at` LIMIT 1");
				list($thumb) = explode(",",$car[afbeeldingen]);
				?>
				
				<div id="spotlight" <?= $car[milieu_bewust] ? 'class="green"' : '' ?>>
			
					<a href="<?=core::car_url($car)?>" class="full"></a>
					<img src="<?= $thumb ?>" alt="<?= $car[merk].' '.$car[model] ?>" />
					
					<div class="spotlight-header">
						<h2>
						Best day deal!<br />
						<?
						if($car[actieprijs]){
						?>
						<span class="small">van</span> <span class="strike">&euro; <?= core::num_format($car[verkoopprijs_particulier]) ?></span> <span class="small">voor</span> &euro; <?= core::num_format($car[actieprijs]) ?>
						<?
						}else{
						?>
						<span class="small">voor</span> &euro; <?= core::num_format($car[verkoopprijs_particulier]) ?>
						<?
						}
						?>
						</h2>
					</div>
					<p><?= $car[merk].' '.$car[model].' '.$car[type] ?></p>
				
				</div>
				
				<div id="yellow_block">
				
					<div id="satisfaction_label"></div>
				
					<p>Facts...</p>
			
					<ul>
					
						<?
						$facts = sql::fetch("array","didyouknow","ORDER BY `order` ASC");
						
						foreach($facts as $fact)			
							echo '<li>'.($fact[link] ? '<a href="'.$fact[link].'">' : '').$fact[text].($fact[link] ? '</a>' : '').'</li>';
						?>
					
					</ul>
				
				</div>
			
			</div>
	
	
			<div class="clear"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('ul.mediablock').codeslide({buttonholder:	'ul.mediablock .slider_pages', 
							  buttonclass: 	'page', 
							  buttonactive: 'active'});
$('ul.mediablock2').codeslide({buttonholder:'ul.mediablock2 .slider_pages2', 
							  buttonclass: 	'page', 
							  buttonactive: 'active'});
</script>
