<?php
    // require_once "conexao.php";

function selectObs($idChamado, $conexao){
    // $idChamado = $_GET['id'];

    $tsql = "SELECT * FROM observacao WHERE idChamado =".$idChamado." ORDER BY idObservacao DESC ";

    $stm = sqlsrv_query($conexao, $tsql);

    $lista = array();

    while($chamado = sqlsrv_fetch_array($stm)){
        $lista[] = $chamado;
    }

    return $lista;
}
 ?>