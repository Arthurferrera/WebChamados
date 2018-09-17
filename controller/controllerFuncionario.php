<?php
    class controllerFuncionario
    {
        function Login() {
            // cria uma instancia da classe
            $funcionario = new Funcionario;

            // seta o usuario e senha
            $funcionario->usuario = $_POST['txtUsuario'];
            $funcionario->senha = $_POST['txtSenha'];

            // chama a função de login
            $funcionario::Login($funcionario);
        }
    }

 ?>
