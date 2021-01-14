<?php 

include_once __DIR__ .'/DB.php';

class Tables extends DB{
	
	//Cria uma tabela por aplicativo
	private function TabelaAplicativo($x){
		$i=1;
		
		$sql = $this->conn->prepare("select * from (
			select backup_report.id, backup_report.date, hardwares.name as hardware_name,hardwares.id as hardware_id, status.name as status, costumers.name as cliente, tools.name as ferramenta from backup_report
				left join status on backup_report.id_status = status.id
				left join backup_log on backup_log.id = backup_report.id_backupLog
				left join backup_title on backup_title.id = backup_log.id_title
				left join hardwares on backup_title.id_hardware = hardwares.id
				left join costumers on hardwares.id_costumer = costumers.id
				left join tools on  tools.id = backup_title.id_tools order by date DESC limit 3000) a
				where ferramenta = :ferramenta group by hardware_id");
		$sql->bindParam("ferramenta", $x);
		$sql->execute();
		
		$result = $sql->fetchall(PDO::FETCH_ASSOC);
		
		if ($result) {
			foreach($result as $row){
				
				echo "<tr>";
				echo "<td>".$i++."</td>";
				echo "<td>".$row["cliente"]."</td>";
				echo "<td>".$row["hardware_name"]."</td>";
				
				echo $this->switchStatus($row["status"]);

				$datanova = str_replace([" ","-"],":",$row["date"]);
				$datanova = explode(":",$datanova);
				$data = $datanova[3].":".$datanova[4]." ".$datanova[2]."/".$datanova[1]."/".$datanova[0];

				echo "<td>".$data."</td>";
				echo "</tr>";
			}
		}
	}

	//Switch para escrever as tags de exito, posteriormente sera convertido para o usuario poder alterar a cor da tag.
	private function switchStatus($x){
		$erro = "<td><span class='label bg-red'>".$x."</span></td>";
		
		switch($x){
			case "Erro":
				return $erro;
				break;
			case "Exito":
				return "<td><span class='label bg-green'>".$x."</span></td>";
				break;
			case "Aviso":
				return "<td><span class='label bg-warning'>".$x."</span></td>";
				break;
			case "Sem HDD":
				return $erro;
				break;
			case "Cheio":
				return $erro;
				break;
			case "Log ausente":
				return $erro;
				break;
		}
	}

	//Cria uma tabela para cada aplicativo encontrado
	public function Tools(){
		$sql = $this->conn->prepare("select * from tools order by name");
		$sql->execute();

		$result = $sql->fetchall(PDO::FETCH_ASSOC);

		if ($result) {
			foreach ($result as $row) {
				echo "<div class='col-md-6'>
						<div class='box box-success'>
							<div class='box-header with-border'>
								<h3 class='box-title'>".$row['name']."</h3>
								<div class='box-tools pull-right'><!--Inserir botão de gear aqui -->
									<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
									</button>
									<button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
								</div>
							</div>
							<div class='box-body'>
								<table class='table table-bordered'>
									<tbody>
										<tr>
											<th style='width: 10px'>#</th>
											<th>Empresa</th>
											<th>Nome equipamento</th>
											<th>Status</th>
											<th style='width: 120px'>Data</th>
										</tr>";
											$this->TabelaAplicativo($row['name']);
							echo "</tbody>
								</table>
							</div>
						</div>
					</div>";
			}
		}
	}

	//Tabela criada dentro da pagina Clientes
	public function TableCostumers(){
		$sql = $this->conn->prepare("SELECT id_costumer, count(*) as quantidade from hardwares group by id_costumer");
		$sql->execute();

		$result = $sql->fetchall(PDO::FETCH_ASSOC);
		
		if ($result) {
			foreach($result as $row) {
				$hardwaresCount[$row['id_costumer']]=$row['quantidade'];
			}
		}

		$sql = $this->conn->prepare("SELECT id, name, active, date_register, date_update from costumers");
		$sql->execute();

		$result = $sql->fetchall(PDO::FETCH_ASSOC);
		
		if ($result) {
			foreach($result as $row) {
				echo "<tr><td>".$row["id"]."</td><td>";
				echo "<a href='inc/costumers.edit.php?id=".$row["id"]."'>".$row['name']."</a>";
				echo "</td><td>";
				if (isset($hardwaresCount[$row['id']])) {
					echo $hardwaresCount[$row['id']];
				}else{
					echo "0";
				}
				echo "</td><td>";
				echo $row["active"] ? "Sim" : "Não";
				echo "</td><td>".$row["date_register"]."</td>";
				echo "<td>".$row["date_update"]."</td></tr>";
			}
		}
	}

