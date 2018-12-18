<?php $page_title = 'Statystyki utworów'; ?>
<div class="row user statistics">
	<div class="col-md-6">
		<h2>Utwory:</h2>
		<?php $songs = $viewmodel[0];
		if (empty($songs)){
			echo '<p>Żaden utwór nie znajduje się jeszcze w bazie.</p>';
		}else{
			foreach ($songs as $song){
				echo '<h4>'.$song['title'].'</h4>
						<b>Liczba pobrań:</b> '.$song['downloadCount'];
			}
		}
		?>		
	</div>
	<div class="col-md-6">
		<h2>Albumy:</h2>
		<?php $albums = $viewmodel[1];
		if (empty($albums)){
			echo '<p>Nie zrealizowano jeszcze żadnego albumu.</p>';
		}
		foreach ($albums as $album){
			$date = date_create($album['date_creation']);
			$date = date_format($date, 'Y');
			echo '<h4>'.$album['title'].' ('.$date.')</h4>
					<b>Liczba pobrań:</b> '.$album['downloadCount'];
		}
		?>
	</div>
</div>
<div class="row user statistics">
	<div class="col-md-12 search">
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<h2>Wyszukaj:</h2>
			<input type="text" name="phrase"><br>
			<input type="submit" class="btn btn-primary" value="Szukaj"><br>
		</form>
		<?php 
		if (isset($_POST['phrase'])){
			$results = $viewmodel[2];
			if (empty($results)){
				echo '<p>Brak wyników wyszukiwania.</p>';
			}else{
				foreach ($results as $song){
					$date = date_create($song['date_creation']);
					$date = date_format($date, 'd.m.Y');
					echo '<h4>'.$song['title'].'</h4>
							<b>Liczba pobrań:</b> '.$song['downloadCount'].'<br>
							<b>Data publikacji:</b> '.$date;
				}
			}
		}
		?>
	</div>
</div>