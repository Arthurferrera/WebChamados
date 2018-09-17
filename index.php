<?php
    session_start();
    if (isset($_GET['out'])) {
        session_destroy();
        header('location:index.php');
    } else if (isset($_SESSION['idAdmin'])) {
        require_once("view/home.php");
        header('location:view/home.php?pag=home');
    } else {
        require_once("view/autenticacao.php");
    }
?>
