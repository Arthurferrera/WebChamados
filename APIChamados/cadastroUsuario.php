<?php
    // incluindo a conexao
    require_once("conexao.php");
    require_once("validacoes.php");

    // resgatando os parametros da url
    $cnpj = $_GET['cnpj'];
    $razaoSocial = $_GET['razaoSocial'];
    $nome = $_GET['nome'];
    $usuario = $_GET['usuario'];
    $senha = $_GET['senha'];

    // variaveis que serão retornadas no array
    // tipo boolean, true quando a tarefa e executada e false quando não
    $valorCnpjValidado = "";
    $valorUsuario = "";
    $valorSucesso = "";
    $valorCnpj = "";
    $valorSenhaValidada = "";

    // chamando a função que valida o cnpj
	$cnpjValidado = validarCnpj($cnpj);
    $senhaValidada = validarSenha($senha);

    // verifica se o cnpj é valido
    // caso sim, a variavel $valorCnpjValidado recebe true
	if ($cnpjValidado) {
        $valorCnpjValidado = true;
        // echo json_encode(array("Validado" => true));

        // comando sql que verifica se o usuario ja existe
        $select = "SELECT * FROM usuario WHERE usuario = '$usuario'";

        // executando o comando
        $stmSelect = sqlsrv_query($conexao, $select);

        // fazendo a verificação, caso true, o usuario existe
        if (sqlsrv_has_rows($stmSelect) > 0) {
            $valorUsuario = true;
            // echo json_encode(array("usuarioExiste" => true));
        } else {
            $valorUsuario = false;
            // echo json_encode(array("usuarioExiste" => false));

            // select que busca um cnpj especifico
            // (que o usario digitou no aplicativo, na hora do cadastro de usuario)
            $selectCNPJ = "SELECT cnpj FROM usuario WHERE cnpj = '$cnpj'";
            $stmCNPJ = sqlsrv_query($conexao, $selectCNPJ);
            if (sqlsrv_has_rows($stmCNPJ) > 0) {
                $valorCnpj = true;
            } else {
                $valorCnpj = false;
                if ($senhaValidada) {
                    $valorSenhaValidada = true;
                    // comando sql que insere um chamado no banco
                    $tsql = "INSERT INTO usuario (cnpj, razaoSocial, nome, usuario, senha, idNivelUsuario) VALUES (?, ?, ?, ?, ?, 2);";
                    // definindo os parametros
                    $params = array("$cnpj", "$razaoSocial", "$nome", "$usuario", "$senha");

                    // executando o sql no banco
                    $stm = sqlsrv_query($conexao, $tsql, $params);
                    // retornando true ou false, para saber se gravou ou não
                    if($stm){
                        $valorSucesso = true;
                        // echo json_encode(array("Sucesso" => true));
                    } else {
                        $valorSucesso = false;
                        // echo json_encode(array("Sucesso" => false));
                    }
                } else {
                    $valorSenhaValidada = false;
                }

            }
        }
	} else {
        $valorCnpjValidado = false;
		// echo json_encode(array("Validado" => false));
	}
    // retorna um array em formato json, com os resultados obtidos nas operações
    $retorno = json_encode(array("Validado" => $valorCnpjValidado, "cnpjExiste" => $valorCnpj,"usuarioExiste" => $valorUsuario, "senhaValidada" => $valorSenhaValidada,"Sucesso" => $valorSucesso));
    echo $retorno;
?>
