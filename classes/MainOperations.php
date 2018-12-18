<?php
class MainOperations extends Model{		
	
	private $table;
	private $model_name;
	
	private $search_result = array();
		
	public function search($type, $search_phrase){
		$search_phrase = explode(' ', $search_phrase);
		
		foreach ($search_phrase as $word){
			switch ($type){
				case 'public_music':
					// Artist's popular songs
					$this->query("SELECT * FROM artists WHERE name LIKE '%$word%'");
					if ($this->resultSet() != NULL){
						$artist = $this->resultSet();
						$artist_id = $artist[0]['id'];
						$this->setPopularSongs('search_result', $artist_id);
						// Featurings
						$this->query("SELECT * FROM songs WHERE featuring LIKE '%$word%'");
						if ($this->resultSet() != NULL){
							$this->search_result = array_merge($this->search_result, $this->resultSet());
						}
						
						$i = 0;
						foreach ($this->search_result as $song){
							$this->search_result[$i] += ['type' => 'song'];
							$i++;
						}
					}else{
						// Songs
						$this->query("SELECT * FROM songs WHERE title LIKE '%$word%' OR producer LIKE '%$word%' 
											OR featuring LIKE '%$word%' OR lirycs LIKE '%$word%'");
						if ($this->resultSet() != NULL){
							$this->search_result = $this->resultSet();
							$i = 0;
							foreach ($this->search_result as $song){
								$this->search_result[$i] += ['type' => 'song'];
								$i++;
							}		
						}else{
							// Albums
							$this->query("SELECT * FROM albums WHERE title LIKE '%$word%'");
							if ($this->resultSet() != NULL){
								$this->search_result = $this->resultSet();
								$i = 0;
								foreach ($this->search_result as $album){
									$this->search_result[$i] += ['type' => 'album'];
									$i++;
								}		
							}	
						}
					}
					$this->set_artist_by_id('search_result');
					break;
				// ^ public_music
				case 'admin_song':
					$this->query("SELECT * FROM artists WHERE name LIKE '%$word%'");
					if ($this->resultSet() != NULL){
						$artist = $this->resultSet();
						$artist_id = $artist[0]['id'];
						$this->setPopularSongs('search_result', $artist_id);
					}else{
						// Song
						$this->query("SELECT * FROM songs WHERE title LIKE '%$word%' OR producer LIKE '%$word%' 
											OR featuring LIKE '%$word%' OR lirycs LIKE '%$word%'");
						if ($this->resultSet() != NULL){
							$this->search_result = $this->resultSet();
						}else{
							// Album
							$this->query("SELECT * FROM albums WHERE title LIKE '%$word%'");
							if ($this->resultSet() != NULL){
								$row = $this->resultSet();
								$this->query("SELECT * FROM songs WHERE id_album = ".$row[0]['id']);
								$this->search_result = $this->resultSet();
							}
						}
					}
					$this->set_artist_by_id('search_result');
					break;
				// ^ admin_song
				case 'admin_beat':
					$this->query("SELECT * FROM beats WHERE name LIKE '%$word%' OR producer LIKE '%$word%' ORDER BY date_creation DESC");		
					if ($this->resultSet() != NULL){	
						$this->search_result = $this->resultSet();
					}
					break;
		
				case 'admin_article':
					$this->query("SELECT * FROM news WHERE title LIKE '%$word%'");
					if ($this->resultSet() != NULL){
						$this->search_result = $this->resultSet();	
					}else{
						$this->query("SELECT * FROM news WHERE tags LIKE '%$word%'");
						if ($this->resultSet() != NULL){
							$this->search_result = $this->resultSet();
						}else{
							$this->query("SELECT * FROM news WHERE description LIKE '%$word%'");
							if ($this->resultSet() != NULL){
								$this->search_result = $this->resultSet();
							}
						}
					}	
					break;
			}
		}					
		
		if (count($this->search_result) == 0){
			return FALSE;
		}else{
			return $this->search_result;
		}
	}
	
	
	public function add_row($type, $values, $confirmed_user = false){
		$this->set_query_data($type);		
		$keys = implode(array_keys($values), ', ');
		$values = "'".implode("', '", $values)."'";
		//die('INSERT INTO '.$this->table.' ('.$keys.') VALUES ('.$values.')');
		$this->query('INSERT INTO '.$this->table.' ('.$keys.') VALUES ('.$values.')');
		if ($this->resultSet(true)){
			if ($confirmed_user == true){
				return TRUE;
			}else{
				unset ($_POST);
				$_SESSION['p_info'] = 'Dodano pomyślnie.';
				return $this->model_name;
			}
		}else{
			$_SESSION['e_info'] = 'Wystąpił błąd przy dodawaniu danych. Spróbuj ponownie.';
			return $this->model_name;
		}
	}
	
	public function add_file($type){
		$this->set_query_data($type);
		//$this->theFile['name'] = iconv('UTF-8', 'windows-1250', $this->theFile['name']);
		if (is_uploaded_file ($this->theFile['tmp_name'])){
			if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/assets/'.$this->folder)){
				mkdir($_SERVER['DOCUMENT_ROOT'].'/assets/'.$this->folder, 0777, true);
			}
			if (!move_uploaded_file ($this->theFile['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/assets/'.$this->folder.'/'.$this->theFile['name'])){
				$_SESSION['e_info'] = 'Nie udało się przesłać pliku. Spróbuj ponownie.';
				$error = TRUE;
			}
		}else{
			$_SESSION['e_info'] = 'Nie udało się przesłać pliku. Spróbuj ponownie.';
			$error = TRUE;
		}
		if (@$error){
			return FALSE;
		}else{ 
			return TRUE;
		}
	}
	
	public function prepare_edit($type, $id){
		$this->set_query_data($type);
		$this->query("SELECT * FROM ".$this->table." WHERE id = $id");
		$row = $this->resultSet();
		foreach ($row[0] as $key=>$value){
			$_SESSION['r_'.$key] = htmlentities($value);
		}
		
		// Dostosowanie nazwy poprzedniego pliku do usunięcia w $_SESSION['previous']
		if ($this->table == 'songs'){
			$artist = $this->return_artist_name($row[0]['id_artist']);
			$_SESSION['previous'] = $artist.' - '.$row[0]['title'];
			if ($row[0]['featuring'] != NULL){ $_SESSION['previous'] .= ' (feat. '.$row[0]['featuring'].')';}
			if ($row[0]['producer']  != NULL){ $_SESSION['previous'] .= ' (prod. '.$row[0]['producer'].')';}
			$_SESSION['previous'] .= '.mp3';
		}elseif ($this->table == 'beats'){
			$_SESSION['previous'] = $row[0]['name'].'  prod. '.$row[0]['producer'].'.mp3';
		}elseif ($this->table == 'albums'){
			$artist = $this->return_artist_name($row[0]['id_artist']);
			$date = date_create($row[0]['date_creation']);
			$year = date_format($date, 'Y');
			$_SESSION['previous'] = $artist.' - '.$row[0]['title'].' ('.$year.').rar';
		}elseif ($this->table == 'news'){
			$_SESSION['previous'] = $id.'.png';
		}
		unset ($_POST);	
		return $this->model_name;
	}
	
	public function edit_row($type, $id, $values){
		$this->set_query_data($type);		
		$data = '';
		foreach ($values as $key => $value){
			$data .= $key." = '".$value."', ";
		}
		$num = strlen($data) - 2;
		$data = stripslashes(substr($data, 0, $num));
		
		// In case of JOINing beats and ratings tables, this function must have been modified
		if ($this->table == 'ratings'){
			$this->query("UPDATE ".$this->table." SET ".$data." WHERE id_rate = $id");
		}else{
			$this->query("UPDATE ".$this->table." SET ".$data." WHERE id = $id");
		}
		if ($this->resultSet(true)){
			unset ($_POST);
			$_SESSION['p_info'] = 'Edycja przebiegła pomyślnie.';
			return $this->model_name;
		}else{
			$_SESSION['e_info'] = 'Wystąpił błąd przy edycji danych. Spróbuj ponownie.';
			return $this->model_name;
		}
	}
	
	public function delete_row($type, $id){
		$this->set_query_data($type);
		
		$this->query('DELETE FROM '.$this->table.' WHERE id = '.$id);
		if ($this->resultSet(true)){
			unset ($_POST);
			$_SESSION['p_info'] = 'Pomyślnie usunięto z bazy.';
			return $this->model_name;
		}else{
			$_SESSION['e_info'] = 'Wystąpił błąd podczas usuwania. Spróbuj ponownie.';
			return $this->model_name;
		}
	}
	
	public function delete_file($type, $file){
		$this->set_query_data($type);		
		if (!unlink ($_SERVER['DOCUMENT_ROOT'].'/assets/'.$this->folder.'/'.$file)){
			$_SESSION['e_info'] = 'Nie udało się usunąć pliku.';
			$error = TRUE;
		}
		if (@$error){
			return FALSE;
		}else{ 
			return TRUE;
		}
	}
		
	private function set_query_data($type){
		switch ($type){
			case 'admin_artist':
				$this->table = 'artists';
				$this->model_name = 'Artists()';
				break;
			case 'admin_song':
				$this->table = 'songs';
				if ($this->album != 0){
					$this->folder = 'download/'.$this->album;
				}else{
					$this->folder = 'download';
				}
				$this->model_name = 'Songs()';
				break;
			case 'admin_album':
				$this->table = 'albums';
				$this->folder = 'download';
				$this->model_name = 'Albums()';
				break;
			case 'admin_beat':
				$this->table = 'beats';
				$this->folder = 'beats';
				$this->model_name = 'Beats()';
				break;
			case 'admin_articles':
				$this->table = 'news';
				$this->folder = 'articles';
				$this->model_name = 'Articles()';
				break;
			case 'admin_notification':
				$this->table = 'notifications';
				$this->model_name = 'Beats()';
				break;
			case 'user_confirmed':
				$this->table = 'artists';
				$this->model_name = 'Login()';
				break;
			case 'user_project':
				$this->table = 'projects';
				$this->folder = 'projects/'.@$_SESSION['id_project'];
				$this->model_name = 'Projects()';
				unset ($_SESSION['id_project']);
				break;
			case 'user_rate':
				$this->table = 'ratings';
				$this->model_name = 'Beats()';
				break;
		}		
	}
	
	public function set_artist_by_id($array){
		$i = 0;
		foreach ($this->$array as $row){
			if (isset($row['id_artist'])){
				$id_artist = $row['id_artist'];
			}else{
				$id_artist = $row['id'];
			}
			$this->query("SELECT * FROM artists WHERE id = '$id_artist'");
			$result = $this->resultSet();
			$this->$array[$i] += ['artist' => $result[0]['name']];
			$i++;
		}
	}
	public function return_artist_name($id){
		$this->query("SELECT * FROM artists WHERE id = $id");
		$row = $this->resultSet();
		return $row[0]['name'];
	}
	
	public function setPopularSongs($array, $artist = false){
			if ($artist){
				$this->query("SELECT * FROM songs WHERE id_artist = '$artist' ORDER BY downloadCount DESC, date_creation DESC LIMIT 8");
			}else{
				$this->query('SELECT * FROM songs ORDER BY downloadCount DESC, date_creation DESC LIMIT 3');
			}
			$this->$array = $this->resultSet();
	}
}
		
?>