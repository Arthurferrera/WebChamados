<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");

    // chamando funções necessárias
    autentica();
    $conexao  = conexao();

    // resgatando as informações do chamado
    $idChamado = $chamado->idChamado;
    $titulo    = $chamado->titulo;
    $mensagem  = $chamado->mensagem;
    $status    = $chamado->status;

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
    $horaAbertura       = strstr($dataBanco, "T");
    $horaAbertura       = str_replace("T", "", $horaAbertura);
    $horaE              = explode(":", $horaAbertura);
    $horaAbertura       = $horaE[0].":".$horaE[1];

    $dataChamado        = strstr($dataBanco, "T", true);
    $dataChamado        = explode("-", $dataChamado);
    $dataChamado        = $dataChamado[2]."/".$dataChamado[1]."/".$dataChamado[0];

    $idUsuario          = $chamado->idUsuario;
    $cnpj               = $chamado->cnpj;
    $razaoSocial        = $chamado->razaoSocial;
    $nomeUsuario        = $chamado->nomeUsuario;

    $usuario_adm        = 1;
    $usuario_comum      = 2;
    $nivel_do_usuario   = $_SESSION['idNivelUsuario'];

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
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Detalhes do chamado</h4>
        </div>
        <div class="modal-body">
            <div class="container">
                <?php
                    if ($nivel_do_usuario == $usuario_adm) {
                        echo '
                        <div class="printChamado">
                            <!-- link que quando acionado, abre a tela de impressão da pagina -->
                            <a id="iconeImprimirChamado" onclick="ImprimirChamado(<?php echo $idChamado ?>);"> <img src="imagens/print.png" alt="Imprimir Detalhes do chamado" title="Imprimir Detalhes do chamado" width="25" height="25"> </a>
                        </div>
                        ';
                    }
                ?>
                <div class="row">
                    <div class="col"><strong>Solicitante:</strong></div>
                    <div class="col"><?php echo $nomeUsuario; ?></div>
                </div>
                <div class="row">
                    <div class="col"><strong>Empresa:</strong></div>
                    <div class="col"><?php echo $razaoSocial; ?></div>
                </div>
                <div class="row">
                    <div class="col"><strong>CNPJ:</strong></div>
                    <div class="col"><?php echo $cnpj; ?></div>
                </div>
                <div class="row">
                    <div class="col"><strong>Status do chamado:</strong></div>
                    <div class="col" style="color: <?php echo $cor; ?>"><?php echo $textStatus; ?></div>
                </div>
                <div class="row">
                    <div class="col"><strong>Título do chamado:</strong></div>
                    <div class="col"><?php echo $titulo; ?></div>
                </div>
                <div class="row">
                    <div class="col"><strong>Solicitação:</strong></div>
                    <div class="col"><?php echo $mensagem; ?></div>
                </div>
                <div class="row">
                    <div class="col"><strong>Data da Solicitação:</strong></div>
                    <div class="col"><?php echo $dataChamado." às ".$horaAbertura;?></div>
                </div>
                <div class="row">
                    <div class="col">
                        <strong>Evidências do Chamado:</strong>
                        <div class="row">
                            <?php
                                // aqui pega o id do chamado e solicita à uma função
                                // todas as fotos de um chamado
                                require_once($_SESSION['require']."controller/controllerChamado.php");
                                $listfotos = new controllerChamado();
                                $rsFotos = $listfotos::buscarFotosChamados($idChamado);
                                $cont = 0;
                                if ($rsFotos != null) {
                                    while($cont < count($rsFotos)){
                             ?>
                                 <picture class="col-md-4 col-sm-4">
                                     <img onclick="window.open('../<?php echo $rsFotos[$cont];?>')" class="img-thumbnail evidencia" src="../<?php echo $rsFotos[$cont]; ?>" alt="Evidências do chamado" title="<?php echo $rsFotos[$cont]; ?>">
                                 </picture>
                            <?php
                                $cont ++;
                                    }
                                }
                             ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="linhaDescricao linhaContentObservacao" id="linhaContentObservacao">
                    <div class="row">
                        <h4 style="margin-left: 38%;">Respostas</h4>
                    </div>
                    <div class="row">
                        <?php
                            // aqui pega o id do chamado e solicita à uma função
                            // todas as observações/respostas de um chamado
                            require_once($_SESSION['require']."controller/controllerChamado.php");
                            $listObervacoes = new controllerChamado();
                            $rsObservacoes = $listObervacoes::buscarObservacoes($idChamado);
                            $cont = 0;
                            while($cont < count($rsObservacoes)){
                        ?>
                        <script>document.getElementById('linhaContentObservacao').style.display = "block";</script>
                        <br>
                        <div class="linhaObservacao">
                            <div class="col">
                                <span class="labelMensagem">Mensagem:</span>
                                <?php echo $rsObservacoes[$cont]->observacao; ?>
                            </div>
                            <div class="col">
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
                        <hr>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- fecha a modal -->
                    <button type="button" class="btn btn-danger fecharModal" data-dismiss="modal" onclick="fecharModal()"><span class="mdi mdi-close-circle"></span> Fechar</button>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
