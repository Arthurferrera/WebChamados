<?php
    // inicia a sessão, importa o arquivo e chama a função que valida a autenticação do usuario
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    require_once($_SESSION['require']."controller/controllerChamadosUsuario.php");

    // conexao com banco
    $conexao  = conexao();
    autentica();

    // // pegando a data do dia atual
    // date_default_timezone_set('America/Sao_Paulo');
    // // defifindo o formato da data
    // $dateAtualInicio = date('Y-m-d');
    // $dateAtualFim = date('Y-m-d');
    //
    // // variaveis do filtro por nome de empresa
    // $pesquisaEmpresaInicial = "";
    // $pesquisaEmpresaFInal = "";
    //
    // $listChamados = new controllerChamado();
    // $chamado = $listChamados::empresas();
    // $cont = 0;
    // while($cont < count($chamado)){
    //     $listaEmpresas[$cont] = $chamado[$cont]->razaoSocial;
    //     $cont++;
    // }
    // $stringLista =  implode("|",$listaEmpresas);
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
                url: "../router.php?controller=chamadoUsuario&modo=buscar&tela=visualizar",
                data: {id:idChamado},
                success: function(dados){
                    $("#modal").html(dados);
                    console.log(dados);
                }
            });
        }

        // else if(tipo == 'atualizar') {
        //     document.getElementById('modal').setAttribute("class", "modal");
        //     $.ajax({
        //         type: "POST",
        //         url: "modalAtualizar.php",
        //         data: {id:idChamado},
        //         success: function(dados){
        //             $("#modal").html(dados);
        //         }
        //     });
        // }
    }

    // FUNÇÃO QUE SOLICITA A IMPRESSÃO DA PÁGINA
    // function ImprimirLista(){
    //     window.print();
    // }
</script>

<!-- sessão da modal -->
<div class="container">
    <div id="modal">
        <div class="fecharModal">
        </div>
    </div>
</div>

<div class="card-body">
        <h1 class="card-title"><i class="menu-icon mdi mdi-check-all"></i> Chamados - Resolvidos</h1>
        <br>

        <!-- Tabela que lista os chamados pendentes -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Data</th>
                    <th>Título</th>
                    <th>Visualizar</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    // chama-se a função que traz os chamados
                    $listChamados = new controllerChamadoUsuario();
                    // verifica se o formulario de filtros foi submetido
                    // para chamar a função de listagem correta
                    // if (isset($_POST['btnFiltrar'])) {
                    //     $chamado = $listChamados::filtroPorData(1);
                    // } else {
                    $idCliente = $_SESSION['id'];
                    $chamado = $listChamados::listarChamadoCliente(1, $idCliente);
                    // }
                    $cont = 0;
                    while($cont < count($chamado)) {
                ?>
                <tr>
                    <td>
                        <?php
                            $dataBanco = $chamado[$cont]->dataAbertura;
                            echo $dataBanco->format('d/m/Y');
                        ?>
                    </td>
                    <td><?php echo $chamado[$cont]->titulo; ?></td>
                    <td>
                        <div class="atualizar">
                            <button onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'visualizar');" type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal"><i class="mdi mdi-magnify-plus"></i></button>
                        </div>
                    </td>
                </tr>
                <?php
                $cont++;
            } ?>
                </tbody>
            </table>
    </div>
</div>
