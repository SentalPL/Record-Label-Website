<?php
class NewsModel extends MainOperations{
	
	private $news = array();
	private $newSongs = array();
	private $randomSong = array();
	
	public function Index(){
		$this->setNews();
		$this->setNewSongs();
		$this->setRandomSong();
		return array($this->news, $this->newSongs, $this->randomSong);
	}
	
	public function View($id){
		$this->query("UPDATE news SET views = views + 1");
		$this->resultSet();
		$this->query("SELECT * FROM news WHERE id = $id");
		$article = $this->resultSet();
		
		$tags = explode(', ', $article[0]['tags']);
		$similar = array();
		foreach ($tags as $tag){
			$this->query("SELECT * FROM news WHERE id != $id AND tags LIKE '%$tag%'");
			$row = $this->resultSet();
			if (!in_array(@$row[0], $similar)){
				$similar = array_merge($similar, $this->resultSet());
			}
		}
		
		return array ($article, $similar);
	}
	
	private function setNewSongs($last3songs = false){	
		$this->query("SELECT * FROM songs ORDER BY date_creation DESC LIMIT 5");
		$this->newSongs = $this->resultSet();
		
		// Przypisanie nazwy artysty do id_artist w celu wyświetlenia
		// na stronie głównej
		$i = 0;
		foreach ($this->newSongs as $song){
			$id_artist = $song['id_artist'];
			$this->query("SELECT name FROM artists WHERE id = '$id_artist'");
			$row = $this->resultSet();
			$this->newSongs[$i]['id_artist'] = $row[0]['name'];
			$i++;
		}
	}
	
	private function setRandomSong(){
		$this->query("SELECT * FROM songs WHERE youtube != '' ORDER BY RAND() LIMIT 1");
		$this->randomSong = $this->resultSet();

		if (!empty($this->randomSong)){
			$id_artist = $this->randomSong[0]['id_artist'];
			$this->query("SELECT name FROM artists WHERE id = '$id_artist'");
			$row = $this->resultSet();
			$this->randomSong[0]['id_artist'] = $row[0]['name'];
		}else{
			$this->randomSong = FALSE;
		}
	}

	private function setNews(){
		$this->query("SELECT * FROM news ORDER BY date_creation DESC LIMIT 4");
		$this->news = $this->resultSet();
	}
}
?>