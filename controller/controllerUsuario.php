<?php
    // session_start();
    require_once($_SESSION['require']."view/modulo.php");
    //autentica();
    $conexao  = conexao();

    class controllerUsuario
    {

        function __construct(){
            require_once($_SESSION['require']."model/usuarioClass.php");
        }

        // método que grava um novo usuário
        public static function Inserir()
        {
            // resgata as informações
            $usuario               = new Usuario();
            $usuario->cnpj         = $_POST['cnpj'];
            $usuario->razaoSocial  = $_POST['razaoSocial'];
            $usuario->nome         = $_POST['nome'];
            $usuario->nomeUsuario  = $_POST['usuario'];
            $usuario->senha        = $_POST['senha'];
            return $usuario->Inserir($usuario);
        }
    }
?>
