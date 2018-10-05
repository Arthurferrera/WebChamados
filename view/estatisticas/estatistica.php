<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    autentica();
    require_once($_SESSION['require']."controller/controllerChamado.php");
    $conexao  = conexao();

    $chamado = new controllerChamado();
    $retornoEstatisticas = $chamado::Estatisticas();
    $totalChamados = $retornoEstatisticas[0];
    $resolvidos = $retornoEstatisticas[1];
    $pendentes = $retornoEstatisticas[2];
    $observacoes = $retornoEstatisticas[3];
?>
<script src="js/Chart.bundle.js"></script>
<script src="js/utils.js"></script>
<style>
	#canvas-holder {
		width: 100%;
		margin-top: 50px;
		text-align: center;
        margin-right: auto;
        margin-left: auto;
	}
	#chartjs-tooltip {
		opacity: 1;
		position: absolute;
		background: rgb(0, 0, 0);
		color: white;
		border-radius: 20px;
		-webkit-transition: all .1s ease;
		transition: all .1s ease;
		pointer-events: none;
		-webkit-transform: translate(-50%, 0);
		transform: translate(-50%, 0);
	}

	.chartjs-tooltip-key {
		display: inline-block;
		width: 10px;
		height: 10px;
		margin-right: 10px;
	}
</style>
<section class="contentForm">
    <div class="tituloForm">
        Estatísicas
    </div>

    <!-- <div class="linhastatistica">
        <div class="labelEstatistica">
            Solicitações:
        </div>
        <div class="porcentagem">
            <?php //echo $totalChamados ?>
        </div>
    </div>

    <div class="linhastatistica">
        <div class="labelEstatistica">
            Respondidas:
        </div>
        <div class="contentBarra">
            <div class="centralizarBarra">
                <div class="barraEstatistica" style="width: <?php //echo $observacoes ?>%; background-color: #0000ff;">
                </div>
            </div>
        </div>
        <div class="porcentagem">
            <?php //echo number_format($observacoes, 2, '.', ','); ?>%
        </div>
    </div>

    <div class="linhastatistica">
        <div class="labelEstatistica">
            Resolvidas:
        </div>
        <div class="contentBarra">
            <div class="centralizarBarra">
                <div class="barraEstatistica" style="width: <?php //echo $resolvidos ?>%; background-color: #00ff00;">
                </div>
            </div>
        </div>
        <div class="porcentagem">
            <?php //echo number_format($resolvidos, 2, '.', ','); ?>%
        </div>
    </div>

    <div class="linhastatistica">
        <div class="labelEstatistica">
            Pentendes:
        </div>
        <div class="contentBarra">
            <div class="centralizarBarra">
                <div class="barraEstatistica" style="width: <?php //echo $pendentes ?>%; background-color: #ff0000;">
                </div>
            </div>
        </div>
        <div class="porcentagem">
            <?php //echo number_format($pendentes, 2, '.', ','); ?>%
        </div>
    </div> -->

    <div id="canvas-holder" style="width: 300px;">
		<canvas id="chart-area" width="300" height="300"></canvas>
		<div id="chartjs-tooltip">
			<table></table>
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
					data: [<?php echo $totalChamados; ?>, <?php echo number_format($observacoes); ?>, <?php echo number_format($resolvidos); ?>, <?php echo number_format($pendentes); ?>],
					backgroundColor: [
						window.chartColors.yellow,
						window.chartColors.blue,
						window.chartColors.green,
                        window.chartColors.red
					],
				}],
				labels: [
                    'Total Solicitações',
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

		window.onload = function() {
			var ctx = document.getElementById('chart-area').getContext('2d');
			window.myPie = new Chart(ctx, config);
		};
	</script>

</section>
