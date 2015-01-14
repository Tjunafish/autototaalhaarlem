<?
header('Content-Type: application/rss+xml; charset=ISO-8859-1');

require '../php/config.php';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="<?= ROOT.'rss/twitter.php' ?>" rel="self" type="application/rss+xml" />
<title>Auto Service Haarlem - Voorraad RSS</title>
<link><?= ROOT ?></link>
<description>Nieuw binnen bij Auto Service Haarlem</description>
<?
$cars = sql::fetch("array","cars","ORDER BY `created_at` DESC LIMIT 10");

foreach($cars as $car){
?>
<item>


	<title><?= htmlspecialchars($car[merk].' '.$car[model].' '.$car[type].' &#8364; '.core::car_price($car).' ,-') ?></title>
	<link><?= ROOT.core::car_url($car) ?></link>
	<guid><?= ROOT.core::car_url($car) ?></guid>
	<description><?= substr($car[accessoires],0,150) ?></description>
	<pubDate><?= date('r',strtotime($car[created_at])) ?></pubDate>

</item>
<?
}
?>

</channel>
</rss>