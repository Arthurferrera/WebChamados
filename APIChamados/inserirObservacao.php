<?php
    // inclui a conexao com o banco
    require_once("conexao.php");

    // resgatando as informações necessárias
    $observacao = $_GET['observacao'];
    $status = $_GET['statusChamado'];
    $idChamado = $_GET['idChamado'];

    // string sql, string dos parametros e execução co comando
    $sqlObs = "INSERT INTO observacao (observacao, dataHora, idChamado) VALUES (?, GETDATE(), ?)";
    $paramsObs = array("$observacao", $idChamado);
    $stmObs = sqlsrv_query($conexao, $sqlObs, $paramsObs);

    // atualizando o status do chamado
    $sqlUpdate = "UPDATE chamados SET status = '".$status."' WHERE id = ".$idChamado;
    $stmUpdate = sqlsrv_query($conexao, $sqlUpdate);

    // verificando se os comandos foram executados
    if ($stmObs && $stmUpdate) {
        echo json_encode(array("Sucesso" => true));
    } else {
        echo json_encode(array("Sucesso" => false));
    }

 ?>
