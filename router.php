<?php
    session_start();
    require_once($_SESSION['require']."view/modulo.php");
    autentica();
    $conexao  = conexao();

    $controller = $_GET['controller'];

    switch ($controller) {
        case 'funcionario':
            require_once($_SESSION['require']."model/funcionarioClass.php");
            require_once($_SESSION['require']."controller/controllerFuncionario.php");
            $modo = $_GET['modo'];
            switch ($modo) {
                case 'login':
                    $controllerFuncionario = new controllerFuncionario();
                    return $controllerFuncionario::Login();
                    break;
                case 'inserir':
                    $controllerFuncionario = new controllerFuncionario();
                    return $controllerFuncionario::Inserir();
                    break;
                case 'excluir':
                    $controllerFuncionario = new controllerFuncionario();
                    return $controllerFuncionario::Excluir();
                    break;
                case 'consultar':
                    $controllerFuncionario = new controllerFuncionario();
                    $funcionarioInfo = $controllerFuncionario::listarFuncionarioById();
                    // removendo os espaços do inicio e do fim da string
                    $funcionarioInfo->nome = trim($funcionarioInfo->nome);
                    $funcionarioInfo->usuario = trim($funcionarioInfo->usuario);
                    $funcionarioInfo->senha = trim($funcionarioInfo->senha);

                    $array = [$funcionarioInfo->nome, $funcionarioInfo->usuario, $funcionarioInfo->senha, $funcionarioInfo->idFuncionario];
                    echo json_encode($array);
                    break;
                default:
                    // code...
                    break;
            }
            break;
        case 'chamado':
            require_once($_SESSION['require']."controller/controllerChamado.php");
            require_once($_SESSION['require']."model/chamadoClass.php");
            $modo = $_GET['modo'];
            switch ($modo) {
                case 'inserir':
                    $controllerChamado = new controllerChamado();
                    return $controllerChamado->atualizarChamado();
                    break;
                case 'buscar':
                    $idChamado = $_GET['id'];
                    $controllerChamado = new controllerChamado();
                    $chamado = $controllerChamado::buscarChamado($idChamado);
                    if($_GET['tela'] == 'imprimir'){
                        echo $chamado->idChamado;
                    } else if ($_GET['tela'] == 'visualizar') {
                        require_once("view/modalVisualizar.php");
                    } else if($_GET['tela'] == 'impressao'){
                        require_once("view/printDetalhes.php");
                    }
                    break;
                case 'buscarObservacoes':
                    $idChamado = $_GET['id'];
                    $controllerChamado = new controllerChamado();
                    $chamado = $controllerChamado::buscarObservacoes($idChamado);
                    require_once("view/modalVisualizar.php");
                    break;
                case 'empresas':
                    $controllerChamado = new controllerChamado();
                    return $controllerChamado::empresas();
                    // require_once("view/modalVisualizar.php");
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
