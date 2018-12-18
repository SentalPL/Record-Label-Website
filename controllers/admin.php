<?php
class Admin extends Controller{
	protected function Index(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Index(), true);
	}
	
	protected function Login(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Login(), true);
	}
	
	protected function Logout(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Logout(), true);
	}
	
	protected function Beats(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Beats(), true);
	}
	
	protected function Songs(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Songs(), true);
	}
	
	protected function Albums(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Albums(), true);
	}
	
	protected function Artists(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Artists(), true);
	}
	
	protected function Articles(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Articles(), true);
	}
	
	protected function Statistics(){
		$viewmodel = new AdminModel();
		$this->returnView($viewmodel->Statistics(), true);
	}
}
?>