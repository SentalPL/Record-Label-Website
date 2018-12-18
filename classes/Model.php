<?php
abstract class Model extends ProvenUsefulFunctions{
	protected $dbh;
	protected $stmt;
	
	public function __construct(){
		$this->dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS,
										array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", "SET CHARACTER_SET utf8_polish_ci"));
	}
	
	public function query($query){
		$this->stmt = $this->dbh->prepare($query);
	}	
	
	public function bind($param, $value, $type = null){
		if (is_null($type)){
			switch (true){
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
					default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}
	
	public function execute(){

$url = $_SERVER['REQUEST_URI'];
if (preg_match('@(\?fbclid=)@', $url)){
	$url = strstr($url, '?fbclid=', true);
	?><script><?php echo("location.href = '".$url."'");?></script><?php
	exit();
}

		$this->stmt->execute();
	}
	
	public function resultSet($boolean = false){
		$this->execute();
		if ($boolean == true){
			return $this->stmt;
		}else{
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	public function lastInsertId(){
		return $this->dbh->lastInsertId();
	}
	
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function addData($type, $data){
		if (!is_array ($data)){
			return 'Data must be stored in array.';
		}
		switch ($type){
			case 'beat':
				$name = $data['name'];
				$producer = $data['producer'];
				
			default:
				return 'Unknown type of data.';
				break;
		}
	}
	
	public function accessCheckSession($access){
		switch ($access){		
			case 'admin':			
				if (!isset ($_SESSION['admin'])){
					header ('Location: '.ROOT_URL.'admin/login');
					return;
				}
				break;
				
			case 'user':			
				if (!isset ($_SESSION['user'])){
					header ('Location: '.ROOT_URL.'user/login');
					return;
				}
				break;
		}
	}
	
	public function tokenCheck($location){
			if (@$_SESSION['token'] != @$_POST['token']){
				$_SESSION['token'] = $_POST['token'];
			}else{
				unset ($_SESSION['token']);
				header ('Location: '.ROOT_URL.$location);
				exit();
			}
	}
	
}
?>