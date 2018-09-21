<?php
    require_once("./modulo.php");
    $conexao  = conexao();
    // autentica();
    $class = "";

    date_default_timezone_set('America/Sao_Paulo');
    $dateAtualInicio = date('Y-m-d');
    $dateAtualFim = date('Y-m-d');
    // echo $dateAtual;
?>

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
                url: "../router.php?controller=chamado&modo=buscar",
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
</script>
<div class="container">
    <div id="modal">
        <div class="fecharModal">
            X
        </div>
    </div>
</div>

<div class="tituloTela">
    Lista de chamados - Resolvidos
</div>
<div class="contentFiltro">
    <div class="labelFiltro">
        Filtrar por periodo:
    </div>
    <form action="?pag=chamadosResolvidos" method="post">
        <div class="dtInicio">
            <span class="labelInput">Inicio periodo:</span>
            <input class="inputData" type="date" name="txtDtInicio" value="<?php echo $dateAtualInicio; ?>" required>
        </div>
        <div class="dtFim">
            <span class="labelInput">Fim periodo:</span>
            <input class="inputData" type="date" name="txtDtFim" value="<?php echo $dateAtualFim; ?>" required>
        </div>
        <div class="contentBotaoFiltro">
            <input class="botaoStyleFiltro" type="submit" name="btnFiltrar" value="filtrar">
        </div>
    </form>
</div>

<div class="table tableResolvida">
    <div class="contentTitulos">
        <div class="tituloStatus">
        </div>

        <div class="tituloStatus opcoes">
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
            require_once("../controller/controllerChamado.php");
            $listChamados = new controllerChamado();
            if (isset($_POST['btnFiltrar'])) {
                $dateAtualInicio = $_POST['txtDtInicio'];
                $dateAtualFim = $_POST['txtDtFim'];
                $chamado = $listChamados::filtroPorData();
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
                <div class="registroStatus">
                    <img src="<?php echo $img; ?>">
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
                    <div class="atualizar" onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'visualizar');" style="color:black;">
                        <a> <img src="imagens/lupa.png" alt="visualizar Chamado" title="visualizar Chamado" width="25" height="25"> </a>
                    </div>
                </div>
            </div>
        <?php
                $cont++;
            } ?>
    </div>
</div>
