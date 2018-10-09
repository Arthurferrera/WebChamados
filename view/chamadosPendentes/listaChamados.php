<?php
    // inicia a sessão, importa o arquivo e chama a função que valida a autenticação do usuario
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    autentica();
    // conexao com banco
    $conexao  = conexao();
    // pegando a data do dia atual
    date_default_timezone_set('America/Sao_Paulo');
    // defifindo o formato da data
    $dateAtualInicio = date('Y-m-d');
    $dateAtualFim = date('Y-m-d');
    $pesquisaEmpresa = "";
?>
<!-- linkando com o arquivo css, que muda o layout quando a pagina for solicitada para imressão -->
<link href="css/printLista.css" rel="stylesheet" type="text/css" media="print">

<!-- SEÇÃO DE SCRIPTS -->
<script>
    $(document).ready(function(){
        $('.atualizar').click(function(){
            $('.container').fadeIn(600);
        });
    });

    function modal(idChamado, tipo){
        if (tipo == 'visualizar') {
            document.getElementById('modal').setAttribute("class", "modalVisualizar");
            $.ajax({
                type: "GET",
                url: "../router.php?controller=chamado&modo=buscar&tela=visualizar",
                data: {id:idChamado},
                success: function(dados){
                    $("#modal").html(dados);
                }
            });
        } else if(tipo == 'atualizar') {
            document.getElementById('modal').setAttribute("class", "modal");
            $.ajax({
                type: "POST",
                url: "modalAtualizar.php",
                data: {id:idChamado},
                success: function(dados){
                    $("#modal").html(dados);
                }
            });
        }
    }

    function ImprimirLista(){
        window.print();
    }
</script>
<div class="container">
    <div id="modal">
    </div>
</div>

<div class="tituloTela">
    Lista de chamados - Pendentes
</div>

<div class="contentFiltro">
    <div class="labelFiltro">
        Filtrar por periodo:
    </div>
    <form action="?pag=chamadosPendentes" method="post">
        <div class="dtInicio">
            <span class="labelInput">Inicio periodo:</span>
            <input class="inputData" type="date" name="txtDtInicio" value="<?php echo $dateAtualInicio; ?>">
        </div>
        <div class="dtFim">
            <span class="labelInput">Fim periodo:</span>
            <input class="inputData" type="date" name="txtDtFim" value="<?php echo $dateAtualFim; ?>">
        </div>
        <div class="contentBotaoFiltro">
            <input class="botaoStyleFiltro" type="submit" name="btnFiltrar" value="filtrar">
        </div>
        <div class="selectInicio">
            <span class="labelInput">Empresa Inicial:</span>
            <input class="inputPesquisa" type="search" name="txtEmpresaInicial" value="<?php echo $pesquisaEmpresa; ?>" alt="Empresa Inicial" title="Empresa Inicial">
        </div>
        <div class="selectFim">
            <span class="labelInput">Empresa Final:</span>
            <input class="inputPesquisa" type="search" name="txtEmpresaFinal" value="<?php echo $pesquisaEmpresa; ?>" alt="Empresa Final" title="Empresa Final">
        </div>
    </form>
</div>

<div class="contentBotaoImprimir">
    <input type="button" onclick="ImprimirLista();" name="btnImprimir" value="Imprimir">
</div>

<div class="table">
    <div class="contentTitulos">
        <div class="tituloStatus">
        </div>

        <div class="tituloStatus">
            N°
        </div>

        <div class="titulosTabela">
            EMPRESA
        </div>

        <div class="titulosTabela">
            CNPJ
        </div>

        <div class="titulosTabela">
            SOLICITANTE
        </div>

        <div class="titulosTabela tituloMaior">
            TITULO
        </div>

        <div class="titulosTabela opcoes">
            OPÇÕES
        </div>
    </div>
    <div class="contentRegistros">
        <?php
            require_once($_SESSION['require']."controller/controllerChamado.php");
            $listChamados = new controllerChamado();
            if (isset($_POST['btnFiltrar'])) {
                $chamado = $listChamados::filtroPorData(0);
            } else {
                $chamado = $listChamados::listarChamado(0, '');
            }
            $cont = 0;
            while($cont < count($chamado)){
                if ($chamado[$cont]->status) {
                    $img = "./imagens/statusVerde.png";
                } else {
                    $img = "./imagens/statusVermelho.png";
                }
        ?>
            <div class="linhaRegistro">
                <div class="registroStatus">
                    <img src="<?php echo $img; ?>" alt="status">
                </div>
                <div class="registroStatus">
                    <?php echo $cont+1 ?>
                </div>
                <div class="registros">
                    <?php echo $chamado[$cont]->razaoSocial; ?>
                </div>
                <div class="registros">
                    <?php echo $chamado[$cont]->cnpj; ?>
                </div>
                <div class="registros">
                    <?php echo $chamado[$cont]->nomeUsuario; ?>
                </div>
                <div class="registros registroMaior">
                    <?php echo $chamado[$cont]->titulo; ?>
                </div>
                <div class="registros opcoes">
                    <div class="atualizar">
                        <a onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'atualizar');"> <img src="imagens/atualizar.png" alt="Atualizar Chamado" title="Atualizar Chamado" width="20" height="20"> </a>
                        <a onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'visualizar');"> <img src="imagens/lupa.png" alt="Visualizar Chamado" title="Visualizar Chamado" width="20" height="20"> </a>
                    </div>
                </div>
            </div>
        <?php
                $cont++;
            } ?>
    </div>
</div>
