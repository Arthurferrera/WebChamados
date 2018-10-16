<!-- arquivo que aparece na pagina HOME, do sistema -->
<?php
    session_start();
    require_once($_SESSION['require']."view/modulo.php");
    autentica();
    $conexao  = conexao();
 ?>
