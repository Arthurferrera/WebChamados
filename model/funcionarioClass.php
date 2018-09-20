<?php
class Funcionario {

    public $idFuncionario;
    public $nome;
    public $idNivel;
    public $usuario;
    public $senha;
    public $idNivelUsuario;

    function __construct() {
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

    public function Inserir($funcionario){
        session_start();

        $sql = "INSERT INTO usuarioAdm (nome, login, senha, idNivelUsuario) VALUES (?,?,?,1)";
        $params = array("$funcionario->nome", "$funcionario->usuario", "$funcionario->senha");

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $stm = sqlsrv_query($pdoCon, $sql, $params);

        if ($stm) {
            echo "<script>
                alert('Cadastro efetuado.');
                window.location.href = 'http://localhost/WebChamados/view/home.php?pag=cadastroUsuario';
            </script>";
        }
    }

    public function Editar($idFuncionario){
            $sql "UPDATE usuarioAdm SET nome = , login = ,"
    }

    public function SelectAllFuncionario(){

        $sql = "SELECT u.id, u.nome, u.login, u.senha, u.idNivelUsuario
                FROM usuarioAdm AS u
                INNER JOIN nivelUsuario AS n
                ON n.idNivelUsuario = u.idNivelUsuario";

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $select = sqlsrv_query($pdoCon, $sql);
        $rows_affected = sqlsrv_rows_affected($select);
        $cont = 0;

        if ($rows_affected === false) {
            echo "erro na chamada";
        } elseif ($rows_affected == -1) {
            while ($rs = sqlsrv_fetch_array($select)) {
                $funcionario[] = new Funcionario();
                $funcionario[$cont]->nome = $rs['nome'];
                $funcionario[$cont]->usuario = $rs['login'];
                $funcionario[$cont]->senha = $rs['senha'];
                $funcionario[$cont]->idFuncionario = $rs['id'];
                $cont++;
            }
            return $funcionario;
        }else {
            return null;
        }
        $con->Desconectar();
    }

    public function Excluir($id){
        $sql = "DELETE FROM usuarioAdm WHERE id =".$id;

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $stm = sqlsrv_query($pdoCon, $sql);
        $rows_affected = sqlsrv_rows_affected($stm);

        if ($rows_affected === false) {
            return false;
        } elseif ($rows_affected == -1) {
            return true;
        } else {
            return false;
        }
        $con->Desconectar();

    }
}
 ?>
