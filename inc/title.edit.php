<?php
include_once __DIR__ .'/../inc/class/Query.php';
DB::logado();

$action = new Query();

$titleInfo = [];

//Caso possua todos os parametros para o registro ira registrar
if (isset($_POST['title']) &&
	isset($_POST['tool']) &&
	isset($_POST['hardware'])
	){
		$action->editTitle($_POST);
	}

if (isset($_POST['tool-name'])) {
	$action->registerTool($_POST);
}

if (isset($_POST['excluir'])) {
	$location = "location: /".FOLDER_NAME."/title.php?delete=true&error=";

	$result = $action->delete("backup_title","id = ".$_POST['excluir']);

	if($result == true){
		header($location."success");
	}elseif ($result == false) {
		header($location."has_reports");
	}
}

if (isset($_GET['id'])) {
	$titleInfo = $action->titleInfo($_GET['id']);
}else{
	header("location: /".FOLDER_NAME."/title.php?edit=false&error=no_id");
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Titulo</title>
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
								<h3 class="box-title">Titulo: <b>Novo</b></h3>
							</div>
							<div class="box-body">
								<form class="form-horizontal" method="post">
									<div class="box-body">
										<div class="form-group">
											<label class="col-sm-1 control-label">Titulo</label>
											<div class="col-sm-5">
												<input name="id" style="display: none;" value="<?=$_GET['id']?>">
												<input name="title" placeholder="Titulo" type="text" value="<?=$titleInfo['title']?>" class="form-control">
											</div>

											<label class="col-sm-1 control-label">Ferramenta</label>
											<div class="col-sm-5">
												<div class="input-group input-group">
													<select class="form-control" name="tool">
														<?php $action->selectTools($titleInfo['id_tools']); ?>
													</select>
													<span class="input-group-btn">
														<button type="button" data-toggle="modal" data-target="#registerToolModal" class="btn btn-default"><i class="fa fa-plus"></i></button>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-1 control-label">Equipamento</label>
											<div class="col-sm-5">
												<select class="form-control" name="hardware">
													<?php $action->selectHardwares($titleInfo['id_hardware']); ?>
												</select>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="box-tools pull-right">
											<button type="button" onclick="location.href='/<?=FOLDER_NAME?>/title.php'" class="btn btn-default">Cancelar</button>
											<button type="submit" class="btn btn-info pull-right">Editar</button>
										</div>
									</form>
									<form method="post">
										<div class="box-tools pull-left">
											<input type="text" name="excluir" value="<?=$_GET['id']?>" style="display: none;">
											<button type="submit" class="btn btn-danger pull-right">Excluir</button>
										</div>
									</form>
									</div>
								<form class="form-horizontal" method="post">
									<div class="modal fade" id="registerToolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title" id="myModalLabel">Cadastro de ferramenta</h4>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label class="col-sm-2 control-label">Ferramenta</label>
														<div class="col-sm-10">
															<input name="tool-name" placeholder="Ferramenta" type="text" class="form-control">
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													<button type="submit" class="btn btn-primary">Cadastrar</button>
												</div>
											</div>
										</div>
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
		document.getElementById('title-menu').classList.add("active");
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