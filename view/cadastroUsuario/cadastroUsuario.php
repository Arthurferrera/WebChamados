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

<div class="tab-pane fade show active" id="v-pills-usuario" role="tabpanel" aria-labelledby="v-pills-usuario-tab">
    <!-- sessão que contém o form para o cadastro de usuario -->
    <section class="contentForm">
        <h3 class="card-title">
            Cadastro de Usuários
        </h3>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="well">
                <!-- <div class="col-sm-8"></div> -->
                <form class="form-inline" id="form" action="home.php?pag=cadastroUsuario" method="post">
                    <input id="id" type="hidden" name="txtId">

                    <label for="txtNome" class="sr-only">Nome</label>
                    <input id="inpNome" type="text" placeholder="Nome" class="form-control mb-2 mr-sm-2" name="txtNome" maxlength="50" required>

                    <label for="txtLogin" class="sr-only">Login</label>
                    <input id="inpUsuario" type="text" placeholder="Login" class="form-control mb-2 mr-sm-2" name="txtLogin" maxlength="25" required>

                    <label for="txtSenha" class="sr-only">Senha</label>
                    <input id="inpSenha" placeholder="Senha" class="form-control mb-2 mr-sm-2" maxlength="20" type="password" name="txtSenha" required>

                    <input id="btnSalvar" class="btn btn-primary mb-2" type="submit" name="btnSalvar" value="Salvar">
                </form>
            </div>
            <div class="col-md-2"></div>
        </div>



        <!-- sessão da tabela que lista todos os usuários -->
        <div class="text-overflow ">
            <table class="table table-striped col-sm-10">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Login</th>
                        <th scope="col">Senha</th>
                        <th scope="col">Opções</th>
                    </tr>
                </thead>
                <div class="table-overflow">
                    <tbody class="table-overflow">
                        <?php
                        // chama-se a função que traz todos os usuários cadastrados
                        require_once($_SESSION['require']."controller/controllerFuncionario.php");
                        $listFuncionario = new controllerFuncionario();
                        $funcionario = $listFuncionario::listarFuncionario();
                        $cont = 0;
                        while($cont < count($funcionario)){
                            ?>
                            <tr>
                                <td>
                                    <?php echo $funcionario[$cont]->nome; ?>
                                </td>
                                <td>
                                    <?php echo $funcionario[$cont]->usuario; ?>
                                </td>
                                <td>
                                    <?php echo $funcionario[$cont]->senha; ?>
                                </td>
                                <td>
                                    <a id="excluir" onclick="Excluir(<?php echo $funcionario[$cont]->idFuncionario ?>);"> <img src="imagens/deletar.png" alt="Deletar Funcionário" title="Deletar Funcionário" width="25" height="25"> </a>
                                    <a id="editar" onclick="Buscar(<?php echo $funcionario[$cont]->idFuncionario ?>);"> <img src="imagens/editarUsuario.png" alt="Editar Funcionário" title="Editar Funcionário" width="25" height="25"> </a>
                                </td>
                            </tr>
                            <?php
                            $cont++;
                        } ?>
                    </tbody>
                </div>
            </table>
        </div>
</div>
