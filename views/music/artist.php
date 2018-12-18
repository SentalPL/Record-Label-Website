<div class="row artist">
	<?php
		$artist = $viewmodel[0];
		
		$page_title = $artist['name'].' | Masarnia Records';
		$page_description = $artist['description'];
		$page_keywords = $artist['name'].', Masarnia Records, raper, rap, muzyka, artysta, hip-hop';								  
	?>
	<div class="col-md-12 mainInfo">
		<h2><?php echo $artist['name'];?></h2>
		<?php 
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/assets/img/'.$artist['name'].'.png')){
			echo '<img src="'.ROOT_URL.'assets/img/'.$artist['name'].'.png">';
		}
		?>
		<p><?php echo $artist['description'];?></p>
	</div>
</div>
<div class="row artist">
	<div class="col-md-12 ">
		<h3>Dyskografia:</h3>
		<?php
		$albums = $viewmodel[1];
		
		if (count($albums) == 0){
			echo '<p>'.$artist['name'].' nie wypuścił żadnego solowego albumu. W jego kartotece znajduje się
					natomiast wiele luźnych, solidnych numerów.</p></div></div>'; // Zamknięcie div-a
		}else{
			echo '</div></div>'; // Zamknięcie div-a
			foreach ($albums as $album){
				$date = date_create($album['date_creation']);
				$date = date_format($date, 'Y');
				echo '<div class="row artist disc">
							<div class="col-md-12">
								<h4><span>'.$date.'</span><a href="'.ROOT_URL.'music/album/'.$album['id'].'">'.$album['title'].'</a></h4>
							</div>
						</div>';
			}
		}
		?>
<div class="row artist">
	<div class="col-md-12">
		<h3>Popularne utwory:</h3>
<?php
	$songs = $viewmodel[2];
	
	if (count($songs) == 0){
			echo '<p>Obecnie brak utworów tego artysty.</p></div></div>';
	}else{
		echo '</div></div>
				<div class="row artist">
					<div class="col-md-12 popularSongs">';
		foreach ($songs as $song){
				echo '<i class="icon-music"></i><a href="'.ROOT_URL.'music/song/'.$song['id'].'">'.$song['title'].'</a><br>';
		}
		echo '</div></div>';
	}

