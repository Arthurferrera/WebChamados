<?php
    autentica();

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
        $idNivel;
        // verifica se a variavel de sessão está nula
        if ((isset($_SESSION['idAdmin']) ==  '')) {
            header('location:http://localhost/WebChamados/index.php?out=1');
        } else {
            // caso a sessão não esteja nula, verifica o idNivelUsuario
            $id = $_SESSION['idAdmin'];
            $sql = "SELECT idNivelUsuario FROM usuarioAdm WHERE idNivelUsuario = ".$id;

            $conexao = conexao();
            $result = sqlsrv_query($conexao, $sql);

            if($rs = sqlsrv_fetch_array($result)){
                $idNivel = $rs['idNivelUsuario'];
                if ($idNivel != 1) {
                    header('location:http://localhost/WebChamados/index.php?out=1');
                }
            }
        }
    }
 ?>
