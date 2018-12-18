<?php
class Music extends Controller{
	protected function Index(){
		$viewmodel = new MusicModel();
		$this->returnView($viewmodel->Index(), true);
	}
	
	public function Song(){
		$viewmodel = new MusicModel();
		$this->returnView($viewmodel->Song(basename(ROOT_URL.$_SERVER['REQUEST_URI'])), true);
	}
	
	public function Album(){
		$viewmodel = new MusicModel();
		$this->returnView($viewmodel->Album(basename(ROOT_URL.$_SERVER['REQUEST_URI'])), true);
	}
	
	public function Artist(){
		$viewmodel = new MusicModel();
		$this->returnView($viewmodel->Artist(basename(ROOT_URL.$_SERVER['REQUEST_URI'])), true);
	}
}
?>