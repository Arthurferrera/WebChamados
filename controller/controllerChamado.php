<?php
class controllerChamado {
    function __construct() {
        require_once($_SESSION['require']."model/chamadoClass.php");
    }

    public function atualizarChamado(){
        $chamado = new Chamado();
        $chamado->observacao = $_POST['txtObservacao'];
        $chamado->status = $_POST['rdoFinalizar'];
        $chamado->idChamado = $_POST['txtIdChamado'];
        return $chamado->Atualizar($chamado);
    }

    public function buscarChamado($idChamado){
        $chamado = new Chamado();
        $chamado->idChamado = $idChamado;
        $retornoChamado = $chamado::SelectById($chamado->idChamado);
        return $retornoChamado;
    }

    public function buscarObservacoes($idChamado){
        $listObervacoes = new Chamado();
        $listObervacoes->idChamado = $idChamado;
        return $listObervacoes::SelectObsById($listObervacoes->idChamado);
    }

    public function listarChamado($status, $tipoSelect){
        $chamado = new Chamado();
        if ($tipoSelect == 'SelectDiaResolvido') {
            $retornoChamado = $chamado::SelectDiaResolvido();
        } else {
            $retornoChamado = $chamado::SelectAllPendentes($status);
        }
        return $retornoChamado;
    }

    public function filtroPorData($status){
        $chamado = new Chamado();
        $chamado->dtInicio = $_POST['txtDtInicio'];
        $chamado->dtFim = $_POST['txtDtFim'];
        $chamado->empresaInicial = $_POST['txtEmpresaInicial'];
        $chamado->empresaFinal = $_POST['txtEmpresaFinal'];
        $retornoChamado = $chamado::FiltroPorData($chamado, $status);
        return $retornoChamado;
    }

    public function Estatisticas(){
        $chamado = new Chamado();
        $retornoEstatisticas = $chamado::Estatisticas();
        return $retornoEstatisticas;
    }

    // função que chama o método que retorna as empresas de clientes cadastrados
    // public function empresas(){
    //     $chamado = new Chamado();
    //     return $chamado::listarEmpresas();
    // }
}
 ?>
