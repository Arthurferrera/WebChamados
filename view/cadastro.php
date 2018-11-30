<!DOCTYPE html>
<html lang="pt" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Cadastro de Usuário | Chamados - APESP</title>
        <script src="./view/js/jquery.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- plugins:css -->
        <link rel="stylesheet" href="view/vendorsiconfonts/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="view/vendorscss/vendor.bundle.base.css">
        <link rel="stylesheet" href="view/vendorscss/vendor.bundle.addons.css">
        <link rel="stylesheet" href="view/css/styleLogin.css">
        <link rel="stylesheet" href="view/css/style.css">

        <style>
            .auth.theme-one .auto-form-wrapper {
                background: rgba(380,1003,955,0.9);
            }
        </style>

        <!-- <link rel="shortcut icon" href="../../images/favicon.png" /> -->

        <script>
            // função que para o evento SUBMIT do formulario
            // pega o formulario inteiro, e chama a router
            // para executar o restante do processo
            $(document).ready(function(){
                $('#form').submit(function(){
                    event.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: './router.php?controller=usuario&modo=inserir',
                        data: new FormData($('#form')[0]),
                        cache: false,
                        contentType: false,
                        processType: false,
                        processData: false,
                        async: true,
                        dataType: 'json',
                        success: function(resposta){
                            let tamanhoSenha = resposta.tamanhoSenha;
                            let cnpjValidado = resposta.validado;
                            let cnpjExiste = resposta.cnpjExiste;
                            let usuarioExiste = resposta.usuarioExiste;
                            let senhaValidada = resposta.senhaValidada;
                            let sucesso = resposta.sucesso;

                            if (!tamanhoSenha) {
                               alert('Senha deve conter no mínimo 6 caracteres.');
                            } else if (!cnpjValidado) {
                                alert("CNPJ inválido.");
                                $('#CNPJ').val("");
                            } else if (cnpjExiste) {
                                alert("Este CNPJ já está cadastrado, não é possivel cadastrar novamente.");
                                $('#CNPJ').val("");
                            } else if (usuarioExiste) {
                                alert("Este nome de usuário já existe. Favor tente outro.");
                                $('#txtUsuario').val("");
                            } else if (!senhaValidada) {
                                alert('Senha deve conter letras e números.');
                                $('#senha').val("");
                                $('#senha2').val("");
                            } else if (sucesso){
                                window.location.href = "view/home.php?pag=home";
                            } else {
                                alert("Erro desconhecido, contate o administrador do sistema.");
                            }
                        }
                    });
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
        <script>
            // definindo a mascara do cnpj
            $(function ($) {
                $("#CNPJ").mask("99.999.999/9999-99");
            });
        </script>
    </head>
    <body style="background-image: url('view/imagens/fundo_login.jpg');">

   <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper">
              <form id="form" action="cadastro.php" method="post">
                 <!-- CNPJ  -->
                <div class="form-group">
                  <div class="input-group">
                    <input name="cnpj" id="CNPJ" type="text" class="form-control" placeholder="CNPJ" required>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Razão Social -->
                <div class="form-group">
                  <div class="input-group">
                    <input name="razaoSocial" type="text" class="form-control" placeholder="Razão Social" required>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Nome completo -->
                <div class="form-group">
                  <div class="input-group">
                    <input name="nome" type="text" class="form-control" placeholder="Nome completo" required>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Nome de usuário -->
                <div class="form-group">
                  <div class="input-group">
                    <input id="txtUsuario" name="usuario" type="text" class="form-control" placeholder="Nome de usuário" required>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Senha -->
                <div class="form-group">
                  <div class="input-group">
                    <input name="senha" type="password" id="senha" class="form-control" placeholder="Senha" required>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- Confirme a senha -->
                <div class="form-group">
                  <div class="input-group">
                    <input type="password" id="senha2" class="form-control" placeholder="Confirme a senha..." oninput="validaSenha(this)" required>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-success submit-btn btn-block">Cadastrar</button>
                </div>
              </form>
              <a href="./index.php" class="btn btn-danger btn-block">Voltar</a>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
    <script>
    // Função que verifica a igualdade das senhas
      function validaSenha (input)
      {
        if (input.value != document.getElementById('senha').value)
        {
          input.setCustomValidity('As senhas são diferentes!');
        } else
        {
          input.setCustomValidity('');
        }
      }
    </script>
    </body>
</html>
