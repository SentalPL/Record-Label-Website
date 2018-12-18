<?php
class UserModel extends MainOperations{
	public function Index(){
		$this->accessCheckSession('user');
		if (isset ($_SESSION['r_id'])){
			unset ($_SESSION['r_id']);
		}
		$this->query("SELECT * FROM artists WHERE id = ".$_SESSION['user']);
		$this->user = $this->resultSet();
		
		$this->query("SELECT DISTINCT * FROM notifications WHERE id_user = ".$_SESSION['user']." GROUP BY message");
		if ($this->resultSet()){
			$notifications = $this->resultSet();
			$this->query("DELETE FROM notifications WHERE id_user = ".$_SESSION['user']);
			$this->resultSet();
		}
		return array ($this->user[0], @$notifications);
	}
	
	public function Login(){
		if (isset ($_SESSION['user'])){
			header ('Location: '.ROOT_URL.'user');
			return;
		}
		if (isset ($_POST['email'])){
			$email = addslashes($_POST['email']);
			$password = addslashes($_POST['password']);
			
			$this->query("SELECT * FROM artists WHERE email = '$email'");
			$row = $this->resultSet();
			// Aktywowani użytkownicy
			if ($row != FALSE){
				if (password_verify($password, $row[0]['password'])){
					$_SESSION['user'] = $row[0]['id'];
					header ('Location: '.ROOT_URL.'user');
					return;
				}else{
					$_SESSION['e_info'] = 'Podane hasło jest niepoprawne.';
					return;
				}
			// Nieaktywowani użytkownicy
			}else{
				$this->query("SELECT * FROM not_confirmed WHERE email = '$email'");
				$row = $this->resultSet();
				if ($row != FALSE){
					if (password_verify($password, $row[0]['password'])){
						$name = $row[0]['name'];
						$email = $row[0]['email'];
						$password = $row[0]['password'];
						$description = $row[0]['description'];
						$date_creation = $row[0]['date_creation'];
						
						$this->query("DELETE FROM not_confirmed WHERE id = ".$row[0]['id']);
						if ($this->resultSet(true)){
							$values = array ('name' => $name, 'email' => $email, 'password' => $password,
													'description' => $description, 'date_creation' => $date_creation);
							if ($this->add_row('user_confirmed', $values, true)){
								$this->query("SELECT * FROM artists WHERE name = '$name'");
								$row = $this->resultSet();
								$_SESSION['user'] = $row[0]['id'];
								header ('Location: '.ROOT_URL.'user/settings');
								return;
							}
						}else{
							$_SESSION['e_info'] = 'Wystąpił błąd podczas usuwania nieaktywowanego konta. Spróbuj ponownie lub powiadom administratora.';
							return;
						}
					}else{
						$_SESSION['e_info'] = 'Podane hasło jest niepoprawne.';
						return;
					}
				}else{
					$_SESSION['e_info'] = 'Podany adres e-mail nie istnieje.';
					return;
				}
			}
		}
	}
	
	public function Logout(){
		if (isset ($_SESSION['user'])){
			unset ($_SESSION['user']);
			header ('Location: '.ROOT_URL);
			return;
		}else{
			header ('Location: '.ROOT_URL.'user');
			return;
		}
	}
	
