<?php
include_once __DIR__ .'/../inc/class/Query.php';
DB::logado();

$action = new Query();

$costumerInfo = [
	'password'=>"",
	'name'=>"",
	'active'=>"",
	'tool'=>"",
	'id'=>""
];

//Caso possua todos os parametros para a edicao ira prosseguir
if (isset($_POST['name']) &&
	isset($_POST['password']) &&
	isset($_POST['id']) &&
	isset($_POST['active'])
	){
		$action->editCostumer($_POST);
	}

if (isset($_POST['excluir'])) {
	$location = "location: /".FOLDER_NAME."/costumers.php?delete=true&error=";

	$result = $action->delete("costumers","id = ".$_POST['excluir']);

	if($result == true){
		header($location."success");
	}elseif ($result == false) {
		header($location."has_hardwares");
	}
}

if (isset($_GET['id'])) {
	$costumerInfo = $action->CostumerInfo($_GET['id']);
}else{
	header("location: /".FOLDER_NAME."/costumers.php?edit=false&error=no_id");
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Usuarios</title>
	<?php include_once(__DIR__."/../inc/header.php"); ?>
</head>
<body class="skin-blue fixed sidebar-mini">
	<div class="wrapper">

		<?php include_once(__DIR__."/../inc/sidebar.php"); ?>

		<div class="content-wrapper" style="min-height: 900px">
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title">Cliente: <b><?=$costumerInfo['name']?></b></h3>
							</div>
							<div class="box-body">
								<form class="form-horizontal" method="post">
									<div class="box-body">
										<div class="form-group">
											<label class="col-sm-1 control-label">Nome</label>
											<div class="col-sm-11">
												<input name="id" style="display: none;" value="<?=$costumerInfo['id']?>">
												<input name="name" placeholder="Cliente" type="text" class="form-control" value="<?=$costumerInfo['name']?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-1 control-label">Senha</label>

											<div class="col-sm-11">
												<div class="input-group input-group">
												<input autocomplete="new-password" placeholder="Senha" type="password" name="password" class="form-control" id="password" value="<?=$costumerInfo['password']?>">
												<span class="input-group-btn">
													<button type="button" onclick="changeIconPassword()" class="btn btn-default"><i id="seePassword" class="fa fa-eye-slash"></i></button>
												</span>
											</div>
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-1 control-label">Ativo</label>
											<div class="col-sm-5">
												<select class="form-control" name="active">
													<?php 
													if ($costumerInfo['active'] == 0) {
														echo '
														<option value="0" selected>Não</option>
														<option value="1">Sim</option>
														';
													}elseif($costumerInfo['active'] == 1){
														echo '
														<option value="0">Não</option>
														<option value="1" selected>Sim</option>
														';
													} 
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="box-tools pull-right">
											<button type="button" onclick="location.href='/<?=FOLDER_NAME?>/costumers.php'" class="btn btn-default">Cancelar</button>
											<button type="submit" class="btn btn-info pull-right">Alterar</button>
										</div>
									</form>
									<form method="post">
										<div class="box-tools pull-left">
											<input type="text" name="excluir" value="<?=$costumerInfo['id']?>" style="display: none;">
											<button type="submit" class="btn btn-danger pull-right">Excluir</button>
										</div>
									</form>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<script type="text/javascript">
		document.getElementById('costumers-menu').classList.add("active");
		var x = 0;

		function changeIconPassword(){
			var element = document.getElementById('seePassword');
			if (x == 0) {
				element.classList.remove("fa-eye-slash");
				element.classList.add("fa-eye");
				document.getElementById('password').type = "text";
				x = 1;
			}else{
				element.classList.remove("fa-eye");
				element.classList.add("fa-eye-slash");
				document.getElementById('password').type = "password";
				x = 0;
			}
		}
	</script>
	<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../dist/js/app.min.js"></script>
</body>
</html>