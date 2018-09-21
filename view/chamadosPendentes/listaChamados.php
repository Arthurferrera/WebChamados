<?php
    require_once("./modulo.php");
    $conexao  = conexao();
    // autentica();
?>
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
    </div>
</div>

<div class="tituloTela">
    Lista de chamados - Pendentes
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
            require_once("../controller/controllerChamado.php");
            $listChamados = new controllerChamado();
            $chamado = $listChamados::listarChamado(0, '');
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
