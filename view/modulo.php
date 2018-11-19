<?php
    // função que conecta como banco
    function conexao(){
        // definindo o host do banco
        $serverName = "mssql.demarchicompany.com.br";
        // definindo o banco, passando o usuario, a senha, e o padrao da lingua
        $conexaoInfo = array("Database"=>"demarchicompany", "UID"=>"demarchicompany", "PWD"=>"ImohtepHotep71", "CharacterSet"=>"UTF-8");
        // conectando no banco
        $conexao = sqlsrv_connect($serverName, $conexaoInfo);

        // retornando mensagem caso não conecte
        if(!$conexao){
            echo "Falha na conexão";
            die(print_r(sqlsrv_errors(), true));
        }

        return $conexao;
    }

    // função que faz a verificação se o usuário tem permissão ou está logado
    function autentica(){
        // verifica se a variavel de sessão está nula
        if (isset($_SESSION['login'])) {
            // pode fazer algo;
        } else {
            $redirect = "http://www.demarchicompany.com.br/webchamados/index.php?out=1";
            header('location:'.$redirect);
        }
    }
 ?>
