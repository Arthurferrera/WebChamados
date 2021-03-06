<?php
    // incluindo a conxao
    require_once "conexao.php";

    // rsgatando o status do chamado
    $status = $_GET['status'];
    $idUsuario = $_GET['idUsuario'];

    if ($status) {
        // comando sql
        $tsql = "SELECT ch.id, ch.titulo, ch.mensagem, ch.status, ch.data, ch.dataFechamento,
                    ch.idUsuario, u.razaoSocial, u.nome
                    FROM chamados AS ch
                    INNER JOIN usuario AS u
                    ON u.id = ch.idUsuario
                    WHERE ch.status = $status AND idUsuario = $idUsuario ORDER BY ch.dataFechamento DESC";
    } else {
        // comando sql
        $tsql = "SELECT ch.id, ch.titulo, ch.mensagem, ch.status, ch.data,
                    ch.idUsuario, u.razaoSocial, u.nome
                    FROM chamados AS ch
                    INNER JOIN usuario AS u
                    ON u.id = ch.idUsuario
                    WHERE ch.status = $status AND idUsuario = $idUsuario ORDER BY ch.data DESC";
    }

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
