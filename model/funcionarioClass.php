<?php
class Funcionario {

    public $idFuncionario;
    public $nome;
    public $idNivel;
    public $usuario;
    public $senha;
    public $idNivelUsuario;

    public function __construct() {
        require_once("bdClass.php");
    }

    public function Login($funcionario) {
        session_start();

            $sql = "SELECT * FROM usuarioAdm WHERE BINARY_CHECKSUM(login) = BINARY_CHECKSUM('$funcionario->usuario')
                AND BINARY_CHECKSUM(senha) = BINARY_CHECKSUM('$funcionario->senha')";

        $con = new Sql_db();
        $pdoCon = $con->Conectar();
        $select = sqlsrv_query($pdoCon, $sql);

        $idFuncionario = 0;

        if($rs = sqlsrv_fetch_array($select)){
            $idFuncionario = $rs['id'];
            $idNivelUsuario = $rs['idNivelUsuario'];
        }

        $con->Desconectar();

        if ($idNivelUsuario != 1) {
            session_destroy();
            header('location:index.php');
        }
        if ($idFuncionario > 0) {
            $_SESSION['idAdmin'] =  $idFuncionario;
            header('location:index.php');
        } else {
            session_destroy();
            echo "<script>
                alert('Usuário e ou senha incorreta(o)');
                window.location.href = 'index.php';
            </script>";
        }
    }
}
 ?>
