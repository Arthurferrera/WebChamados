<?php
    // session_start();
    require_once($_SESSION['require']."view/modulo.php");
    autentica();
    $conexao  = conexao();

    // session_start();
    class controllerFuncionario
    {
        function __construct(){
            require_once($_SESSION['require']."model/funcionarioClass.php");
        }

        function Login() {
            // cria uma instancia da classe
            $funcionario = new Funcionario();

            // seta o usuario e senha
            $funcionario->usuario = $_POST['txtUsuario'];
            $funcionario->senha = $_POST['txtSenha'];

            // chama a função de login
            return $funcionario::Login($funcionario);
        }

        // metodo que grava um novo funcionario
        function Inserir(){
            // resgata as informações
            $funcionario = new Funcionario();
            $funcionario->nome = $_POST['txtNome'];
            $funcionario->usuario = $_POST['txtLogin'];
            $funcionario->senha = $_POST['txtSenha'];
            $funcionario->idFuncionario = $_POST['txtId'];

            // caso a senha seja menor que 6 caracteres
            $senha = $funcionario->senha;
            if (trim(strlen($senha)) < 6) {
                echo 3;
                return;
            }

            // verifica se o id esta vazio
            // caso sim, chama o metodo Editar
            // caso nao, chama o inserir
            $id = $_POST['txtId'];
            if ($id <> '') {
                return $funcionario::Editar($funcionario);
            } else {
                return $funcionario->Inserir($funcionario);
            }
        }

        // metodo que lista todos os funcionarios
        function listarFuncionario(){
            $funcionario = new Funcionario();
            $retornoFuncionario = $funcionario::SelectAllFuncionario();
            return $retornoFuncionario;
        }

        // metodo que busca um usuario pelo id
        function listarFuncionarioById(){
            $funcionario = new Funcionario();
            $funcionario->idFuncionario = $_GET['id'];
            $retornoFuncionario = $funcionario::SelectByIdFuncionario($funcionario->idFuncionario);

            $funcionario->nome = $retornoFuncionario->nome;
            $funcionario->usuario = $retornoFuncionario->usuario;
            $funcionario->senha = $retornoFuncionario->senha;
            $funcionario->idFuncionario = $retornoFuncionario->idFuncionario;
            return $funcionario;
        }

        // metodo que exclui um funcionario
        function Excluir(){
            $funcionario = new Funcionario();
            $funcionario->idFuncionario = $_GET['id'];
            $excluiu = $funcionario::Excluir($funcionario->idFuncionario);
            return $excluiu;
        }
    }

 ?>
