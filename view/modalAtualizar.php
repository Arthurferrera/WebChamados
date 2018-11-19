<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    $conexao  = conexao();
    autentica();
    $idChamado = $_POST['id'];
 ?>

<!-- linkando o arquivo jquery -->
<script src="./view/js/jquery.js"></script>

 <script>
    // FUNÇÃO QUE FECHA A MODAL
     $(document).ready(function(){
         $('.fecharModal').click(function(){
             $('.container').fadeOut(600);
         });
     });

     // FUNÇÃO QUE PARA O EVENTO SUBMIT DO FORMULARIO
     // E PASSA DE FORMA ASSINCRONA PARA A ROUTER
     $(document).ready(function(){
         $('#formObs').submit(function(event){
             event.preventDefault();

             //armazenando o formulário em uma variável
             var formulario = new FormData($('#formObs')[0]);

             $.ajax({
                 type: 'POST',
                 url: '../router.php?controller=chamado&modo=inserir',
                 data: formulario,
                 cache: false,
                 contentType: false,
                 processData: false,
                 async: true,
                 success: function(resposta){
                     //se o conteúdo da variável resposta for 1, significa que a observação foi inserida no banco
                     //então, é redirecionado para a lista
                     if(resposta == 1){
                         alert("Resposta inserida com sucesso");
                         window.location.href = "home.php?pag=chamadosPendentes";
                     }else{
                         alert("Ocorreu um erro ao tentar inserir a resposta. Contate o administrador do sistema.");
                     }
                 }
             });
         });
     });

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
        <h4 class="modal-title">Atualizar Chamado</h4>
      </div>
      <div class="modal-body">
            <!-- FORMULARIO COM OS INPUTS -->
            <form id="formObs" method="post">
                <div class="conteudoModal">
                    <div class="linhaCampo">
                        <div class="labelModal">
                            Observação:
                        </div>
                        <textarea name="txtObservacao" required></textarea>
                    </div>
                    <div class="linhaCampo">
                        <div class="labelModal">
                            Finalizar chamado:
                        </div>
                        <div style="margin-left: 5%;">
                            <input type="hidden" name="txtIdChamado" value="<?php echo $idChamado; ?>">
                            <input class="radioFinalizar" type="radio" name="rdoFinalizar" value="true">Sim
                            <input class="radioFinalizar" type="radio" name="rdoFinalizar" value="false" checked>Não
                        </div>
                        <div style="margin-left: 5%; margin-top: 5%;">
                            <button type="button" class="btn btn-danger fecharModal" data-dismiss="modal" onclick="fecharModal()"><span class="mdi mdi-close-circle"></span> Cancelar</button>
                            <button type="submit" class="btn btn-success"><span class="mdi mdi-content-save"></span> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
      </div>
      <div class="modal-footer">
      
      
      
      </div>
    </div>

  </div>
</div>