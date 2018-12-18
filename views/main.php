<!DOCTYPE html>
<html lang="pl">
<head>
	<!--
		Title tag is near content div
	-->
	<link rel="shortcut icon" href="<?php echo ROOT_URL; ?>assets/img/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/mainStyle.css">
	<link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/fontello.css">
	<?php
	if (preg_match ('@(music)@', $_SERVER['REQUEST_URI'])){
		echo '<link rel="stylesheet" href="'. ROOT_URL.'assets/css/music.css">';
		
	}elseif (preg_match ('@(news)@', $_SERVER['REQUEST_URI'])){
		echo '<link rel="stylesheet" href="'. ROOT_URL.'assets/css/news.css">';
		
	}elseif (preg_match ('@(contact)@', $_SERVER['REQUEST_URI'])){
		echo '<link rel="stylesheet" href="'. ROOT_URL.'assets/css/contact.css">';
		
	}elseif (preg_match ('@(admin)@', $_SERVER['REQUEST_URI'])){
		echo '<link rel="stylesheet" href="'. ROOT_URL.'assets/css/admin.css">
				<meta name=”robots” content=”noindex, nofollow”>';
	}elseif (preg_match ('@(user)@', $_SERVER['REQUEST_URI'])){
		echo '<link rel="stylesheet" href="'. ROOT_URL.'assets/css/user.css">
				<meta name=”robots” content=”noindex, nofollow”>';
	}else{ // Default main/news site
		echo '<link rel="stylesheet" href="'. ROOT_URL.'assets/css/news.css">';
	}
	?>	
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta http-equiv="content-type" content="text/html" charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Alegreya+Sans+SC|Saira" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Berkshire+Swash|Merienda" rel="stylesheet">
</head>
<body>
<div class="container header">
	<div class="row">
		<div class="col-sm-12 col-md-3 logo">
			<a href="<?php echo ROOT_URL;if (isset($_SESSION['admin'])){echo 'admin';}elseif(isset($_SESSION['user'])){echo 'user';}?>">
				<h1>Masarnia Records</h1>
				<img class="img-fluid" src="<?php echo ROOT_URL;?>assets/img/masarnia.png">
			</a>
		</div>
		<div class="col-sm-12 col-md-4">
			<p>Promujemy rap prawdy.</p>
		</div>
		<div class="col-sm-12 col-md-5">
				<?php
					if (preg_match ('@(admin)@', $_SERVER['REQUEST_URI'])){
						echo '<ul><li>Tryb administratorski</li></ul>';
					}elseif (preg_match ('@(user)@', $_SERVER['REQUEST_URI'])){
						echo '<ul><li>Panel użytkownika</li></ul>';
					}else{					
						echo '<ul>
									<li><a href="'.ROOT_URL.'">Aktualności</a></li>
									<li><a href="'.ROOT_URL.'music">Strefa muzyki</a></li>
									<li><a href="'.ROOT_URL.'contact">Współpraca</a></li>
								</ul>';
					}
				?>
		</div>
	</div>
</div>
<div class="container content">	
		<?php require ($view);?>
</div>
<head>
	<?php 
	if (isset($page_title)){
		echo '<title>'.$page_title.'</title>
				<meta name="description" content="'.$page_description.'">
				<meta name="keywords" content="'.$page_keywords.'">';
	}else{
		echo '<title>Masarnia Records</title>
				<meta name="description" content="Oficjalna strona wytwórni muzycznej specjalizującej
																się w muzyce rap.">
				<meta name="keywords" content="Masarnia, Records, muzyka rap, wytwórnia muzyczna, hip-hopowa, Viper, Tadzik, Kaza, rap prawdy">';
	}
	?>
</head>
<div class="container footer">
	<div class="row">
		<div class="col-md-12">
			Masarnia Records &copy
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>