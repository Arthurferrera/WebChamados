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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gerenciador de Chamados - APESP</title>
        <!-- Início - plugins:css -->
        <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
        <!-- Final - plugins:css -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <!-- <link rel="stylesheet" href="css/stylePendentes.css"> -->
        <!-- <link rel="stylesheet" href="css/styleCadastroUsuario.css"> -->
        <link rel="stylesheet" href="css/styleModal.css">
        <link rel="stylesheet" href="css/styleVisualizar.css">
        <link rel="stylesheet" href="css/styleEstatistica.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="shortcut icon" type="image/x-icon" href="imagens/favicon.ico">
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
        <style>
            @media (max-width: 991px) {
                .navbar.default-layout .navbar-menu-wrapper {
                    width: 10%;
                    margin-left: 68%;
                }
            }
        </style>
    </head>
    <body id="body">
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="opa navbar default-layout col-lg-12 col-12 col-sm-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
                <a class="navbar-brand brand-logo" href="index.html">
                    <img id="imgLogo" src="imagens/logo.png" alt="logo" style="width: 100%; height: 100%; padding-left: 2%; padding-right: 2%;"/>
                </a>
                <a class="navbar-brand brand-logo brand-logo-mini" href="index.html">
                    <img id="imgLogoMin" src="imagens/logo.png" alt="logo" style="width: 100%; height: 100%; padding-left: 2%; padding-right: 2%;"/>
                </a>
                <!-- <a class="navbar-brand brand-logo-mini" href="index.html">
                    <img src="imagens/logo.png" alt="logo" />
                </a> -->
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown d-none d-xl-inline-block">
                    <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        <span class="profile-text">Olá, Administrador !</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <a class="dropdown-item" href="../index.php?out=1" id="textoSair" style="margin-top: 5%;">Sair</a>
                    </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <!-- Início - Chamados -->
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#ui-chamados" aria-expanded="false" aria-controls="ui-basic">
                        <i class="menu-icon mdi mdi-ticket-account"></i>
                        <span class="menu-title">Chamados</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="ui-chamados">
                        <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="?pag=chamadosPendentes">Pendentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?pag=chamadosResolvidos">Resolvidos</a>
                        </li>
                        </ul>
                    </div>
                    </li>
                    <!-- Final - Chamados  -->
                    <!-- Início - Estatísticas -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#ui-estatisticas" aria-expanded="false" aria-controls="ui-basic">
                        <i class="menu-icon mdi mdi-chart-areaspline"></i>
                        <span class="menu-title">Estatísticas</span>
                        <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-estatisticas">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                            <a class="nav-link" href="?pag=estatisticaParcial">Parciais</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="?pag=estatistica">Gerais</a>
                            </li>
                        </ul>
                        </div>
                    </li>
                    <!-- Final - Estatísticas -->
                    <!-- Início - Usuários -->
                    <li class="nav-item">
                        <a class="nav-link" href="?pag=cadastroUsuario">
                        <i class="menu-icon mdi mdi-account-multiple"></i>
                        <span class="menu-title">Usuários do Sistema</span>
                        </a>
                    </li>
                    <!-- Final - Usuários -->
                </ul>
                </nav>
                <!-- Conteúdo -->

                <div class="main-panel" id="content_principal" style="height: 100%;">
                <div class="content-wrapper">
                    <div class="row" style="margin-top: 3%;">
                    <div class="col-lg-12 grid-margin">
                        <div class="card">
                            <!-- <div class="card-body">
                                <h4 class="card-title">Conteúdo das telas</h4>
                            </div> -->
                            <section id="main">
                                    <!-- content principal, muda o conteudo conforme o menu que estiver selecionado -->
                                    <section id="content_principal">
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
                                                    case 'estatisticaParcial':
                                                        require_once("estatisticas/estatisticaParcial.php");
                                                        break;
                                                    default:
                                                        require_once("chamadosPendentes/listaChamados.php");
                                                        // require_once("divHome.php");
                                                        break;
                                                }
                                            }
                                        ?>
                                    </section>
                                </section>
                            </section>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <!-- sessão do rodapé do site -->
                <footer class="footer">
                    <div class="container-fluid clearfix">
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block" style="color: black;">Copyright © 2018
                        APESP. Todos os direitos reservados.</span>
                    </div>
                </footer>
                <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
        <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->

        <!-- plugins:js -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <script src="vendors/js/vendor.bundle.addons.js"></script>
        <!-- endinject -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="js/dashboard.js"></script>
        <!-- End custom js for this page-->
    </body>
</html>
