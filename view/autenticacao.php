<!DOCTYPE html>
<html lang="pt" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Login | Chamados - SINCAESP</title>
        <link rel="stylesheet" href="view/css/styleLogin.css">
    </head>
    <body>
        <section class="mainLogin">
            <div class="titulo">
                Login
            </div>
            <form action="router.php?controller=funcionario&modo=login" method="post">
                <section class="contentCampos">
                    <div class="campos">
                        <div class="labels">Usuário: </div>
                        <input class="inputs" type="text" name="txtUsuario" required>
                    </div>
                    <div class="campos">
                        <div class="labels">Senha: </div>
                        <input class="inputs" type="password" name="txtSenha" required>
                    </div>
                    <div class="contentBotao">
                        <input class="botaoStyle" type="submit" name="btnEntrar" value="Entrar">
                    </div>
                </section>
            </form>
        </section>
    </body>
</html>
