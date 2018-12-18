<?php
// SwiftMailer
require_once '/home/klient.dhosting.pl/sentalpl/vendor/autoload.php';

class AdminModel extends MainOperations{
	
	public function Index(){
		$this->accessCheckSession('admin');
		return;
	}
	
	public function Login(){
		if (isset ($_SESSION['admin'])){
			header ('Location: '.ROOT_URL.'admin');
			return;
		}
		if (isset ($_POST['login'])){
			if ($this->checkLogIn($_POST['login'], $_POST['password'])){
				$_SESSION['admin'] = true;
				header ('Location: '.ROOT_URL.'admin');
				return;
			}else{
				$_SESSION['e_info'] = 'Podano nieprawidłowe dane.';
				return;
			}
		}
	}
	
	public function Logout(){
		if (isset ($_SESSION['admin'])){
			unset ($_SESSION['admin']);
			header ('Location: '.ROOT_URL);
			return;
		}else{
			header ('Location: '.ROOT_URL.'admin');
			return;
		}
	}
	
	public function Beats(){
		$this->accessCheckSession('admin');
		$this->back_block();
		/* DODANIE PODKŁADU
		*/
		if (isset ($_POST['add'])){
			$this->back_block();
			$this->form_attributes();
			if ($this->formValidate('adminBeat')){
				$this->theFile['name'] = $this->name.'  prod. '.$this->producer.'.mp3';
				if ($this->add_file('admin_beat')){
					
					$name = addslashes($this->name);
					$producer = addslashes($this->producer);
					$price = $this->price;
					
					$values = array ('name' => $name, 'producer' => $producer, 'price' => $price);
					$this->add_row('admin_beat', $values);
					
					// Notification about new beat
					$this->query("SELECT * FROM artists");
					$users = $this->resultSet();
					foreach ($users as $user){
						$values = array ('id_user' => $user['id'], 'message' => 'Nowy bit zagościł w naszej bazie! Który z nas go wykorzysta?');
						$this->add_row('admin_notification', $values);
					}
					
					/* To correct functionality of beats displaying, which bases on
						beats and ratings tables, first 0 rate by non-user must be added
					*/
					$this->query("SELECT * FROM beats WHERE name = '".$name."'");
					$row = $this->resultSet();
					$values = array ('id_beat' => $row[0]['id'], 'id_user' => 0, 'rate' => 0);
					$this->add_row('user_rate', $values);
					
				}
			}else{
				$this->form_session();
			}
		}
		
		/* EDYCJA DANYCH 
		*/
		if (isset ($_POST['edit'])){
			$this->back_block();
			$id = $_POST['edit'];
			$_SESSION['r_id'] = $id;
			$this->prepare_edit('admin_beat', $id);
		}

		if (isset ($_POST['edit2'])){
			$this->back_block();			
			$this->form_attributes();	
			if ($this->formValidate('adminBeatEdit')){			
				$this->theFile['name'] = $this->name.'  prod. '.$this->producer.'.mp3';
				
				if ($this->delete_file('admin_beat', $_SESSION['previous'])){
					unset ($_SESSION['previous']);			
					if ($this->add_file('admin_beat')){
						$id = $this->edit2;
						$name = addslashes($this->name);
						$producer = addslashes($this->producer);
						$price = $this->price;
						
						$values = array ('name' => $name, 'producer' => $producer, 'price' => $price);
						$this->edit_row('admin_beat', $id, $values);
						$_SESSION['p_info'] = 'Edycja przebiegła pomyślnie.';
					}
				}
			}else{
				$this->form_session();
			}
		}
		
		/* USUNIĘCIE PODKŁADU
		*/
		if (isset ($_POST['delete'])){
			$this->back_block();
			$this->tokenCheck('admin/beats');
			$id = $_POST['delete'];
			$this->query("SELECT * FROM beats WHERE id = '$id'");
			$beat = $this->resultSet();		
			$file = $beat[0]['name'].'  prod. '.$beat[0]['producer'].'.mp3';
			if ($this->delete_file('admin_beat', $file)){
				$this->delete_row('admin_beat', $id);
				$this->query("DELETE FROM ratings WHERE id_beat = $id");
			}
		}
		
		/* WYSZUKIWANIE PODKŁADU
		*/
		if (isset ($_POST['searchPhrase'])){
			$this->back_block();
			$_SESSION['searchPhrase'] = $_POST['searchPhrase'];
			$search_phrase = $_POST['searchPhrase'];
			return array ($this->search('admin_beat', $search_phrase));
		}else{
			$this->query("SELECT * FROM beats ORDER BY date_creation DESC");
			if ($this->resultSet()){
				return array($this->resultSet());
			}else{
				return FALSE;
			}
		}
	}
	
