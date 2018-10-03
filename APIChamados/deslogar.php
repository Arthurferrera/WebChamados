<?php
    // require do arquivo de conexao
    require_once('conexao.php');

    // resgatando parametros da url
    $id = $_GET['id'];
    $nivel = $_GET['nivel'];

    // verificando qual o nivel de usuario
    if ($nivel == "Cliente") {
        $update = "UPDATE usuario SET logado = 0 WHERE id =".$id;
    } else if($nivel == "Administrador"){
        $update = "UPDATE usuarioAdm SET logado = 0 WHERE id =".$id;
    }

    // executando o comando no banco
    $stm = sqlsrv_query($conexao, $update);

    // verificando se foi executado, para retornar algum valor
    if ($stm) {
        echo json_encode(array("sair" => true));
    } else {
        echo json_encode(array("sair" => false));
    }
 ?>
