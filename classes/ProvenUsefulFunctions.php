<?php
	/* Proven Useful Functions by
		VG-Electronics
		V-Generation Ent.
		
		Upgrades:
		v.1 (2017.09.19 - now):
			_the newest_
			
	*/
	class ProvenUsefulFunctions{		
	
		public function back_block(){
			if (isset($_SESSION['r_id']) && !isset($_POST['edit2']) && !isset($_POST['edit_song']) && !isset($_POST['add']) && !isset($_POST['add_song'])){
				unset($_SESSION['r_id']);
			}
		}
	
		public function form_session(){ 
			foreach ($_POST as $input=>$value){
				if (!empty($input)){
					$_SESSION['r_'.$input.''] = $value;
				}
			}
		}
		public function form_attributes(){
			foreach ($_POST as $input=>$value){
				if ($value == NULL){
					$this->$input = NULL;
				}else{
					$this->$input = $value;
				}
			}
			if (isset ($_FILES)){
				foreach ($_FILES as $file){
					$this->theFile = $file;
				}
			}
		}
		
		public function formValidate($type = false){				
			switch ($type){		
				case false:
					break;
				case 'adminBeat':
					if ($_FILES['file']['type'] != 'audio/mp3'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .mp3.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}
					foreach ($_POST as $field){
						if ($field == ''){
							$_SESSION['e_info'] = 'Wszystkie pola muszą być wypełnione.';
						}
					}
					$name = $_POST['name'];
					$this->query("SELECT * FROM beats WHERE name = '$name'");
					$result = $this->resultSet();
					
					if ($this->resultSet() != NULL){
						$_SESSION['e_info'] = 'Istnieje już plik o takiej nazwie.';
					}
					break;
					
				case 'adminBeatEdit':
					if ($_FILES['file']['type'] != 'audio/mp3'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .mp3.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}
					foreach ($_POST as $field){
						if ($field == ''){
							$_SESSION['e_info'] = 'Wszystkie pola muszą być wypełnione.';
						}
					}
					$name = $_POST['name'];
					$this->query("SELECT * FROM beats WHERE name = '$name'");
					$result = $this->resultSet();
					
					if ($result != NULL && @$result[0]['id'] != $this->edit2){
						$_SESSION['e_info'] = 'Istnieje już plik o takiej nazwie.';
					}
					break;
					
				case 'adminSong':
					if ($_FILES['file']['type'] != 'audio/mp3'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .mp3.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}
					$title = $this->title;
					$id_artist = $this->artist;
					$id_album = $this->album;
					
					$this->query("SELECT * FROM songs WHERE id_artist = $id_artist AND title = '$title' AND
										id_album = '$id_album'");
					if ($this->resultSet() != NULL){
						$_SESSION['e_info'] = 'Utwór tego artysty jest już w bazie.';
					}
					if (isset($this->number)){
						if ($this->number <= 0 || $this->number > 60){
							$_SESSION['e_info'] = 'Numer utworu nie może być większy niż 60.';
						}
					}
					
					foreach ($_POST as $field=>$value){
						if (empty($value)){
							if ($field == 'artist' || $field == 'title' || $field == 'file'){
								$_SESSION['e_info'] = 'Artysta, tytuł utworu i dodanie pliku są obowiązkowe.';
							}else{
								$value = NULL;
							}
						}						
					}
					break;
				
				case 'adminSongEdit':
					if ($_FILES['file']['type'] != 'audio/mp3'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .mp3.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}
					$title = $this->title;
					$id_artist = $this->artist;
					$id_album = $this->album;
					
					$this->query("SELECT * FROM songs WHERE id_artist = $id_artist AND title = '$title' AND
										id_album = '$id_album'");
					$result = $this->resultSet();
					if ($result != NULL && @$result[0]['id'] != $this->edit2){
						$_SESSION['e_info'] = 'Istnieje już taki utwór tego artysty.';
					}
					if (isset($this->number)){
						if (@$this->number <= 0 || @$this->number > 60){
							$_SESSION['e_info'] = 'Numer utworu nie może być większy niż 60.';
						}
					}
					foreach ($_POST as $field=>$value){
						if (empty($value)){
							//die();
							if ($field == 'producer' || $field == 'featuring' || $field == 'album' || $field == 'youtube' 
								|| $field == 'file'){
								$value = NULL;                                          
							}else{
								$_SESSION['e_info'] = 'Artysta i tytuł utworu są obowiązkowe.';
							}
						}			
					}
					break;
				
				case 'adminAlbum':
					if ($_FILES['file']['type'] != 'application/octet-stream'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .rar.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}
					
					foreach ($_POST as $field => $value){
						if (empty($value)){
							$_SESSION['e_info'] = 'Wszystkie pola są obowiązkowe. Plik .rar może zostać dodany później.';
						}
					}
					$this->query("SELECT * FROM albums WHERE title = '".$this->title."'");
					if ($this->resultSet() != NULL){
						$_SESSION['e_info'] = 'Album jest już w bazie danych.';
					}
					break;
				
				case 'adminAlbumEdit':
					if ($_FILES['file']['type'] != 'application/octet-stream'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .rar.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}

					foreach ($_POST as $field => $value){
						if (empty($value)){
							$_SESSION['e_info'] = 'Wszystkie pola są obowiązkowe. Plik .rar może zostać dodany później.';
						}
					}
					$title = $this->title;
					$this->query("SELECT * FROM albums WHERE title = '$title'");
					$result = $this->resultSet();
					
					if ($result != NULL && @$result[0]['id'] != $this->edit2){
						$_SESSION['e_info'] = 'Album jest już w bazie danych.';
					}
					break;
				
				case 'adminArtist':
					$this->name = ucfirst($this->name);
					if (strlen($this->description) < 50 || strlen($this->description) > 300){
						$_SESSION['e_info'] = 'Opis musi liczyć od 50 do 300 znaków.';
					}
					if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
						$_SESSION['e_info'] = 'Podany e-mail jest niepoprawny.';
					}
					foreach ($_POST as $field => $value){
						if (empty($value)){
							$_SESSION['e_info'] = 'Wszystkie pola są obowiązkowe.';
						}
					}
					$this->query("SELECT * FROM artists WHERE name = '".$this->name."'");
					$result = $this->resultSet();
					if ($result != NULL){
						$_SESSION['e_info'] = 'Istnieje już taki artysta.';
					}
					break;
					
				case 'adminArtistEdit':
					$this->name = ucfirst($this->name);
					if (strlen($this->description) < 50 || strlen($this->description) > 300){
						$_SESSION['e_info'] = 'Opis musi liczyć od 50 do 300 znaków.';
					}
					if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
						$_SESSION['e_info'] = 'Podany e-mail jest niepoprawny.';
					}
					foreach ($_POST as $field => $value){
						if (empty($value)){
							$_SESSION['e_info'] = 'Wszystkie pola są obowiązkowe.';
						}
					}
					
					$this->query("SELECT * FROM artists WHERE name = '".$this->name."'");
					$result = $this->resultSet();
					if ($result != NULL && @$result[0]['id'] != $this->edit2){
						$_SESSION['e_info'] = 'Istnieje już taki artysta.';
					}
					break;
				
				case 'adminArticle':
					if ($_FILES['file']['type'] != 'image/png'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .png.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}
					
					if (strlen($this->title) < 20 || strlen($this->title) > 150){
						$num = strlen($this->title);
						$_SESSION['e_info'] = 'Tytuł musi liczyć od 20 do 150 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (strlen($this->description) < 100 || strlen($this->description) > 250){
						$num = strlen($this->description);
						$_SESSION['e_info'] = 'Opis musi liczyć od 100 do 250 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (strlen($this->content) < 400 || strlen($this->content) > 4000){
						$num = strlen($this->content);
						$_SESSION['e_info'] = 'Treść artykułu musi liczyć od 400 do 4000 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (str_word_count($this->tags) < 8 || str_word_count($this->tags) > 15){
						$_SESSION['e_info'] = 'Tagi muszą zawierać od 8 do 15 słów.';
					}
					
					foreach ($_POST as $field => $value){
						if (empty($value)){
							$_SESSION['e_info'] = 'Wszystkie pola są obowiązkowe.';
						}
					}
					break;
					
				case 'adminArticleEdit':
					if ($_FILES['file']['type'] != 'image/png'){
						$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .png.';
					}
					if (empty($_FILES['file']['name'])){
						$_SESSION['e_info'] = 'Nie wybrano pliku.';
					}
					
					if (strlen($this->title) < 20 || strlen($this->title) > 150){
						$num = strlen($this->title);
						$_SESSION['e_info'] = 'Tytuł musi liczyć od 20 do 150 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (strlen($this->description) < 100 || strlen($this->description) > 250){
						$num = strlen($this->description);
						$_SESSION['e_info'] = 'Opis musi liczyć od 100 do 250 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (strlen($this->content) < 400 || strlen($this->content) > 4000){
						$num = strlen($this->content);
						$_SESSION['e_info'] = 'Treść artykułu musi liczyć od 400 do 4000 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (str_word_count($this->tags) < 8 || str_word_count($this->tags) > 15){
						$_SESSION['e_info'] = 'Tagi muszą zawierać od 8 do 15 słów.';
					}
					
					foreach ($_POST as $field => $value){
						if (empty($value)){
							$_SESSION['e_info'] = 'Wszystkie pola są obowiązkowe.';
						}
					}
					$this->query("SELECT * FROM news WHERE title = '".$this->title."'");
					$result = $this->resultSet();
					if ($result != NULL && @$result[0]['id'] != $this->edit2){
						$_SESSION['e_info'] = 'Istnieje już artykuł o takim tytule.';
					}
					break;
				
				case 'userProject':
					if (strlen($this->title) < 5 || strlen($this->title) > 100){
						$num = strlen($this->title);
						$_SESSION['e_info'] = 'Tytuł może liczyć od 5 do 100 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (strlen($this->lirycs) > 6000){
						$num = strlen($this->lirycs);
						$_SESSION['e_info'] = 'Tekst może liczyć do 6000 znaków.
														Obecnie liczy '.$num.'.';
					}
					if (!empty($_FILES['file']['name'])){
						if ($_FILES['file']['type'] != 'audio/mp3'){
							$_SESSION['e_info'] = 'Nieprawidłowe rozszerzenie pliku. Wymagane .mp3.';
						}
					}
					$this->query("SELECT * FROM projects WHERE title = '".$this->title."' AND id_user = ".$_SESSION['user']);
					$result = $this->resultSet();
					if ($result != NULL){
						$_SESSION['e_info'] = 'Utworzyłeś już projekt o takim tytule.';
					}
					break;
			}
			
			if (isset ($_SESSION['e_info'])){
				// zapamiętane w sesji dane
				return FALSE;
			}else{
				return TRUE;
			}
		}
		// Notice about error
		function error($name){
			echo '<h2>Ops! Error!</h2><p>It was an error during operation "'.$name.'". Apprise VG-Electronics programistic team.</p>';
			exit();
		}
		
		
		function test_input($data){
			$data = trim($data);
			$data = stripslashes($data);
			return $data;
		}
		
		function check_login($name){
			if (!isset($_SESSION[$name])){
				header ('Location: index.php');
				exit();
			}
			else{
				return $_SESSION['nick'];
			}	
		}
		
		function test($name){
			echo $name;
			exit();
		}	
		public  function setArtistById($array){
			$i = 0;
			foreach ($this->$array as $song){
				$id_artist = $song['id_artist'];
				$this->query("SELECT * FROM artists WHERE id = '$id_artist'");
				$row = $this->resultSet();
				$this->$array[$i] += ['artist' => $row[0]['name']];
				//	die ($row[0]['name']);
				$i++;
			}
		}
		
		
	}
		
?>