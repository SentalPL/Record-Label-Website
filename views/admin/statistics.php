<?php $page_title = 'Statystyki';?>
<div class="row admin statistics">
	<div class="col-md-12">
		<?php
			if (empty($_POST)):
		?>
		<h2>Statystyki witryny:</h2>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="submit" value="Utwory" name="songs" class="btn btn-primary"><br>
			<input type="submit" value="Artykuły" name="articles" class="btn btn-primary"><br>
			<input type="submit" value="Albumy" name="albums" class="btn btn-primary"><br>
			<input type="submit" value="Bity" name="beats" class="btn btn-primary"><br>
		</form>
	</div>
</div>
		<?php
			elseif (isset($_POST['songs'])):
				$TYPE = 'songs';
				$count = $viewmodel[0];
				$all_downloads = $viewmodel[1];
				$last_downloads = $viewmodel[2];
				echo '<div class="row admin statistics">
							<div class="col-md-12">
								<h2>Statystyki utworów:</h2>
								<span>Nagrane utwory: <b>'.$count.'</b></span>
							</div>
						</div>';
						echo '<div class="row admin statistics">
								<div class="col-md-6">
									<h3>Najwięcej pobrań ogółem:</h3>';
						if ($all_downloads == FALSE){
							echo '<p>Żaden utwór nie został jeszcze pobrany.</p></div>';
						}else{
							foreach ($all_downloads as $song){
								echo '<h4>'.$song['artist'].' - '.$song['title'].'</h4>
										Ilość pobrań: <b>'.$song['downloadCount'].'</b><br>';
							}echo '</div>';
						}
						echo '<div class="col-md-6">
									<h3>Pobrania najnowszych utworów:</h3>';
						if ($last_downloads == FALSE){
							echo '<p>Żaden z utworów opublikowanych w przeciągu 30 dni nie został jeszcze pobrany.</p></div>';
						}else{
							foreach ($last_downloads as $song){
								echo '<h4>'.$song['artist'].' - '.$song['title'].'</h4>
										Ilość pobrań: <b>'.$song['downloadCount'].'</b><br>';
							}echo '</div>';
						}
						
						
			elseif (isset($_POST['articles'])):
				$TYPE = 'articles';
				$count = $viewmodel[0];
				$all_views = $viewmodel[1];
				$last_views = $viewmodel[2];
				echo '<div class="row admin statistics">
							<div class="col-md-12">
								<h2>Statystyki artykułów:</h2>
								<span>Ilość artykułów: '.$count.'</span><br>
							</div>
						</div>';						
						echo '<div class="row admin statistics">
								<div class="col-md-6">
									<h3>Najwięcej wyświetleń ogółem:</h3>';
						if ($all_views == FALSE){
							echo '<p>Żaden artykuł nie został jeszcze przeczytany.</p></div>';
						}else{
							foreach ($all_views as $article){
								echo $article['title'].'<br>'.
										$article['date_creation'].'<br>
										Wyświetlenia: <b>'.$article['views'].'</b><br>';
							}echo '</div>';
						}
						echo '<div class="col-md-6">
									<h3>Najnowsze artykuły:</h3>';
						if ($last_views == FALSE){
							echo '<p>Żaden z najnowszych artykułów nie został jeszcze przeczytany.</p></div>';
						}else{
							foreach ($last_views as $article){
								echo $article['title'].'<br>
										Wyświetlenia: <b>'.$article['views'].'</b><br>';
							}echo '</div>';
						}
						
			elseif (isset($_POST['albums'])):
				$TYPE = 'albums';
				$count = $viewmodel[0];
				$albums_downloads = $viewmodel[1];
				$songs_downloads = $viewmodel[2];
				echo '<div class="row admin statistics">
							<div class="col-md-12">
								<h2>Statystyki albumów:</h2>
								<span>Zrealizowane albumy: '.$count.'</span><br>
							</div>
						</div>						
						<div class="row admin statistics">
							<div class="col-md-6">
							<h3>Pobrane albumy:</h3>';
				foreach ($albums_downloads as $album){
					echo '<h3>'.$album['title'].'</h3>
							Ilość pobrań: <b>'.$album['downloadCount'].'</b><br>';
				}		
				echo '</div>
						<div class="col-md-6">
							<h3>Pobrane utwory:</h3>';
				foreach ($songs_downloads as $album){
					echo '<h3>'.$album['title'].'</h3>
							Ilość pobranych utworów: <b>'.$album['downloads'].'</b><br>';
				}echo '</div></div>';
			
			elseif (isset($_POST['beats'])):
				$TYPE = 'beats';
				$count = $viewmodel[0];
				$beats = $viewmodel[1];
				$price = 0;
				if ($beats != FALSE){
					foreach ($beats as $beat){
						$price = $price + $beat['price'];
					}
				}
				echo '<div class="row admin statistics">
							<div class="col-md-12">
								<h2>Statystyki podkładów:</h2>
								<span>Ilość podkładów: '.$count.'</span><br>
								<span>Łączny koszt: '.$price.' zł</span><br>
							</div>
						</div>';
			endif;
			
			if (!empty($_POST) && !isset($_POST['albums'])):?>
			<div class="row admin statistics search">
				<div class="col-md-12">
				<h2>Szukaj:</h2>
				<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
					<input type="hidden" name="<?php echo $TYPE;?>">
					<input type="hidden" name="token" value="<?php echo md5(rand);?>">
					<input type="text" name="search_phrase" value="<?php if (isset($_SESSION['search_phrase'])){echo $_SESSION['search_phrase'];unset($_SESSION['search_phrase']);}?>">
					<input type="submit" value="Szukaj">
				</form>
				<?php
				if (isset($_POST['search_phrase'])){
					$search_result = $viewmodel[3];
					if ($search_result == FALSE){
						echo '<p>Brak wyników wyszukiwania.</p>';
					}else{
						foreach ($search_result as $row){
							switch ($TYPE){
								case 'songs':
									echo '<h4>'.$row['artist'].' - '.$row['title'].'</h4>
											<span>'.$row['date_creation'].'</span><br>
											<span>Ilość pobrań: <b>'.$row['downloadCount'].'</b></span><br>';
									break;
								case 'articles':
									echo '<h4>'.$row['title'].'</h4>
											<span>'.$row['date_creation'].'</span><br>
											<span>Ilość wyświetleń: <b>'.$row['views'].'</b></span><br>';
									break;
								case 'albums':
									echo '<h4>'.$row['title'].'</h4>
											<span>'.$row['date_creation'].'</span><br>
											<span>Ilość pobrań: <b>'.$row['downloadCount'].'</b></span><br>';
									break;
								case 'beats':
									echo '<h4>'.$row['name'].'  prod.'.$row['producer'].'</h4>
											<span>'.$row['date_creation'].'</span><br>
											<span>Koszt: <b>'.$row['price'].'</b></span><br>';
									break;
							}
						}
					}
				}
				?>
				</div>
			</div>
			<?php endif;?>