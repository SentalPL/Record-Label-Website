<?php $page_title = 'Ustawienia konta'; ?>
<div class="row user settings">
	<div class="col-md-12">
		<h2>Ustawienia konta</h2>
		<p>W tej zakładce możesz zmienić podstawowe dane, takie jak e-mail i hasło.</p>
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
		<h3>Zmień hasło:</h3>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			Stare hasło: <input type="password" name="old_password"><br>
			Nowe hasło: <input type="password" name="new_password"><br>
			<input type="submit" name="change_password" class="btn btn-success" value="Zatwierdź">
		</form>
		
		<h3>Zmień e-mail:</h3>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			Nowy e-mail: <input type="text" name="email"><br>
			Zatwierdź hasłem: <input type="password" name="password"><br>
			<input type="submit" name="change_email" class="btn btn-success" value="Zatwierdź">
		</form>
	</div>
</div>