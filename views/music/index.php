<?php $page_title = 'Strefa muzyki | Masarnia Records';
		$page_description = 'Przesłuchuj i pobieraj utwory i albumy naszych artystów, dzięki
									kompletnej bibliotece muzycznej naszej wytwórni.';
		 $page_keywords = 'odsłuch, muzyka, rap, hip-hop, free download, pobierz za darmo,
									albumy, CD, utwory, kawałki, przesłuchaj, Masarnia Records';
?>
<div class="row index">
	<div class="col-md-6 top">
		<h2>Najnowsze utwory</h2>
		<?php
			$newSongs = $viewmodel[0];
			
			foreach ($newSongs as $song){
				echo '<tr>
							<td><span class="index tracklistFont"><i class="icon-music"></i><a href="'.ROOT_URL.'music/song/'.$song['id'].'">'.$song['artist'].' - '.$song['title'];if ($song['featuring']){echo ' (ft. '.$song['featuring'].')';}echo '</a></span></td>
						</tr><br>';
			}
		?>
	</div>
	<div class="col-md-6 top">
		<h2>Najczęściej pobierane</h2>
		<?php
			$popularSongs = $viewmodel[1];
			
			foreach ($popularSongs as $song){
				echo '<tr>
							<td><span class="tracklistFont"><i class="icon-music"></i><a href="'.ROOT_URL.'music/song/'.$song['id'].'">'.$song['artist'].' - '.$song['title'];if ($song['featuring']){echo ' (ft. '.$song['featuring'].')';}echo '</a></span></td>
						</tr><br>';
			}
		?>
	</div>
</div>
<div class="row index">
	<div class="col-md-12 albumsh2">
		<h2>Albumy</h2>
	</div>
</div>
<div class="row index">
	<div class="albumsContainer">
	<?php
		$albums = $viewmodel[2];
			
		foreach ($albums as $album){
			
			$date = date_create($album['date_creation']);
			$date = date_format($date, 'Y');
			echo '<div class="col-sm-3 col-md-2 albums">
						<a href="'.ROOT_URL.'music/album/'.$album['id'].'">
						<div class="single">
							<img class="img-fluid" src="'.ROOT_URL.'assets/img/'.$album['title'].'.png">
							<h3>'.$album['title'].'<br>
									('.$date.')</h3>
						</div></a>
					</div>';
		}
	?>
	</div>
</div>
<div class="row index">
	<div class="col-md-12 search">
		<h2>Wyszukiwarka</h2>
	</div>
</div>
<div class="row index">
	<div class="col-md-12 search">
		<form class="search" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<?php $token = md5(rand());?>
			<input type="hidden" name="token" value="<?php echo $token;?>">
			<input type="text" name="searchPhrase" value="<?php if (isset ($_SESSION['r_phrase'])){echo $_SESSION['r_phrase'];unset($_SESSION['r_phrase']);}?>" placeholder="Wpisz dowolną frazę...">
			<br><input type="submit" class="btn btn-primary submit" value="Szukaj">
			<br><span>Znajdź najpopularniejsze utwory</span>
			<div class="artists_search">
			<?php
				foreach ($viewmodel[3] as $artist){
					echo '<input type="submit" class="btn btn-primary" name="artist_name" value="'.$artist['name'].'"><br>';
				}
			?>
			</div>
			</form>
	</div>
</div>
<div class="row index">
	<div class="col-md-12">
		<?php
			$search = $viewmodel[4];
			
			if (isset($_POST['searchPhrase'])){
				if ($search == FALSE){
					echo 'Brak wyników wyszukiwania.';
				}else{
					echo '<table>
								<tr>
									<th></th>
									<th>Gościnnie</th>
									<th>Producent</th>
								</tr>';
					foreach ($search as $search){
						echo '<tr>';
						switch ($search['type']){
							case 'song':
								echo '<td><a href="'.ROOT_URL.'music/song/'.$search['id'].'">'.$search['artist'].' - '.$search['title'].'</a></td>';
								echo '<td>';
									if ($search['featuring']){ echo $search['featuring'];}
								echo '</td>
										<td>';
									if ($search['producer']){ echo $search['producer'];}
								echo '</td>';
								break;
							case 'album':
								$date = date_create($search['date_creation']);
								$date = date_format($date, 'Y');
								echo '<td><a href="'.ROOT_URL.'music/album/'.$search['id'].'">'.$search['title'].' ('.$date.') - '.$search['artist'].'</a></td>';
								break;
							case 'artist':
								echo '<td><a href="'.ROOT_URL.'music/song/'.$search['id'].'">'.$search['artist'].' - '.$search['title'].'</a></td>';
								break;
						}
							echo	'</tr>';
					}
					echo '</table>';
				}
			}
		?>
	</div>
</div>
