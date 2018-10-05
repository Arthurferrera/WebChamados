<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    $conexao  = conexao();
    autentica();
    $idChamado = $_POST['id'];
 ?>
<script src="./view/js/jquery.js"></script>
 <script>
     $(document).ready(function(){
         $('.fecharModal').click(function(){
             $('.container').fadeOut(600);
         });
     });

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
 </script>

     <div class="fecharModal">
         X
     </div>
    <div class="tituloModal">
        Atualizar Chamado
    </div>
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
                <input type="hidden" name="txtIdChamado" value="<?php echo $idChamado; ?>">
                <input class="radioFinalizar" type="radio" name="rdoFinalizar" value="true">Sim
                <input class="radioFinalizar" type="radio" name="rdoFinalizar" value="false" checked>Não
                <div class="contentBotao">
                    <button class="botaoStyle">Salvar</button>
                </div>
            </div>
        </div>
    </form>
