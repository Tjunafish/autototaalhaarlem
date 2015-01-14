<div id="ipad_fix">
	<div id="wrapper">
		<div id="container">
			<?
			core::draw_papertrail();
			?>
			
			<h2>Voor uw zekerheid</h2>
			
			<?
			$packages = sql::fetch("array","packages","ORDER BY `price` ASC");
			
			foreach($packages as $pack){
						
				?>
				<div class="pack">
				
					<img src="<?= $pack[img] ?>" alt="<?= $pack[title] ?>" />
					
					<h2><?= $pack[title] ?></h2>
					<p>
					<?= $pack[desc] ?>
					</p>
					
					<div class="price">
						Prijs: &euro; <?= core::num_format($pack[price]); ?> ,-
					</div>
					
					<div class="clear"></div>
				
				</div>
				<?
				
			}
			?>
			
			<p>
			<?= core::$cur_page[text] ?>
			</p>
		</div>
	</div>
</div>