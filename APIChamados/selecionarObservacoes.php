<?php
function selectObs($idChamado, $conexao){

    $tsql = "SELECT * FROM observacao WHERE idChamado = $idChamado ORDER BY idObservacao DESC ";

    $stm = sqlsrv_query($conexao, $tsql);

    $lista = array();

    while($chamado = sqlsrv_fetch_array($stm)){
        $lista[] = $chamado;
    }

    return $lista;
}
 ?>
