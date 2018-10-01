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
    public $dataFechamento;

    function __construct() {
        require_once("bdClass.php");
    }

    public function Atualizar($chamado){
        // session_start();

        $status = $chamado->status;
        $sqlInserirObservacao = "INSERT INTO observacao (observacao, idChamado, dataHora) VALUES ('".$chamado->observacao."', $chamado->idChamado, GETDATE())";
        if ($status == 'true') {
            $sqlAtualizarStatus = "UPDATE chamados SET status = '".$status."', dataFechamento = GETDATE() WHERE id = ".$chamado->idChamado;
        } else if($status == 'false') {
            $sqlAtualizarStatus = "UPDATE chamados SET status = '".$status."' WHERE id = ".$chamado->idChamado;
        }

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $atualizarStatus = sqlsrv_query($pdoCon, $sqlAtualizarStatus);
        $inserirObservacao = sqlsrv_query($pdoCon, $sqlInserirObservacao);

        if ($atualizarStatus && $inserirObservacao) {
            echo 1;
        } else {
            echo 0;
        }
        $con->Desconectar();
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
        $con->Desconectar();
    }

    public function SelectDiaResolvido(){
        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data, c.dataFechamento
                FROM chamados AS c
                INNER JOIN usuario AS u
                ON c.idUsuario = u.id
                WHERE status = 1 AND CONVERT(CHAR(10), dataFechamento, 103) = CONVERT(CHAR(10), GETDATE(),103) ORDER BY idChamado DESC";

        $con = new Sql_db();
        $pdoCon = $con->Conectar();
        // echo $sql;
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
        $con->Desconectar();
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
        $con->Desconectar();
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
        $con->Desconectar();
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
                        WHERE status = 1 AND dataFechamento BETWEEN '$dataInicio' AND '$dataFim 23:59:59' ORDER BY idChamado DESC";

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
        $con->Desconectar();
    }

    public function Estatisticas(){
        // RETORNA O TOTAL DE CHAMADOS
        $sqlTotal = "select count(*) AS totalChamados from chamados";
        // RETORNA TOTAL DE CHAMADOS RESOLVIDOS
        $sqlResolvidos = "select count(*) AS resolvidos from chamados where status = 1";
        // RETORNA TOTAL DE CHAMADOS pendentes
        $sqlPendentes = "select count(*) AS pendentes from chamados where status = 0";
        // RETORNA O TOTAL DE OBSERVAÇÕES
        $sqlObservacoes = "SELECT @@ROWCOUNT AS respostas FROM observacao GROUP BY idChamado";

        // conexao com o banco
        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $selectTotal = sqlsrv_query($pdoCon, $sqlTotal);
        $selectResolvidos = sqlsrv_query($pdoCon, $sqlResolvidos);
        $selectPendentes = sqlsrv_query($pdoCon, $sqlPendentes);

        if($rsTotal = sqlsrv_fetch_array($selectTotal)){
            $totalChamados = $rsTotal['totalChamados'];
        }
        if($rsResolvidos = sqlsrv_fetch_array($selectResolvidos)){
            $resolvidos = $rsResolvidos['resolvidos'];
        }
        if($rsPendentes = sqlsrv_fetch_array($selectPendentes)){
            $pendentes = $rsPendentes['pendentes'];
        }
        for ($i=0; $i < 2; $i++) {
            $selectObservacoes = sqlsrv_query($pdoCon, $sqlObservacoes);
        }
        if($rsObservacoes = sqlsrv_fetch_array($selectObservacoes)){
            $observacoes = $rsObservacoes['respostas'];
        }

        if ($totalChamados != 0) {
            $resolvidos = $resolvidos * 100 / $totalChamados;
            $pendentes = $pendentes * 100 / $totalChamados;
            $observacoes = ($observacoes * 100 / $totalChamados);
        }
        $con->Desconectar();

        return array($totalChamados, $resolvidos, $pendentes, $observacoes);
    }
}
 ?>
