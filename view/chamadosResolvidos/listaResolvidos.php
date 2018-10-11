<?php
    // inicia a sessão, importa o arquivo e chama a função que valida a autenticação do usuario
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");

    // conexao com banco
    $conexao  = conexao();
    autentica();

    // pegando a data do dia atual
    date_default_timezone_set('America/Sao_Paulo');
    // defifindo o formato da data
    $dateAtualInicio = date('Y-m-d');
    $dateAtualFim = date('Y-m-d');

    // variaveis do filtro por nome de empresa
    $pesquisaEmpresaInicial = "";
    $pesquisaEmpresaFInal = "";

?>

<!-- linkando com o arquivo css, que muda o layout quando a pagina for solicitada para imressão -->
<link href="css/printLista.css" rel="stylesheet" type="text/css" media="print">

<!-- SEÇÃO DE SCRIPTS -->
<script>
// abrir modal
    $(document).ready(function(){
        $('.atualizar').click(function(){
            $('.container').fadeIn(600);
        });
    });
// fechar modal
    $(document).ready(function(){
        $('.fecharModal').click(function(){
            $('.container').fadeOut(600);
        });
    });

// função que passa para modal a informação de um chamado
    function modal(idChamado, tipo){
        if (tipo == 'visualizar') {
            document.getElementById('modal').setAttribute("class", "modalVisualizar");
            $.ajax({
                type: "GET",
                url: "../router.php?controller=chamado&modo=buscar&tela=visualizar",
                data: {id:idChamado},
                success: function(dados){
                    $("#modal").html(dados);
                    console.log(dados);
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

    // FUNÇÃO QUE SOLICITA A IMPRESSÃO DA PÁGINA
    function ImprimirLista(){
        window.print();
    }
</script>

<!-- sessão da modal -->
<div class="container">
    <div id="modal">
        <div class="fecharModal">
            X
        </div>
    </div>
</div>

<!-- titulo -->
<div class="tituloTela">
    Lista de chamados - Resolvidos
</div>

<!-- sessão de filtros -->
<div class="contentFiltro">
    <div class="labelFiltro">
        Filtrar por periodo:
    </div>
    <form action="?pag=chamadosResolvidos" method="post">
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
            <input class="inputPesquisa" type="search" name="txtEmpresaInicial" value="<?php echo $pesquisaEmpresaInicial; ?>" title="Empresa Inicial">
        </div>
        <div class="selectFim">
            <span class="labelInput">Empresa Final:</span>
            <input class="inputPesquisa" type="search" name="txtEmpresaFinal" value="<?php echo $pesquisaEmpresaFInal; ?>" title="Empresa Final">
        </div>
    </form>
</div>

<!-- botão que solicita a IMPRESSÃO de um chamado -->
<div class="contentBotaoImprimir">
    <input type="button" onclick="ImprimirLista();" name="btnImprimir" value="Imprimir">
</div>

<!-- tabela que lista os chamados -->
<div class="table tableResolvida">
    <div class="contentTitulos thead">
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
    <div class="contentRegistros tfoot">
        <?php
            // chama-se a função que traz os chamados
            require_once($_SESSION['require']."controller/controllerChamado.php");
            $listChamados = new controllerChamado();
            // verifica se o formulario de filtros foi submetido
            // para chamar a função de listagem correta
            if (isset($_POST['btnFiltrar'])) {
                $chamado = $listChamados::filtroPorData(1);
            } else {
                $chamado = $listChamados::listarChamado(1, 'SelectDiaResolvido');
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
                <div class="registroStatusResolvidos">
                    <img src="<?php echo $img; ?>" alt="status">
                </div>
                <div class="registroStatusResolvidos">
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
                        <a onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'visualizar');"> <img src="imagens/lupa.png" alt="visualizar Chamado" title="visualizar Chamado" width="25" height="25"> </a>
                    </div>
                </div>
            </div>
        <?php
                $cont++;
            } ?>
    </div>
</div>
