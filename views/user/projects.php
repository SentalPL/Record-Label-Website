<?php $page_title = 'Projekty'; ?>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
<?php
		if (isset($_POST['new_project']) || isset($_SESSION['new_project'])|| isset($_SESSION['r_id'])):
			echo '<div class="row user project">
						<div class="col-md-12 form">';
						
			if (isset($_POST['new_project']) || isset($_SESSION['new_project'])){
				echo '<h2>Nowy projekt:</h2>
						<p>Podkład muzyczny może zostać dodany później. Zaleca się zapisanie w jego nazwie autora bitu dla umożliwienia ewentualnego kontaktu.</p>';
			}elseif (isset($_SESSION['r_id'])){
				echo '<h2>Edycja projektu:</h2>';
			}
		?>
		<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="token" value="<?php echo md5(rand());?>">
			<h3>Tytuł utworu:</h3><input type="text" class="title" name="title" value="<?php if (isset($_SESSION['r_title'])){echo $_SESSION['r_title'];unset($_SESSION['r_title']);}?>"><br>
			<h3>Tekst:</h3>
			<textarea name="lirycs" class="lirycs"><?php if (isset($_SESSION['r_lirycs'])){echo $_SESSION['r_lirycs'];unset($_SESSION['r_lirycs']);}?></textarea><br>
			<p>Bit:</p><input type="file" name="file" class="file">
			<?php
				if (isset ($_SESSION['p_info'])){
					echo '<p class="p_info">'.$_SESSION['p_info'].'</p>';
					unset ($_SESSION['p_info']);
				}
				if (isset ($_SESSION['e_info'])){
					echo '<p class="e_info">'.$_SESSION['e_info'].'</p>';
					unset ($_SESSION['e_info']);
				}
				
				if (isset($_SESSION['new_project']) || isset($_POST['new_project'])){
					echo '<input type="submit" name="add" class="btn btn-success" value="Utwórz projekt">';
				}elseif (isset($_SESSION['r_id'])){
					echo '<input type="hidden" name="edit2" value="'.$_SESSION['r_id'].'">
							<input type="submit" class="btn btn-success" value="Zmień dane">';
				}
				unset($_SESSION['r_date_creation']);
				?>
		</form>
		</div>
		</div>
		<?php elseif (isset($_POST['view'])):?>
<div class="row user project">
	<div class="col-md-12">
		<?php
			$project = $viewmodel[1];
			echo '<h2>'.$project['title'].'</h2>
					<audio class="audio" src="'.$project['file'].'" controls></audio>
					<p>'.$project['lirycs'].'</p>';
		?>
	</div>
</div>
		<?php else:?>
<div class="row user project">
	<div class="col-md-12">
		<h2>Nowy projekt:</h2>
		<p>Połącz podkład muzyczny i napisany tekst w jeden projekt, aby zapewnić ochronę przed utratą plików i ułatwić
			do nich dostęp. Dzięki temu wszystko co potrzebne znajdziesz w jednym miejscu, nie będziesz musiał również
			martwić się o przeniesienie plików do studia.</p>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="submit" name="new_project" class="btn btn-success" value="Rozpocznij">
		</form>
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
		<h2>Bieżące projekty:</h2>
		<?php
			$projects = $viewmodel[0];
			
			if ($projects == FALSE){
				echo '<p>Nie utworzono jeszcze żadnego projektu.</p>';
			}else{
				foreach ($projects as $project){
					$token = md5(rand());
					echo '<h3>'.$project['title'].'</h3>
					<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="view" value="'.$project['id'].'">
						<input type="hidden" name="token" value="'.$token.'">
						<input type="submit" class="btn btn-primary" value="Wyświetl"> 
					</form>
					<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="edit" value="'.$project['id'].'">
						<input type="hidden" name="token" value="'.$token.'">
						<input type="submit" class="btn btn-primary" value="Edytuj"> 
					</form>
					<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
						<input type="hidden" name="delete" value="'.$project['id'].'">
						<input type="hidden" name="token" value="'.$token.'">
						<input type="submit" class="btn btn-danger" value="Usuń" onclick="return confirm(\'Projekt zostanie bezpowrotnie usunięty z witryny.\')">
					</form>';
				}
			}
		?>
	</div>
</div>
	<?php endif;?>