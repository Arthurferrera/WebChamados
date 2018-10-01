<?php
    require_once('conexao.php');
    $id = $_GET['id'];
    $nivel = $_GET['nivel'];

    if ($nivel == "Cliente") {
        $update = "UPDATE usuario SET logado = 0 WHERE id =".$id;
    } else if($nivel == "Administrador"){
        $update = "UPDATE usuarioAdm SET logado = 0 WHERE id =".$id;
    }
    $stm = sqlsrv_query($conexao, $update);
    if ($stm) {
        echo json_encode(array("sair" => true));
    } else {
        echo json_encode(array("sair" => false));
    }

 ?>
