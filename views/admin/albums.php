<?php $page_title = 'Albumy';?>
<div class="row admin albums">
	<div class="col-md-12">
	<?php if (!isset($_SESSION['r_id'])):?>
	<h2>Dodaj album:</h2>
	<p>W tym panelu możesz dodawać albumy i zmieniać podstawowe dane z nimi związane. By opublikować album
		  wymagane jest dołączenie pliku .rar. Gdy utworzysz album, możesz dodać
		  do niego utwory tutaj lub w zakładce Utwory, pamiętając o ich właściwej kolejności.</p>
	<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
		<input type="hidden" name="add" value="NULL">
		<input type="hidden" name="token" value="<?php echo md5(rand());?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
		Artysta: <select name="id_artist">
				<?php
					$artists = $viewmodel[1];
					foreach ($artists as $artist){
						echo '<option value="'.$artist['id'].'" ';
						if (isset($_SESSION['r_id_artist'])){
							if ($_SESSION['r_id_artist'] == $artist['id']){
								echo 'selected';
								unset($_SESSION['r_id_artist']);
							}
						}
						echo '>'.$artist['name'].'</option>';
					}
				?></select><br>
		Tytuł albumu: <input type="text" name="title" value="<?php if (isset($_SESSION['r_title'])){echo $_SESSION['r_title']; unset($_SESSION['r_title']);}?>"><br>
		Data premiery: <input type="text" name="date_creation" value="<?php if (isset($_SESSION['r_date_creation'])){echo $_SESSION['r_date_creation']; unset($_SESSION['r_date_creation']);}?>"><br>
		Opis: <textarea name="description"><?php if (isset($_SESSION['r_description'])){echo $_SESSION['r_description']; unset($_SESSION['r_description']);}?></textarea><br>
		Plik .rar: <input type="file" name="file"><br>
		<input type="submit" class="btn btn-primary" value="Dodaj album"><br>
	</form>
	<?php endif;?>
	<?php if (isset($_SESSION['r_id'])):?>
	<h2>Zmień dane o albumie:</h2>
	<p>W tym panelu możesz dodawać albumy i zmieniać podstawowe dane z nimi związane. By opublikować album
		  wymagane jest dołączenie pliku .rar. Gdy utworzysz album, możesz dodać
		  do niego utwory tutaj lub w zakładce Utwory, pamiętając o ich właściwej kolejności.</p>
	<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
		<input type="hidden" name="edit2" value="<?php echo $_SESSION['r_id'];?>">
		<input type="hidden" name="token" value="<?php echo md5(rand());?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
		Artysta: <select name="id_artist">
				<?php
					$artists = $viewmodel[1];
					foreach ($artists as $artist){
						echo '<option value="'.$artist['id'].'" ';
						if (isset($_SESSION['r_id_artist'])){
							if ($_SESSION['r_id_artist'] == $artist['id']){
								echo 'selected';
								unset($_SESSION['r_id_artist']);
							}
						}
						echo '>'.$artist['name'].'</option>';
					}
				?></select><br>
		Tytuł albumu: <input type="text" name="title" value="<?php if (isset($_SESSION['r_title'])){echo $_SESSION['r_title']; unset($_SESSION['r_title']);}?>"><br>
		Data premiery: <input type="text" name="date_creation" value="<?php if (isset($_SESSION['r_date_creation'])){echo $_SESSION['r_date_creation']; unset($_SESSION['r_date_creation']);}?>"><br>
		Opis: <textarea name="description"><?php if (isset($_SESSION['r_description'])){echo $_SESSION['r_description']; unset($_SESSION['r_description']);}?></textarea><br>
		Plik .rar: <input type="file" name="file"><br>
		<input type="submit" class="btn btn-primary" value="Zmień dane"><br>
	</form><br>
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
</div>
<div class="row admin album">
	<div class="col-md-12">
	<?php
	
	if (!isset($_SESSION['r_id'])){
		$albums = $viewmodel[2];
		
		foreach ($albums as $album){
			$date = date_create($album['date_creation']);
			$date = date_format($date, 'd.m.Y');
			echo '<h3>'.$album['title'].'</h3>
					<p>'.$date.'</p>
					<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="edit" value="'.$album['id'].'">
						<input type="submit" class="btn btn-success" value="Edytuj dane">
					</form>
					<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="delete" value="'.$album['id'].'">
						<input type="submit" class="btn btn-danger" value="Usuń album" onclick="return confirm(\'Czy jesteś pewny? Usunięcie albumu to poważne posunięcie. Wszystkie przypisane do niego utwory zostaną zachowane jako nieprzypisane. Wszystkie dane o albumie znikną. Kontynuuj, jeśli masz naprawdę dobry powód.\')">
					</form>';
		}
	}else{
		$songs = $viewmodel[0];
		
		$i = 1;
		foreach ($songs as $song){
			$token = md5(rand());
			echo '<form enctype="multipart/form-data" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="edit_song" value="'.$song['id'].'">
						<input type="hidden" name="artist" value="'.$_SESSION['artist'].'">
						<input type="hidden" name="album" value="'.$_SESSION['album'].'">
						<input type="hidden" name="date_album" value="'.$_SESSION['date_album'].'">
						Numer: <input type="text" name="number" value="'.$_SESSION['n_'.$i]['number'].'"><br>
						Tytuł: <input type="text" name="title" value="'.$_SESSION['n_'.$i]['title'].'"><br>
						Featuring: <input type="text" name="featuring" value="'.$_SESSION['n_'.$i]['featuring'].'"><br>
						Producent: <input type="text" name="producer" value="'.$_SESSION['n_'.$i]['producer'].'"><br>
						YouTube: <input type="text" name="youtube" value="'.$_SESSION['n_'.$i]['youtube'].'"><br>
						<input type="file" name="file"><br>
						<input type="submit" class="btn btn-primary" value="Zmień dane o utworze" onclick="return confirm(\'Dla poprawnego działania konieczne będzie ponowne podanie ścieźki do pliku. Kontynuuj, jeśli na pewno go posiadasz.\')">
					</form>';
			$i++;
		}
		
		echo '<h3><b>Dodaj utwór:</b></h3>
				<form enctype="multipart/form-data" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="add_song">
						<input type="hidden" name="token" value="';echo md5(rand());echo '">
						<input type="hidden" name="artist" value="'.$_SESSION['artist'].'">
						<input type="hidden" name="album" value="'.$_SESSION['album'].'">
						<input type="hidden" name="date_album" value="'.$_SESSION['date_album'].'">
						Numer: <input type="text" name="number" value="'.$i.'"><br>
						Tytuł: <input type="text" name="songtitle" value="';if (isset($_SESSION['r_songtitle'])){echo $_SESSION['r_songtitle'];unset($_SESSION['r_songtitle']);}echo '"><br>
						Featuring: <input type="text" name="featuring" value="';if (isset($_SESSION['r_featuring'])){echo $_SESSION['r_featuring'];unset($_SESSION['r_featuring']);}echo '"><br>
						Producent: <input type="text" name="producer" value="';if (isset($_SESSION['r_producer'])){echo $_SESSION['r_producer'];unset($_SESSION['r_producer']);}echo '"><br>
						YouTube: <input type="text" name="youtube" value="';if (isset($_SESSION['r_youtube'])){echo $_SESSION['r_youtube'];unset($_SESSION['r_youtube']);}echo '"><br>
						<input type="file" name="file"><br>
						<input type="submit" class="btn btn-primary" value="Dodaj utwór">
					</form>';
	}
	?>
	</div>
</div>