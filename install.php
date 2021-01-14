<?php
$dbhost = $dbuser = $dbpass = $options = '';

if (isset($_POST['dbhost']) && isset($_POST['dbuser']) && isset($_POST['dbname'])) {

	$fp = fopen('config/db_config.php', 'w+');
	$text = '<?php

class Connection{
	public $dbhost = "'.$_POST['dbhost'].'";
	public $dbuser = "'.$_POST['dbuser'].'";
	public $dbpassword = "'.$_POST['dbpassword'].'";
	public $dbname = "'.$_POST['dbname'].'";
}
';
	fwrite($fp, $text);
	fclose($fp);
	echo("<script>location.href = 'index.php';</script>");
}elseif(isset($_POST['dbhost']) || isset($_POST['dbuser']) || isset($_POST['password'])){

	$dbhost = $_POST['dbhost'];
	$dbuser = $_POST['dbuser'];
	$dbpass = $_POST['dbpassword'];
	
	try{
		$conn = new PDO("mysql:charset=utf8;host=".$dbhost,$dbuser,$dbpass);			
	} catch (Exception $e) {
		echo("<script>location.href = 'install.php?invalidLogin';</script>");
	}

	$sql = $conn->prepare('show DATABASES;');
	$sql->execute();
	$result = $sql->fetchAll(PDO::FETCH_ASSOC);

	if ($result) {
		foreach ($result as $row) {
			$exclude = array('information_schema','mysql','performance_schema','phpmyadmin');
			if (!in_array($row['Database'], $exclude)) {
				$options .= "<option value='".$row['Database']."'>".$row['Database']."</option>";
			}
		}
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
							Login, senha ou servidor invalidos!
						</div>
					</div>
				</div>
			</div>
		</form>';
}

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
				<input type="text" class="form-control" placeholder="Servidor" value="<?=$dbhost?>" name="dbhost">
				<span class="fa fa-server form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" class="form-control" placeholder="Usuario" value="<?=$dbuser?>" name="dbuser">
				<span class="fa fa-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" class="form-control" placeholder="Senha" value="<?=$dbpass?>" name="dbpassword">
				<span class="fa fa-lock form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<select class="form-control" name="dbname">
					<?=$options?>
				</select>
			</div>			
			<div class="row">
				<div class="col-xs-8">
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