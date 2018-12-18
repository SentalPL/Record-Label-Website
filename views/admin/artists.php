<?php $page_title = 'Artyści';?>
<div class="row admin artists">
	<div class="col-md-12">
		<?php if (!isset($_SESSION['r_id'])): ?>
			<h2>Nowy Artysta:</h2>
			<p>Dodanie nowego artysty jest równoznaczne z utworzeniem nowego konta, z którego będzie mógł on korzystać.
				  Wymagany jest do tego e-mail, na który artysta lub grupa artystów otrzyma tymczasowe hasło pozwalające się zalogować.</p>
			<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
				Pseudonim / Nazwa grupy: <input type="text" name="name" value="<?php if (isset($_SESSION['r_name'])){echo $_SESSION['r_name'];unset($_SESSION['r_name']);}?>"><br>
				Opis: <textarea name="description"><?php if (isset($_SESSION['r_description'])){echo $_SESSION['r_description'];unset($_SESSION['r_description']);}?></textarea><br>
				E-mail: <input type="text" name="email" value="<?php if (isset($_SESSION['r_email'])){echo $_SESSION['r_email'];unset($_SESSION['r_email']);}?>"><br>
				<input type="submit" name="add" class="btn btn-success" value="Dodaj artystę">
			</form>
		<?php else: ?>
			<h2>Edytuj dane:</h2>
			<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
				Pseudonim / Nazwa grupy: <input type="text" name="name" value="<?php if (isset($_SESSION['r_name'])){echo $_SESSION['r_name'];unset($_SESSION['r_name']);}?>"><br>
				Opis: <textarea name="description"><?php if (isset($_SESSION['r_description'])){echo $_SESSION['r_description'];unset($_SESSION['r_description']);}?></textarea><br>
				E-mail: <input type="text" name="email" value="<?php if (isset($_SESSION['r_email'])){echo $_SESSION['r_email'];unset($_SESSION['r_email']);}?>"><br>
				<input type="submit" name="edit2" class="btn btn-success" value="Zmień dane">
			</form>
		<?php unset ($_SESSION['r_id']);endif;
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
<div class="row admin artist">
	<div class="col-md-12">
		<?php
			$artists = $viewmodel[0];
			
			foreach ($artists as $artist){
				echo '<h3>'.$artist['name'].'</h3>
						<p>'.$artist['description'].'</p>
						<b>E-mail: </b>'.$artist['email'].'<br>
						<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
							<input type="hidden" name="edit" value="'.$artist['id'].'">
							<input type="submit" class="btn btn-primary" value="Zmień dane">
						</form>
						<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
							<input type="hidden" name="delete" value="'.$artist['id'].'">
							<input type="submit" class="btn btn-danger" value="Usuń artystę i historię muzyczną" onclick="return confirm(\'Czy jesteś pewny? Usunięcie artysty to najpoważniejszy krok dla całej bazy danych. Wraz z informacją o artyście usunięte zostaną wszystkie jego utwory i albumy. Kontynuuj, jeśli masz naprawdę dobry powód.\')">
						</form>';
			}
		?>
	</div>
</div>