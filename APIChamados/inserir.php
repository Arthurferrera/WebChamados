<?php
    // incluindo a conexao
    require_once "conexao.php";

    // resgatando os parametros da url
    $titulo = $_GET['titulo'];
    $mensagem = $_GET['mensagem'];
    $status = $_GET['status'];
    $idUsuario = $_GET['idUsuario'];
    $local = $_GET['local'];
    //TODO: pegar array de fotos
    $caminhoImagem = $_GET['imagem'];

    // comando sql
    $tsql = "INSERT INTO chamados (titulo, mensagem, local, data, status, idUsuario) VALUES (?,?, ?,GETDATE(),?,?);";

    // definindo os parametros a serem salvos
    $params = array("$titulo", "$mensagem", "$local", $status, $idUsuario);

    // executando o sql
    $stm = sqlsrv_query($conexao, $tsql, $params);
    $rowChamado = sqlsrv_fetch_assoc($stm);
    // retornando mensagem para saber se salvou ou não
    if($rowChamado){
        $idChamado = $row['idFoto'];
        $tsqlImagem = "INSERT INTO fotosChamados (caminhoFoto, idChamado) VALUES (?,?);";
        $paramsFoto = array("$caminhoImagem", $idChamado);
        $stmFoto = sqlsrv_query($conexao, $tsql, $params);
        if ($stmFoto) {
            echo json_encode(array("Sucesso" => true));
        } else {
            echo json_encode(array("Sucesso" => false));
        }
    } else {
        echo json_encode(array("Sucesso" => false));
    }
?>
