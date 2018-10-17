<?php

// session_start();
require_once($_SESSION['require']."view/modulo.php");
//autentica();
$conexao  = conexao();

class Sql_db {

    // atributos necessários para conexão com banco
    private $server;
    private $user;
    private $password;
    private $dataBaseName;

    // contrutor, atribui-se os valores
    public function __construct() {
        $this->server = "LENOVO-PC";
        $this->user = "sa";
        $this->password = "123456";
        $this->dataBaseName = "CHAMADOS_APP";
    }

    // função que conecta com o banco de dados
    public function Conectar(){
        try {
            $conexaoInfo = array("Database"=>$this->dataBaseName, "UID"=>$this->user, "PWD"=>$this->password, "CharacterSet"=>"UTF-8");
            $conexao = sqlsrv_connect($this->server, $conexaoInfo);
            return $conexao;
        } catch (Exception $e) {
            echo ("Erro tentar Conectar com o banco de dados <br>".$e);
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // função que zera a conexao com o banco
    public function Desconectar(){
        $conexao = null;
    }
}
 ?>
