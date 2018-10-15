<?php
    // incluindo a conxao
    require_once "conexao.php";

    // resgatando o ID da url
    $idusuario = $_GET['idUsuario'];

    // comando sql
    $tsql = "SELECT * FROM chamados WHERE status = 0 and idUsuario = $idusuario ORDER BY id DESC";
    // executando o comando
    $stm = sqlsrv_query($conexao, $tsql);
    // criando o array
    $lista = array();
    // loop que carrega os itens resgatados dentro da lista
    while($chamado = sqlsrv_fetch_object($stm)){
        $lista[] = $chamado;
    }
    // "retornando" a lista em forma de json
    echo json_encode($lista);
?>
