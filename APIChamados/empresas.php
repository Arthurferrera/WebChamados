<?php
    // conexao
    require_once("conexao.php");

    $status = $_GET['status'];

    // string sql que retorna as empresas cadastradas no banco
    // as empresas são um atributo de um cadastro de uma pessoa
    $tsql = "SELECT u.id, u.razaoSocial, c.idUsuario
            FROM usuario AS u
            INNER JOIN chamados AS c
            ON c.idUsuario = u.id
            WHERE c.status = $status
            GROUP BY u.id, u.razaoSocial, c.idUsuario
            ORDER BY u.razaoSociaL ASC";
     // GROUP BY razaoSocial
     //        ORDER by razaoSocial ASC";

    // executando o comando no banco
    $stm = sqlsrv_query($conexao, $tsql);

    // criando o array
    $lista = array();

    // looop que carrega os itens resgatados dentro da lista
    while ($empresa = sqlsrv_fetch_object($stm)) {
        $lista[] = $empresa;
    }

    // retorna a lista de empresas, em forma de objeto json
    echo json_encode($lista);
 ?>
