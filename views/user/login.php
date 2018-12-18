<?php $page_title = 'Logowanie'; ?>
<div class="row user login">
	<div class="col-md-12">
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			E-mail: <input type="text" name="email"><br>
			Hasło: <input type="password" name="password"><br>
			<input type="submit" class="btn btn-primary" value="Zaloguj">
		</form>
		<?php
			if (isset($_SESSION['e_info'])){
				echo $_SESSION['e_info'];
				unset ($_SESSION['e_info']);
			}
		?>
	</div>
</div>