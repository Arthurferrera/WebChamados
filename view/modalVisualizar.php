<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");

    // chamando funções necessárias
    autentica();
    $conexao  = conexao();

    // resgatando as informações do chamado
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

<!-- linkando com o arquivo css, que muda o layout quando a pagina é submetida a uma impressão -->
 <link href="css/printChamado.css" rel="stylesheet" type="text/css" media="print">


 <script>
    // código que faz o efeito de fechar a modal
     $(document).ready(function(){
         $('.fecharModal').click(function(){
             $('.container').fadeOut(600);
         });
     });

     // FUNÇÃO QUE SOLICITA A IMPRESSÃO DE UM CHAMADO
     function ImprimirChamado(idChamado){
         $.ajax({
             type: "GET",
             url: "../router.php?controller=chamado&modo=buscar&tela=imprimir",
             data: {id:idChamado},
             success: function(dados){
                 // alert(dados);
                 window.open("printDetalhes.php?chamado="+dados);
             }
         });
    }

    $(function () {
        $('#myModal').modal('toggle');
    });

    function fecharModal(){
        location.reload();
    }
 </script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog-md-4">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalhes do chamado</h4>
      </div>
      <div class="modal-body">
            <!-- titulo da modal -->
            <!-- <div class="tituloVisualizar">
                Detalhes do chamado
            </div> -->
            <!-- content que mostra todos os detalhes de um chamado -->
            <div class="contentVisualizarInformacoes">
                <div class="printChamado">
                    <!-- link que quando acionado, abre a tela de impressão da pagina -->
                    <a id="iconeImprimirChamado" onclick="ImprimirChamado(<?php echo $idChamado ?>);"> <img src="imagens/print.png" alt="Imprimir Detalhes do chamado" title="Imprimir Detalhes do chamado" width="25" height="25"> </a>
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
                    <div class="visualizarInformacoes" style="color: <?php echo $cor; ?>">
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
                                // aqui pega o id do chamado e solicita à uma função
                                // todas as observações/respostas de um chamado
                                require_once($_SESSION['require']."controller/controllerChamado.php");
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
      </div>
      <div class="modal-footer">
        <!-- fecha a modal -->
        <button type="button" class="btn btn-danger fecharModal" data-dismiss="modal" onclick="fecharModal()"><span class="mdi mdi-close-circle"></span> Fechar</button>
      </div>
    </div>

  </div>
</div>
