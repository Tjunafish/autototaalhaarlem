<?

if(!$filter)
	require dirname(__FILE__).'/../php/cars_settings.php';
		
$ex = core::$cur_page[id] == 4 ? "WHERE `milieu_bewust` = '1' " : false;
?>
<form id="search_adv" method="POST" action="<?= core::page_url('file','sub/voorraad.php') ?>" autocomplete="off" <?= core::$cur_page[file] == 'sub/home.php' ? 'style="margin-top:0;"' : '' ?>>

	<div class="shadow"></div>

	<h2>Uitgebreid zoeken</h2>
	
	<div class="col">
		
		<label for="select_brand">Merk</label>
		
		<select id="select_brand" name="brand" <?= $filter[brand] != '' ? 'class="mark"' : ''?>>
		
			<option value="">Selecteer merk</option>
			<?
			sql::$select = 'SELECT DISTINCT(`merk`)';
			
			foreach(sql::fetch("array","cars",$ex."ORDER BY `sortname` ASC") as $car)
				echo '<option value="'.$car[merk].'" '.
					 ($filter[brand] == $car[merk] ? 'selected="selected"' : '').
					 '>'.$car[merk].'</option>';
			?>
		
		</select>
		
		<label for="select_model">Model</label>

		<select id="select_model" name="model" <?= $filter[brand] != '' && $filter[model] != '' ? 'class="mark"' : ''?>>
		
			<?
			if(!empty($filter[brand])){
			
				echo '<option value="">Selecteer model</option>';
				sql::$select = 'SELECT DISTINCT(`model`) ';
				
				
				
				foreach(sql::fetch("array","cars",$ex."ORDER BY `model` ASC") as $car) {
					echo '<option value="'.$car[model].'" '.
						 ($filter[model] == $car[model] ? 'selected="selected"' : '').
						 '>'.$car[model].'</option>';
				}
						 
		 	}else
		 		echo '<option value="">Selecteer eerst een merk</option>';
			?>
		
		</select>
		
		<? if($filter[brand]){ ?>
					<script>
						$(window).ready(function(){
							$.post('ajax/models.php',{brand: $('#select_brand').val()},function(data){
								if(data != 'error') {
									$('#select_model').html(data);
									<?
										$model_acb = $filter[model] ?: $_POST[model] ?: $_SESSION[model] ?: false;
									?>
									<?= $model_acb ? '$("#select_model").val("'.$model_acb.'")' : ''?>
								}									
								$("#select_model").sb("refresh");							
							});
						});
					</script>
		<? } ?>
			
	</div>
	
	<div class="col">
	
		<label for="input_min_price">Prijs</label>
		
		<input type="text" id="input_min_price" value="<?= $filter[minprice] ? $filter[minprice] : 'Van' ?>" class="clickclear first <?= $filter[minprice] != '' ? 'mark' : ''?>" name="minprice" />		
		<input type="text" id="input_max_price" value="<?= $filter[maxprice] ? $filter[maxprice] : 'Tot' ?>" class="clickclear <?= $filter[maxprice] != '' ? 'mark' : ''?>"		  name="maxprice" />
		
		<label for="input_km_min">Kilometerstand</label>
		
		<input type="text" id="input_km_min" value="<?= $filter[minkm] ? $filter[minkm] : 'Van' ?>" class="clickclear first <?= $filter[minkm] != '' ? 'mark' : ''?>" name="minkm" />		
		<input type="text" id="input_km_max" value="<?= $filter[maxkm] ? $filter[maxkm] : 'Tot' ?>" class="clickclear <?= $filter[maxkm] != '' ? 'mark' : ''?>" 	  name="maxkm" />
	
	</div>
	
	<div class="col">
	
		<label for="input_min_year">Bouwjaar</label>
		
		<input type="text" id="input_min_year" value="<?= $filter[minyear] ? $filter[minyear] : 'Van' ?>" class="clickclear first <?= $filter[minyear] != '' ? 'mark' : ''?>" name="minyear" />		
		<input type="text" id="input_max_year" value="<?= $filter[maxyear] ? $filter[maxyear] : 'Tot' ?>" class="clickclear <?= $filter[maxyear] != '' ? 'mark' : ''?>" 		name="maxyear" />
		
		<label for="select_transmission">Transmissie</label>
		
		<select id="select_transmission" name="transmission" <?= $filter[transmission] != '' ? 'class="mark"' : ''?>>
			
			<option value="">Selecteer transmissie</option>
			<?
			sql::$select = 'SELECT DISTINCT(`transmissie`)';
			$cars = sql::fetch("array","cars",$ex."ORDER BY `transmissie` ASC");
			sql::reset_select();
			
			foreach($cars as $car)
					echo '<option value="'.$car[transmissie].'" '.
						 ($filter[transmission] == $car[transmissie] ? 'selected="selected"' : '').
						 '>'.core::string("transmissie ".$car[transmissie]).'</option>';
			?>
		
		</select>
		<?
		/*
		?>
		<label for="select_seats">Zitplaatsen</label>
		
		<select id="select_seats" name="seats">
			
			<option value="">Selecteer aantal zitplaatsen</option>
			<?
			sql::$select = 'SELECT DISTINCT(`aantal_zitplaatsen`)';
			
			foreach(sql::fetch("array","cars",$ex."ORDER BY `aantal_zitplaatsen` ASC") as $car)
				if($car[aantal_zitplaatsen] != 0)
					echo '<option value="'.$car[aantal_zitplaatsen].'" '.
						 ($filter[aantal_zitplaatsen] == $car[aantal_zitplaatsen] ? 'selected="selected"' : '').
						 '>'.$car[aantal_zitplaatsen].'</option>';
			?>
		
		</select>
		<?
		*/
		?>
	
	</div>
	
	<div class="col">		
		
		<label for="select_doors">Aantal deuren</label>
		
		<select id="select_doors" name="doors" <?= $filter[doors] != '' ? 'class="mark"' : ''?>>
		
			<option value="">Selecteer aantal deuren</option>
			<?
			sql::$select = 'SELECT DISTINCT(`aantal_deuren`)';
			
			foreach(sql::fetch("array","cars",$ex."ORDER BY `aantal_deuren` ASC") as $car)
				echo '<option value="'.$car[aantal_deuren].'" '.
					 ($filter[doors] == $car[aantal_deuren] ? 'selected="selected"' : '').
					 '>'.$car[aantal_deuren].'</option>';
			?>
		
		</select>
		
		<label for="select_fuel">Brandstof</label>
		
		<select id="select_fuel" name="fuel" <?= $filter[fuel] != '' ? 'class="mark"' : ''?>>
		
			<option value="">Selecteer type brandstof</option>
			<?
			sql::$select = 'SELECT DISTINCT(`brandstof`)';
			$cars 		 = sql::fetch("array","cars",$ex);
			sql::reset_select();
			
			foreach($cars as $car)
				echo '<option value="'.$car[brandstof].'" '.
					 ($filter[fuel] == $car[brandstof] ? 'selected="selected"' : '').
					 '>'.core::string("brandstof ".$car[brandstof]).'</option>';
			?>
		
		</select>
	
	</div>
	
	<div class="label darkgray" <?= core::$cur_page[file] != 'sub/home.php' ? 'id="search_adv_submit"' : '' ?>>

		<?= core::$cur_page[file] == 'sub/home.php' ? '<input type="submit" value="" class="full" />' : '' ?>

		<div class="left"> <div class="corner"></div></div>
		<div class="right"><div class="corner"></div></div>
	
		<p><span>Zoeken</span> <img src="img/search.png" alt="Zoeken"/></p>
	
		<div class="shadow"></div>
		
	</div>

</form>