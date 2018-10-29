<?php
    // inicia a sessão, importa o arquivo e chama a função que valida a autenticação do usuario
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    require_once($_SESSION['require']."controller/controllerChamado.php");

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

    $listChamados = new controllerChamado();
    $chamado = $listChamados::empresas();
    $cont = 0;
    while($cont < count($chamado)){
        $listaEmpresas[$cont] = $chamado[$cont]->razaoSocial;
        $cont++;
    }
    $stringLista =  implode("|",$listaEmpresas);
?>

<!-- linkando com o arquivo css, que muda o layout quando a pagina for solicitada para imressão -->
<link href="css/printLista.css" rel="stylesheet" type="text/css" media="print">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

    $(function() {
        var arrayEmpresas, stringArray;
        stringArray = "<?php echo $stringLista; ?>";
        arrayEmpresas = stringArray.split("|");
        $(".inputPesquisa").autocomplete({
            source: arrayEmpresas
        });
    });
</script>


<div class="tab-pane fade show active" id="v-pills-resolvidos" role="tabpanel" aria-labelledby="v-pills-resolvidos-tab">
    <!-- sessão da modal -->
    <div class="container">
        <div id="modal">
            <div class="fecharModal">
                X
            </div>
        </div>
    </div>

    <!-- titulo -->
    <h3 class="card-title">
        Lista de chamados - Resolvidos
    </h3>

    <!-- sessão que fica o form de filtros que podem ser aplicados -->
    <div class="col-12">
        <!-- <div class="labelFiltro">
            Filtrar por periodo:
        </div> -->
        <form action="?pag=chamadosPendentes" method="post">
            <div class="form-row">
                <div class="col-3"></div>
                <div class="col-3 form-group">
                    <label for="dataInicio">Inicio periodo:</label>
                    <input id="dataInicio" class="form-control" type="date" name="txtDtInicio" value="<?php echo $dateAtualInicio; ?>">
                </div>
                <div class="col-3 form-group">
                    <label for="dataFim">Fim periodo:</label>
                    <input id="dataFim" class="form-control" type="date" name="txtDtFim" value="<?php echo $dateAtualFim; ?>">
                </div>
                <div class="col-3"></div>
            </div>
            <div class="form-row">
                <div class="col-2"></div>
                <div class="col-4 form-group">
                    <!-- <label for="empresaInicio">Empresa Inicial:</label> -->
                    <input id="empresaInicio" placeholder="Empresa Inicial" autocomplete="off" class="form-control" type="text" name="txtEmpresaInicial" value="<?php echo $pesquisaEmpresaInicial; ?>">
                </div>
                <div class="col-4 form-group">
                    <!-- <label for="empresaFinal">Empresa Final:</label> -->
                    <input id="empresaFinal" placeholder="Empresa Final" autocomplete="off" class="form-control" type="text" name="txtEmpresaFinal" value="<?php echo $pesquisaEmpresaFInal; ?>">
                </div>
                <div class="col-2">
                    <input class="btn btn-primary" type="submit" name="btnFiltrar" value="Filtrar">
                </div>
            </div>
        </form>
    </div>

    <!-- botão de impressão da lista -->
    <div class="contentBotaoImprimir">
        <input class="btn btn-secondary" type="button" onclick="ImprimirLista();" name="btnImprimir" value="Imprimir">
    </div>

    <table class="table table-striped col-10">
        <thead class="thead-dark">
            <tr>
                <th scope="col"></th>
                <th scope="col">N°</th>
                <th scope="col">EMPRESA</th>
                <th scope="col">CNPJ</th>
                <th scope="col">SOLICITANTE</th>
                <th scope="col">TITULO</th>
                <th scope="col">OPÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // chama o método adequado para a listagem dos chamados
                require_once($_SESSION['require']."controller/controllerChamado.php");
                $listChamados = new controllerChamado();
                // verifica se o form de filtro foi submetido
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
            <tr>
                <td class="registroStatus">
                    <img src="<?php echo $img; ?>" alt="status">
                </td>
                <th scope="row">
                    <?php echo $cont+1 ?>
                </th>
                <td>
                    <?php echo $chamado[$cont]->razaoSocial; ?>
                </td>
                <td>
                    <?php echo $chamado[$cont]->cnpj; ?>
                </td>
                <td>
                    <?php echo $chamado[$cont]->nomeUsuario; ?>
                </td>
                <td>
                    <?php echo $chamado[$cont]->titulo; ?>
                </td>
                <td>
                    <a onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'atualizar');"> <img src="imagens/atualizar.png" alt="Atualizar Chamado" title="Atualizar Chamado" width="20" height="20"> </a>
                    <a onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'visualizar');"> <img src="imagens/lupa.png" alt="Visualizar Chamado" title="Visualizar Chamado" width="20" height="20"> </a>
                </td>
            </tr>
            <?php
                    $cont++;
                } ?>
        </tbody>
    </table>




    <!-- tabela que lista os chamados -->
    <!-- <div class="table tableResolvida">
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
    </div> -->
</div>
