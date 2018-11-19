<!DOCTYPE html>
<html lang="pt" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Login | Chamados - APESP</title>
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
            // para exeutar o restante do processo
            $(document).ready(function(){
                $('#formLogin').submit(function(event){
                    event.preventDefault();

                    //armazenando o formulário em uma variável
                    var formulario = new FormData($('#formLogin')[0]);

                    $.ajax({
                        type: 'POST',
                        url: 'router.php?controller=funcionario&modo=login',
                        data: formulario,
                        cache: false,
                        contentType: false,
                        processData: false,
                        async: true,
                        success: function(resposta){
                            //se o conteúdo da variável resposta for 1, significa que o usuário existe no banco
                            //então, é redirecionado para a home
                            // alert(resposta);
                            if(resposta){
                                window.location.href = "view/home.php?pag=home";
                            } else {
                                alert("Usuário e/ou Senha Incorretos!!");
                                $('#txtSenha').val("");
                            }
                        }
                    });
                });
            });
        </script>
    </head>
    <body style="background-image: url('view/imagens/fundo_login.jpg');">
        <!-- sessão que possui os elementos da tela de login, form, inputs... -->
        <!-- <section class="mainLogin">
            <div class="titulo">
                Login
            </div>
            <form id="formLogin" method="post">
                <section class="contentCampos">
                    <div class="campos">
                        <div class="labels">Usuário: </div>
                        <input class="inputs" type="text" name="txtUsuario" required>
                    </div>
                    <div class="campos">
                        <div class="labels">Senha: </div>
                        <input class="inputs" type="password" id="txtSenha" name="txtSenha" required>
                    </div>
                    <div class="contentBotao">
                        <button class="botaoStyle">Entrar</button>
                    </div>
                </section>
            </form>
        </section> -->

  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper">
              <form id="formLogin" method="post">
                <div class="form-group">
                  <label class="label">Usuário</label>
                  <div class="input-group">
                    <input type="text" name="txtUsuario" class="form-control" placeholder="Digite o seu usuário">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="label">Senha</label>
                  <div class="input-group">
                    <input type="password" id="txtSenha" name="txtSenha" class="form-control" placeholder="Digite a sua senha">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block"><i class="mdi mdi-login-variant"></i> Entrar</button>
                </div>
              </form>
            </div>        
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>        
    </body>
</html>
