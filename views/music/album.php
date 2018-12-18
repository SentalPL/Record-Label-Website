<div class="row album">
	<div class="col-md-12 mainInfo">
	<?php
		$today_date = date('Y-m-d H:i:s');
		$album = $viewmodel[0];
		
		$page_title = $album['title'];
		$page_description = $album['description'];
		$page_keywords = $album['title'].', '.$album['artist'].', Masarnia Records, rap, hip-hop,
								  album, odsłuch, przesłuchaj, free download, pobierz';
		
		if ($album['date_creation'] < $today_date){
			$published = TRUE;
		}
		
		$date = date_create($album['date_creation']);
		$date = date_format($date, 'd.m.Y');
		
		echo '<h2>'.$album['title'].'</h2>	
				<h3><a href="'.ROOT_URL.'music/artist/'.$album['id_artist'].'">'.$album['artist'].'</a></h3>';
		if (isset($published)){
			echo '<img src="'.ROOT_URL.'assets/img/'.$album['title'].'.png">
				<p><b>Data premiery:</b> '.$date.'</p>';
		}else{
			echo '<small>Album nie miał jeszcze swojej premiery.</small><br>';
		}
		echo '<p>'.$album['description'].'</p>';
	?>
	</div>
</div>
<?php
	if (isset($published)):
?>
<div class="row album">
	<div class="col-md-12 fileInfo">
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="file" value="<?php echo $album['artist'].' - '.
				$album['title'].' ('.date('Y', strtotime($date)).').rar';?>">		
			<input type="submit" class="btn btn-primary" 
					name="download" value="Pobierz album">
		</form>
		
		<p>Rozmiar: <?php echo $album['size'];?><br>
		<?php 
			if ($album['downloadCount'] > 0){
				echo 'Ilość pobrań: '.$album['downloadCount'].'<br>';
			}
		?></p>
	</div>
</div>
<?php
	endif;
?>
<div class="row album">
	<div class="col md-12 tracklisth3">
		<?php 
			if (isset($published)){
				echo '<h3>Tracklista:</h3>';
			}else{
				echo '<h3>Opublikowane utwory:</h3>';
			}
		?>
	</div>
</div>
	<?php
		$songs = $viewmodel[1];
		$i = 0;
		foreach ($songs as $song){
			$i++;
			echo '<div class="row album">
					<div class="col-md-12 tracklist">
					<h4>'.$i.'. '.$song['title'];
			if ($song['featuring']){
				echo ' (feat. '.$song['featuring'].')';
			}
			if ($song['producer']){
				echo ' (prod. '.$song['producer'].')';
			}
			echo '</h4>';
			echo '<audio class="audio" src="'.ROOT_URL.'assets/download/';if($song['id_album'] != 0){echo $song['id_album'].'/';}echo $song['artist'].' - '.$song['title'];if ($song['featuring']){ echo ' (feat. '.$song['featuring'].')';}if ($song['producer']){ echo ' (prod. '.$song['producer'].')';} echo '.mp3" controls></audio>';
			echo '</div>
					</div>';
		}
	?>