<?php

include_once __DIR__ .'/inc/class/Tables.php';
include_once __DIR__ .'/inc/class/Query.php';
include_once __DIR__ .'/inc/class/Alert.php';
$tables = new Tables();

DB::logado();

$query = new Query();
$alerta = new Alert();

if (isset($_POST['email']) && 
	isset($_POST['passEmail'])
	){
		$query->editEmailConfig($_POST['email'], $_POST['passEmail']);
	}

function alerta(){
	global $alerta;

	if (isset($_GET['edit'])) {
		if ($_GET['error'] == 'success') {
			$message = "E-mail cadastrado com sucesso!";
		}else{
			$message = "Erro ao alterar e-mail.";
		}

		echo $alerta->message($_GET['error'],$message);
	}
}

$config = $query->emailConfig();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Configurações</title>
	<?php include_once(__DIR__."/inc/header.php"); ?>
</head>
<body class="skin-blue fixed sidebar-mini">
	<div class="wrapper">

		<?php include_once(__DIR__."/inc/sidebar.php"); ?>

		<div class="content-wrapper" style="min-height: 900px">
			<section class="content">				
				<div class="row">
					<div class="col-md-12">
						<?php alerta(); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<i class="fa fa-info-circle" title="Configuração utilizada para acessar o servidor de e-mail e contabilizar os backups"></i>
								<h3 class="box-title">Configuração do e-mail</b></h3>
							</div>
							<div class="box-body">
								<form class="form-horizontal" method="post" action="/<?=FOLDER_NAME?>/config.php">
									<div class="box-body">
										<div class="form-group">
											<label class="col-sm-1 control-label">E-mail</label>
											<div class="col-sm-5">
												<input name="email" id="emailInput" placeholder="E-mail" value="<?=$config['email']?>" type="text" class="form-control" disabled="">
											</div>


											<label class="col-sm-1 control-label">Senha</label>
											<div class="col-sm-5">
												<input name="id" value="" style="display: none;">
												<div class="input-group input-group">
													<input name="passEmail" id="passEmailInput" placeholder="Senha" value="<?=$config['passEmail']?>" type="password" class="form-control" disabled="">
													<span class="input-group-btn">
														<button type="button" onclick="changeIconPassword()" class="btn btn-default"><i id="seePassword" class="fa fa-eye-slash"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="box-tools pull-right" id="editBox">
											<button type="button" onclick="edit()" class="btn btn-danger" id="cancelButton">Cancelar</button>
											<button type="submit" class="btn btn-success pull-right" id="saveButton">Salvar</button>
										</div>
										<div class="box-tools pull-right">
											<button type="button" onclick="edit()" class="btn btn-info pull-right" id="editButton">Editar</button>
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
		document.getElementById('config-menu').classList.add("active");
		document.getElementById('editBox').classList.add("hidden");
		document.getElementById('saveButton').disabled = true;
		document.getElementById('cancelButton').disabled = true;

		var control = control2 = true;

		function edit(){
			if (control) {
				document.getElementById('editBox').classList.remove("hidden");
				document.getElementById('editButton').classList.add("hidden");
				document.getElementById('saveButton').disabled = false;
				document.getElementById('cancelButton').disabled = false;
				document.getElementById('emailInput').disabled = false;
				document.getElementById('passEmailInput').disabled = false;
				control = false;
			}else{
				document.getElementById('editBox').classList.add("hidden");
				document.getElementById('editButton').classList.remove("hidden");
				document.getElementById('saveButton').disabled = true;
				document.getElementById('cancelButton').disabled = true;				
				document.getElementById('emailInput').disabled = true;
				document.getElementById('passEmailInput').disabled = true;
				control = true;
			}
		}

		function changeIconPassword(){
			var element = document.getElementById('seePassword');
			if (control2) {
				element.classList.remove("fa-eye-slash");
				element.classList.add("fa-eye");
				document.getElementById('passEmailInput').type = "text";
				control2 = false;
			}else{
				element.classList.remove("fa-eye");
				element.classList.add("fa-eye-slash");
				document.getElementById('passEmailInput').type = "password";
				control2 = true;
			}
		}
	</script>
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="dist/js/app.min.js"></script>
</body>
</html>