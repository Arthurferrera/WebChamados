<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    require_once($_SESSION['require']."controller/controllerChamado.php");
    autentica();

    $conexao  = conexao();
    $id = $_GET['chamado'];
    $controllerChamado = new controllerChamado();
    $chamado = $controllerChamado::buscarChamado($id);
    $idChamado = $chamado->idChamado;
    $titulo = $chamado->titulo;
    $mensagem  = $chamado->mensagem;
    $status = $chamado->status;

    if (!$status) {
        $textStatus = "Pendente";
        $cor = "#ff0000";
    } else {
        $textStatus = "Resolvido";
        $cor = "#009b00";
    }
    $dataBanco = $chamado->dataAbertura;
    // pega a data que está gravada no banco
    // separa a data da Hora
    // converte a data para o padrao brasileiro
    // e concatena as 2 string para mostrar na tela
    $horaAbertura = strstr($dataBanco, "T");
    $horaAbertura = str_replace("T", "", $horaAbertura);
    $horaE = explode(":", $horaAbertura);
    $horaAbertura = $horaE[0].":".$horaE[1];

    $dataChamado = strstr($dataBanco, "T", true);
    $dataChamado = explode("-", $dataChamado);
    $dataChamado = $dataChamado[2]."/".$dataChamado[1]."/".$dataChamado[0];

    $idUsuario = $chamado->idUsuario;
    $cnpj = $chamado->cnpj;
    $razaoSocial = $chamado->razaoSocial;
    $nomeUsuario = $chamado->nomeUsuario;
 ?>
<!DOCTYPE html>
<html lang="pt" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Imprimir Chamado</title>
        <!-- link do arquivo css, para o modo de impressão da página -->
        <link href="css/printChamado.css" rel="stylesheet" type="text/css" media="print">
        <link href="css/styleVisualizar.css" rel="stylesheet" type="text/css" media="screen">
        <script src="../view/js/jquery.js"></script>
        <script>
            $(document).ready(function(){
                window.print();
            });

            // TODO: FECHAR TELA PRINT AO APERTAR ESC
            // function tecla(){
            //    window.alert("O código da tecla pressionada foi: " + event.keyCode);
            //  }
            //  document.body.onkeypress = tecla();
        </script>
    </head>
    <body id="body">
        <div class="tituloVisualizar">
            Detalhes do chamado
        </div>
        <div class="contentVisualizarInformacoes">
            <div class="linhaInformacoes">
                <div class="labelVisualizar">
                    N° Solicitação:
                </div>
                <div class="visualizarInformacoes">
                    <?php echo $idChamado; ?>
                </div>
            </div>
            <div class="linhaInformacoes">
                <div class="labelVisualizar">
                    Solicitante:
                </div>
                <div class="visualizarInformacoes">
                    <?php echo $nomeUsuario; ?>
                </div>
            </div>
            <div class="linhaInformacoes">
                <div class="labelVisualizar">
                    Empresa:
                </div>
                <div class="visualizarInformacoes nomeEmpresa">
                    <?php echo $razaoSocial; ?>
                </div>
            </div>

            <div class="linhaInformacoes">
                <div class="labelVisualizar">
                    CNPJ:
                </div>
                <div class="visualizarInformacoes">
                    <?php echo $cnpj; ?>
                </div>
            </div>

            <div class="linhaInformacoes">
                <div class="labelVisualizar visualizarStatus">
                    Status do chamado:
                </div>
                <div class="visualizarInformacoes" style="color: <?php //echo $cor; ?>">
                    <?php echo $textStatus; ?>
                </div>
            </div>

            <div class="linhaInformacoes">
                <div class="labelVisualizar visualizarTitulo">
                    Titulo do Chamado:
                </div>
                <div class="visualizarInformacoes visualizarInfoTitulo">
                    <?php echo $titulo; ?>
                </div>
            </div>

            <div class="linhaDescricao">
                <div class="labelDescricao">
                    Solicitação:
                </div>
                <div class="visualizarDescricao">
                    <?php echo $mensagem; ?>
                </div>
            </div>

            <div class="linhaInformacoes dataSolcitacao">
                <div class="labelVisualizar visualizarStatus">
                    Data da Solicitação:
                </div>
                <div class="visualizarInformacoes">
                    <?php echo $dataChamado." às ".$horaAbertura;?>
                </div>
            </div>

            <div class="linhaDescricao linhaContentObservacao" id="linhaContentObservacao">
                <div class="labelObservacao">
                    Respostas:
                </div>
                <div class="contentObservacoes">
                    <?php
                           require_once($_SESSION['require']."controller/controllerChamado.php");
                           // require_once('controller/controllerChamado.php');
                           $listObervacoes = new controllerChamado();
                           $rsObservacoes = $listObervacoes::buscarObservacoes($idChamado);
                           $cont = 0;
                           while($cont < count($rsObservacoes)){
                     ?>
                       <script>
                           document.getElementById('linhaContentObservacao').style.display = "block";
                       </script>
                        <div class="linhaObservacao">
                            <div class="mensagemObservacao">
                                <span class="labelMensagem">Mensagem:</span>
                                <?php echo $rsObservacoes[$cont]->observacao; ?>
                            </div>
                            <div class="mensagemObservacao">
                                <span class="labelMensagem">Data e Hora:</span>
                                <?php
                                   // pega a data que está gravada no banco
                                   // separa a data da Hora
                                   // converte a data para o padrao brasileiro
                                   // a data deixa apenas horas e minutos
                                   // e concatena as 2 string para mostrar na tela
                                   $dataObservacao = $rsObservacoes[$cont]->dataObs;
                                   $hora = strstr($dataObservacao, "T");
                                   $hora = str_replace("T", "", $hora);
                                   $hr = explode(":", $hora);
                                   $hora = $hr[0].":".$hr[1];

                                   $data = strstr($dataObservacao, "T", true);
                                   $data = explode("-", $data);
                                   $data = $data[2]."/".$data[1]."/".$data[0];
                                   echo $data." às ".$hora;

                               ?>
                            </div>
                        </div>
                        <?php
                               $cont++;
                           }
                         ?>
                </div>
            </div>
        </div>
    </body>
</html>
