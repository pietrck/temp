<?php
require_once __DIR__."/../../config/db_config.php";

class DB extends Connection{

	public $conn;
	public $connGlpi;

	public function __construct(){
		 $this->conn = $this->OpenCon();
	}

	public function OpenCon(){
		try{
			$conn = new PDO("mysql:dbname=".$this->dbname.";charset=utf8;host=".$this->dbhost,$this->dbuser,'');
		}catch(PDOException $e){
			print "Erro!: " . $e->getMessage() . "<br/>";
			die();
		}
		
		return $conn;
	}

	//Login do usuario com base no banco
	public function LoginUsuario($x){
		$sql = $this->conn->prepare("SELECT `password` FROM users WHERE `name` = :usuario;");
		$user = $x['user'];
		$sql->bindParam('usuario',$user);
		$sql->execute();
		$result = $sql->fetchall(PDO::FETCH_ASSOC);

		if ($result) {
			foreach($result as $row) {
				if (password_verify ($x['password'], $row["password"])){
					$_SESSION['usuario']=$user;
					$_SESSION['logado']="sim";
					echo("<script>location.href = '/".FOLDER_NAME."/home.php';</script>");
				}else {
					echo("<script>location.href = '/".FOLDER_NAME."/index.php?invalidLogin';</script>");
				}
			}
		}else{
			echo("<script>location.href = '/".FOLDER_NAME."/index.php?invalidLogin';</script>");
		}
	}

	static function logado(){
		session_start();
		if(!isset($_SESSION["logado"])){
			echo("<script>location.href = 'index.php';</script>");
		}
	}
}
