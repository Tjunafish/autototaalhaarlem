<?
header('Content-Type: application/rss+xml; charset=ISO-8859-1');

require '../php/config.php';
?>
<rss version="2.0">
<channel>
<title>Auto Service Haarlem - Voorraad RSS</title>
<link><?= ROOT ?></link>
<description>Nieuw binnen bij Auto Service Haarlem</description>
<?
$cars = sql::fetch("array","cars","ORDER BY `created_at` DESC LIMIT 10");

foreach($cars as $car){
?>
<item>

	<title><?= htmlspecialchars($car[merk].' '.$car[model].' '.$car[type].' '.core::string("transmissie ".$car[transmissie]).' '.core::string("brandstof ".$car[brandstof]).' Prijs: € '.core::car_price($car).' ,-') ?></title>
	<link><?= ROOT.core::car_url($car) ?></link>
	<pubDate><?= date('r',strtotime($car[created_at])) ?></pubDate>
	<description>
	
		<? 
		ob_start(); 
		
		$images = explode(",",$car[afbeeldingen]);
		?>
		<img src="<?=$images[0]?>" width="320" height="240" /><br />
		<br />
		<table>
		
			<tr>
				<td width="300"><b>Prijs:</b> &euro; 	<?= core::car_price($car) ?> ,- <br /></td>
				<td width="300"><b>Bouwjaar:</b> 		<?= $car[bouwjaar] ?><br /></td>
				<td width="300"><b>Kilometerstand:</b>  <?= $car[tellerstand] ?><br /></td>
			</tr>
			
			<tr>
				<td><b>Carrosserievorm:</b> 			<?= $car[carrosserie] ?><br /></td>
				<td><b>Transmissie:</b>					<?= core::string("transmissie ".$car[transmissie]) ?><br /></td>
				<td><b>Cilinderinhoud:</b>				<?= $car[cilinderinhoud] ?> cc<br /></td>
			</tr>
			
			<tr>
				<td><b>Brandstof:</b>					<?= core::string("brandstof ".$car[brandstof]) ?><br /></td>
				<td><b>Aantal deuren:</b>				<?= $car[aantal_deuren] ?><br /></td>
				<td><b>Kleur:</b>						<?= $car[basiskleur].' '.$car[laksoort] ?><br /></td>
			</tr>
		
		</table>	
		<br />
		<? 
		echo $car[accessoires];
		echo htmlspecialchars(ob_get_clean()); 
		?>
	
	</description>

</item>
<?
}
?>

</channel>
</rss>