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
        <title>Gerenciador de Chamados - SINCAESP</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/stylePendentes.css">
        <link rel="stylesheet" href="css/styleCadastroUsuario.css">
        <link rel="stylesheet" href="css/styleModal.css">
        <link rel="stylesheet" href="css/styleVisualizar.css">
        <link rel="stylesheet" href="css/styleEstatistica.css">
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
        <header>
            <section class="centralizaHeader">
                <div class="alinha"></div>
                <div class="logo"></div>
                <div class="logoff">
                    <div class="informacoesUsuarios">
                        <p>Bem vindo, <?php echo $_SESSION['nome']; ?></p>
                    </div>
                    <div class="sair">
                        <a href="../index.php?out=1" id="textoSair">Sair</a>
                    </div>
                </div>
            </section>
        </header>
        <section id="main">
            <!-- sessão do menu lateral -->
            <section class="centralizaMain">
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
                    <a onclick="Listar()" href="?pag=cadastroUsuario">
                        <div class="itens">
                            Cadastro de Usuário
                        </div>
                    </a>
                </section>
                <!-- content principal, muda o conteudo conforme o menu que estiver selecionado -->
                <section id="content_principal">
                    <?php
                        if (isset($_GET['pag'])) {
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
                        }
                    ?>
                </section>
            </section>
        </section>
        <!-- sessão do rodapé do site -->
        <footer>
            <section class="centralizarFooter">
                © COPYRIGHT 2018 - APESP, TODOS OS DIREITOS RESERVADOS.
            </section>
        </footer>
    </body>
</html>
