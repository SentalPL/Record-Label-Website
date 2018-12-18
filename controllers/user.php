<?php
class User extends Controller{
	protected function Index(){
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->Index(), true);
	}
	
	protected function Login(){
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->Login(), true);
	}
	
	protected function Logout(){
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->Logout(), true);
	}
	
	protected function Beats(){
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->Beats(), true);
	}
	
	protected function Projects(){
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->Projects(), true);
	}
	
	protected function Statistics(){
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->Statistics(), true);
	}
	
	protected function Settings(){
		$viewmodel = new UserModel();
		$this->returnView($viewmodel->Settings(), true);
	}
}
?>