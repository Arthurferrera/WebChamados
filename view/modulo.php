<?php
    autentica();

    // função que conecta como banco
    function conexao(){
        // definindo o host do banco
        $serverName = 'LENOVO-PC';
        // definindo o banco, passando o usuario, a senha, e o padrao da lingua
        $conexaoInfo = array("Database"=>"CHAMADOS_APP", "UID"=>"sa", "PWD"=>"123456", "CharacterSet"=>"UTF-8");
        // conectando no banco
        $conexao = sqlsrv_connect($serverName, $conexaoInfo);

        return $conexao;

        // retornando mensagem caso não conecte
        if($conexao){
        //        echo "conexao sucedida";
        } else {
           echo "conexao falhada";
           die(print_r(sqlsrv_errors(), true));
        }
    }

    // função que faz a verificação se o usuário tem permissão ou está logado
    function autentica(){
        // verifica se a variavel de sessão está nula
        if (isset($_SESSION['nome'])) {
            // pode fazer algo;
            // TODO: ARRUMAR LOGIN/AUTENTICAÇÃO
        } else {
            echo "string";
            // header('location:http://localhost/WebChamados/index.php?out=1');
        }
    }
 ?>
