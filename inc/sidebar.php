<?php 
if (isset($_GET['sair'])) {
	session_destroy();
	echo "<script>location.href='/".FOLDER_NAME."/index.php';</script>";
}

if (!defined('FOLDER_NAME')) {
	$x = explode("/", $_SERVER['SCRIPT_NAME']);
	define('FOLDER_NAME', $x[1]);
}

?>
<header class="main-header">
	<a href="home.php" class="logo">
		<span class="logo-mini"><b>B</b>C</span>
		<span class="logo-lg"><b>Backup</b>Control</span>
	</a>
	<nav class="navbar navbar-static-top" role="navigation">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav"><!--
				<li class="dropdown tasks-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-flag-o"></i>
						<span class="label label-danger">9</span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">9 Backups na ultima hora</li>
						<li>
							<ul class="menu">
								<li>
									<label>1</label>
								</li>
							</ul>
						</li>
						<li class="footer">
							<a href="#">Veja todos os backups</a>
						</li>
					</ul>
				</li>-->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="hidden-xs"><?=$_SESSION['usuario']?></span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-footer">
							<div class="pull-right">
								<input type="submit" name="sair" onclick="location.href='?sair=sair'" class="btn btn-danger btn-flat" value="Sair">
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<li class="header">MENU</li>
			<li id="home-menu">
				<a href="/<?=FOLDER_NAME?>/home.php">
					<i class="fa fa-home"></i>
					<span>Inicio</span>
				</a>
			</li>
			<li id="hardwares-menu">
				<a href="/<?=FOLDER_NAME?>/hardwares.php">
					<i class="fa fa-desktop"></i>
					<span>Equipamentos</span>
				</a>
			</li>
			<li id="costumers-menu">
				<a href="/<?=FOLDER_NAME?>/costumers.php">
					<i class="fa fa-user"></i>
					<span>Clientes</span>
				</a>
			</li>
			<li id="tabelas-menu">
				<a href="/<?=FOLDER_NAME?>/tools.php">
					<i class="fa fa-table"></i>
					<span>Backups</span>
				</a>
			</li>
			<li id="title-menu">
				<a href="/<?=FOLDER_NAME?>/title.php">
					<i class="fa fa-envelope-o"></i>
					<span>Titulos de e-mail</span>
				</a>
			</li>
			<li id="config-menu">
				<a href="/<?=FOLDER_NAME?>/config.php">
					<i class="fa fa-gear"></i>
					<span>Configurações</span>
				</a>
			</li>
		</ul>
	</section>
</aside>