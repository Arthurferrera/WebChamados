<?php
    // incluindo a conexao
    require_once("conexao.php");
    // resgatando os parametros da url
    $usuario = $_GET['usuario'];
    $senha = $_GET['senha'];
    // comando sql
    //este select 'converte' o registro do BD, para binario
    // possibilitando a diferenciação de letras maiúsculas e minúsculas
    $tsql = "SELECT u.id, u.cnpj, u.razaoSocial, u.nome, u.usuario, u.senha, u.idNivelUsuario, n.nivel, n.idNivelUsuario
            FROM usuario AS u
            INNER JOIN nivelUsuario AS n
            ON u.idNivelUsuario = n.idNivelUsuario
            WHERE BINARY_CHECKSUM(usuario) = BINARY_CHECKSUM('$usuario')
            AND BINARY_CHECKSUM(senha) = BINARY_CHECKSUM('$senha') AND u.logado != 1";
    // executando o comando no banco
    $stm = sqlsrv_query($conexao, $tsql);

    // verificando se o select retornou algum resultado
    // caso sim retorna o objeto usuario, e permite o login
    // caso contrario, não permite o login
    if (sqlsrv_has_rows($stm) > 0 && $usuario = sqlsrv_fetch_object($stm)) {
        echo json_encode(array("login" => true, "usuario" => $usuario));
        $update = "UPDATE usuario set logado = 1 where id =".$usuario->id;
        sqlsrv_query($conexao, $update);
    } else {
        // echo json_encode(array("login" => false));
        $tsqlAdm = "SELECT u.id, u.nome, u.login, u.senha, u.idNivelUsuario, n.nivel, n.idNivelUsuario
                FROM usuarioAdm AS u
                INNER JOIN nivelUsuario AS n
                ON u.idNivelUsuario = n.idNivelUsuario
                WHERE BINARY_CHECKSUM(login) = BINARY_CHECKSUM('$usuario')
                AND BINARY_CHECKSUM(senha) = BINARY_CHECKSUM('$senha') AND u.logado != 1";
        $stmAdm = sqlsrv_query($conexao, $tsqlAdm);
        if (sqlsrv_has_rows($stmAdm) > 0 && $usuario = sqlsrv_fetch_object($stmAdm)) {
            $update = "UPDATE usuarioAdm set logado = 1 where id =".$usuario->id;
            sqlsrv_query($conexao, $update);
            echo json_encode(array("login" => true, "usuario" => $usuario));
        } else {
            echo json_encode(array("login" => false));
        }
    }
?>