	public function Songs(){
		$this->accessCheckSession('admin');
		$this->back_block();
		
		$this->query("SELECT * FROM albums");
		$this->albums = $this->resultSet();
		
		$this->query("SELECT * FROM artists");
		$this->artists = $this->resultSet();

		if (isset ($_POST['edit'])){
			$this->back_block();
			$id = $_POST['edit'];
			// Ustawienie ID albumu na potrzeby działania funkcji set_query_data() w 
			// prepare_edit()
			$this->query("SELECT * FROM songs WHERE id = $id");
			$row = $this->resultSet();
			$this->album = $row[0]['id_album'];
			
			$this->prepare_edit('admin_song', $id);
		}
		
		// Przesłanie zmienionych danych
		if (isset ($_POST['edit2'])){
			$this->back_block();
			$this->form_attributes();
			if ($this->formValidate('adminSongEdit')){
				$id = $this->edit2;
				$values = array ('id_artist' => $this->artist, 'title' => $this->title, 'id_album' => $this->album, 'producer' => $this->producer,
												'featuring' => $this->featuring, 'youtube' => $this->youtube);
				$this->edit_row('admin_song', $id, $values);		
				
				$artist = $this->return_artist_name($this->artist);
				$this->theFile['name'] = $artist.' - '.$this->title;
				if ($this->featuring != NULL){ $this->theFile['name'] .= ' (feat. '.$this->featuring.')';}
				if ($this->producer != NULL){ $this->theFile['name'] .= ' (prod. '.$this->producer.')';}
				//$this->theFile['name'] = iconv('windows-1250', 'UTF-8', $this->theFile['name']);
				$this->theFile['name'] .= '.mp3';			
					
				if ($this->delete_file('admin_song', $_SESSION['previous'])){
					unset ($_SESSION['previous']);
					if (!$this->add_file('admin_song')){
						$_SESSION['e_info'] = 'Wystąpił błąd przy dodaniu pliku. Spróbuj ponownie.';	
						$this->form_session();
					}else{		
						$_SESSION['p_info'] = 'Edycja przebiegła pomyślnie.';	
					}
				}
				if (isset($_SESSION['p_info'])){
					unset ($_SESSION['r_id']);
				}
			}else{
				$this->form_session();
			}
		}
		
		if (isset($_POST['add'])){
			$this->back_block();
			$this->tokenCheck('admin/songs');
			$this->form_attributes();
			if ($this->formValidate('adminSong')){
				
				$artist_name = $this->return_artist_name($this->artist);
				$this->theFile['name'] = $artist_name.' - '.$this->title;
				if ($this->featuring != NULL){ $this->theFile['name'] .= ' (feat. '.$this->featuring.')';}
				if ($this->producer != NULL){ $this->theFile['name'] .= ' (prod. '.$this->producer.')';}
				//$this->theFile['name'] = iconv('windows-1250', 'UTF-8', $this->theFile['name']);
				$this->theFile['name'] .= '.mp3';
				
				//die($this->theFile['name']);
				if ($this->add_file('admin_song')){	
					$values = array ('id_artist' => $this->artist, 'title' => $this->title, 'id_album' => $this->album,
											'producer' => $this->producer, 'featuring' => $this->featuring, 
											'youtube' => $this->youtube, 'date_creation' => $this->date_creation);
					$this->add_row('admin_song', $values);
				}// else{ display session info in View}
			}else{
				$this->form_session();
			}
		}
		
		if (isset ($_POST['delete'])){
			$this->back_block();
			$this->tokenCheck('admin/songs');
			$id = $_POST['delete'];
			$this->query("SELECT * FROM songs WHERE id = '$id'");
			$this->song = $this->resultSet();
			$this->album = $this->song[0]['id_album'];
			$this->setArtistById('song');
			
			$file = $this->song[0]['artist'].' - '.$this->song[0]['title'];
			if ($this->song[0]['featuring'] != NULL){ $file .= ' (feat. '.$this->song[0]['featuring'].')';}
			if ($this->song[0]['producer'] != NULL){ $file .= ' (prod. '.$this->song[0]['producer'].')';}
			//$file = iconv('windows-1250', 'UTF-8', $file);
			$file .= '.mp3';
			
			if ($this->delete_file('admin_song', $file)){
				$this->delete_row('admin_song', $id);
			}
		}
		
		if (isset($_POST['search'])){
			$this->back_block();
			// wyświetlenie właściwych rekordów
			$_SESSION['searchPhrase'] = $_POST['search_phrase'];			
			$phrase = $_POST['search_phrase'];
			if (empty($phrase) || $phrase == ' '){
				$this->songs = FALSE;
			}else{
				$this->songs = $this->search('admin_song', $phrase);
			}
		}else{
			// wyświetlenie najnowszych utworów
			$this->query("SELECT * FROM songs ORDER BY id DESC LIMIT 1");
			$this->songs = $this->resultSet();
			$this->setArtistById('songs');
		}
		
		return array ($this->songs, $this->artists, $this->albums);
	}
	
	
	public function Albums(){
		$this->accessCheckSession('admin');
		$this->back_block();
		
		$this->query("SELECT * FROM artists");
		$this->artists = $this->resultSet();
		
		if (isset($_POST['add'])){
			$this->back_block();
			$this->tokenCheck('admin/albums');
			$this->form_attributes();
			if ($this->formValidate('adminAlbum')){	
			
				$date = date_create($this->date_creation);
				$premiere = date_format($date, 'Y-m-d');
				$year = date_format($date, 'Y');
							
				$values = array ('id_artist' => $this->id_artist, 'title' => $this->title, 'description' => $this->description,
										'date_creation' => $this->date_creation);
				$this->add_row('admin_album', $values);

				$artist_name = $this->return_artist_name($this->id_artist);
				$this->theFile['name'] = $artist_name.' - '.$this->title.' ('.$year.').rar';
				if (!$this->add_file('admin_album')){
					$this->form_session();
				}
			}else{
				$this->form_session();
			}
		}
		
		if (isset($_POST['edit'])){
			$this->back_block();
			$id = $_POST['edit'];
			$_SESSION['r_id'] = $id;
			$this->prepare_edit('admin_album', $id);
			
			$this->query("SELECT * FROM albums WHERE id = $id");
			$album = $this->resultSet();

			$this->query("SELECT * FROM songs WHERE id_album = $id ORDER BY date_creation ASC");
			$songs = $this->resultSet();
			$_SESSION['artist'] = $album[0]['id_artist'];
			$_SESSION['album'] = $id;
			$_SESSION['date_album'] = $album[0]['date_creation'];
			$i = 1;
			foreach ($songs as $song){
				$_SESSION['n_'.$i]['number'] = substr($song['date_creation'], -2);
				$_SESSION['n_'.$i]['title'] = $song['title'];
				$_SESSION['n_'.$i]['featuring'] = $song['featuring'];
				$_SESSION['n_'.$i]['producer'] = $song['producer'];
				$_SESSION['n_'.$i]['youtube'] = $song['youtube'];
				$i++;
			}
			$this->songs = $songs;
		}
		
		if (isset($_POST['edit2'])){
			$this->back_block();
			$this->tokenCheck('admin/albums');
			$this->form_attributes();
			if ($this->formValidate('adminAlbumEdit')){
				$values = array ('id_artist' => $this->artist, 'title' => $this->title, 'description' => $this->description,
										'date_creation' => $this->date_creation);
				$this->edit_row('admin_album', $this->edit2, $values);
																
				$artist = $this->return_artist_name($this->artist);
				$date = date_create($this->date_creation);
				$premiere = date_format($date, 'Y-m-d');
				$year = date_format($date, 'Y');
					
				$this->theFile['name'] = $artist.' - '.$this->title.' ('.$year.').rar';
				if ($this->delete_file('admin_album', $_SESSION['previous'])){
					unset ($_SESSION['previous']);
					if (!$this->add_file('admin_album')){
						$_SESSION['e_info'] = 'Wystąpił błąd przy dodaniu pliku. Spróbuj ponownie.';	
						$this->form_session();
					}else{		
						$_SESSION['p_info'] = 'Edycja przebiegła pomyślnie.';	
					}
				}
				
				if (isset($_SESSION['p_info'])){
					unset ($_SESSION['r_id']);
				}
			}else{
				$id = $this->album;
				$this->query("SELECT * FROM albums WHERE id = $id");
				$album = $this->resultSet();				
				$this->query("SELECT * FROM songs WHERE id_album = '".$this->edit2."'");
				$songs = $this->resultSet();
				
				$_SESSION['artist'] = $song['id_artist'];
				$_SESSION['album'] = $this->edit2;
				$_SESSION['date_album'] = $album['date_creation'];
				$i = 1;
				foreach ($songs as $song){
					$_SESSION['n_'.$i]['number'] = substr($song['date_creation'], -2);
					$_SESSION['n_'.$i]['title'] = $song['title'];
					$_SESSION['n_'.$i]['featuring'] = $song['featuring'];
					$_SESSION['n_'.$i]['producer'] = $song['producer'];
					$_SESSION['n_'.$i]['youtube'] = $song['youtube'];
					$i++;
				}
				$this->songs = $songs;
				$this->form_session();
			}
		}
		
		if (isset($_POST['delete'])){
			$this->back_block();
			$id = $_POST['delete'];
			
			// Informacje do usunięcia pliku .rar
			$this->query("SELECT * FROM albums WHERE id = $id");
			$row = $this->resultSet();
			$artist = $this->return_artist_name($row[0]['id_artist']);
			$title = $row[0]['title'];
			$date = date_format(date_create($row[0]['date_creation']), 'Y');
			
			$this->query("UPDATE songs SET id_album = 0 WHERE id_album = $id");
			if ($this->resultSet(true)){
				$this->query("DELETE FROM albums WHERE id = $id");
				$this->resultSet();
				
				$files = scandir($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$id);
				$files = array_diff($files, array('.', '..'));
				foreach ($files as $file){
					if (file_exists($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$file)){
						unlink($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$file);
					}
					rename($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$id.'/'.$file, $_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$file);       
				}
				rmdir($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$id);
				unlink($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$artist.' - '.$title.' ('.$date.').rar');
			}else{
				$_SESSION['e_info'] = 'Wystąpił błąd podczas zmiany informacji o utworach. Spróbuj ponownie lub powiadom zespół VG-Electronics.';
			}
		}
		
		if (isset($_POST['edit_song'])){
			$this->back_block();
			$this->form_attributes();
			$this->edit2 = $this->edit_song; // Na potrzeby działania funkcji formValidate
			if ($this->formValidate('adminSongEdit')){
				$id = $this->edit_song;
				$date = substr($this->date_album, 0 , -2);
				$date .= $this->number;
				
				$values = array ('id_artist' => $this->artist, 'title' => $this->title, 'id_album' => $this->album, 'producer' => $this->producer,
										'featuring' => $this->featuring, 'youtube' => $this->youtube, 'date_creation' => $date);
				$this->edit_row('admin_song', $id, $values);		
				
				$artist = $this->return_artist_name($this->artist);
				$this->theFile['name'] = $artist.' - '.$this->title;
				if ($this->featuring != NULL){ $this->theFile['name'] .= ' (feat. '.$this->featuring.')';}
				if ($this->producer != NULL){ $this->theFile['name'] .= ' (prod. '.$this->producer.')';}
				$this->theFile['name'] .= '.mp3';			
					
				// Zdefiniowanie poprzedniego pliku do usunięcia z powodu
				// braku możliwości użycia funkcji prepare_edit
				$this->query("SELECT * FROM songs WHERE id = $id");
				$row = $this->resultSet();
				$artist = $this->return_artist_name($row[0]['id_artist']);				
				$this->album = $row[0]['id_album'];

				$previous = $artist.' - '.$row[0]['title'];
				if ($row[0]['featuring'] != NULL){ $previous .= ' (feat. '.$row[0]['featuring'].')';}
				if ($row[0]['producer'] != NULL){ $previous .= ' (prod. '.$row[0]['producer'].')';}
				$previous .= '.mp3';	
				
				if ($this->delete_file('admin_song', $previous)){
					unset ($_SESSION['previous']);
					if (!$this->add_file('admin_song')){
						$_SESSION['e_info'] = 'Wystąpił błąd przy dodaniu pliku. Spróbuj ponownie.';	
						$this->form_session();
					}else{		
						$_SESSION['p_info'] = 'Edycja przebiegła pomyślnie.';	
					}
				}
				
				if (isset($_SESSION['p_info'])){
					$_SESSION['album_edit'] = TRUE;
					
					$id = $_SESSION['r_id'];
					$this->prepare_edit('admin_album', $id);
					
					$this->query("SELECT * FROM albums WHERE id = $id");
					$album = $this->resultSet();
					$this->query("SELECT * FROM songs WHERE id_album = $id ORDER BY date_creation ASC");
					$songs = $this->resultSet();
					
					$_SESSION['artist'] = $songs[0]['id_artist'];
					$_SESSION['album'] = $id;
					$_SESSION['date_album'] = $album[0]['date_creation'];
					$i = 1;
					foreach ($songs as $song){
						$_SESSION['n_'.$i]['number'] = substr($song['date_creation'], -2);
						$_SESSION['n_'.$i]['title'] = $song['title'];
						$_SESSION['n_'.$i]['featuring'] = $song['featuring'];
						$_SESSION['n_'.$i]['producer'] = $song['producer'];
						$_SESSION['n_'.$i]['youtube'] = $song['youtube'];
						$i++;
					}
					$this->songs = $songs;
				}
			}else{
				$this->form_session();
			}
		}
		
		if (isset($_POST['add_song'])){
			$this->back_block();
			$this->tokenCheck('admin/albums');
			$this->form_attributes();
			$this->title = $this->songtitle;
			$_POST['artist'] = $_SESSION['artist'];
			
			if ($this->formValidate('adminSong')){
				$date = substr($this->date_album, 0 , -2);
				$date .= $this->number;
				
				$artist = $this->return_artist_name($this->artist);
				$this->theFile['name'] = $artist.' - '.$this->title;
				if ($this->featuring != NULL){ $this->theFile['name'] .= ' (feat. '.$this->featuring.')';}
				if ($this->producer != NULL){ $this->theFile['name'] .= ' (prod. '.$this->producer.')';}
				$this->theFile['name'] .= '.mp3';
				
				if ($this->add_file('admin_song')){
					$values = array ('id_artist' => $this->artist, 'title' => $this->title, 'id_album' => $this->album,
											'producer' => $this->producer, 'featuring' => $this->featuring, 
											'youtube' => $this->youtube, 'date_creation' => $date);
					$this->add_row('admin_song', $values);
				}// else{ display session info in View}
			}else{
				$this->form_session();
			}
			$id = $this->album;
			$this->prepare_edit('admin_album', $id);
				
			$this->query("SELECT * FROM albums WHERE id = $id");
			$album = $this->resultSet();				
			$this->query("SELECT * FROM songs WHERE id_album = '".$id."'");
			$songs = $this->resultSet();
				
			$_SESSION['artist'] = $album[0]['id_artist'];
			$_SESSION['album'] = $id;
			$_SESSION['date_album'] = $album[0]['date_creation'];
			$i = 1;
			foreach ($songs as $song){
				$_SESSION['n_'.$i]['number'] = substr($song['date_creation'], -2);
				$_SESSION['n_'.$i]['title'] = $song['title'];
				$_SESSION['n_'.$i]['featuring'] = $song['featuring'];
				$_SESSION['n_'.$i]['producer'] = $song['producer'];
				$_SESSION['n_'.$i]['youtube'] = $song['youtube'];
				$i++;
			}
			$this->songs = $songs;
		}
		
		$this->query("SELECT * FROM albums ORDER BY date_creation DESC");
		$this->albums = $this->resultSet();
		$this->set_artist_by_id('albums');
		
		return array (@$this->songs, $this->artists, $this->albums);
	}
	
