
<div id="ipad_fix">
	<div id="wrapper">
		<div id="container">
			<?
			core::draw_papertrail();
			?>


			<h2>Even voorstellen</h2>
			
			<?
			$people = sql::fetch("array","people","ORDER BY `order` ASC");
			
			foreach($people as $person){
				
				list($alt) = explode("-",$person[name]);
						
				?>
				<div class="person">
				
					<img src="<?= $person[img] ?>" alt="<?= $alt ?>" />
					
					<h2><?= $person[name] ?></h2>
					<p>
					<?= $person[desc] ?>
					</p>
					
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