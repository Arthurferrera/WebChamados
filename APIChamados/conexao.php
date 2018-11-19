<?php
    // definindo o host do banco
    // $serverName = 'mssql.demarchicompany.com.br';
    $serverName = 'LENOVO-PC';
    // definindo o banco, passando o usuario, a senha, e o padrao da lingua
    $conexaoInfo = array("Database"=>"CHAMADOS_APP", "UID"=>"sa", "PWD"=>"123456", "CharacterSet"=>"UTF-8");
    // $conexaoInfo = array("Database"=>"demarchicompany", "UID"=>"demarchicompany", "PWD"=>"ImohtepHotep71", "CharacterSet"=>"UTF-8");
    // conectando no banco
    $conexao = sqlsrv_connect($serverName, $conexaoInfo);

    // retornando mensagem caso não conecte
   if($conexao){
//        echo "conexao sucedida";
   } else {
       echo "conexao falhada";
       die(print_r(sqlsrv_errors(), true));
   }
?>
