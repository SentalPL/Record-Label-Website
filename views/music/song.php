<div class="row song">
	<div class="col-md-12">
	<?php
		$song = $viewmodel[0];
		
		$page_title = $song['artist'].' - '.$song['title'];
		$page_description = 'Przesłuchaj utwór "'.$song['title'].'" w naszej bibliotece. Spodobał Ci się utwór
									artysty '.$song['artist'].'? Pobierz go za darmo na komputer lub telefon.';
		$page_keywords = $song['artist'].', '.$song['title'].', odsłuch, download, pobierz, Masarnia Records,
								  za darmo, free, muzyka do pobrania';
		
		echo '<h2>'.$song['title'];
		if ($song['featuring']){
			echo ' (feat. '.$song['featuring'].')';
		}
		if ($song['producer']){
			echo ' (prod. '.$song['producer'].')';
		}
		echo '</h2>';
		
		if ($song['id_album'] != 0){
			echo '<h3><a href="'.ROOT_URL.'music/album/'.$song['id_album'].'">'.$song['album'].'
					</a></h3>';
		}else{
			echo '<h3><a href="'.ROOT_URL.'music/artist/'.$song['id_artist'].'">'.$song['artist'].'
					</a></h3>';
		}
		$date = date_format(date_create($song['date_creation']), 'Y');
		echo '<small>'.$date.'</small><br>';
	?>
		<audio src="<?php echo ROOT_URL.'assets/download/';if($song['id_album'] != 0){echo $song['id_album'].'/';}echo $song['artist'].' - '.$song['title'];if ($song['featuring']){ echo ' (feat. '.$song['featuring'].')';}if ($song['producer']){ echo ' (prod. '.$song['producer'].')';} echo '.mp3';?>" controls autoplay></audio>
		<?php 
		/*
		$file = iconv('UTF-8','windows-1250', $song['artist'].' - '.$song['title'].'.mp3');
		unlink($_SERVER['DOCUMENT_ROOT'].'/assets/download/'.$file);
		*/?>
		
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="file" value="<?php if($song['id_album'] != 0){echo $song['id_album'].'/';} echo $song['artist'].' - '.
				$song['title']; if ($song['featuring']){ echo ' (feat. '.$song['featuring'].')';}if ($song['producer']){ echo ' (prod. '.$song['producer'].')';} echo '.mp3';?>">		
			<input type="submit" class="btn btn-primary" 
					name="download" value="Pobierz utwór">
		</form>
		<small class="fileInfo">Rozmiar: <?php echo $song['size'];?></small>
		<?php 
			if ($song['downloadCount'] > 0){
				echo '<small>Ilość pobrań: '.$song['downloadCount'].'</small>';
			}
			if ($song['youtube']){
				echo '<div class="YouTube embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="'.$song['youtube'].'" allowfullscreen></iframe>
						</div>';
			}
		?>
		
	</div>
</div>