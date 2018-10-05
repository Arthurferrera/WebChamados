<?php
    session_start();

    // verifica se existe uma solicitação de logout e faz o logout
    if (isset($_GET['out'])) {
        session_destroy();
        header('location:index.php');
    } else if (isset($_SESSION['idAdmin'])) {
        header('location:view/home.php?pag=home');
    } else {
        require_once("view/autenticacao.php");
    }
    // coloca em uma variavel de sessão, o caminho padrão dos require
    $caminho = $_SERVER['DOCUMENT_ROOT']."/WebChamados/";
    $_SESSION['require'] = $caminho;
?>
