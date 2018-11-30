<?php
    // require_once("conexao.php");
    //
    // // resgatando valores passados na url
    // $idChamado = $_GET['idChamado'];

    function selectImagens($idChamado, $conexao){
        // string do comando sql
        $tsql = "SELECT *  FROM fotosChamados WHERE idChamado = $idChamado";

        // executando a query
        $stm = sqlsrv_query($conexao, $tsql);

        // criando a lista
        $lista = array();

        // loop que armazena na variavel os registros retornados do banco
        while($foto = sqlsrv_fetch_object($stm)){
            $lista[] = $foto;
        }

        // retornando a lista de imagens
        return $lista;
    }


 ?>
