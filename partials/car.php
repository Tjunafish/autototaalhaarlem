<?php

if(!$thumb) {
	list($thumb) = core::car_thumbs($car);
}
?><a href="voorraad/<?= core::slug( $car['merk'] . '-' . $car['model'] ) . '-' . $car['voertuignr'] . '.html'; ?>" class="car-block">
	<img src="<?= $thumb ?>">
	<h2>
	<?php 
	if($title)
		echo $title;
	else
		echo $car['merk'] . ' ' . $car['model']; 
	?></h2>
	<h3><?= ( $title ? $car['merk'] . ' ' . $car['model'] . ' ' : '') . $car['type']; ?></h3>
	<p>Prijs: &euro; <?= core::car_price($car); ?>,-</p>
</a><?php
unset ($thumb);