<?php

include_once __DIR__ .'/inc/class/Tables.php';
$tables = new Tables();
DB::logado();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Backups por ferramenta</title>
	<?php include_once(__DIR__."/inc/header.php"); ?>
</head>
<body class="skin-blue fixed sidebar-mini">
	<div class="wrapper">

		<?php include_once(__DIR__."/inc/sidebar.php"); ?>

		<div class="content-wrapper" style="min-height: 900px">
			<section class="content">
				<?php $tables->Tools(); ?>
			</section>
		</div>
	</div>
	<script type="text/javascript">
		document.getElementById('tabelas-menu').classList.add("active");
	</script>
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="dist/js/app.min.js"></script>
</body>
</html>