<?php
    class controllerFuncionario
    {
        function __construct(){}

        function Login() {
            // cria uma instancia da classe
            $funcionario = new Funcionario();

            // seta o usuario e senha
            $funcionario->usuario = $_POST['txtUsuario'];
            $funcionario->senha = $_POST['txtSenha'];

            // chama a função de login
            $funcionario::Login($funcionario);
        }

        function Inserir(){
            $funcionario = new Funcionario();
            $funcionario->nome = $_POST['txtNome'];
            $funcionario->usuario = $_POST['txtLogin'];
            $funcionario->senha = $_POST['txtSenha'];

            $funcionario::Inserir($funcionario);
        }

        function listarFuncionario(){
            require_once("../model/funcionarioClass.php");
            $funcionario = new Funcionario();
            $retornoFuncionario = $funcionario::SelectAllFuncionario();
            return $retornoFuncionario;
        }

        function Excluir(){
            $funcionario = new Funcionario();
            $funcionario->idFuncionario = $_GET['id'];
            $excluiu = $funcionario::Excluir($funcionario->idFuncionario);
            return $excluiu;
        }

        function Editar(){
            $funcionario = new Funcionario();
            $funcionario->idFuncionario = $_GET['id'];
            $editou = $funcionario::Editar($funcionario->idFuncionario);
            return $editou;
        }
    }

 ?>