	public function Projects(){
		$this->accessCheckSession('user');
		$this->back_block();
		
		if (isset($_POST['view'])){
			$this->back_block();
			$id = $_POST['view'];
			$this->query("SELECT * FROM projects WHERE id = $id");
			$row = $this->resultSet();
			$this->project = $row[0];
			
			$files = scandir($_SERVER['DOCUMENT_ROOT'].'/mvc/assets/projects/'.$id);				
			foreach ($files as $file){
				if (!preg_match('@^\.+$@', $file)){
					$this->project += ['file' => ROOT_URL.'assets/projects/'.$id.'/'.$file];
				}
			}
		}
		
		if (isset($_POST['add'])){
			$this->back_block();
			$this->tokenCheck('user/projects');
			$_SESSION['new_project'] = TRUE;
			$this->form_attributes();
			if ($this->formValidate('userProject')){
				$values = array ('id_user' => $_SESSION['user'], 'title' => $this->title, 
										'lirycs' => $this->lirycs);
				$this->add_row('user_project', $values);
				
				if (!empty($_FILES['file']['name'])){
					$this->query("SELECT * FROM projects WHERE title = '".$this->title."'");
					$row = $this->resultSet();
					$_SESSION['id_project']= $row[0]['id'];

					if (!$this->add_file('user_project')){
						$_SESSION['e_info'] = 'Wystąpił błąd przy dodaniu pliku. Spróbuj ponownie.';	
						$this->form_session();
					}
				}
				if (!isset($_SESSION['e_info'])){
					unset ($_SESSION['new_project']);
					$_SESSION['p_info'] = 'Nowy projekt został utworzony.';					
				}
			}else{
				$this->form_session();
			}
			
		}
		
		if (isset($_POST['edit'])){
			$this->tokenCheck('user/projects');
			$id = $_POST['edit'];
			$_SESSION['r_id'] = $_POST['edit'];
			$this->prepare_edit('user_project', $id);
		}
		
		if (isset($_POST['edit2'])){
			$this->tokenCheck('user/projects');
			// Poprawne wyświetlenie danych w formularzu
			foreach ($_POST as $key => $value){
				$value = addslashes($value);
			}
			$this->form_attributes();
			if ($this->formValidate('userProjectEdit')){
				$id = $_SESSION['r_id'];
				$values = array ('id_user' => $_SESSION['user'], 'title' => $this->title, 
										'lirycs' => $this->lirycs);
				$this->edit_row('user_project', $id, $values);
				
				if (!empty($_FILES['file']['name'])){
					$_SESSION['id_project']= $id;
					
					$path = $_SERVER['DOCUMENT_ROOT'].'/mvc/assets/projects/'.$id;
					if (file_exists($path)){
						$this->delete_all_files($path);
					}
					if (!$this->add_file('user_project')){
						$_SESSION['e_info'] = 'Wystąpił błąd przy dodaniu pliku. Spróbuj ponownie.';	
						$this->form_session();
					}
				}
				if (!isset($_SESSION['e_info'])){
					unset ($_SESSION['r_id']);
					$_SESSION['p_info'] = 'Dane zostały zmienione.';
				}
			}else{
				$this->form_session();
			}	
		}
		
		if (isset($_POST['delete'])){
			$id = $_POST['delete'];
			$path = $_SERVER['DOCUMENT_ROOT'].'/mvc/assets/projects/'.$id;
			if (file_exists($path)){
				$this->delete_all_files($path);
				rmdir($path);
			}
			$this->delete_row('user_project', $id);
		}
		
		$this->query("SELECT * FROM projects WHERE id_user = ".$_SESSION['user']);
		if ($this->resultSet()){
			$this->projects = $this->resultSet();
		}else{
			$this->projects = FALSE;
		}
		return array (@$this->projects, @$this->project);
	}
	
	public function Beats(){
		$this->accessCheckSession('user');
		
		if (isset ($_POST['rate'])){
			$this->back_block();
			$id = $_POST['id'];
			$rate = $_POST['rate'];
			$this->query("SELECT * FROM ratings WHERE id_beat = $id AND id_user = ".$_SESSION['user']);
			if ($this->resultSet()){
				$row = $this->resultSet();
				$values = array ('rate' => $rate);
				$this->edit_row('user_rate', $row[0]['id_rate'], $values);
			}else{
				$values = array ('id_beat' => $id, 'id_user' => $_SESSION['user'],
										'rate' => $rate);
				$this->add_row('user_rate', $values);
			}
		}
		
		if (isset ($_POST['phrase'])){
			if (isset($_POST['newest'])){
				$this->query("SELECT beats.*, SUM(ratings.rate) AS allrate FROM beats LEFT JOIN ratings ON beats.id = ratings.id_beat GROUP BY id_beat ORDER BY date_creation DESC");
			}else{				
				$phrase = $_POST['phrase'];
				$this->query("SELECT beats.*, SUM(ratings.rate) AS allrate FROM beats LEFT JOIN ratings ON beats.id = ratings.id_beat WHERE name LIKE '%$phrase%' OR producer LIKE '%$phrase%' GROUP BY id_beat ORDER BY allrate DESC");
			}
			$beats = $this->resultSet();
			
			if ($beats != NULL){
				$i = 0;
				foreach ($beats as $beat){
					$this->query("SELECT * FROM ratings WHERE id_beat = ".$beat['id']." AND id_user = ".$_SESSION['user']);
					if ($this->resultSet()){
						$result = $this->resultSet();
						$beats[$i] += ['my_rate' => $result[0]['rate']];
					}
					$i++;
				}
				return array($beats);
			}else{
				return FALSE;
			}
		}else{
			$this->query("SELECT beats.*, SUM(ratings.rate) AS allrate FROM beats LEFT JOIN ratings ON beats.id = ratings.id_beat GROUP BY id_beat ORDER BY allrate DESC");		
			if ($this->resultSet()){
				$beats = $this->resultSet();
				$i = 0;
				foreach ($beats as $beat){
					$this->query("SELECT * FROM ratings WHERE id_beat = ".$beat['id']." AND id_user = ".$_SESSION['user']);
					if ($this->resultSet()){
						$result = $this->resultSet();
						$beats[$i] += ['my_rate' => $result[0]['rate']];
					}
					$i++;
				}
				return array($beats);
			}else{
				return FALSE;
			}
		}
	}
	
