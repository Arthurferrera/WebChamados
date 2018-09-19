
<?php
    require_once("modulo.php");
    $conexao = conexao();
    session_start();
    // autentica();

    $sql = "SELECT * FROM usuarioAdm WHERE id =".$_SESSION['idAdmin'];
    $result = sqlsrv_query($conexao, $sql);
    if ($rs = sqlsrv_fetch_array($result)) {
        $_SESSION['nomeUsuario'] = $rs['nome'];
    }
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Gerenciador de Chamados - SINCAESP</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/stylePendentes.css">
        <link rel="stylesheet" href="css/styleCadastroUsuario.css">
        <link rel="stylesheet" href="css/styleModal.css">
        <link rel="stylesheet" href="css/styleVisualizar.css">
        <link rel="stylesheet" href="css/styleEstatistica.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=0.0, user-scalable=no" />
        <script src="../view/js/jquery.js"></script>
        <script>
            function Excluir(idItem){
                $.ajax({
                    type: "GET",
                    url: "../router.php?controller=funcionario&modo=excluir",
                    data: {id:idItem},
                    success: function(dados){
                        Listar();
                    }
                });
            }

            function Listar(){
                $.ajax({
                    type: "GET",
                    url: "?pag=cadastroUsuario",
                    success: function(dados){
                        $('#body').html(dados);
                    }
                });
            }
        </script>
    </head>
    <body id="body">
        <header>
            <div class="alinha">

            </div>
            <div class="logo">
                <!-- <img src="view/imagens/logosincaesp.jpg" width="200" height="80"> -->
            </div>
            <div class="logoff">
                <div class="informacoesUsuarios">
                    <p>Bem vindo, <?php echo $_SESSION['nomeUsuario']; ?></p>
                </div>
                <div class="sair">
                    <a href="../index.php?out=1" id="textoSair">Sair</a>
                </div>
            </div>
        </header>
        <section id="main">
            <section id="menu_lateral">
                <a href="?pag=chamadosPendentes">
                    <div class="itens">
                        Chamados Pendentes
                    </div>
                </a>
                <a href="?pag=chamadosResolvidos">
                    <div class="itens">
                        Chamados Resolvidos
                    </div>
                </a>
                <a href="?pag=estatistica">
                    <div class="itens">
                        Estatísticas
                    </div>
                </a>
                <a onclick="Listar();">
                    <div class="itens">
                        Cadastro de Usuário
                    </div>
                </a>
            </section>
            <section id="content_principal">
                <?php
                    $pag = $_GET['pag'];
                    switch ($pag) {
                        case 'home':
                            require_once("divHome.php");
                            break;
                        case 'chamadosPendentes':
                            require_once("chamadosPendentes/listaChamados.php");
                            break;
                        case 'chamadosResolvidos':
                            require_once("chamadosResolvidos/listaResolvidos.php");
                            break;
                        case 'cadastroUsuario':
                            require_once("cadastroUsuario/cadastroUsuario.php");
                            break;
                        case 'estatistica':
                            require_once("estatisticas/estatistica.php");
                            break;
                        default:
                            require_once("divHome.php");
                            break;
                    }
                ?>
            </section>
        </section>
        <footer>
            Desenvolvido por: Arthur Ferreira
        </footer>
    </body>
</html>
