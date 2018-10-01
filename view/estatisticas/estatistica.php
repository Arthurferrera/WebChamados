<?php
    require_once($_SESSION['require']."view/modulo.php");
    // require_once("../modulos.php");
    // autentica();
    // require_once("../controller/controllerChamado.php");
    require_once($_SESSION['require']."controller/controllerChamado.php");
    $conexao  = conexao();

    $chamado = new controllerChamado();
    $retornoEstatisticas = $chamado::Estatisticas();
    $totalChamados = $retornoEstatisticas[0];
    $resolvidos = $retornoEstatisticas[1];
    $pendentes = $retornoEstatisticas[2];
    $observacoes = $retornoEstatisticas[3];
?>

<section class="contentForm">
    <div class="tituloForm">
        Estatísicas
    </div>

    <div class="linhastatistica">
        <div class="labelEstatistica">
            Solicitações:
        </div>
        <div class="porcentagem">
            <?php echo $totalChamados ?>
        </div>
    </div>

    <div class="linhastatistica">
        <div class="labelEstatistica">
            Respondidas:
        </div>
        <div class="contentBarra">
            <div class="centralizarBarra">
                <div class="barraEstatistica" style="width: <?php echo $observacoes ?>%; background-color: #0000ff;">
                </div>
            </div>
        </div>
        <div class="porcentagem">
            <?php echo number_format($observacoes, 2, '.', ','); ?>%
        </div>
    </div>

    <div class="linhastatistica">
        <div class="labelEstatistica">
            Resolvidas:
        </div>
        <div class="contentBarra">
            <div class="centralizarBarra">
                <div class="barraEstatistica" style="width: <?php echo $resolvidos ?>%; background-color: #00ff00;">
                </div>
            </div>
        </div>
        <div class="porcentagem">
            <?php echo number_format($resolvidos, 2, '.', ','); ?>%
        </div>
    </div>

    <div class="linhastatistica">
        <div class="labelEstatistica">
            Pentendes:
        </div>
        <div class="contentBarra">
            <div class="centralizarBarra">
                <div class="barraEstatistica" style="width: <?php echo $pendentes ?>%; background-color: #ff0000;">
                </div>
            </div>
        </div>
        <div class="porcentagem">
            <?php echo number_format($pendentes, 2, '.', ','); ?>%
        </div>
    </div>

</section>
