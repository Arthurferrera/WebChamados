<?php
    @session_start();
    require_once($_SESSION['require']."view/modulo.php");
    require_once($_SESSION['require']."model/funcionarioClass.php");

    autentica();
    $conexao  = conexao();

    $funcionario = new Funcionario();
?>
<script>
    // para o evento submit do form e passa as
    // informações de forma assincrona para a router
    $(document).ready(function(){
           $('#form').submit(function(){
              event.preventDefault();
               $.ajax({
                type: "POST",
                url: "../router.php?controller=funcionario&modo=inserir",
                data: new FormData($('#form')[0]),
                cache: false,
                contentType: false,
                processType: false,
                processData: false,
                async: true,
                success: function(dados){
                    if(dados == 1){
                        // executou
                        Listar();
                    } else if (dados == 0){
                        // erro ao tentar cadastrar
                        alert('Erro ao cadastrar um novo usuário. Tente novamente mais tarde.');
                    } else if (dados == 2){
                        // senha fora do padrão exigido
                        alert('Senha deve conter no letras e números.');
                    } else if (dados == 3){
                        // senha muito pequena
                        alert('Senha deve conter no mínimo 6 caracteres.');
                    }
               }
           });
        });
    });
</script>
<!-- sessão que contém o form para o cadastro de usuario -->
<section class="contentForm">
    <div class="tituloForm">
        Cadastro de Usuários
    </div>

    <div class="contentCampos">
        <form id="form" action="home.php?pag=cadastroUsuario" method="post">
            <input id="id" type="hidden" name="txtId">
            <div class="campos">
                <span>Nome:</span>
                <input id="inpNome" class="campoInput" maxlength="50" type="text" name="txtNome" required>
            </div>
            <div class="campos">
                <span>Login:</span>
                <input id="inpUsuario" class="campoInput" maxlength="25" type="text" name="txtLogin" required>
            </div>
            <div class="campos">
                <span>Senha:</span>
                <input id="inpSenha" class="campoInput" maxlength="20" type="password" name="txtSenha" required>
            </div>
            <div class="campoBotao">
                <input id="btnSalvar" class="botaoSalvar" type="submit" name="btnSalvar" value="Salvar">
            </div>
        </form>
    </div>

    <!-- sessão da tabela que lista todos os usuários -->
    <div class="tableUsuario">
        <div class="contentTitulosUsuarios">
            <div class="titulosTabelaUsuario">
                NOME
            </div>

            <div class="titulosTabelaUsuario">
                LOGIN
            </div>

            <div class="titulosTabelaUsuario">
                SENHA
            </div>

            <div class="titulosTabelaUsuario">
                OPÇÕES
            </div>
        </div>
        <div id="contentRegistrosUsuarios" class="contentRegistrosUsuarios">
            <?php
                // chama-se a função que traz todos os usuários cadastrados
                require_once($_SESSION['require']."controller/controllerFuncionario.php");
                $listFuncionario = new controllerFuncionario();
                $funcionario = $listFuncionario::listarFuncionario();
                $cont = 0;
                while($cont < count($funcionario)){
            ?>
                <div class="linhaRegistroUsuario">
                    <div class="registrosUsuarios">
                        <?php echo $funcionario[$cont]->nome; ?>
                    </div>
                    <div class="registrosUsuarios">
                        <?php echo $funcionario[$cont]->usuario; ?>
                    </div>
                    <div class="registrosUsuarios">
                        <?php echo $funcionario[$cont]->senha; ?>
                    </div>
                    <div class="registrosUsuarios">
                        <div class="atualizar">
                            <a id="excluir" onclick="Excluir(<?php echo $funcionario[$cont]->idFuncionario ?>);"> <img src="imagens/deletar.png" alt="Deletar Funcionário" title="Deletar Funcionário" width="25" height="25"> </a>
                            <a id="editar" onclick="Buscar(<?php echo $funcionario[$cont]->idFuncionario ?>);"> <img src="imagens/editarUsuario.png" alt="Editar Funcionário" title="Editar Funcionário" width="25" height="25"> </a>
                        </div>
                    </div>
                </div>
            <?php
                    $cont++;
                } ?>
        </div>
    </div>
</section>
