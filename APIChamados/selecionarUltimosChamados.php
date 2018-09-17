<?php
//    conexao com o banco
    require_once "conexao.php";
//    COMANDO SQL QUE TRAZ UM DETERMINADO NUMERO DE REGISTROS, EM ORDEM DECRESCENTE
    $tsql = "SELECT TOP (5) * 
                FROM (
                    SELECT ROW_NUMBER() OVER (ORDER BY id DESC) AS rou_number, *
                    FROM chamados
                    ) CHAMADOS_APP;";
//    executando o comando
    $stm = sqlsrv_query($conexao, $tsql);
//    criando a lista
    $lista = array();
//    carregando na lista todos os resultados encontrados
    while($chamado = sqlsrv_fetch_object($stm)){
        $lista[] = $chamado;
    }
//    retornando a lista
    echo json_encode($lista);
?>