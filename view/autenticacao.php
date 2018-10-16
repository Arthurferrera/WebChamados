<!DOCTYPE html>
<html lang="pt" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Login | Chamados - SINCAESP</title>
        <link rel="stylesheet" href="view/css/styleLogin.css">
        <script src="./view/js/jquery.js"></script>
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
                            if(resposta == 1){
                                window.location.href = "view/home.php";
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
    <body>
        <!-- sessão que possui os elementos da tela de login, form, inputs... -->
        <section class="mainLogin">
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
        </section>
    </body>
</html>
