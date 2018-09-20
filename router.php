<?php
    $controller = $_GET['controller'];

    switch ($controller) {
        case 'funcionario':
            require_once('controller/controllerFuncionario.php');
            require_once('model/funcionarioClass.php');
            $modo = $_GET['modo'];
            switch ($modo) {
                case 'login':
                    $controllerFuncionario = new controllerFuncionario();
                    $controllerFuncionario::Login();
                    break;
                case 'inserir':
                    $controllerFuncionario = new controllerFuncionario();
                    $controllerFuncionario::Inserir();
                    break;
                case 'excluir':
                    $controllerFuncionario = new controllerFuncionario();
                    $sucesso = $controllerFuncionario::Excluir();
                    return $sucesso;
                    break;
                case 'consultar':
                    $controllerFuncionario = new controllerFuncionario();
                    $sucesso = $controllerFuncionario::Editar();
                    return $sucesso;
                    break;
                default:
                    // code...
                    break;
            }
            break;
        case 'chamado':
            require_once('controller/controllerChamado.php');
            require_once('model/chamadoClass.php');
            $modo = $_GET['modo'];
            switch ($modo) {
                case 'inserir':
                    $controllerChamado = new controllerChamado();
                    $controllerChamado::atualizarChamado();
                    break;
                case 'buscar':
                    $idChamado = $_GET['id'];
                    $controllerChamado = new controllerChamado();
                    $chamado = $controllerChamado::buscarChamado($idChamado);
                    require_once("view/modalVisualizar.php");
                    break;
                case 'buscarObservacoes':
                    $idChamado = $_GET['id'];
                    $controllerChamado = new controllerChamado();
                    $chamado = $controllerChamado::buscarObservacoes($idChamado);
                    require_once("view/modalVisualizar.php");
                    break;
                default:
                    // code...
                    break;
            }
            break;
        default:
            // code...
            break;
    }

 ?>
