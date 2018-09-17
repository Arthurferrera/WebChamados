<?php
    require_once("conexao.php");

    $observacao = $_GET['observacao'];
    $status = $_GET['statusChamado'];
    $idChamado = $_GET['idChamado'];

    $sqlObs = "INSERT INTO observacao (observacao, dataHora, idChamado) VALUES (?, GETDATE(), ?)";
    $paramsObs = array("$observacao", $idChamado);
    $stmObs = sqlsrv_query($conexao, $sqlObs, $paramsObs);

    $sqlUpdate = "UPDATE chamados SET status = '".$status."' WHERE id = ".$idChamado;
    // $paramsUpdate = array("$observacao", $idChamado);
    $stmUpdate = sqlsrv_query($conexao, $sqlUpdate);

    if ($stmObs && $stmUpdate) {
        echo json_encode(array("Sucesso" => true));
    } else {
        echo json_encode(array("Sucesso" => false));
    }

 ?>
