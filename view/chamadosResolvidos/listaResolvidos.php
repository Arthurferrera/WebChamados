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

<!-- sessão da modal -->
<div class="container">
    <div id="modal">
        <div class="fecharModal">
        </div>
    </div>
</div>

<div class="card-body">
        <h1 class="card-title"><i class="menu-icon mdi mdi-ticket-account"></i> Chamados - Resolvidos</h1>
        <br>
        <div class="panel panel-info">
            <div class="panel-heading">Filtrar por período</div>
            <div class="panel-body">
                <form action="?pag=chamadosResolvidos" method="post">
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
                    <div class="form-group">                    
                        <div class="col-md-6">
                            <label class="col-md control-label" for="textinput">Empresa inicial</label>
                            <input autocomplete="off" title="Empresa Inicial" name="txtEmpresaInicial" type="text" placeholder="Empresa inicial" class="form-control input-md" value="<?php echo $pesquisaEmpresaInicial; ?>">                            
                        </div>                        
                        <div class="col-md-6">
                            <label class="col-md control-label" for="textinput">Empresa final</label>
                            <input autocomplete="off" title="Empresa Final" name="txtEmpresaFinal" type="text" placeholder="Empresa Final" class="form-control input-md" value="<?php echo $pesquisaEmpresaFInal; ?>">
                        </div>                        
                    </div>
                    <button type="submit" class="btn btn-outline-primary" name="btnFiltrar" value="filtrar" style="margin-top: 2%; margin-left: 1%;"><i class="mdi mdi-filter-outline"></i>Filtrar</button>
                    <!-- botão de impressão da lista -->
                    <button type="button" class="btn btn-primary" onclick="ImprimirLista();" name="btnImprimir" value="Imprimir" style="margin-top: 2%; margin-left: 1%;"><i class="mdi mdi-printer"></i>Imprimir</button>
                </form>    
            </div>
        </div>

        <!-- Tabela que lista os chamados pendentes -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th>Nº</th>
                    <th>Empresa</th>
                    <th>CNPJ</th>
                    <th>Solicitante</th>
                    <th>Título</th>
                    <th>Data</th>
                    <th>Opções</th>
                </tr>
                </thead>       
                <tbody>
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
                <tr>
                    <td><img src="<?php echo $img; ?>" alt="status"></td>
                    <td><?php echo $cont+1 ?></td>
                    <td><?php echo $chamado[$cont]->razaoSocial; ?></td>
                    <td><?php echo $chamado[$cont]->cnpj; ?></td>
                    <td><?php echo $chamado[$cont]->nomeUsuario; ?></td>
                    <td><?php echo $chamado[$cont]->titulo; ?></td>
                    <td>
                        <?php
                            $dataBanco = $chamado[$cont]->dataAbertura;
                            echo $dataBanco->format('d/m/Y');
                        ?>                
                    </td>
                    <td>
                        <div class="atualizar">
                            <button onclick="modal(<?php echo $chamado[$cont]->idChamado; ?>, 'visualizar');" type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="mdi mdi-magnify-plus"></i></button>
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