	public function Statistics(){
		$this->accessCheckSession('user');
		
		$this->query("SELECT * FROM songs WHERE id_artist = ".$_SESSION['user']." ORDER BY downloadCount DESC LIMIT 5");
		$this->songs = $this->resultSet();
		$this->query("SELECT * FROM albums WHERE id_artist = ".$_SESSION['user']." ORDER BY date_creation DESC LIMIT 5");
		$this->albums = $this->resultSet();
		
		if (isset ($_POST['phrase'])){
			$phrase = $_POST['phrase'];
			if ($phrase == ''){
				$results = array();
			}else{
				$this->query("SELECT * FROM songs WHERE id_artist = ".$_SESSION['user']." AND title LIKE '%$phrase%'");
				$results = $this->resultSet();
			}
		}
		return array($this->songs, $this->albums, @$results);
	}
	
	public function Settings(){
		
		if (isset($_POST['change_password'])){
			$this->back_block();
			$old = $_POST['old_password'];
			$new = $_POST['new_password'];
			
			if (strlen($new) < 8){
				$_SESSION['e_info'] = 'Hasło musi liczyć min. 8 znaków.';
			}elseif($new == $old){
				$_SESSION['e_info'] = 'Podane hasła są takie same.';
			}else{			
				$this->query("SELECT * FROM artists WHERE id = ".$_SESSION['user']);
				$data = $this->resultSet();
				if (password_verify($old, $data[0]['password'])){
					$new = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
					$this->query("UPDATE artists SET password = '$new' WHERE id = ".$_SESSION['user']);
					if ($this->resultSet(true)){
						$_SESSION['p_info'] = 'Hasło zostało pomyślnie zmienione.';
					}else{
						$_SESSION['e_info'] = 'Wystąpił błąd podczas zmiany hasła. Spróbuj ponownie.';
					}
				}else{
					$_SESSION['e_info'] = 'Podane hasło jest nieprawidłowe.';
				}
			}
		}
		
		if (isset($_POST['change_email'])){
			$this->back_block();
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			$this->query("SELECT * FROM artists WHERE id = ".$_SESSION['user']);
			$data = $this->resultSet();
			if (password_verify($password, $data[0]['password'])){
				$this->query("UPDATE artists SET email = '$email' WHERE id = ".$_SESSION['user']);
				if ($this->resultSet(true)){
					$_SESSION['p_info'] = 'Adres e-mal został zmieniony na <b>'.$email.'</b>.';
				}else{
					$_SESSION['e_info'] = 'Wystąpił błąd podczas zmiany adresu e-mail. Spróbuj ponownie.';
				}
			}else{
				$_SESSION['e_info'] = 'Podane hasło jest nieprawidłowe.';
			}
		}
		
		return;
	}
	
	private function delete_all_files($path){
		$files = scandir($path);				
		foreach ($files as $file){
			if (!preg_match('@^\.+$@', $file)){
				unlink ($path.'/'.$file);
			}
		}
	}
	
}
?>