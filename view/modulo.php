<?php
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

    function autentica(){
        $id = $_SESSION['idAdmin'];
        $idNivel;
        $sql = "SELECT idNivelUsuario FROM usuario WHERE idNivelUsuario = ".$id." LIMIT 1";
        $result = sqlsrv_query($conexao, $sql);
        if($rs = sqlsrv_fetch_array($result)){
            $idNivel = $rs['idNivelUsuario'];
        }

        switch ($idNivel) {
            case 1:
                break;
            default:
                header('location:home.php?pag=home');
                break;
        }
    }
 ?>