<?php
    require_once("modulo.php");
    $conexao  = conexao();
    // autentica();
    $idChamado = $_POST['id'];
 ?>
 <script>
     $(document).ready(function(){
         $('.fecharModal').click(function(){
             $('.container').fadeOut(600);
         });
     });
 </script>
 <link rel="stylesheet" href="../css/styleModal.css">
     <div class="fecharModal">
         X
     </div>
    <div class="tituloModal">
        Atualizar Chamado
    </div>
    <form name="frmAtualizarChamado" action="../router.php?controller=chamado&modo=inserir" method="post">
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
                    <input class="botaoStyle" type="submit" name="btnSalvar" value="Salvar">
                </div>
            </div>
        </div>
    </form>
