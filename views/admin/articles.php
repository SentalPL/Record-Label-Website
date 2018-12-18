<?php $page_title = 'Artykuły';?>
<script src="//cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<?php
		if (isset($_POST['new_article']) || isset($_SESSION['new_article'])|| isset($_SESSION['r_id'])):
			echo '<div class="row admin articles">
						<div class="col-md-12 form">';
						
			if (isset($_POST['new_article']) || isset($_SESSION['new_article'])){
				echo '<h2>Nowy artykuł:</h2>';
			}elseif (isset($_SESSION['r_id'])){
				echo '<h2>Edytuj artykuł:</h2>';
			}
		?>
		<form enctype="multipart/form-data" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<h3>Tytuł:</h3><input type="text" name="title" value="<?php if (isset($_SESSION['r_title'])){echo $_SESSION['r_title'];unset($_SESSION['r_title']);}?>"><br>
			<h3>Opis:</h3><textarea name="description" class="description"><?php if (isset($_SESSION['r_description'])){echo $_SESSION['r_description'];unset($_SESSION['r_description']);}?></textarea><br>
			<h3>Tagi:</h3><input type="text" name="tags" placeholder="Umieść od 8 do 15 słów kluczowych." value="<?php if (isset($_SESSION['r_tags'])){echo $_SESSION['r_tags'];unset($_SESSION['r_tags']);}?>"><br>
			<h3>Treść artykułu:</h3>
			<textarea name="content" id="editor1" class="content"><?php if (isset($_SESSION['r_content'])){echo $_SESSION['r_content'];unset($_SESSION['r_content']);}?></textarea><br>
			<script>
			CKEDITOR.replace( 'editor1' );
		</script>
			<p>Obraz główny:</p><input type="file" name="file" class="file">
			<?php
				if (isset ($_SESSION['p_info'])){
					echo '<p class="p_info">'.$_SESSION['p_info'].'</p>';
					unset ($_SESSION['p_info']);
				}
				if (isset ($_SESSION['e_info'])){
					echo '<p class="e_info">'.$_SESSION['e_info'].'</p>';
					unset ($_SESSION['e_info']);
				}
				
				if (isset($_SESSION['new_article']) || isset($_POST['new_article'])){
					echo '<input type="submit" name="add" class="btn btn-success" value="Dodaj artykuł">';
				}elseif (isset($_SESSION['r_id'])){
					echo '<input type="hidden" name="edit2" value="'.$_SESSION['r_id'].'">
							<input type="submit" class="btn btn-success" value="Zmień dane">';
				}
				unset($_SESSION['r_date_creation']);
				?>
		</form>
		</div>
		</div>
		<?php else:?>
<div class="row admin articles">
	<div class="col-md-12">
		<h2>Nowy artykuł:</h2>
		<p>Informuj o planach i wydarzeniach, aby budować pozycję strony w internecie i nawiązywać relacje ze słuchaczami. Stałe publikowanie informacji to doskonała forma pozycjonowania strony i przekazania
			ludziom wielu informacji, których nie sposób przekazać innymi źródłami.</p>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="submit" name="new_article" class="btn btn-success" value="Rozpocznij">
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
	</div>
</div>
<div class="row admin articles">
	<div class="col-md-12 search">
		<h2>Wyszukaj:</h2><br><br><br>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="hidden" name="token" value="<?php echo md5(rand());?>">
			<input type="text" name="search_phrase" value="<?php if (isset ($_SESSION['search_phrase'])){echo $_SESSION['search_phrase'];unset($_SESSION['search_phrase']);}?>"><br>
			<input type="submit" name="search" class="btn btn-primary" value="Szukaj"><br>
		</form>
		<br><br>
		<?php 
			if (!isset($_POST['search'])){
				echo '<h3>Najnowsze artykuły:</h3><br>';
			}
			$articles = $viewmodel[0];
			if ($articles == FALSE){
				echo '<small>Brak artykułów.</small></div></div>'; // ZAMKNIĘCIE DIV-A
			}else{
				echo '</div></div>'; // ZAMKNIĘCIE DIV-A
				foreach ($articles as $article):
					echo '<div class="row admin articles">
							<div class="col-md-12 article">
							<h3>'.$article['title'].'</h3>
							<span>'.$article['date_creation'].'</span><br>
							<p>'.$article['description'].'</p>';
					$token = md5(rand());?>
					<br><br>
					<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
						<input type="hidden" name="edit" value="<?php echo $article['id'];?>">
						<input type="hidden" name="token" value="<?php echo $token;?>'">
						<input type="submit" class="btn btn-primary" value="Edytuj" onclick="return confirm('Dla poprawnego działania konieczne będzie ponowne podanie ścieźki do pliku obrazu. Kontynuuj, jeśli na pewno go posiadasz.')"> 
					</form>
					<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
						<input type="hidden" name="delete" value="<?php echo $article['id'];?>">
						<input type="hidden" name="token" value="<?php echo $token;?>">
						<input type="submit" class="btn btn-danger" value="Usuń z bazy" onclick="return confirm('Artykuł zostanie bezpowrotnie usunięty z witryny.')">
					</form>
					</div>
					</div>
			<?php endforeach;
			}endif;?>