<?php
    require_once($_SESSION['require']."view/modulo.php");
    $conexao = conexao();

    /**
     *
     */
    class controllerChamadoUsuario {
        function __construct() {
            require_once($_SESSION['require']."model/chamadoClass.php");
        }

        // método de inserir um novo chamado
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
        public static function listarChamadoCliente($status, $idCliente){
            $chamado = new Chamado();
            $retornoChamado = $chamado::SelectAllCliente($status, $idCliente);
            return $retornoChamado;
        }
    }




 ?>
