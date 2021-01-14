<?php 

class Alert{
	
	public function message($type, $message){
		switch ($type) {
			case 'error':
				return "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-warning'></i> Erro!</h4>
					".$message."
				</div>";
				break;
			case 'warning':
				return "<div class='alert alert-warning alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-exclamation'></i> Alerta!</h4>
					".$message."
				</div>";
				break;
			case 'success':
				return "<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><h4><i class='icon fa fa-check'></i> Sucesso!</h4>
					".$message."
				</div>";
				break;
			case 'info':
				break;
		}
	}
}
