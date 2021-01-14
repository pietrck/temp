<?php
include_once __DIR__ .'/inc/class/Tables.php';
DB::logado();

$tables= new Tables();
$geral = $tables->TabelaInicial();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<?php include_once(__DIR__."/inc/header.php"); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<?php include_once(__DIR__."/inc/sidebar.php"); ?>
		<div class="content-wrapper">
			<br>
			<div class="col-md-6">
				<div class="box">
					<div class="box-header with-border">
					  <h3 class="box-title">Backups Geral</h3>
					</div>
					<div class="box-body">
				  		<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 10px">#</th>
									<th>Ferramenta</th>
									<th>Progresso</th>
									<th style="width: 40px">Exitos</th>
									<th style="width: 40px">Erros</th>
								</tr>
								<?php
								$i=1; //incremento dos itens
								$totalExito = $totalErro = 0;

								//PARA CADA ITEM RETORNADO NO ARRAY DA FUNCAO TabelaInicialExito ELE IRA CRIAR UMA ROW NA TABELA POR FERRAMENTA
								foreach ($geral as $key => $value) {

									if (array_key_exists('Exito', $geral[$key])) {
										$exitos = $geral[$key]['Exito'];
										$totalExito += $exitos;
									}else{
										$exitos = 0;
									}

									if (array_key_exists('Erro', $geral[$key])) {
										$erros = $geral[$key]['Erro'];
										$totalErro += $erros;
									}else{
										$erros = 0;
									}

									$porcentagem = ($exitos / ($exitos + $erros)) * 100;
									$color = '';


									echo "<tr>
										<td>".$i++.".</td>
										<td>".ucfirst($key)."</td>
										<td>
											<div class='progress progress-xs'>";
									if ($porcentagem <= 50) {
										$color = 'danger';
									}elseif ($porcentagem >= 50 and $porcentagem <= 75) {
										$color = 'warning';
									}else{
										$color = 'success';
									}

									echo "<div class='progress-bar progress-bar-".$color."' style='width: ".$porcentagem."%'></div>";
									echo "</div>
										</td>
										<td><span class='badge bg-green'>".$exitos."</span></td>
										<td><span class='badge bg-red'>".$erros."</span></td>
									</tr>";
								}

								//IRA CRIAR UMA ROW COM O TOTAL
								$porcentagemTotal = ($totalExito / ($totalExito + $totalErro)) * 100;
								echo "
									<tr>
										<td>".$i++.".</td>
										<td>Total</td>
										<td>
											<div class='progress progress-xs'>";
									if ($porcentagemTotal <= 50) {
										$color = 'danger';
									}elseif ($porcentagemTotal >= 50 and $porcentagemTotal <= 75) {
										$color = 'warning';
									}else{
										$color = 'success';
									}

									echo "<div class='progress-bar progress-bar-".$color."' style='width: ".$porcentagemTotal."%'></div>";
									echo "</div>
										</td>
										<td><span class='badge bg-green'>".$totalExito."</span></td>
										<td><span class='badge bg-red'>".$totalErro."</span></td>
									</tr>";


								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="box box-primary">
            <div class="box-header with-border">
              <i class="fa fa-bar-chart-o"></i>

              <h3 class="box-title">Donut Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div id="donut-chart" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 509.5px; height: 300px;" width="509" height="300"></canvas><canvas class="flot-overlay" width="509" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 509.5px; height: 300px;"></canvas><span class="pieLabel" id="pieLabel0" style="position: absolute; top: 71px; left: 313.352px;"><div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">Series2<br>30%</div></span><span class="pieLabel" id="pieLabel1" style="position: absolute; top: 211px; left: 291.352px;"><div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">Series3<br>20%</div></span><span class="pieLabel" id="pieLabel2" style="position: absolute; top: 130px; left: 132.352px;"><div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">Series4<br>50%</div></span></div>
            </div>
            <!-- /.box-body-->
          </div>
			</div>
		</div>

	</div>
	<script type="text/javascript">
		document.getElementById('home-menu').classList.add("active");
	</script>
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="plugins/flot/jquery.flot.js"></script>
	<script src="plugins/flot/jquery.flot.pie.js"></script>
	<script src="plugins/flot/jquery.flot.resize.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="dist/js/app.min.js"></script>
<script>
  $(function () {

    /*
     * DONUT CHART
     * -----------
     */

    var donutData = [
      { label: 'Series2', data: 30, color: '#3c8dbc' },
      { label: 'Series3', data: 20, color: '#0073b7' },
      { label: 'Series4', data: 50, color: '#00c0ef' }
    ]
    $.plot('#donut-chart', donutData, {
      series: {
        pie: {
          show       : true,
          radius     : 1,
          innerRadius: 0.5,
          label      : {
            show     : true,
            radius   : 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    })
    /*
     * END DONUT CHART
     */

  })

  /*
   * Custom Label formatter
   * ----------------------
   */
  function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
      + label
      + '<br>'
      + Math.round(series.percent) + '%</div>'
  }
</script>
</body>
</html>