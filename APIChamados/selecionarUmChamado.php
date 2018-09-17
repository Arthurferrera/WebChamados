<?php
    //conexao com o banco
    require_once("conexao.php");
    require_once("selecionarObservacoes.php");
    //resgatando o id
    $id = $_GET['id'];
    //comando sql
    $tsql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                    u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data AS data
                    FROM chamados AS c
                    INNER JOIN usuario AS u
                    ON c.idUsuario = u.id
                    WHERE c.id =".$id;
    //executando o comando
    $stm = sqlsrv_query($conexao, $tsql);
    //caso o comando seja executado com sucesso, da um echo no objeto
    if($chamado = sqlsrv_fetch_object($stm)){
        $listaObservacoes = selectObs($id, $conexao);
        echo json_encode(array("chamado"=>$chamado, "obs"=>$listaObservacoes));
    }
?>
