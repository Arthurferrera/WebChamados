<?php
    // incluindo a conexao
    require_once "conexao.php";

    // resgatando os parametros da url
    $titulo = $_GET['titulo'];
    $mensagem = $_GET['mensagem'];
    $status = $_GET['status'];
    $idUsuario = $_GET['idUsuario'];

    // comando sql
    $tsql = "INSERT INTO chamados (titulo, mensagem, data, status, idUsuario) VALUES (?,?,GETDATE(),?,?);";

    // definindo os parametros a serem salvos
    $params = array("$titulo", "$mensagem", $status, $idUsuario);

    // executando o sql
    $stm = sqlsrv_query($conexao, $tsql, $params);

    // retornando mensagem para saber se salvou ou não
    if($stm){
        echo json_encode(array("Sucesso" => true));
    } else {
        echo json_encode(array("Sucesso" => false));
    }
?>
