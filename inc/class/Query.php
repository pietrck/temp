<?php 

if (!defined('FOLDER_NAME')) {
	$x = explode("/", $_SERVER['SCRIPT_NAME']);
	define('FOLDER_NAME', $x[1]);
}

include_once __DIR__ .'/DB.php';

class Query extends DB{

	private function execute($select, $params = array()){
		$sql = $this->conn->prepare($select);
		foreach ($params as $param => $value) {
			$sql->bindParam($param,$value);
		}
		$sql->execute();
		$result = $sql->fetchall(PDO::FETCH_ASSOC);

		return $result;
	}

	/*Bloco das informações*/
	public function CostumerInfo(int $id){
		$params = ["id"=>$id];
		$return=[];

		$select = "SELECT costumers.id, costumers.name, costumers.password, costumers.active from costumers where costumers.id = :id";
		$result = $this->execute($select, $params);

		if ($result) {
			foreach ($result as $row) {
				$return["name"] = $row["name"];
				$return["password"] = $row["password"];
				$return["active"] = $row["active"];
				$return["id"] = $row["id"];
			}
		}
		return $return;
	}

	public function hardwareInfo(int $id){
		$params = ['id'=>$id];
		$return = [];

		$select = "SELECT hardwares.id, hardwares.name, types.id as tipo, costumers.name as cliente from hardwares 
			left join costumers on costumers.id = hardwares.id_costumer
			left join types on types.id = hardwares.id_type
			where hardwares.id = :id";
		$result = $this->execute($select, $params);

		if ($result) {
			foreach ($result as $row) {
				$return["name"] = $row["name"];
				$return["tipo"] = $row["tipo"];
				$return["cliente"] = $row["cliente"];
				$return["id"] = $row["id"];
			}
		}
		return $return;
	}

	public function titleInfo(int $id){
		$return = [];
		$params = ['id'=>$id];
		$select = "SELECT * from backup_title where id = :id";
		$result = $this->execute($select,$params);

		if ($result) {
			foreach ($result as $row) {
				$return["title"] = $row["title"];
				$return["id_hardware"] = $row["id_hardware"];
				$return["id_tools"] = $row["id_tools"];
			}
		}
		return $return;
	}
	/*Fim do bloco*/

	public function registerCostumer($post){
		$sql = $this->conn->prepare("
			insert into costumers (password, name, active, date_register, date_update) values (:password, :name, :active,  CURRENT_TIME,CURRENT_TIME);"
		);
		$sql->bindParam("name",$post['name']);
		$sql->bindParam("password",$post['password']);
		$sql->bindParam("active",$post['active']);

		if($sql->execute()){
			header("location: /".FOLDER_NAME."/costumers.php?register=true&error=success&name=".$costumer['realname']);
			exit;
		}else{
			header("location: /".FOLDER_NAME."/costumers.php?register=true&error=error2&name=".$costumer['realname']);
			exit;
		}
	}

	public function registerHardware($post){
		$sql = $this->conn->prepare("
			insert into hardwares (id_costumer, id_type, name, date_register) values (:costumer, :type, :name, CURRENT_TIME);"
		);
		$sql->bindParam("name",$post['name']);
		$sql->bindParam("costumer",$post['costumer']);
		$sql->bindParam("type",$post['type']);

		if($sql->execute()){
			header("location: /".FOLDER_NAME."/hardwares.php?register=true&error=success&name=".$_POST['name']);
			exit;
		}else{
			header("location: /".FOLDER_NAME."/hardwares.php?register=true&error=error_register&name=".$_POST['name']);
			exit;
		}
	}

	public function registerTool($post){
		$sql = $this->conn->prepare("
			insert into tools (name, date) values (:name, CURRENT_TIME);"
		);
		$sql->bindParam("name",$post['tool-name']);

		if($sql->execute()){
			return true;
		}else{
			return false;
		}
	}

	public function registerType($post){
		$sql = $this->conn->prepare("
			insert into types (name, date) values (:name, CURRENT_TIME);"
		);
		$sql->bindParam("name",$post['type-name']);

		if($sql->execute()){
			return true;
		}else{
			return false;
		}
	}

	public function registerTitle($post){
		$sql = $this->conn->prepare("
			insert into backup_title (title, id_hardware, id_tools, date) values (:title, :id_hardware, :id_tools, CURRENT_TIME);"
		);
		$sql->bindParam("title",$post['title']);
		$sql->bindParam("id_hardware",$post['hardware']);
		$sql->bindParam("id_tools",$post['tool']);

		if($sql->execute()){
			header("location: /".FOLDER_NAME."/title.php?register=true&error=success&name=".$_POST['title']);
			exit;
		}else{
			header("location: /".FOLDER_NAME."/title.php?register=true&error=error_register&name=".$_POST['title']);
			exit;
		}
	}

	public function editHardware($post){
		$sql = $this->conn->prepare("
			update hardwares set id_costumer = :costumer, id_type = :type, name = :name, date_update = CURRENT_TIME where id = :id;"
		);
		$sql->bindParam("name",$post['name']);
		$sql->bindParam("costumer",$post['costumer']);
		$sql->bindParam("type",$post['type']);
		$sql->bindParam("id",$post['id']);

		if($sql->execute()){
			header("location: /".FOLDER_NAME."/hardwares.php?edit=true&error=success&name=".$_POST['name']);
			exit;
		}else{
			header("location: /".FOLDER_NAME."/hardwares.php?edit=true&error=error_register&name=".$_POST['name']);
			exit;
		}
	}

