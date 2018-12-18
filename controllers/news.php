<?php
class News extends Controller{
	protected function Index(){
		$viewmodel = new NewsModel();
		$this->returnView($viewmodel->Index(), true);
	}
	
	public function View(){
		$viewmodel = new NewsModel();
		$this->returnView($viewmodel->View(basename(ROOT_URL.$_SERVER['REQUEST_URI'])), true);
	}
}
?>