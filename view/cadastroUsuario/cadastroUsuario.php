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

<div class="card-body">
    <h1 class="card-title"><i class="menu-icon mdi mdi-account-multiple"></i> Usuários do Sistema</h1>

<form id="form" action="home.php?pag=cadastroUsuario" method="post">
    <input id="id" type="hidden" name="txtId">
    <div class="form-group">
        <div class="row">
            <div class="form-group col-md-4">
                <label for="textinput">Nome</label>
                <input id="inpNome" name="txtNome" maxlength="50" type="text" placeholder="" class="form-control" required="">
            </div>
            <div class="form-group col-md-4">
                <label for="textinput">Login</label>            
                <input id="inpUsuario" name="txtLogin" maxlength="25" type="text" placeholder="" class="form-control" required="">
            </div>
            <div class="form-group col-md-4">
                <label for="passwordinput">Senha</label>
                <input id="inpSenha" name="txtSenha" maxlength="20" type="password" placeholder="" class="form-control" required="">
            </div>
            <div class="form-group col-md-4">
                <button type="submit" id="btnSalvar" class="btn btn-success"><span class="mdi mdi-content-save"></span> Salvar</button>                
            </div>    
        </div>
    </div>    
</form>

<!-- Tabela que lista os usuários do sistema -->
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Login</th>
            <th>Opções</th>
        </tr>
        </thead>       
        <tbody>
        <?php
            // chama-se a função que traz todos os usuários cadastrados
            require_once($_SESSION['require']."controller/controllerFuncionario.php");
            $listFuncionario = new controllerFuncionario();
            $funcionario = $listFuncionario::listarFuncionario();
            $cont = 0;
            while($cont < count($funcionario)){
        ?>
        <tr>
            <td><?php echo $funcionario[$cont]->nome; ?></td>
            <td><?php echo $funcionario[$cont]->usuario; ?></td>
            <td>
                <div class="atualizar">
                    <button onclick="Buscar(<?php echo $funcionario[$cont]->idFuncionario ?>);" class="btn btn-dark"><i class="mdi mdi-lead-pencil"></i></button>
                    <button onclick="Excluir(<?php echo $funcionario[$cont]->idFuncionario ?>);" class="btn btn-danger"><i class="mdi mdi-close-circle"></i></button>
                </div>                
            </td>
        </tr>
        <?php
        $cont++;
    } ?>                
        </tbody>
    </table>
</div>