	public function editCostumer($post){
		$sql = $this->conn->prepare("
			update costumers set password = :password, name = :name, active = :active, date_update = CURRENT_TIME where id = :id;"
		);
		$sql->bindParam("id",$post['id']);
		$sql->bindParam("name",$post['name']);
		$sql->bindParam("password",$post['password']);
		$sql->bindParam("active",$post['active']);

		if($sql->execute()){
			header("location: /".FOLDER_NAME."/costumers.php?edit=true&error=success&name=".$post['name']);
			exit;
		}else{
			header("location: /".FOLDER_NAME."/costumers.php?edit=true&error=error&name=".$post['name']);
			exit;
		}
	}

	public function editEmailConfig($email, $pass){
		$sqlEmail = $this->conn->prepare("update config_geral set valor_config = :email where nome_config = 'email';");
		$sqlEmail->bindParam("email",$email);

		if ($sqlEmail->execute()) {
			$updateEmail = true;
		}

		$sqlPass = $this->conn->prepare("update config_geral set valor_config = :passEmail where nome_config = 'passEmail';");
		$sqlPass->bindParam("passEmail",$pass);
		
		if ($sqlPass->execute()) {
			$updatePass = true;
		}

		if ($updateEmail && $updatePass) {
			header("location: /".FOLDER_NAME."/config.php?edit=true&error=success&config=email");
			exit;
		}else{
			header("location: /".FOLDER_NAME."/config.php?edit=true&error=error&config=email");
			exit;
		}
	}

	public function editTitle($post){
		$sql = $this->conn->prepare("
			update backup_title set title = :title, id_hardware = :id_hardware, id_tools = :id_tools, date = CURRENT_TIME where id = :id;"
		);
		$sql->bindParam("title",$post['title']);
		$sql->bindParam("id_hardware",$post['hardware']);
		$sql->bindParam("id_tools",$post['tool']);
		$sql->bindParam("id",$post['id']);

		if($sql->execute()){
			header("location: /".FOLDER_NAME."/title.php?edit=true&error=success&name=".$_POST['title']);
			exit;
		}else{
			header("location: /".FOLDER_NAME."/title.php?edit=true&error=error_register&name=".$_POST['title']);
			exit;
		}
	}

	public function selectTools($id){
		$select = "select * from tools order by name";
		$result = $this->execute($select);

		if ($result) {
			foreach ($result as $row) {
				if ($row['id'] == $id) {
					echo "<option value='".$row['id']."' selected>".$row['name']."</option>";
				}else{
					echo "<option value='".$row['id']."'>".$row['name']."</option>";
				}
			}
		}
	}

	public function selectTypes($id){
		$select = "select * from types order by name";
		$result = $this->execute($select);

		if ($result) {
			foreach ($result as $row) {
				if ($row['id'] == $id) {
					echo "<option value='".$row['id']."' selected>".$row['name']."</option>";
				}else{
					echo "<option value='".$row['id']."'>".$row['name']."</option>";
				}
			}
		}
	}

	public function selectCostumers($id){
		$select = "select costumers.id, hardwares.id as hardware_id, costumers.name from costumers left join hardwares on hardwares.id_costumer = costumers.id group by costumers.name order by costumers.name";
		$result = $this->execute($select);

		if ($result) {
			foreach ($result as $row) {
				if ($row['hardware_id'] == $id) {
					echo "<option value='".$row['id']."' selected>".$row['name']."</option>";
				}else{
					echo "<option value='".$row['id']."'>".$row['name']."</option>";
				}
			}
		}
	}

	public function selectHardwares($id = ''){
		$select = "select hardwares.name, hardwares.id, costumers.name as empresa from hardwares left join costumers on costumers.id = hardwares.id_costumer;";
		$result = $this->execute($select);

		if ($result) {
			foreach ($result as $row) {
				if ($row['id'] == $id) {
					echo "<option value='".$row['id']."' selected>".$row['empresa']." - ".$row['name']."</option>";
				}else{
					echo "<option value='".$row['id']."'>".$row['empresa']." - ".$row['name']."</option>";
				}
			}
		}
	}

	public function emailConfig(){
		$sql = $this->conn->prepare("Select * from config_geral where nome_config in ('email','passEmail');");
		$sql->execute();

		$result = $sql->fetchall(PDO::FETCH_ASSOC);

		$config = array();

		if ($result) {
			foreach ($result as $row) {
				$config[$row['nome_config']] = $row['valor_config'];
			}
		}

		return $config;
	}

	//Função de delete
	public function delete($where, $what){
		$sql = $this->conn->prepare("
			delete from {$where} where {$what};"
		);

		if($sql->execute()){
			return true;
		}else{
			if (strstr($sql->errorInfo()[2], 'Cannot delete or update a parent row: a foreign key')) {
				return false;
			}else{
				return 'error';
			}
		}
	}
}
