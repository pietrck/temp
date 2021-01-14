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
			$message = "Equipamento <b>".$_GET['name']."</b> cadastrado com sucesso!";
		}else{
			$message = "Erro ao cadastrar o equipamento.";
		}

		echo $alerta->message($_GET['error'],$message);
	}
	if (isset($_GET['edit'])) {
		switch ($_GET['error']) {
			case 'success':
				echo $alerta->message('success', "Equipamento <b>".$_GET['name']."</b> alterado com sucesso.");
				break;
			case 'error':
				echo $alerta->message('error', "Erro ao alterar o equipamento <b>".$_GET['name']."</b>.");
				break;
			case 'no_id':
				echo $alerta->message('warning', 'Metodo de acesso invalido!');
				break;
		}
	}		
	if (isset($_GET['delete'])) {
		switch ($_GET['error']) {
			case 'success':
				echo $alerta->message('success', 'Equipamento excluido com sucesso.');
				break;
			case 'error':
				echo $alerta->message('error', "Erro ao excluir o equipamento.");
				break;
			case 'has_reports':
				echo $alerta->message('warning', "Possui titulos de backup para esse equipamento.");
				break;
		}
	}
}
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
						<?php alerta(); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title">Equipamentos</h3>
								<div class="box-tools pull-right">
									<button type="button" onclick="location.href='inc/hardwares.register.php'" class="btn btn-success">Adicionar</button>
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
											<th>Nome</th>
											<th>Cliente</th>
											<th>Tipo</th>
											<th>Data Registro</th>
											<th>Data Atualizado</th>
										</tr>
										<?php 
											$tables->TableHardwares();
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
		document.getElementById('hardwares-menu').classList.add("active");
	</script>
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="dist/js/app.min.js"></script>
</body>
</html>