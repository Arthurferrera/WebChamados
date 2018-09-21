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
                AND BINARY_CHECKSUM(senha) = BINARY_CHECKSUM('$funcionario->senha') AND idNivelUsuario = 1";
        echo $sql;
        echo "<br>";

        $con = new Sql_db();
        $pdoCon = $con->Conectar();
        $select = sqlsrv_query($pdoCon, $sql);
        echo $select;
        $idFuncionario = 0;
        echo "<br>";
        if($rs = sqlsrv_fetch_array($select)){
            $idFuncionario = $rs['id'];
        }
        echo $idFuncionario;
        echo "<br>";
        $con->Desconectar();

        if ($idFuncionario > 0) {
            $_SESSION['idAdmin'] =  $idFuncionario;
            header('location:index.php');
        } else {
            session_destroy();
            echo "<script>
                alert('Usuário e/ou senha incorreta');
                window.history.back(-1);
            </script>";
            header('location:index.php');
            // TODO: CONTINUAR FAZENDO O LOGIN CORRETAMENTE
        }
    }

    public function Inserir($funcionario){
        session_start();

        if (validarSenha($funcionario->senha)) {
            $sql = "INSERT INTO usuarioAdm (nome, login, senha, idNivelUsuario) VALUES (?,?,?,1)";
            $params = array("$funcionario->nome", "$funcionario->usuario", "$funcionario->senha");

            $con = new Sql_db();
            $pdoCon = $con->Conectar();

            $stm = sqlsrv_query($pdoCon, $sql, $params);

            if ($stm) {
                echo "<script>
                    alert('Cadastro efetuado.');
                    window.location.href = 'home.php?pag=cadastroUsuario';
                </script>";
            }
        } else {
            echo "<script>
                $(document).html(function(){
                    alert('Senha deve conter letras e números');
                })
            </script>";
        }


    }

    public function Editar($funcionario){
            $sql = "UPDATE usuarioAdm SET nome = '".$funcionario->nome."', login = '".$funcionario->usuario."', senha = '".$funcionario->senha."' WHERE id =".$funcionario->idFuncionario;

            $con = new Sql_db();
            $pdoCon = $con->Conectar();

            $stm = sqlsrv_query($pdoCon, $sql);

            if ($stm) {
                return true;
            }
    }

    public function SelectByIdFuncionario($idFuncionario){
        $sql = "SELECT u.id, u.nome, u.login, u.senha, u.idNivelUsuario
                FROM usuarioAdm AS u
                INNER JOIN nivelUsuario AS n
                ON n.idNivelUsuario = u.idNivelUsuario WHERE id =".$idFuncionario;

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $select = sqlsrv_query($pdoCon, $sql);
        $rows_affected = sqlsrv_rows_affected($select);

        if ($rows_affected === false) {
            echo "erro na chamada";
        } elseif ($rows_affected == -1) {
            if ($rs = sqlsrv_fetch_array($select)) {
                $funcionario = new Funcionario();
                $funcionario->nome = $rs['nome'];
                $funcionario->usuario = $rs['login'];
                $funcionario->senha = $rs['senha'];
                $funcionario->idFuncionario = $rs['id'];
            }
            return $funcionario;
        } else {
            return null;
        }
        $con->Desconectar();
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
        } else {
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

    // função que exige um certo padrão para a senha
    function validarSenha($senha){

        // filtra só os valores inteiros
        $temNumeros = filter_var($senha, FILTER_SANITIZE_NUMBER_INT) !== '';
        // 1 (true, tem maiusculas) ou 0 (false, não tem)
        $temLetras = preg_match('/[a-z-A-Z]/', $senha);
        // 1 (true, tem maiusculas) ou 0 (false, não tem)
        // $temMaiusculas = preg_match('/[A-Z]/', $senha);

        if ($temNumeros && $temLetras) {
            return true;
        } else {
            return false;
        }
    }
}
 ?>