	public function Artists(){
		if (isset($_POST['add'])){
			$this->back_block();
			$this->form_attributes();
			$password = md5(rand());
			$_SESSION['password'] = $password;
			$password_hash = password_hash($password, PASSWORD_DEFAULT);
			if ($this->formValidate('adminArtist')){
				$values = array ('name' => $this->name, 'email' => $this->email, 'password' => $password_hash, 'description' => $this->description);
				$this->add_row('admin_artist', $values);
				
				// SwiftMailer
				 $transport = (new Swift_SmtpTransport(MAIL_SMTP, 25))
				  ->setUsername(MAIL_USER)
				  ->setPassword(MAIL_PASS)
				  ;

				$mailer = new Swift_Mailer($transport);

				$content = 
'Witaj '.$this->name.'!
Z dumą ogłaszamy start witryny www.masarniarecords.pl. Twoje miejsce w szeregach naszej wytwórni nie mogło nie znaleźć odzwierciedlenia także na stronie, która za kulisami sceny jest Naszą biblioteką legalnych podkładów oraz miejscem na przechowywanie realizowanych projektów. Zapewniają one ochronę tekstów i bitów oraz bezproblemowe przeniesienie ich do studia.
By zalogować się do panelu użytkownika użyj poniższych danych:

E-mail: '.$this->email.'
Hasło: '.$password.'

Wejdź na adres www.masarniarecords.pl/user, aby zalogować się z użyciem tymczasowego hasła. Możesz zmienić je w zakładce "Ustawienia".
Zapraszamy do skorzystania z panelu i zgłaszania wszelkich uwag i propozycji!
X Masarnia Records X';
				
				$message = (new Swift_Message('Twoje konto w MasarniaRecords.pl zostało utworzone!'))
				
				->setFrom(['kontakt@masarniarecords.pl' => 'Masarnia Records'])
				->setTo($this->email)
				->setBody($content)
				;

				$emailSent = $mailer->send($message);

				if (!$emailSent){
					echo 'Wysłanie nie powiodło się.<br>'.$mailer->ErrorInfo;
					die();
				}
				unset ($_SESSION['r_id']);
				$_SESSION['p_info'] = 'Nowy artysta został dodany.';
			}else{
				$this->form_session();
			}
		}
		
		if (isset($_POST['edit'])){
			$this->back_block();
			$_SESSION['r_id'] = $_POST['edit'];
			$id = $_POST['edit'];
			$this->prepare_edit('admin_artist', $id);
		}
		
		if (isset($_POST['edit2'])){
			$this->back_block();
			$this->form_attributes();
			if ($this->formValidate('adminArtistEdit')){
				$id = $_SESSION['r_id'];
				$this->query("SELECT * FROM artists WHERE id = $id");
				$row = $this->resultSet();
				if ($row[0]['email'] != $this->email){
					// Send password into new e-mail
				}
				$values = array ('name' => $this->name, 'email' => $this->email, 'description' => $this->description);
				$this->edit_row('admin_artist', $id, $values);
				
				unset ($_SESSION['r_id']);
				$_SESSION['p_info'] = 'Dane zostały zmienione.';
			}else{
				$this->form_session();
			}
		}
		
		if (isset($_POST['delete'])){
			$this->back_block();
			$id = $_POST['delete'];
			$artist = $this->return_artist_name($id);
			
			$this->query("SELECT * FROM songs WHERE id_artist = $id");
			$songs = $this->resultSet();
			foreach ($songs as $song){
				$file = $artist.' - '.$song['title'];
				if ($song['featuring'] != NULL){ $file .= ' (feat. '.$song['featuring'].')';}
				if ($song['producer'] != NULL){ $file .= ' (prod. '.$song['producer'].')';}
				$file .= '.mp3';	
				$this->album = $song['id_album'];
				$this->delete_file('admin_song', $file);
				$this->delete_row('admin_song', $song['id']);
			}
			
			$this->query("SELECT * FROM albums WHERE id_artist = $id");
			$albums = $this->resultSet();
			foreach ($albums as $album){
				$this->query("DELETE FROM albums WHERE id = ".$album['id']);
				$this->resultSet();
				
				$date = date_format(date_create($album['date_creation']), 'Y');
				rmdir($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$album['id']);			
				unlink($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/download/'.$artist.' - '.$album['title'].' ('.$date.').rar');
			}
			$this->query("DELETE FROM projects, notifications WHERE id_user = $id");
			$this->resultSet();
			$this->query("DELETE FROM artists WHERE id = $id");
			$this->resultSet();
		}
		
		$this->query('SELECT * FROM artists ORDER BY date_creation');
		$this->artists = $this->resultSet();
		return array ($this->artists);
	}
	
	public function Articles(){
		$this->accessCheckSession('admin');
		$this->back_block();

		if (isset($_POST['add'])){
			$this->back_block();
			$_SESSION['new_article'] = TRUE;
			foreach ($_POST as $key => $value){
				$value = addslashes($value);
			}
			$this->form_attributes();
			if ($this->formValidate('adminArticle')){
				$values = array ('title' => $this->title, 'description' => $this->description, 
										'content' => $this->content, 'tags' => $this->tags);
				$this->add_row('admin_articles', $values);
				
				$this->query("SELECT * FROM news WHERE title = '".$this->title."'");
				$row = $this->resultSet();
				$id = $row[0]['id'];
				$this->theFile['name'] = $id.'.png';

				if (!$this->add_file('admin_articles')){
					$_SESSION['e_info'] = 'Wystąpił błąd przy dodaniu pliku. Spróbuj ponownie.';	
					$this->form_session();
				}

				unset ($_SESSION['new_article']);
				$_SESSION['p_info'] = 'Nowy artykuł został dodany.';
			}else{
				$this->form_session();
			}
		}
		
		if (isset($_POST['edit'])){
			$this->back_block();
			$id = $_POST['edit'];
			$_SESSION['r_id'] = $_POST['edit'];
			$this->prepare_edit('admin_articles', $id);
		}
		
		if (isset($_POST['edit2'])){
			$this->back_block();
			// Poprawne wyświetlenie danych w formularzu
			foreach ($_POST as $key => $value){
				$value = addslashes($value);
			}
			$this->form_attributes();
			if ($this->formValidate('adminArticleEdit')){
				$id = $_SESSION['r_id'];
				$values = array ('title' => $this->title, 'description' => $this->description, 
										'content' => $this->content, 'tags' => $this->tags);
				$this->edit_row('admin_articles', $id, $values);
				
				$this->theFile['name'] = $id.'.png';
				if ($this->delete_file('admin_articles', $_SESSION['previous'])){
					unset ($_SESSION['previous']);
					if (!$this->add_file('admin_articles')){
						$_SESSION['e_info'] = 'Wystąpił błąd przy dodaniu pliku. Spróbuj ponownie.';	
						$this->form_session();
					}else{		
						$_SESSION['p_info'] = 'Edycja przebiegła pomyślnie.';	
					}
				}
				
				unset ($_SESSION['r_id']);
				$_SESSION['p_info'] = 'Dane zostały zmienione.';
			}else{
				$this->form_session();
			}
		}
		
		if (isset($_POST['delete'])){
			$this->back_block();
			$id = $_POST['delete'];
			$this->delete_row('admin_articles', $id);
		}
		
		if (isset($_POST['search'])){
			$this->back_block();
			// wyświetlenie właściwych rekordów
			$_SESSION['search_phrase'] = $_POST['search_phrase'];			
			$phrase = $_POST['search_phrase'];
			if (empty($phrase) || $phrase == ' '){
				$this->articles = FALSE;
			}else{
				$this->articles = $this->search('admin_article', $phrase);
			}
		}else{
			// wyświetlenie najnowszych utworów
			$this->query("SELECT * FROM news ORDER BY date_creation DESC LIMIT 5");
			$this->articles = $this->resultSet();
		}
		return array ($this->articles);
	}
	
	public function Statistics(){
		if (!empty($_POST)){
			$this->back_block();
			if (isset($_POST['songs'])){
				$table = 'songs';
				$param = 'downloadCount';
			}elseif (isset($_POST['articles'])){
				$table = 'news';
				$param = 'views';
			}elseif (isset($_POST['albums'])){
				$table = 'albums';
				$param = 'downloadCount';
			}elseif (isset($_POST['beats'])){
				$table = 'beats';
				$param = 'price';
			}
			
			if ($table == 'songs' || $table == 'news' || $table == 'albums' || $table == 'beats'){	
				$this->query("SELECT * FROM ".$table);
				$this->data1 = count($this->resultSet());
					
				$this->query("SELECT * FROM ".$table." ORDER BY ".$param." DESC LIMIT 5");
				if ($this->resultSet()){
					$this->data2 = $this->resultSet();
					if ($table == 'songs'){
						$this->set_artist_by_id('data2');
					}
				}else{
					$this->data2 = FALSE;
				}
			}	
			if ($table == 'songs' || $table == 'news'){
				$date = new DateTime(date('Y-m-d H:i:s'));
				$date->modify('-1 month');
				$date = $date->format('Y-m-d H:i:s');
				$this->query("SELECT * FROM ".$table." WHERE date_creation > '".$date."' AND ".$param." > 0 ORDER BY ".$param." DESC LIMIT 5");
				if ($this->resultSet()){
					$this->data3 = $this->resultSet();
					if ($table == 'songs'){
						$this->set_artist_by_id('data3');
					}
				}else{
					$this->data3 = FALSE;
				}
			}
			if ($table == 'albums'){
				$this->query("SELECT * FROM albums");
				$albums = $this->resultSet();
				$i = 0;
				foreach ($albums as $album){
					$this->query("SELECT * FROM songs WHERE id_album = ".$album['id']);
					$songs = $this->resultSet();
					$downloads = 0;
					foreach ($songs as $song){
						$downloads = $downloads + $song['downloadCount'];
					}
					$albums[$i] += ['downloads' => $downloads];
					$i++;
				}
				$this->data3 = $albums;
			}
			
			if (isset($_POST['search_phrase'])){
				$this->back_block();
				$this->tokenCheck('admin/statistics');
				// wyświetlenie właściwych rekordów
				$_SESSION['search_phrase'] = $_POST['search_phrase'];			
				$phrase = $_POST['search_phrase'];
				
				if (empty($phrase) || $phrase == ' '){
					$this->search = FALSE;
				}else{
					switch ($table){
						case 'songs': $type = 'song'; break;
						case 'news': $type = 'article'; break;
						case 'albums': $type = 'album'; break;
						case 'beats': $type = 'beat'; break;
					}
					$this->search = $this->search('admin_'.$type, $phrase);
				}
			}
		}
		return array (@$this->data1, @$this->data2, @$this->data3, @$this->search);
	}
	
	private function checkLogIn($login, $password){
		if ($login == ADMIN_LOG && $password == ADMIN_PASS){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>