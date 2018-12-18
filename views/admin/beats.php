<?php $page_title = 'Podkłady muzyczne';?>
<div class="row admin beats">
	<div class="col-md-12">
		<?php if (!isset ($_SESSION['r_id'])):  ?>
		<h2>Dodaj:</h2>
		<form  enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="add" value="NULL">
			<input type="hidden" name="token" value="<?php echo md5(rand);?>">
			<input type="hidden" name="MAX_FILE_SIZE" value="11400000" />
			Nazwa: <input type="text" name="name" value="<?php if (isset($_SESSION['r_name'])){echo $_SESSION['r_name']; unset($_SESSION['r_name']);}?>"><br>
			Producent: <input type="text" name="producer" value="<?php if (isset($_SESSION['r_producer'])){echo $_SESSION['r_producer']; unset($_SESSION['r_producer']);}?>"><br>
			Koszt: <input type="text" name="price" value="<?php if (isset($_SESSION['r_price'])){echo $_SESSION['r_price']; unset($_SESSION['r_price']);}?>"><br>
			
			<input type="file" name="file"><br>
			<input type="submit" class="btn btn-primary" value="Dodaj do bazy"><br>
		</form>
		<?php endif;
		if (isset ($_SESSION['r_id'])):
		?>
		
		<h2>Edytuj:</h2>
		<form  enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="edit2" value="<?php echo $_SESSION['r_id'];?>">
			<input type="hidden" name="token" value="<?php echo md5(rand());?>">
			<input type="hidden" name="MAX_FILE_SIZE" value="11400000" />
			Nazwa: <input type="text" name="name" value="<?php if (isset($_SESSION['r_name'])){echo $_SESSION['r_name']; unset($_SESSION['r_name']);}?>"><br>
			Producent: <input type="text" name="producer" value="<?php if (isset($_SESSION['r_producer'])){echo $_SESSION['r_producer']; unset($_SESSION['r_producer']);}?>"><br>
			Koszt: <input type="text" name="price" value="<?php if (isset($_SESSION['r_price'])){echo $_SESSION['r_price']; unset($_SESSION['r_price']);}?>"><br>
			
			<input type="file" name="file"><br>
			<input type="submit" class="btn btn-primary" value="Zmień dane"><br>
		</form>
		<?php endif; unset ($_SESSION['r_id'], $_SESSION['r_name'], $_SESSION['r_producer'], $_SESSION['r_price']); ?>
		
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

<div class="row admin beats">
	<div class="col-md-12">
		<h2>Podkłady muzyczne</h2>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="search">
			<input type="hidden" name="token" value="<?php echo md5(rand());?>">
			<input type="text" name="searchPhrase" value="<?php if (isset ($_SESSION['searchPhrase'])){echo $_SESSION['searchPhrase'];unset($_SESSION['searchPhrase']);}?>"><br>
			<input type="submit" class="btn btn-primary" value="Szukaj"><br>
		</form>
	</div>
</div>

<div class="row admin beats">
	<?php
		$beats = $viewmodel[0];
		
		if ($beats == FALSE){
			echo 'Brak wyników.';
		}else{
			foreach ($beats as $beat){
				$token = md5(rand());
				echo '<div class="col-md-12 beat">
							<h3>'.$beat['name'].' prod. '.$beat['producer'].'</h3>
						<audio class="audio" src="'.ROOT_URL.'assets/beats/'.$beat['name'].'  prod. '.$beat['producer'].'.mp3" controls></audio>
						<br><br>
						<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
							<input type="hidden" name="edit" value="'.$beat['id'].'">
							<input type="hidden" name="token" value="'.$token.'">
							<input type="submit" class="btn btn-primary" value="Edytuj" onclick="return confirm(\'Przy edycji danych należy ponownie podać ścieżkę do pliku. Kontynuuj, jeśli na pewno go posiadasz.\')">
						</form>
						<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
							<input type="hidden" name="delete" value="'.$beat['id'].'">
							<input type="hidden" name="token" value="'.$token.'">
							<input type="submit" class="btn btn-danger" value="Usuń z bazy">
						</form>
						</div>';
						
			}
		}
	?>
	
</div>