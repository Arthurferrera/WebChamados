<?php
class Funcionario {

    // atributos de um funcionario
    public $idFuncionario;
    public $nome;
    public $idNivel;
    public $usuario;
    public $senha;
    public $idNivelUsuario;

    function __construct() {
        require_once("bdClass.php");
    }

    // método que recebe login e senha como parâmetro
    // faz a autentição do usuario no sistema
    public function Login($funcionario) {
        $sql = "SELECT * FROM usuarioAdm WHERE BINARY_CHECKSUM(login) = BINARY_CHECKSUM('$funcionario->usuario')
                AND BINARY_CHECKSUM(senha) = BINARY_CHECKSUM('$funcionario->senha') AND idNivelUsuario = 1";

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $select = sqlsrv_query($pdoCon, $sql);

        if($rs = sqlsrv_fetch_array($select)){
            $nome = $rs['nome'];
        }
        $rows_affected = sqlsrv_rows_affected($select);


        // verificando se algum registro foi retornado
        if ($rows_affected > 0) {
            $_SESSION['nome'] = $nome;
            $_SESSION['login'] = true;
            echo 1;
        } else {
            unset($_SESSION['nome']);
            unset($_SESSION['login']);
            return false;
        }
        $con->Desconectar();
    }

    // método que cadastra um novo usuario do sistema
    // recebe um objeto do tipo 'funcionario' como parametro
    public function Inserir($funcionario){

        // faz um filtro na senha, para ver se contém letras e números
        $temNumeros = filter_var($funcionario->senha, FILTER_SANITIZE_NUMBER_INT) !== '';
        $temLetras = preg_match('/[a-z-A-Z]/', $funcionario->senha);

        // faz a verificação da senha
        if ($temNumeros && $temLetras) {
            $sql = "INSERT INTO usuarioAdm (nome, login, senha, idNivelUsuario) VALUES (?,?,?,1)";
            $params = array("$funcionario->nome", "$funcionario->usuario", "$funcionario->senha");

            $con = new Sql_db();
            $pdoCon = $con->Conectar();

            $stm = sqlsrv_query($pdoCon, $sql, $params);

            if ($stm) {
                // foi executado
                echo 1;
            } else {
                // não executou
                echo 0;
            }
        } else {
            // senha fora do exigido
            echo 2;
        }
    }

    // método que atualiza as informações do usuario
    public function Editar($funcionario){
            $sql = "UPDATE usuarioAdm SET nome = '".$funcionario->nome."', login = '".$funcionario->usuario."', senha = '".$funcionario->senha."' WHERE id =".$funcionario->idFuncionario;

            $con = new Sql_db();
            $pdoCon = $con->Conectar();

            $stm = sqlsrv_query($pdoCon, $sql);

            if ($stm) {
                echo 1;
            } else {
                echo 0;
            }
    }

    // traz um usuario pelo id que está cadastrado no banco
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

    // método que traz todos os usuários cadastrados no banco
    // para fazer a listagem dos registros
    public function SelectAllFuncionario(){

        $sql = "SELECT u.id, u.nome, u.login, u.senha, u.idNivelUsuario
                FROM usuarioAdm AS u
                INNER JOIN nivelUsuario AS n
                ON n.idNivelUsuario = u.idNivelUsuario
                WHERE u.id != 1";

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

    // método recebe um ID, como paramêtro
    // para excluir um usuario
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

    // // função que exige um certo padrão para a senha
    // public function validarSenha($senha){
    //
    //     // filtra só os valores inteiros
    //     $temNumeros = filter_var($senha, FILTER_SANITIZE_NUMBER_INT) !== '';
    //     // 1 (true, tem maiusculas) ou 0 (false, não tem)
    //     $temLetras = preg_match('/[a-z-A-Z]/', $senha);
    //     // 1 (true, tem maiusculas) ou 0 (false, não tem)
    //     // $temMaiusculas = preg_match('/[A-Z]/', $senha);
    //
    //     if ($temNumeros && $temLetras) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}
 ?>
