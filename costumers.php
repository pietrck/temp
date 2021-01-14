<?php
include_once __DIR__ .'/inc/class/Tables.php';
include_once __DIR__ .'/inc/class/Alert.php';
DB::logado();

$tables= new Tables();
$alerta = new Alert();

function alerta(){
	global $alerta;

	if (isset($_GET['register'])) {
		if ($_GET['error'] == 'success') {
			$message = "Usuario <b>".$_GET['name']."</b> cadastrado com sucesso!";
		}else{
			$message = "Erro ao cadastrar o usuario.";
		}

		echo $alerta->message($_GET['error'],$message);
	}
	if (isset($_GET['edit'])) {
		switch ($_GET['error']) {
			case 'success':
				echo $alerta->message('success', "Usuario <b>".$_GET['name']."</b> alterado com sucesso.");
				break;
			case 'error':
				echo $alerta->message('error', "Erro ao alterar o usuario <b>".$_GET['name']."</b>.");
				break;
			case 'no_id':
				echo $alerta->message('warning', 'Metodo de acesso invalido!');
				break;
		}
	}	
	if (isset($_GET['delete'])) {
		switch ($_GET['error']) {
			case 'success':
				echo $alerta->message('success', 'Usuario excluido com sucesso.');
				break;
			case 'error':
				echo $alerta->message('error', "Erro ao excluir o usuario.");
				break;
			case 'has_hardwares':
				echo $alerta->message('warning', 'Possui equipamento(s) cadastrado(s), exclua primeiro o(s) equipamento(s).');
				break;
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Clientes</title>
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
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title">Clientes</h3>
								<div class="box-tools pull-right">
									<button type="button" onclick="location.href='inc/costumers.register.php'" class="btn btn-success">Adicionar</button>
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-bordered">
									<tbody>
										<tr>
											<th style="width: 10px">ID</th>
											<th>Empresa</th>
											<th style="width: 10px">Equipamentos</th>
											<th>Ativo</th>
											<th>Data Registro</th>
											<th>Data Atualizado</th>
										</tr>
										<?php 
											$tables->TableCostumers();
										?>
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
		document.getElementById('costumers-menu').classList.add("active");
	</script>
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="dist/js/app.min.js"></script>
</body>
</html>