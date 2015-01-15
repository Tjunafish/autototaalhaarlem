<?
require 'php/cars_settings.php';
?>

<div id="ipad_fix">
	<div id="wrapper">
		<div id="container">
			<div id="top_spacer"></div>
			<?
			core::draw_papertrail();
			?>
			
			<form id="controls" autocomplete="off">
			
				<input type="hidden" name="curr" value="page" />
				<input type="hidden" name="prev" />
				<input type="hidden" name="section" value="<?= $_GET['section'] ?>" />
				
				<?
				$fields = array('view'		=> $search['view'],
								'sort'		=> $search['sort']['type'],
								'sort_dir'	=> $search['sort']['dir'],
								'page'		=> $search['page']['current']);
								
				foreach($fields as $field => $value)
					echo '<input type="hidden" name="'.$field.'" value="'.$value.'" />';
				?>
			
			</form>
			
			<div class="options <?= $landing ? 'landing' : '' ?>">
			
				<span class="left">
				
					<p>Bekijk:</p>
					
					<a class="control" rel="view" href="grid"><img src="img/grid<?= $search['view'] == 'grid' ? '_active' : '' ?><?= $search['view'] == 'grid' && core::$cur_page['id'] == 4 ? '_green' : '' ?>.png" alt="Grid" /></a>
					
					<span class="divider"></span>
					
					<a class="control" rel="view" href="list"><img src="img/list<?= $search['view'] == 'list' ? '_active' : '' ?><?= $search['view'] == 'list' && core::$cur_page['id'] == 4 ? '_green' : '' ?>.png" alt="List" /></a>
					
				</span>
				
				<span class="mid">
				
					<p>Sorteer op:</p>
					
					<a class="control <?= $search['sort']['type'] == 'name'	 ? 'active' : '' ?>" rel="sort" data-dir="asc" 	href="name">Naam 
					<img src="img/sort_asc<?= core::$cur_page['id'] == 4 ? '_green' : '' ?>.png" alt="asc"   rel="sort" <?= $search['sort']['type'] != 'name'  ? 'style="display:none;"' : '' ?>/></a>   
					
					<span class="divider"></span>
					
					<a class="control <?= $search['sort']['type'] == 'date'  ? 'active' : '' ?>" rel="sort" data-dir="desc" href="date">Datum
					<img src="img/sort_desc<?= core::$cur_page[id] == 4 ? '_green' : '' ?>.png" alt="desc" rel="sort" <?= $search['sort']['type'] != 'date'  ? 'style="display:none;"' : '' ?> /></a>
					
					<span class="divider"></span>
					
					<a class="control <?= $search['sort']['type'] == 'price' ? 'active' : '' ?>" rel="sort" data-dir="asc" 	href="price">Prijs
					<img src="img/sort_asc<?= core::$cur_page[id] == 4 ? '_green' : '' ?>.png" alt="asc"   rel="sort" <?= $search['sort']['type'] != 'price' ? 'style="display:none;"' : '' ?> /></a>
				
				</span>
				
				<span class="right">
				
					<p>Pagina:</p>
					
					<span class="page_holder">
					<?		
					for($i=$search['page']['start'];$i<=$search['page']['end'];$i++)
						echo '<a class="control '.($i == $search['page']['current'] ? 'active' : '').
							 '" rel="page" href="'.$i.'">'.$i."</a>\n";
					?>
					</span>
				
				</span>
			
			</div>
			
			<div class="left <?= $search['view'] ?>">
			
				<?
				if(core::$cur_page['id'] != 3)
					require __DIR__.'/../includes/search_form.php';
				?>
				
				<div id="block_left">
				
					<h2>Jouw favorieten</h2>
					
					<div class="label darkgray">
					
						<a href="<?= core::page_url('id',10) ?>" class="full"></a>
				
						<div class="left"><div class="corner"></div></div>
					
						<p>Bekijken</p>
					
						<div class="shadow"></div>
						
					</div>
					
					<form class="newsletter ajax" action="ajax/newsletter.php">
					
						<h2>Nieuwsbrief</h2>
							
						<input type="text" name="name"  value="Je naam"			class="clickclear" />
						<input type="text" name="email" value="Je e-mailadres"	class="clickclear" />
						
						<div class="label darkgray">
						
							<input type="submit" class="full" value="" />
				
							<div class="left"><div class="corner"></div></div>
						
							<p>Verstuur</p>
						
							<div class="shadow"></div>
							
						</div>
					
					</form>
					
					<p>
					Bel ons voor meer informatie of maak een afspraak!<br /> <br />
					Vragen? Wij bellen jou!
					</p>
					
					<form class="ajax" action="ajax/callback.php">
							
						<input type="text" name="name"  value="Je naam"				class="clickclear" />
						<input type="text" name="phone" value="Je telefoonnummer"	class="clickclear" />
						
						<div class="label darkgray">
						
							<input type="submit" value="" />
				
							<div class="left"><div class="corner"></div></div>
						
							<p>Verstuur</p>
						
							<div class="shadow"></div>
							
						</div>
					
					</form>
					
				</div>
			
			</div>
			
			<div id="cars_holder">
			<?
			require 'ajax/cars.php';
			?>
			</div>
			
			<div class="options bottom">
				
				<span class="right">
				
					<a href="#" class="totop">Terug naar boven</a>
					
					<span class="divider"></span>
					
					<p>Pagina:</p>
					
					<span class="page_holder">
					<?		
					for($i=$search['page']['start'];$i<=$search['page']['end'];$i++)
						echo '<a class="control totop '.($i == $search['page']['current'] ? 'active' : '').
							 '" rel="page" href="'.$i.'">'.$i."</a>\n";
					?>
					</span>
				
				</span>
			
			</div>
		</div>
	</div>
</div>