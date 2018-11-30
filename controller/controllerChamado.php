<?php
    // session_start();
    require_once($_SESSION['require']."view/modulo.php");
    //autentica();
    $conexao  = conexao();

class controllerChamado {
    function __construct() {
        require_once($_SESSION['require']."model/chamadoClass.php");
    }

    // metodo que grava um novo chamado
    public static function Inserir(){
        // resgata as informações
        $chamado                = new Chamado();
        $chamado->titulo        = $_POST['titulo'];
        $chamado->mensagem      = $_POST['mensagem'];
        $chamado->status        = $_POST['status'];
        $chamado->idUsuario     = $_POST['idUsuario'];
        $chamado->local         = $_POST['local'];
        return $chamado->Inserir($chamado);
    }

    // atualiza o chamado, ou seja, adiciona alguma observação/resposta, e muda ou não o status
    public static function atualizarChamado(){
        $chamado                = new Chamado();
        $chamado->observacao    = $_POST['txtObservacao'];
        $chamado->status        = $_POST['rdoFinalizar'];
        $chamado->idChamado     = $_POST['txtIdChamado'];
        return $chamado->Atualizar($chamado);
    }

    // método busca um chamado pelo id
    public static function buscarChamado($idChamado){
        $chamado                = new Chamado();
        $chamado->idChamado     = $idChamado;
        $retornoChamado         = $chamado::SelectById($chamado->idChamado);
        return $retornoChamado;
    }

    // busca imagens pelo id do chamado
    public static function buscarFotosChamados($idChamado){
        $fotosChamados              = new Chamado();
        $fotosChamados->idChamado   = $idChamado;
        return $fotosChamados::SelectFotoByIdChamado($fotosChamados->idChamado);
    }

    // busca as observações de um chamado
    public static function buscarObservacoes($idChamado){
        $listObervacoes             = new Chamado();
        $listObervacoes->idChamado  = $idChamado;
        return $listObervacoes::SelectObsById($listObervacoes->idChamado);
    }

    // lista todos os chamados de acordo com o status desejado
    public static function listarChamado($status, $tipoSelect){
        $chamado = new Chamado();
        if ($tipoSelect == 'SelectDiaResolvido') {
            $retornoChamado = $chamado::SelectDiaResolvido();
        } else {
            $retornoChamado = $chamado::SelectAll($status);
        }
        return $retornoChamado;
    }

    // método filtra od chamados pela data e o nome da empresa
    public static function filtroPorData($status){
        $chamado                    = new Chamado();
        $chamado->dtInicio          = $_POST['txtDtInicio'];
        $chamado->dtFim             = $_POST['txtDtFim'];
        $chamado->empresaInicial    = $_POST['txtEmpresaInicial'];
        $chamado->empresaFinal      = $_POST['txtEmpresaFinal'];

        $chamado->dtInicio  = explode("-", $chamado->dtInicio);
        $chamado->dtInicio  = $chamado->dtInicio[2]."/".$chamado->dtInicio[1]."/".$chamado->dtInicio[0];

        $chamado->dtFim     = explode("-", $chamado->dtFim);
        $chamado->dtFim     = $chamado->dtFim[2]."/".$chamado->dtFim[1]."/".$chamado->dtFim[0];

        $retornoChamado     = $chamado::FiltroPorData($chamado, $status);
        return $retornoChamado;
    }

    // método que traz as informações de Estatisticas dos chamados
    public static function Estatisticas(){
        $chamado = new Chamado();
        $retornoEstatisticas = $chamado::Estatisticas();
        return $retornoEstatisticas;
    }

    public static function parcialEstatistica($tipoEstatistica){
        $chamado = new Chamado();
        if ($tipoEstatistica == "filtro") {
            $chamado->dtInicio  = $_POST['txtDtInicio'];
            $chamado->dtFim     = $_POST['txtDtFim'];

            $chamado->dtInicio  = explode("-", $chamado->dtInicio);
            $chamado->dtInicio  = $chamado->dtInicio[2]."/".$chamado->dtInicio[1]."/".$chamado->dtInicio[0];

            $chamado->dtFim     = explode("-", $chamado->dtFim);
            $chamado->dtFim     = $chamado->dtFim[2]."/".$chamado->dtFim[1]."/".$chamado->dtFim[0];
        } else if ($tipoEstatistica == "dia") {
            $chamado->dtInicio  = "";
            $chamado->dtFim     = "";
        }
        $retornoEstatisticas = $chamado::EstatisticasParciais($chamado, $tipoEstatistica);

        return $retornoEstatisticas;
    }


    // função que chama o método que retorna as empresas de clientes cadastrados
    public static function empresas(){
        $chamado = new Chamado();
        return $chamado::listarEmpresas();
    }
}
 ?>
