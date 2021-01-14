<?php
include_once __DIR__ .'/../inc/class/Query.php';
DB::logado();

$action = new Query();

//Caso possua todos os parametros para o registro ira registrar
if (isset($_POST['name']) &&
	isset($_POST['active'])
	){
		$action->registerCostumer($_POST);
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
								<h3 class="box-title">Cliente: <b>Novo</b></h3>
							</div>
							<div class="box-body">
								<form class="form-horizontal" method="post">
									<div class="box-body">
										<div class="form-group">
											<label class="col-sm-1 control-label">Nome</label>
											<div class="col-sm-5">
												<input type="text" name="name" class="form-control" placeholder="Nome" required="">
											</div>
											<label class="col-sm-1 control-label">Senha</label>
											<div class="col-sm-5">
												<input type="password" name="password" class="form-control" placeholder="Senha">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-1 control-label">Ativo</label>
											<div class="col-sm-5">
												<select class="form-control" name="active">
													<option value="0">Não</option>
													<option value="1">Sim</option>
												</select>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="box-tools pull-right">
											<button type="button" onclick="location.href='/<?=FOLDER_NAME?>/costumers.php'" class="btn btn-default">Cancelar</button>
											<button type="submit" class="btn btn-success pull-right">Cadastrar</button>
										</div>
									</form>
								</div>
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