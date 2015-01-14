<?
require '../php/config.php';
require '../php/qrcode/qrlib.php';
require '../php/MPDF52/mpdf.php';

$car = $_GET['car'];

if(!sql::exists("cars",array("voertuignr"=>$car)))
	die('Dit voertuig ('.$_GET['car'].') staat niet in het bestand.');
	
list($car) = sql::fetch("array","cars","WHERE `voertuignr` = '".sql::escape($car)."'");

$qr   = 'tmp_'.uniqid().'.png';
QRcode::png(ROOT.core::car_url($car),$qr);

function decode($input){
        
    if(mb_detect_encoding($input) == 'UTF-8')
        return $input;
    else
        return utf8_decode($input);
        
}

ob_start();
?>		
<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-type" content="application/xhtml+xml;charset=utf-8" />
	<link rel="stylesheet" href="default.css" />
	<title><?= decode($car[merk].' '.$car[model].' '.$car[type]) ?></title>
	
</head>
<body style="background:url(ath.jpg) no-repeat top left;">

<div id="wrap" <?= $car[milieu_bewust] ? 'class="green"' : '' ?>>

	<h2>&euro; <?= core::car_price($car) ?></h2>
	<h3><?= decode($car[merk].' '.$car[model].' '.$car[type]) ?></h3>

	<div class="detail"><b>Model:</b> <?= decode($car[merk].' '.$car[model]) ?></a></div>
	<div class="detail"><b>Bouwjaar:</b> <?= $car[bouwjaar] ?></div>
	<div class="detail"><b>Brandstof:</b> <?= core::string('brandstof '.$car[brandstof]); ?></div>
	<div class="detail"><b>Kilometerstand:</b> <?= number_format($car[tellerstand],0,',','.') ?> km</div>
		
	<div class="title">Opties:</div>
	<p><?= str_replace('*',' ',preg_replace('/(,([^\s]))+/'," <span class=\"bull\">&bull;</span> $2",$car[accessoires])) ?></p>

</div>

</body>
</html>
<?
$html = ob_get_clean();

if(mb_detect_encoding($car[merk]) != 'UTF-8' && mb_detect_encoding($car[model]) != 'UTF-8')
    $html = utf8_encode($html);

$mpdf = new mPDF('', '', 0, '', 0,0,0,0,0,0);
$mpdf->WriteHTML($html);

$mpdf->SetHTMLFooter('
<div id="botimgs">
<img height="230" src="/img/other/nap_big.jpg" alt="Nationale auto pas" />
<img height="300" src="'.$qr.'" alt="QRCode" /></div><br/>
<div id="footer" '.($car[milieu_bewust] ? 'style="background-color:#004371;"' : '').'>ATH  |  Zijlweg 294  |  2015 CN Haarlem  |  T. 023-5392024  |  F. 023-5269898  |   info@autototaalhaarlem.nl</div>
');
//echo $html;	
$mpdf->Output(core::slug($car[merk].' '.$car[model].' '.$car[voertuignr]).'.pdf','I');

unlink($qr);
?>