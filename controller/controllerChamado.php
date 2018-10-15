<?php
    // session_start();
    require_once($_SESSION['require']."view/modulo.php");
    autentica();
    $conexao  = conexao();

class controllerChamado {
    function __construct() {
        require_once($_SESSION['require']."model/chamadoClass.php");
    }

    // atualiza o chamado, ou seja, adiciona alguma observação/resposta, e muda ou não o status
    public function atualizarChamado(){
        $chamado = new Chamado();
        $chamado->observacao = $_POST['txtObservacao'];
        $chamado->status = $_POST['rdoFinalizar'];
        $chamado->idChamado = $_POST['txtIdChamado'];
        return $chamado->Atualizar($chamado);
    }

    // método busca um chamado pelo id
    public function buscarChamado($idChamado){
        $chamado = new Chamado();
        $chamado->idChamado = $idChamado;
        $retornoChamado = $chamado::SelectById($chamado->idChamado);
        return $retornoChamado;
    }

    // busca as observações de um chamado
    public function buscarObservacoes($idChamado){
        $listObervacoes = new Chamado();
        $listObervacoes->idChamado = $idChamado;
        return $listObervacoes::SelectObsById($listObervacoes->idChamado);
    }

    // lista todos os chamados de acordo com o status desejado
    public function listarChamado($status, $tipoSelect){
        $chamado = new Chamado();
        if ($tipoSelect == 'SelectDiaResolvido') {
            $retornoChamado = $chamado::SelectDiaResolvido();
        } else {
            $retornoChamado = $chamado::SelectAll($status);
        }
        return $retornoChamado;
    }

    // método filtra od chamados pela data e o nome da empresa
    public function filtroPorData($status){
        $chamado = new Chamado();
        $chamado->dtInicio = $_POST['txtDtInicio'];
        $chamado->dtFim = $_POST['txtDtFim'];
        $chamado->empresaInicial = $_POST['txtEmpresaInicial'];
        $chamado->empresaFinal = $_POST['txtEmpresaFinal'];
        $retornoChamado = $chamado::FiltroPorData($chamado, $status);
        return $retornoChamado;
    }

    // método que traz as informações de Estatisticas dos chamados
    public function Estatisticas(){
        $chamado = new Chamado();
        $retornoEstatisticas = $chamado::Estatisticas();
        return $retornoEstatisticas;
    }

    // função que chama o método que retorna as empresas de clientes cadastrados
    public function empresas(){
        $chamado = new Chamado();
        return $chamado::listarEmpresas();
    }
}
 ?>
