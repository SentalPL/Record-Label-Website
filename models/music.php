<?php
class MusicModel extends MainOperations{
	
	private $song = array();
	//private $songs = array();
	private $album = array();
	private $albums = array();
	private $artists = array();
	
	public $searchResult = array();
	
	private $newSongs = array();
	//private $popularSongs = array();
	
	public function Index(){
		if (isset($_POST['searchPhrase'])){
			$this->tokenCheck('music');
			$_SESSION['r_phrase'] = $_POST['searchPhrase'];
			$this->searchResult = $this->search('public_music', $_POST['searchPhrase']);
		}
		if(isset($_POST['artist_name'])){
			$this->back_block();
			$name = $_POST['artist_name'];
			$this->query("SELECT * FROM artists WHERE name = '$name' ORDER BY date_creation ASC");
			$artist = $this->resultSet();
			$this->setPopularSongs('searchResult', $artist[0]['id']);
			$this->setArtistById('searchResult');
			$i = 0;
			foreach ($this->searchResult as $result){
				$this->searchResult[$i] += ['type' => 'song'];
				$i++;
			}
		}
		$this->setNewSongs();
		$this->setPopularSongs('popularSongs');
		$this->setAlbums();
		$this->setArtists();
		
		

		$this->setArtistById('newSongs');
		$this->setArtistById('popularSongs');
		$this->setArtistById('albums');
		return array ($this->newSongs, $this->popularSongs,
							$this->albums, $this->artists, $this->searchResult);
	}
	
	public function Song($id){
		
		$this->query("SELECT * FROM songs WHERE id = $id");
		$this->song = $this->resultSet();
		
		if (isset($_POST['download'])){
			$this->back_block();
			$this->download($_POST['file']);
		}

		// Adding ARTIST name by ID_ARTIST to array
		$id = $this->song[0]['id_artist'];
		$this->query("SELECT * FROM artists WHERE id = $id");
		$artist = $this->resultSet();
		$this->song[0] += ['artist' => $artist[0]['name']];
		
		// Adding ALBUM name by ID_ALBUM to array
		if ($this->song[0]['id_album'] != 0){
			$id = $this->song[0]['id_album'];
			$this->query("SELECT * FROM albums WHERE id = $id");
			$album = $this->resultSet();
			$this->song[0] += ['album' => @$album[0]['title']];
		}
		
		$file = $this->song[0]['artist'].' - '.$this->song[0]['title'];
		if ($this->song[0]['featuring']){
			$file .= ' (feat. '.$this->song[0]['featuring'].')';
		}
		if ($this->song[0]['producer']){
			$file .= ' (prod. '.$this->song[0]['producer'].')';
		}
		$file .='.mp3';
		
		$path = 'assets/download/';
		if ($this->song[0]['id_album'] != 0){
			$path .= $this->song[0]['id_album'].'/';
		}
		$path = $path.$file;
		$size = filesize($path);

		
		// Converting size of file to display
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		$size = number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
		
		$this->song[0] += ['size' => $size];
		
		return $this->song;
	}
	
	public function Album($id){
		$this->query("SELECT * FROM albums WHERE id = $id");
		$this->album = $this->resultSet();
		
		if (isset($_POST['download'])){
			$this->back_block();
			$this->download($_POST['file']);
		}
		
		$this->query("SELECT * FROM songs WHERE id_album = $id ORDER BY date_creation ASC");
		$this->songs = $this->resultSet();
		
		$this->setArtistById('album');
		$this->setArtistById('songs');
		
		$file = $this->album[0]['artist'].' - '.$this->album[0]['title'].' ('.date('Y', strtotime($this->album[0]['date_creation'])).').rar';
		$path = 'assets/download/'.$file;
		$size = filesize($path);
		//die ($size);
		
		// Converting size of file to display
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		$size = number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
		
		$this->album[0] += ['size' => $size];
		
		return array($this->album[0], $this->songs);
	}
	
	public function Artist($id){
		$this->query("SELECT * FROM artists WHERE id = '$id'");
		$artist = $this->resultSet();
		$this->setAlbums($id);
		$this->setPopularSongs('popularSongs', $id);
		
		return array($artist[0], $this->albums, $this->popularSongs);
	}
	
	private function download($file){
		$filePath = 'assets/download/'.$file;

		if (file_exists($filePath)){
			$file = preg_replace('@^[0-9]+/@', '', $file, -1);
			
			header ("Cache-Control: public");
			header ("Content-Description: File Transfer");
			header ('Content-Disposition: attachment; filename="'.$file.'"');
			header ('Content-Type: application/zip');
			header ('Content-Transfer-Encoding: binary');
						
			readfile($filePath);
			
			// Increase download count in database
			// Album || Song
			if (preg_match('@.rar$@', $file)){
				$id = $this->album[0]['id'];
				$this->query("UPDATE albums SET downloadCount = downloadCount + 1 WHERE id = $id");
			}else{
				$id = $this->song[0]['id'];
				$this->query("UPDATE songs SET downloadCount = downloadCount + 1 WHERE id = $id");
			}
			$this->resultSet();
			
		}else{
			return 'Plik nie istnieje.';
		}
	}
	
	private function setNewSongs(){
		$this->query('SELECT * FROM songs ORDER BY date_creation DESC LIMIT 3');
		$this->newSongs = $this->resultSet();
	}

	private function setAlbums($artist = false){
		if ($artist){
			$this->query("SELECT * FROM albums WHERE id_artist = '$artist' ORDER BY date_creation ASC");
		}else{
			$date = date('Y-m-d');
			$this->query("SELECT * FROM albums WHERE date_creation < '$date' ORDER BY date_creation ASC");
		}
		$this->albums = $this->resultSet();
	}
	
	private function setArtists(){
		$this->query('SELECT * FROM artists ORDER BY date_creation ASC');
		$this->artists = $this->resultSet();
	}
	public function setArtistById($array){
			$i = 0;
			foreach ($this->$array as $song){
				$id_artist = $song['id_artist'];
				$this->query("SELECT name FROM artists WHERE id = '$id_artist'");
				$row = $this->resultSet();
				$this->$array[$i] += ['artist' => $row[0]['name']];
				$i++;
			}
		}
}
?>