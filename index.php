<?php

if (file_exists('config/db_config.php') && file_exists('install.php')) {
	unlink('install.php');
}elseif(!file_exists('config/db_config.php') && file_exists('install.php')){
	echo("<script>location.href = 'install.php';</script>");
}

session_start();

include __DIR__ .'/inc/class/DB.php';

if (!defined('FOLDER_NAME')) {
	$x = explode("/", $_SERVER['SCRIPT_NAME']);
	define('FOLDER_NAME', $x[1]);
}

function logado(){ if(isset($_SESSION["logado"])){
		echo("<script>location.href = '/".FOLDER_NAME."/home.php';</script>");
	}
}

if (isset($_GET['invalidLogin'])) {
	$modal = '<form class="form-horizontal">
			<div class="modal fade" id="invalidLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel"><b>Erro</b></h4>
						</div>
						<div class="modal-body">
							Login e/ou senha invalidos!
						</div>
					</div>
				</div>
			</div>
		</form>';
}

//Rotina de login
if (isset($_POST['user']) &&
	isset($_POST['password'])) {
	$banco = new DB();
	$conn = $banco->OpenCon();
	$banco->LoginUsuario($_POST);
}

logado();

?>
<html>
<head>
    <title>Backup Control - Login</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="plugins/iCheck/square/blue.css">
	<link rel="icon" href="dist/img/icon.png" type="image/gif" sizes="16x16">
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<div class="login-logo">
		<a href="index2.html"><b>Backup</b>Control</a>
	</div>
	<div class="login-box-body">
		<p class="login-box-msg">Faça login para iniciar a sessão</p>
		<form method="post">
			<div class="form-group has-feedback">
				<input type="text" class="form-control" placeholder="Usuario" name="user" required="">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" class="form-control" placeholder="Senha" name="password" required="">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
						</label>
					</div>
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="dist/js/app.min.js"></script>

<?php if (isset($modal)) {
	echo $modal;
	echo '<script type="text/javascript">
			$("#btnModal").ready(function(){
			    $("#invalidLoginModal").modal("show");
			});
		</script>';
} ?>
</body>
</html>