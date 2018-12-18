<?php
class Contact extends Controller{
	protected function Index(){
		$viewmodel = new ContactModel();
		$this->returnView($viewmodel->Index(), true);
	}
}
?>