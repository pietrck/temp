<?php
include_once __DIR__ .'/inc/class/Tables.php';
include_once __DIR__ .'/inc/class/Query.php';
DB::logado();

$action = new Query();
$hardwareInfo = [];

if (isset($_POST['name']) &&
	isset($_POST['type']) &&
	isset($_POST['costumer'])
	){
		$action->editHardware($_POST);
	}
	
if (isset($_POST['excluir'])) {
	$location = "location: /".FOLDER_NAME."/hardwares.php?delete=true&error=";

	$result = $action->delete("hardwares","id = ".$_POST['excluir']);

	if($result == true){
		header($location."success");
	}elseif ($result == false) {
		header($location."has_reports");
	}
}

if (isset($_GET['id'])) {
	$hardwareInfo = $action->hardwareInfo($_GET['id']);
}else{
	header("location: /".FOLDER_NAME."/hardwares.php?edit=false&error=no_id");
}

$tables= new Tables();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Equipamentos</title>
	<?php include_once(__DIR__."/inc/header.php"); ?>
</head>
<body class="skin-blue fixed sidebar-mini">
	<div class="wrapper">

		<?php include_once(__DIR__."/inc/sidebar.php"); ?>

		<div class="content-wrapper" style="min-height: 900px">
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title">Equipamento: <b><?=$hardwareInfo['name']?></b> do cliente: <b><?=$hardwareInfo['cliente']?></b></h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<form method="post" class="form-horizontal">
									<div class="box-body">
										<div class="form-group">
											<label class="col-sm-1 control-label">Cliente</label>
											<div class="col-sm-5">
												<select class="form-control" name="costumer">
													<?php $action->selectCostumers($_GET['id']);?>
												</select>
											</div>

											<label class="col-sm-1 control-label">Tipo</label>
											<div class="col-sm-5">
												<div class="input-group input-group">
													<select class="form-control" name="type">
														<?php $action->selectTypes($hardwareInfo['tipo']); ?>
													</select>
													<span class="input-group-btn">
														<button type="button" data-toggle="modal" data-target="#registerTypeModal" class="btn btn-default"><i class="fa fa-plus"></i></button>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-1 control-label">Nome</label>
											<div class="col-sm-5">
												<input name="id" value="<?=$hardwareInfo['id']?>" style="display: none;">
												<input name="name" placeholder="Nome" value="<?=$hardwareInfo['name']?>" type="text" class="form-control">
											</div>
										</div>
									</div>

									<div class="box-footer">
										<div class="box-tools pull-right">
											<button type="button" onclick="location.href='/<?=FOLDER_NAME?>/hardwares.php'" class="btn btn-default">Cancelar</button>
											<button type="submit" class="btn btn-info pull-right">Editar</button>
										</div>
									</form>
									
									<form method="post">
										<div class="box-tools pull-left">
											<input type="text" name="excluir" value="<?=$hardwareInfo['id']?>" style="display: none;">
											<button type="submit" class="btn btn-danger pull-right">Excluir</button>
										</div>
									</form>
									</div>
							</div>
						</div>
						<div class='box box-success'>
							<div class='box-header with-border'>
								<h3 class='box-title'>Histórico de backup</h3>
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
											<th>Status</th>
											<th>Titulo</th>
											<th>Ferramenta</th>
											<th>Log</th>
											<th style='width: 120px'>Data</th>
										</tr>
										<?php $tables->TabelaHistory($_GET['id']); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<script type="text/javascript">
		document.getElementById('hardwares-menu').classList.add("active");
	</script>
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="dist/js/app.min.js"></script>
</body>
</html>