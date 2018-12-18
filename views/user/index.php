<?php $page_title = 'Panel użytkownika'; ?>
<div class="row user">
	<div class="col-md-12">
		<?php $user = $viewmodel[0]; ?>
		<h2>Witaj <?php echo $user['name'];?>!</h2>
		<p>Twoje indywidualne konto jako członka Masarni Records, pozwala Ci na magazynowanie
			niezrealizowanych projektów. Dzięki temu masz pewność, że Twoje bity i teksty nie przepadną oraz, że
			będziesz mieć do nich dostęp wszędzie, gdzie tylko będzie internet. Nie musisz już martwić się o fizyczne przenoszenie
			plików do studia, jeśli znajdują się one na Twoim koncie.</p>
		<p>Baza Bitów to magazyn podkładów, do których Masarnia Records ma pełne prawa wykorzystania. Z tymi
			produkcjami można stworzyć w pełni legalne i zarobkowe utwory. Kolekcja autorskich podkładów będzie stale się poszerzać,
			a o pozyskaniu nowych bitów zostaniesz zawsze poinformowany w tym panelu.</p>
		<?php $notifications = $viewmodel[1]; 
			if (!empty($notifications)){
				foreach ($notifications as $info){
					echo '<p class="notification">'.$info['message'].'</p>';
				}
			}
		?>
	</div>
</div>
<div class="row user panel">
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'user/projects';?>">
			<h2><i class="icon-projects"></i><br>Projekty</h2>
		</a>
	</div>
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'user/beats';?>">
			<h2><i class="icon-headphones"></i><br>Baza Bitów</h2>
		</a>
	</div>
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'user/statistics';?>">
			<h2><i class="icon-statistics"></i><br>Twoje statystyki</h2>
		</a>
	</div>
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'user/settings';?>">
			<h2><i class="icon-settings"></i><br>Ustawienia</h2>
		</a>
	</div>
</div>
<div class="row user">
	<div class="col-md-12">
		<h3 class="logout"><a href="<?php echo ROOT_URL.'user/logout';?>">Wyloguj z trybu użytkownika</a></h3>
	</div>
</div>