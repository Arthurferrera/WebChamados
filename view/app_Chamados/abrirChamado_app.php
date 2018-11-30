<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    require_once($_SESSION['require']."model/chamadoClass.php");

    autentica();
    $conexao  = conexao();

    $chamado = new Chamado();
?>

<!-- linkando o arquivo jquery -->
<script src="../view/js/jquery.js"></script>

 <script>
     // FUNÇÃO QUE PARA O EVENTO SUBMIT DO FORMULARIO
     // E PASSA DE FORMA ASSINCRONA PARA A ROUTER
     $(document).ready(function(){
         $('#form').submit(function(event){
             event.preventDefault();
             $.ajax({
                 type: 'POST',
                 url: '../router.php?controller=chamado&modo=inserir',
                 data: new FormData($('#form')[0]),
                 cache: false,
                 contentType: false,
                 processData: false,
                 async: true,
                 success: function(chamado){
                     //se o conteúdo da variável chamado for 1, significa que o chamado foi inserido no banco
                     //então, é redirecionado para a lista
                     if (chamado == 1) {
                         alert("O chamado foi registrado com sucesso!");
                         window.location.href = "home.php?pag=chamadosPendentes_app";
                     } 
                    // else {
                    //     alert("Ocorreu um erro ao tentar inserir o chamado. Contate o administrador do sistema.");
                    // }
                 }
             });
         });
     });
 </script>

<div class="card-body">
    <h1 class="card-title"><i class="mdi mdi-plus-box"></i> Abrir chamado</h1>
    <!-- Formulário para abertura do chamado -->
    <form id="form" class="form-horizontal" action="home.php?pag=abrirChamado_app" method="post" enctype="multipart/form-data">
        <fieldset>
            <!-- Campos ocultos -->
            <input id="id" type="hidden" name="txtId">
            <input id="status" type="hidden" name="status" value="0">
            <input id="idUsuario" type="hidden" name="idUsuario" value="<?php echo $id_do_usuario; ?>">
            <!-- Título -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Título</label>  
                <div class="col-md-4">
                    <input name="titulo" type="text" placeholder="Título" class="form-control input-md" required="">                
                </div>
            </div>
            <!-- Local -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Local</label>  
                <div class="col-md-4">
                    <input name="local" type="text" placeholder="Local" class="form-control input-md" required="">
                </div>
            </div>
            <!-- Descrição -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textarea">Descrição</label>
                <div class="col-md-4">
                    <textarea name="mensagem" placeholder="Descrição" class="form-control" style="margin-left: 0px;" required=""></textarea>
                </div>
            </div>
            <!-- Evidências -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textarea">Anexar evidências</label>
                <div class="col-md-4">
                    <input name="localFoto[]" type="file" class="form-control" multiple>
                </div>
            </div>
            <!-- Botões -->
            <div class="form-group">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-8">
                    <button type="submit" id="btnSalvar" class="btn btn-success"><i class="mdi mdi-thumb-up"></i> Confirmar</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>