	//Tabela criada dentro da pagina Equipamentos
	public function TableHardwares(){
		$i=0;
			
		$sql = $this->conn->prepare("
			SELECT hardwares.id, hardwares.id_costumer, hardwares.id_type, hardwares.name, hardwares.date_register, hardwares.date_update, types.name as tipo, costumers.name as cliente from hardwares
			left join types on types.id = hardwares.id_type
			left join costumers on costumers.id = hardwares.id_costumer
			");
		$sql->execute();

		$result = $sql->fetchall(PDO::FETCH_ASSOC);
		
		if ($result) {
			foreach($result as $row) {
				echo "<tr>";
				echo "<td>".$row["id"]."</td>";
				echo "<td><a href='hardwares.history.php?id=".$row["id"]."'>".$row['name']."</a></td>";
				echo "<td>".$row["cliente"]."</td>";
				echo "<td>".$row["tipo"]."</td>";
				echo "<td>".$row["date_register"]."</td>";
				echo "<td>".$row["date_update"]."</td>";
				echo "</tr>";
				$i++;
			}
		}
	}

	//Tabela criada dentro da pagina inicial
	public function TabelaInicial(){
		global $db;
		$sql = $this->conn->prepare("select * from (
			select hardwares.id as hardwares_id, status.name as status,  tools.name as ferramenta from backup_report
				left join status on backup_report.id_status = status.id
				left join backup_log on backup_log.id = backup_report.id_backupLog
				left join backup_title on backup_title.id = backup_log.id_title
				left join hardwares on backup_title.id_hardware = hardwares.id
				left join costumers on hardwares.id_costumer = costumers.id
				left join tools on  tools.id = backup_title.id_tools
			order by backup_report.date DESC limit 3000) a group by hardwares_id");
		$sql->execute();

		$result = $sql->fetchall(PDO::FETCH_ASSOC);

		$array = array();

		if ($result) {
			foreach($result as $row) {
				if (array_key_exists($row['ferramenta'], $array)) {
					if (array_key_exists($row['status'], $array[$row['ferramenta']])) {
						$array[$row['ferramenta']][$row['status']]++;
					}else{
						$array[$row['ferramenta']][$row['status']]=1;
					}
				}else{
					$array[$row['ferramenta']][$row['status']] = 1;
				}
			}
		}
		return $array;
	}

	//Tabela criada quando clica em algum equipamento
	public function TabelaHistory($id){
		$i=1;
		
		$sql = $this->conn->prepare("select * from (
			select backup_report.id, backup_report.date, costumers.id as costumer_id, backup_log.log, hardwares.name as hardware_name,hardwares.id as hardware_id, status.name as status, costumers.name as cliente, tools.name as ferramenta, backup_title.title from backup_report
				left join status on backup_report.id_status = status.id
				left join backup_log on backup_log.id = backup_report.id_backupLog
				left join backup_title on backup_title.id = backup_log.id_title
				left join hardwares on backup_title.id_hardware = hardwares.id
				left join costumers on hardwares.id_costumer = costumers.id
				left join tools on  tools.id = backup_title.id_tools order by date DESC limit 3000) a
				where hardware_id = :id");
		$sql->bindParam("id", $id);
		$sql->execute();
		
		$result = $sql->fetchall(PDO::FETCH_ASSOC);
		
		if ($result) {
			foreach($result as $row){
				
				echo "<tr>";
				echo "<td>".$i++."</td>";
				echo $this->switchStatus($row["status"]);
				echo "<td>".$row["title"]."</td>";
				echo "<td>".$row["ferramenta"]."</td>";
				echo "<td>".nl2br($row["log"])."</td>";

				$datanova = str_replace([" ","-"],":",$row["date"]);
				$datanova = explode(":",$datanova);
				$data = $datanova[3].":".$datanova[4]." ".$datanova[2]."/".$datanova[1]."/".$datanova[0];

				echo "<td>".$data."</td>";
				echo "</tr>";
			}
		}
	}

	//Tabela criada de titulos
	public function TableTitles(){
		$i=1;
		
		$sql = $this->conn->prepare("
			SELECT backup_title.title, backup_title.id, hardwares.name as hardware_name, costumers.name as empresa, backup_title.date, tools.name as ferramenta FROM `backup_title`
				left join hardwares on hardwares.id = backup_title.id_hardware
				left join costumers on costumers.id = hardwares.id_costumer
				left join tools on tools.id = backup_title.id_tools
				");
		$sql->execute();
		
		$result = $sql->fetchall(PDO::FETCH_ASSOC);
		
		if ($result) {
			foreach($result as $row){
				echo "<tr>";
				echo "<td>".$i++."</td>";
				echo "<td><a href='inc/title.edit.php?id=".$row['id']."'>".$row["title"]."</a></td>";
				echo "<td>".$row["empresa"]."</td>";
				echo "<td>".$row["ferramenta"]."</td>";
				echo "<td>".$row["hardware_name"]."</td>";

				$datanova = str_replace([" ","-"],":",$row["date"]);
				$datanova = explode(":",$datanova);
				$data = $datanova[3].":".$datanova[4]." ".$datanova[2]."/".$datanova[1]."/".$datanova[0];

				echo "<td>".$data."</td>";
				echo "</tr>";
			}
		}
	}
}
