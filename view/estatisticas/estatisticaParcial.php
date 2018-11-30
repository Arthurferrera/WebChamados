<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    require_once($_SESSION['require']."controller/controllerChamado.php");

    autentica();
    $conexao  = conexao();

    // pegando a data do dia atual
    date_default_timezone_set('America/Sao_Paulo');
    // definindo o formato da data
    $dateAtualInicio = date('Y-m-d');
    $dateAtualFim = date('Y-m-d');

    $chamado = new controllerChamado();

    if (isset($_POST['btnFiltrar'])) {
        $retornoEstatisticas = $chamado::parcialEstatistica("filtro");
    } else {
        $retornoEstatisticas = $chamado::parcialEstatistica("dia");
    }

    // chama o método que traz os valores das
    // Estatísicas e resgata os valores
    $totalChamados = $retornoEstatisticas[0];
    $resolvidos = $retornoEstatisticas[1];
    $pendentes = $retornoEstatisticas[2];
    $observacoes = $retornoEstatisticas[3];
?>

<!-- arquivos js, para o gráfico de pizza -->
<script src="js/Chart.bundle.js"></script>
<script src="js/utils.js"></script>

	<div class="card-body">
		<h1 class="card-title"><i class="menu-icon mdi mdi-chart-areaspline"></i> Estatísticas - Parciais</h1>
		<div class="contentFiltro">

        <div class="panel panel-info">
            <div class="panel-heading">Filtrar por período</div>
            <div class="panel-body">
				<form action="?pag=estatisticaParcial" method="post">
					<div class="form-group">
                        <div class="col-md-6">
                            <label class="col-md control-label" for="textinput">Início do período</label>
                            <input name="txtDtInicio" type="date" placeholder="Data inicial" class="inputData form-control input-md" value="<?php echo $dateAtualInicio; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="col-md control-label" for="textinput">Final do período</label>
                            <input id="textinput" name="txtDtFim" type="date" placeholder="Data Final" class="inputData form-control input-md" value="<?php echo $dateAtualFim; ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary" type="submit" name="btnFiltrar" value="filtrar" style="margin-top: 2%; margin-left: 1%;"><i class="mdi mdi-filter-outline"></i>Filtrar</button>
                    <!-- botão de impressão da lista -->
                    <!-- <button type="button" class="btn btn-primary" onclick="ImprimirLista();" name="btnImprimir" value="Imprimir" style="margin-top: 2%; margin-left: 1%;"><i class="mdi mdi-printer"></i>Imprimir</button>					 -->
				</form>
            </div>
        </div>

		<!-- total de chamdos -->
		<div class="linhaEstatistica linhaParcial">
			<div>
				<strong style="margin-right: 5px;">Total de Solicitações:</strong>
                <?php echo $totalChamados; ?>
			</div>
		</div>

		<!-- div que contém o gráfico -->
		<div id="canvas-holder" class="canvasParcial" style="width: 300px;">
			<canvas id="chart-area" width="300" height="300"></canvas>
			<div id="chartjs-tooltip">
				<table></table>
			</div>
		</div>
	</div>
	<script>
		Chart.defaults.global.tooltips.custom = function(tooltip) {
			// elemento tooltip
			var tooltipEl = document.getElementById('chartjs-tooltip');

			// Hide if no tooltip
			if (tooltip.opacity === 0) {
				tooltipEl.style.opacity = 0;
				return;
			}

			// Set caret Position
			tooltipEl.classList.remove('above', 'below', 'no-transform');
			if (tooltip.yAlign) {
				tooltipEl.classList.add(tooltip.yAlign);
			} else {
				tooltipEl.classList.add('no-transform');
			}

			function getBody(bodyItem) {
				return bodyItem.lines;
			}

			// Set Text
			if (tooltip.body) {
				var titleLines = tooltip.title || [];
				var bodyLines = tooltip.body.map(getBody);

				var innerHtml = '<thead>';

				titleLines.forEach(function(title) {
					innerHtml += '<tr><th>' + title + '</th></tr>';
				});
				innerHtml += '</thead><tbody>';

				bodyLines.forEach(function(body, i) {
					var colors = tooltip.labelColors[i];
					var style = 'background:' + colors.backgroundColor;
					style += '; border-color:' + colors.borderColor;
					style += '; border-width: 2px';
					var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
					innerHtml += '<tr><td>' + span + body + '</td></tr>';
				});
				innerHtml += '</tbody>';

				var tableRoot = tooltipEl.querySelector('table');
				tableRoot.innerHTML = innerHtml;
			}

			var positionY = this._chart.canvas.offsetTop;
			var positionX = this._chart.canvas.offsetLeft;

			// Display, position, and set styles for font
			tooltipEl.style.opacity = 1;
			tooltipEl.style.left = positionX + tooltip.caretX + 'px';
			tooltipEl.style.top = positionY + tooltip.caretY + 'px';
			tooltipEl.style.fontFamily = tooltip._bodyFontFamily;
			tooltipEl.style.fontSize = tooltip.bodyFontSize;
			tooltipEl.style.fontStyle = tooltip._bodyFontStyle;
			tooltipEl.style.padding = tooltip.yPadding + 'px ' + tooltip.xPadding + 'px';
		};

		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [<?php echo number_format($observacoes); ?>, <?php echo number_format($resolvidos); ?>, <?php echo number_format($pendentes); ?>],
					backgroundColor: [
						window.chartColors.blue,
						window.chartColors.green,
                        window.chartColors.red
					],
				}],
				labels: [
					'% Respondidas',
					'% Resolvidas',
					'% Pendentes'
				]
			},
			options: {
				responsive: true,
				legend: {
					display: false
				},
				tooltips: {
					enabled: false,
				}
			}
		};
        // ao carregar a pagina, faz o efeito do gráfico aparecendo
		window.onload = function() {
			var ctx = document.getElementById('chart-area').getContext('2d');
			window.myPie = new Chart(ctx, config);
		};
	</script>
