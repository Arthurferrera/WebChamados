<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");

    autentica();
    $conexao = conexao();

    $nome ="";
    $usuario ="";
    $senha ="";
 ?>

<!DOCTYPE html>
<html lang="pt" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Gerenciador de Chamados - APESP</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/stylePendentes.css">
        <link rel="stylesheet" href="css/styleCadastroUsuario.css">
        <!-- <link rel="stylesheet" href="css/styleModal.css"> -->
        <!-- <link rel="stylesheet" href="css/styleVisualizar.css"> -->
        <link rel="stylesheet" href="css/styleEstatistica.css">
        <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../view/js/jquery.js"></script>
        <script>
            // função que exclui um usuário
            function Excluir(idItem){
                if (confirm('Deseja realmente excluir este usuário?') ==  true) {
                    $.ajax({
                        type: "GET",
                        url: "../router.php?controller=funcionario&modo=excluir",
                        data: {id:idItem},
                        success: function(dados){
                            Listar();
                        }
                    });
                }
            }

            // função que lista todos os usuários
            function Listar(){
                $.ajax({
                    type: "GET",
                    url: "?pag=cadastroUsuario",
                    success: function(dados){
                        $('#body').html(dados);
                    }
                });
            }

            // função que busca as informações de um usuário
            function Buscar(idItem){
                $.ajax({
                    type: "GET",
                    url: "../router.php?controller=funcionario&modo=consultar",
                    data: {id:idItem},
                    dataType: 'json',
                    success: function(dados){
                        const nome = dados[0];
                        const usuario = dados[1];
                        const senha = dados[2];
                        const idFuncionario = dados[3];

                        $('#inpNome').val(nome);
                        $('#inpUsuario').val(usuario);
                        $('#inpSenha').val(senha);
                        $('#id').val(idFuncionario);
                        $('#btnSalvar').val("Editar");
                    }
                });
            }
        </script>
    </head>
    <body id="body">
        <!-- sessão do cabeçalho do sistema
        onde possui a logo, nome do usuario e
        link para fazer o logoff do sistema -->
        <header class="jumbotron">
            <!-- <section class="centralizaHeader"> -->
                <!-- <div class="alinha"></div> -->
                <div class="h-100 col-sm-3 float-left"></div>
                <div class="col-sm-6 logo text-center">
                    <img src="imagens/logoApesp.jpg" class="img-fluid" alt="Logo APESP">
                </div>
                <div class="col-sm-3 logoff float-right">
                    <div class="informacoesUsuarios">
                        <p>Bem vindo, <?php echo $_SESSION['nome']; ?></p>
                    </div>
                    <div class="sair">
                        <a href="../index.php?out=1" id="textoSair">Sair</a>
                    </div>
                </div>
            <!-- </section> -->
        </header>
        <section class="centralizaMain col-sm-12">
            <!-- sessão do menu lateral -->
            <!-- <section class="centralizaMain row"> -->
                <section id="dropdown-menu" class="col-md-3 float-left">
                    <div class="nav flex-column nav-pills teste" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link" id="v-pills-pendentes-tab" data-toggle="pill" role="tab" aria-controls="v-pills-pendentes" aria-selected="true" href="?pag=chamadosPendentes">
                            <!-- <div class="dropdown-item"> -->
                                Chamados Pendentes
                            <!-- </div> -->
                        </a>
                        <a class="nav-link" id="v-pills-resolvidos-tab" data-toggle="pill" role="tab" aria-controls="v-pills-resolvidos" aria-selected="false" href="?pag=chamadosResolvidos">
                            <!-- <div class="dropdown-item"> -->
                                Chamados Resolvidos
                            <!-- </div> -->
                        </a>
                        <a class="nav-link" id="v-pills-estatistica-tab" data-toggle="pill" role="tab" aria-controls="v-pills-estatistica" aria-selected="false" href="?pag=estatistica">
                            <!-- <div class="dropdown-item"> -->
                                Estatísticas
                            <!-- </div> -->
                        </a>
                        <a class="nav-link" id="v-pills-usuario-tab" data-toggle="pill" role="tab" aria-controls="v-pills-usuario" aria-selected="false" onclick="Listar()" href="?pag=cadastroUsuario">
                            <!-- <div class="dropdown-item"> -->
                                Cadastro de Usuário
                            <!-- </div> -->
                        </a>
                    </div>
                </section>
                <!-- content principal, muda o conteudo conforme o menu que estiver selecionado -->
                <section class="col-9 float-left" id="main">
                    <div class="tab-content teste" id="v-pills-tabContent">
                        <?php
                            if (isset($_GET['pag'])) {
                                $pag = $_GET['pag'];
                                switch ($pag) {
                                    case 'home':
                                        require_once("chamadosPendentes/listaChamados.php");
                                        // require_once("divHome.php");
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
                                        require_once("chamadosPendentes/listaChamados.php");
                                        // require_once("divHome.php");
                                        break;
                                }
                            }
                        ?>
                    </div>
                </section>
            <!-- </section> -->
        </section>
        <!-- sessão do rodapé do site -->
        <footer class="card-footer text-muted text-center">
            <section class="footer">
                © COPYRIGHT 2018 - APESP, TODOS OS DIREITOS RESERVADOS.
            </section>
        </footer>
    </body>
</html>
