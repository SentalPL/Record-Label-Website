<?php $page_title = 'Utwory';?>
<div class="row admin songs">
	<div class="col-md-6">
		<?php if (!isset ($_SESSION['r_id'])): ?>
		<h2>Dodaj utwór:</h2>
		<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="add" value="NULL">
			<input type="hidden" name="token" value="<?php echo md5(rand());?>">
			<input type="hidden" name="MAX_FILE_SIZE" value="11400000" />
			Artysta: <select name="artist">
				<?php
					$artists = $viewmodel[1];
					foreach ($artists as $artist){
						echo '<option value="'.$artist['id'].'" ';
						if (isset($_SESSION['r_artist'])){
							if ($_SESSION['r_artist'] == $artist['id']){
								echo 'selected';
								unset($_SESSION['r_artist']);
							}
						}
						echo '>'.$artist['name'].'</option>';
					}
				?>
			</select><br>
			Tytuł: <input type="text" name="title" value="<?php if (isset($_SESSION['r_title'])){echo $_SESSION['r_title']; unset($_SESSION['r_title']);}?>"><br>
			Album: <select name="album">
					<option value=""></option>
				<?php
					$albums = $viewmodel[2];
					foreach ($albums as $album){
						echo '<option value="'.$album['id'].'" ';
						if (isset($_SESSION['r_album'])){
							if ($_SESSION['r_album'] == $album['id']){
								echo 'selected';
								unset($_SESSION['r_album']);
							}
						}
						echo '>'.$album['title'].'</option>';
					}
				?>
			</select><br>
			Data premiery: <input type="text" name="date_creation" value="<?php if (isset($_SESSION['r_date_creation'])){echo $_SESSION['r_date_creation']; unset($_SESSION['r_date_creation']);}?>"><br>
			Producent: <input type="text" name="producer" value="<?php if (isset($_SESSION['r_producer'])){echo $_SESSION['r_producer']; unset($_SESSION['r_producer']);}?>"><br>
			Feat.: <input type="text" name="featuring" value="<?php if (isset($_SESSION['r_featuring'])){echo $_SESSION['r_featuring']; unset($_SESSION['r_featuring']);}?>"><br>
			<br>
			YouTube: <input type="text" name="youtube" placeholder="/embed/" value="<?php if (isset($_SESSION['r_youtube'])){echo $_SESSION['r_youtube']; unset($_SESSION['r_youtube']);}?>"><br>
			<input type="file" name="file"><br>
			<input type="submit" class="btn btn-primary" value="Dodaj utwór"><br>
		</form>
		<?php endif; ?>
		
		<?php if (isset ($_SESSION['r_id'])): ?>
		<h2>Edytuj informacje:</h2>
		<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="edit2" value="<?php if (isset($_SESSION['r_id'])){echo $_SESSION['r_id']; unset($_SESSION['r_id']);}?>">
			<input type="hidden" name="token" value="<?php echo md5(rand());?>">
			<input type="hidden" name="MAX_FILE_SIZE" value="11400000" />
			Artysta: <select name="artist">
				<?php
					$artists = $viewmodel[1];
					foreach ($artists as $artist){
						echo '<option value="'.$artist['id'].'" ';
						if (isset($_SESSION['r_artist'])){
							if ($_SESSION['r_artist'] == $artist['id']){
								echo 'selected';
								unset($_SESSION['r_artist']);
							}
						}
						echo '>'.$artist['name'].'</option>';
					}
				?>
			</select><br>
			Tytuł: <input type="text" name="title" value="<?php if (isset($_SESSION['r_title'])){echo $_SESSION['r_title']; unset($_SESSION['r_title']);}?>"><br>
			Album: <select name="album">
					<option></option>
				<?php
					$albums = $viewmodel[2];
					foreach ($albums as $album){
						echo '<option value="'.$album['id'].'" ';
						if (isset($_SESSION['r_album'])){
							if ($_SESSION['r_album'] == $album['id']){
								echo 'selected';
								unset($_SESSION['r_album']);
							}
						}
						echo '>'.$album['title'].'</option>';
					}
				?>
			</select><br>
			Data premiery: <input type="text" name="date_creation" value="<?php if (isset($_SESSION['r_date_creation'])){echo $_SESSION['r_date_creation']; unset($_SESSION['r_date_creation']);}?>"><br>
			Producent: <input type="text" name="producer" value="<?php if (isset($_SESSION['r_producer'])){echo $_SESSION['r_producer']; unset($_SESSION['r_producer']);}?>"><br>
			Feat.: <input type="text" name="featuring" value="<?php if (isset($_SESSION['r_featuring'])){echo $_SESSION['r_featuring']; unset($_SESSION['r_featuring']);}?>"><br>
			<br>
			YouTube: <input type="text" name="youtube" placeholder="/embed/" value="<?php if (isset($_SESSION['r_youtube'])){echo $_SESSION['r_youtube']; unset($_SESSION['r_youtube']);}?>"><br>
			<input type="file" name="file"><br>
			<input type="submit" class="btn btn-primary" value="Zmień dane"><br>
		</form>
		<?php endif;?>
		<?php
		if (isset ($_SESSION['p_info'])){
			echo '<p class="p_info">'.$_SESSION['p_info'].'</p>';
			unset ($_SESSION['p_info']);
		}
		if (isset ($_SESSION['e_info'])){
			echo '<p class="e_info">'.$_SESSION['e_info'].'</p>';
			unset ($_SESSION['e_info']);
		}
		?>
	</div>
	<div class="col-md-6 right">
		<h2>Wyszukaj utwór:</h2>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="search">
			<input type="hidden" name="token" value="<?php echo md5(rand());?>">
			<input type="text" name="search_phrase" value="<?php if (isset ($_SESSION['searchPhrase'])){echo $_SESSION['searchPhrase'];unset($_SESSION['searchPhrase']);}?>"><br>
			<input type="submit" class="btn btn-primary" value="Szukaj"><br>
		</form>
		<?php 
			if (!isset($_POST['search'])){
				echo '<p><b>Ostatnio opublikowany utwór:</b></p>';
			}
			$songs = $viewmodel[0];
			if ($songs == FALSE){
				echo 'Brak wyników.';
			}else{
				foreach ($songs as $song):?>
					<?php $token = md5(rand());?>
					<h3><?php echo $song['title'];
					if ($song['featuring']){
						echo ' (feat. '.$song['featuring'].')';
					}
					if ($song['producer']){
						echo ' (prod. '.$song['producer'].')';
					}?></h3>
					<?php
					if ($song['youtube']){
						echo '<p><a href="'.$song['youtube'].'" target="_blank">'.$song['youtube'].'</a></p>';
					}?>
						<audio src="<?php echo ROOT_URL.'assets/download/';if($song['id_album'] != 0){echo $song['id_album'].'/';}echo $song['artist'].' - '.$song['title'];if ($song['featuring']){ echo ' (feat. '.$song['featuring'].')';}if ($song['producer']){ echo ' (prod. '.$song['producer'].')';} echo '.mp3';?>" controls></audio>
						<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
							<input type="hidden" name="edit" value="<?php echo $song['id'];?>">
							<input type="hidden" name="token" value="<?php echo $token;?>'">
							<input type="submit" class="btn btn-primary" value="Edytuj" onclick="return confirm('Dla poprawnego działania konieczne będzie ponowne podanie ścieźki do pliku. Kontynuuj, jeśli na pewno go posiadasz.')">
						</form>
						<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
							<input type="hidden" name="delete" value="<?php echo $song['id'];?>">
							<input type="hidden" name="token" value="<?php echo $token;?>">
							<input type="submit" class="btn btn-danger" value="Usuń z bazy" onclick="return confirm('Utwór zostanie bezpowrotnie usunięty z witryny.')">
						</form><br><br>
				<?php endforeach;
			}?>		
	</div>
</div>