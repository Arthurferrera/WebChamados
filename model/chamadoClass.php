<?php
class Chamado {

    public $idChamado;
    public $titulo;
    public $mensagem;
    public $status;
    public $dataAbertura;
    public $idUsuario;
    public $cnpj;
    public $razaoSocial;
    public $nomeUsuario;
    public $idObservacao;
    public $observacao;
    public $dataObs;
    public $dtInicio;
    public $dtFim;

    function __construct() {
        require_once("bdClass.php");
    }

    public function Atualizar($chamado){
        session_start();

        $sqlInserirObservacao = "INSERT INTO observacao (observacao, idChamado, dataHora) VALUES ('".$chamado->observacao."', $chamado->idChamado, GETDATE())";
        $sqlAtualizarStatus = "UPDATE chamados SET status = '".$chamado->status."' WHERE id = ".$chamado->idChamado;

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $atualizarStatus = sqlsrv_query($pdoCon, $sqlAtualizarStatus);
        $inserieObservacao = sqlsrv_query($pdoCon, $sqlInserirObservacao);

        if($atualizarStatus && $inserieObservacao){
            echo "<script>
                       alert('Atualização efetuada com sucesso');
                       window.history.go(-1);
                  </script>";
        }
        $con->desonectar();
    }

    public function SelectAllPendentes($status){
        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data
                FROM chamados AS c
                INNER JOIN usuario AS u
                ON c.idUsuario = u.id
                WHERE status = $status ORDER BY idChamado DESC";

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $select = sqlsrv_query($pdoCon, $sql);
        $rows_affected = sqlsrv_rows_affected($select);
        $cont = 0;

        if ($rows_affected === false) {
            echo "erro na chamada";
        } else if($rows_affected == -1) {
            while ($rs = sqlsrv_fetch_array($select)){
                $chamado[] = new Chamado();
                $chamado[$cont]->idChamado = $rs['idChamado'];
                $chamado[$cont]->titulo = $rs['titulo'];
                $chamado[$cont]->mensagem = $rs['mensagem'];
                $chamado[$cont]->status = $rs['status'];
                $chamado[$cont]->dataAbertura = $rs['data'];
                $chamado[$cont]->idUsuario = $rs['usuarioId'];
                $chamado[$cont]->cnpj = $rs['cnpj'];
                $chamado[$cont]->razaoSocial = $rs['razaoSocial'];
                $chamado[$cont]->nomeUsuario = $rs['nome'];
                $cont++;
            }
            return $chamado;
        } else {
            return null;
        }
        $con->desonectar();
    }

    public function SelectById($idChamado){

        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                    u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, CONVERT(nvarchar(30), c.data, 126) AS data
                    FROM chamados AS c
                    INNER JOIN usuario AS u
                    ON c.idUsuario = u.id
                    WHERE c.id =".$idChamado;

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $select = sqlsrv_query($pdoCon, $sql);

        if($rs = sqlsrv_fetch_array($select)){
            $chamado = new Chamado();
            $chamado->idChamado = $rs['idChamado'];
            $chamado->titulo = $rs['titulo'];
            $chamado->mensagem = $rs['mensagem'];
            $chamado->status = $rs['status'];
            $chamado->dataAbertura = $rs['data'];
            $chamado->idUsuario = $rs['cnpj'];
            $chamado->cnpj = $rs['cnpj'];
            $chamado->razaoSocial = $rs['razaoSocial'];
            $chamado->nomeUsuario = $rs['nome'];
            return $chamado;
        }
        $con->desonectar();
    }

    public function SelectObsById($idChamado){

        $sql = "SELECT *, CONVERT(nvarchar(30), dataHora, 126) AS dataConvertida from observacao WHERE idChamado =".$idChamado;

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $select = sqlsrv_query($pdoCon, $sql);
        $cont = 0;

        while($rs = sqlsrv_fetch_array($select)){
            $listObservacoesChamado[] = new Chamado();
            $listObservacoesChamado[$cont]->idObservacao = $rs['idObservacao'];
            $listObservacoesChamado[$cont]->observacao = $rs['observacao'];
            $listObservacoesChamado[$cont]->dataObs = $rs['dataConvertida'];

            $cont++;
        }
        return $listObservacoesChamado;
        $con->desonectar();
    }

    public function FiltroPorData($chamado){

        $dataInicio = $chamado->dtInicio;
        $dataFim = $chamado->dtFim;

        if ($dataInicio != null && $dataFim != null) {

            $con = new Sql_db();
            $pdoCon = $con->Conectar();

            if (strtotime($dataInicio) > strtotime($dataFim)) {
                return null;
            } else {
                $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                        u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data
                        FROM chamados AS c
                        INNER JOIN usuario AS u
                        ON c.idUsuario = u.id
                        WHERE status = 1 AND data BETWEEN '$dataInicio' AND '$dataFim' ORDER BY idChamado DESC";
                // echo $sql;


                $select = sqlsrv_query($pdoCon, $sql);
                $rows_affected = sqlsrv_rows_affected($select);
                $cont = 0;

                if ($rows_affected === false) {
                    echo "Erro na chamada do sqlsrv_rows_affected\n";
                    die (print_r(sqlsrv_errors(), true));
                } else if ($rows_affected == -1) {
                    while($rs = sqlsrv_fetch_array($select)){
                        $listaChamados[] = new Chamado();
                        $listaChamados[$cont]->idChamado = $rs['idChamado'];
                        $listaChamados[$cont]->titulo = $rs['titulo'];
                        $listaChamados[$cont]->mensagem = $rs['mensagem'];
                        $listaChamados[$cont]->status = $rs['status'];
                        $listaChamados[$cont]->dataAbertura = $rs['data'];
                        $listaChamados[$cont]->idUsuario = $rs['usuarioId'];
                        $listaChamados[$cont]->cnpj = $rs['cnpj'];
                        $listaChamados[$cont]->razaoSocial = $rs['razaoSocial'];
                        $listaChamados[$cont]->nomeUsuario = $rs['nome'];
                        $cont++;
                    }
                    return $listaChamados;
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }
        $con->desconectar();
    }
}
 ?>
