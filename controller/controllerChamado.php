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
        // return $retornoObservacoes;
    }

    public function listarChamado($status, $tipoSelect){
        // require_once("../model/chamadoClass.php");
        $chamado = new Chamado();
        if ($tipoSelect == 'SelectDiaResolvido') {
            $retornoChamado = $chamado::SelectDiaResolvido();
        } else {
            $retornoChamado = $chamado::SelectAllPendentes($status);
        }
        return $retornoChamado;
    }

    public function filtroPorData(){
        // require_once("../model/chamadoClass.php");
        $chamado = new Chamado();
        $chamado->dtInicio = $_POST['txtDtInicio'];
        $chamado->dtFim = $_POST['txtDtFim'];
        $chamado->empresaInicial = $_POST['sltEmpresaInicial'];
        $chamado->empresaFinal = $_POST['sltEmpresaFinal'];
        $retornoChamado = $chamado::FiltroPorData($chamado);
        return $retornoChamado;
    }

    public function Estatisticas(){
        // require_once("../model/chamadoClass.php");
        $chamado = new Chamado();
        $retornoEstatisticas = $chamado::Estatisticas();
        return $retornoEstatisticas;
    }

    public function empresas(){
        $chamado = new Chamado();
        return $chamado::listarEmpresas();
    }

    public function pesquisaChamado($pesquisaEmpresa){
        $chamado = new Chamado();
        return $chamado::filtroPesquisaEmpresa($pesquisaEmpresa);
    }
}
 ?>
