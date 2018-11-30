<?php
    class Usuario {

        public $cnpj;
        public $razaoSocial;
        public $nome;
        public $nomeUsuario;
        public $senha;
        public $idUsuario;
        public $idNivel;

        function __construct() {
            require_once("bdClass.php");
            require_once("validacoes.php");
        }

        public static function Inserir($usuario) {

            // variaveis que serão retornadas no array
            // tipo boolean, true quando a tarefa e executada e false quando não
            $valorCnpjValidado = "";
            $valorUsuario = "";
            $valorSucesso = "";
            $valorCnpj = "";
            $valorSenhaValidada = "";
            $valorTamanhoSenha = "";

            $senhaValidada = validarSenha($usuario->senha);
            $cnpjValidado = validarCnpj($usuario->cnpj);

            // conexao com o banco
            $con = new Sql_db();
            $pdoCon = $con->Conectar();

            if (trim(strlen($usuario->senha)) < 6) {
                $valorTamanhoSenha = false;
            } else {
                $valorTamanhoSenha = true;
                if ($cnpjValidado) {
                    $valorCnpjValidado = true;
                    // consulta que verifica se o nome de usuario já existe
                    $sqlUsuario = "SELECT * FROM usuario WHERE usuario = '$usuario->nomeUsuario'";

                    $stmUsuario = sqlsrv_query($pdoCon, $sqlUsuario);

                    if (sqlsrv_has_rows($stmUsuario) > 0) {
                        $valorUsuario = true;
                    } else {
                        $valorUsuario = false;

                        // consulta que verifica se o cnpj já está cadastrado
                        $sqlCNPJ = "SELECT cnpj FROM usuario WHERE cnpj = '$usuario->cnpj'";
                        $stmCNPJ = sqlsrv_query($pdoCon, $sqlCNPJ);

                        if (sqlsrv_has_rows($stmCNPJ) > 0) {
                            $valorCnpj = true;
                        } else {
                            $valorCnpj = false;
                            if ($senhaValidada) {
                                $valorSenhaValidada  = true;

                                // comando sql que insere um chamado no banco
                                $tsql = "INSERT INTO usuario (cnpj, razaoSocial, nome, usuario, senha, idNivelUsuario) VALUES (?, ?, ?, ?, ?, 2);";
                                $params = array("$usuario->cnpj", "$usuario->razaoSocial", "$usuario->nome", "$usuario->nomeUsuario", "$usuario->senha");
                                $stm = sqlsrv_query($pdoCon, $tsql, $params);
                                if ($stm) {
                                    $valorSucesso = true;
                                } else {
                                    $valorSucesso = false;
                                }
                            } else {
                                $valorSenhaValidada  = false;
                            }
                        }
                    }
                } else {
                    $valorCnpjValidado = false;
                }
            }
            // retorna um array em formato json, com os resultados obtidos nas operações
            $retorno = json_encode(array("tamanhoSenha" => $valorTamanhoSenha, "validado" => $valorCnpjValidado, "cnpjExiste" => $valorCnpj,"usuarioExiste" => $valorUsuario, "senhaValidada" => $valorSenhaValidada,"sucesso" => $valorSucesso));
            echo $retorno;
        }
    }

 ?>
