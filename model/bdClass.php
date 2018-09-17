<?php

class Sql_db {

    private $server;
    private $user;
    private $password;
    private $dataBaseName;

    public function __construct() {
        $this->server = "LENOVO-PC";
        $this->user = "sa";
        $this->password = "123456";
        $this->dataBaseName = "CHAMADOS_APP";
    }

    public function Conectar(){
        try {
            $conexaoInfo = array("Database"=>$this->dataBaseName, "UID"=>$this->user, "PWD"=>$this->password, "CharacterSet"=>"UTF-8");
            $conexao = sqlsrv_connect($this->server, $conexaoInfo);
            return $conexao;
        } catch (Exception $e) {
            echo ("Erro tentar conectar com o banco de dados <br>".$e);
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function Desconectar(){
        $conexao = null;
    }
}
 ?>
