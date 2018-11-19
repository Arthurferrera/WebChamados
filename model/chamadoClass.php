<?php
// session_start();
require_once($_SESSION['require']."view/modulo.php");
//autentica();
$conexao  = conexao();

class Chamado {

    // atibutos de um chamado
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
    public $empresaInicial;
    public $empresaFinal;

    function __construct() {
        require_once("bdClass.php");
    }

    // método que recebe um objeto 'chamado', e atualiza
     // as informações no banco (ADICIONA RESPOSTA/OBSERVAÇÃO E ATUALIZA O STATUS)
    public static function Atualizar($chamado){
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

    // seleciona todos os chamados do banco, conforme o STATUS, soicitado
    public static function SelectAll($status){
        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data, c.dataFechamento
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

    // método que seleciona todos os CHAMADOS
    // cujo a data de fechamento é a do dia atual
    public static function SelectDiaResolvido(){
        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data, c.dataFechamento
                FROM chamados AS c
                INNER JOIN usuario AS u
                ON c.idUsuario = u.id
                WHERE status = 1 AND CONVERT(CHAR(10), dataFechamento, 103) = CONVERT(CHAR(10), GETDATE(),103) ORDER BY idChamado DESC";

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
                $chamado[$cont]->dataFechamento = $rs['dataFechamento'];
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

    // método que retorno um chamado, que é buscado pelo ID
    public static function SelectById($idChamado){

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

    // método que traz todas as respostas/observações de um chamado
    // recebe o id do chamado
    public static function SelectObsById($idChamado){

        $sql = "SELECT *, CONVERT(nvarchar(30), dataHora, 126) AS dataConvertida FROM observacao WHERE idChamado =".$idChamado;

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

    // método que busca no banco CHAMADO
    // cujo estejam no range de data selecionado, e sejam de uma empresa especifica
    public static function FiltroPorData($chamado, $status){

        // resgatando os valores que será usado na query
        $dataInicio = $chamado->dtInicio;
        $dataFim = $chamado->dtFim;
        $fornecedorInicio = $chamado->empresaInicial;
        $fornecedorFim = $chamado->empresaFinal;

        // verificando se as datas estão vazias
        if ($dataInicio != null && $dataFim != null) {
            // conectando com o banco
            $con = new Sql_db();
            $pdoCon = $con->Conectar();

            // verificando se a data inicial é maior que a final
            if (strtotime($dataInicio) > strtotime($dataFim)) {
                return null;
            } else {

                if ($fornecedorInicio == '' || $fornecedorFim == '') {
                    if($status == 1){
                        // select de chamados resolvidos, são puxados pela data de Fechamento
                        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data, c.dataFechamento
                                FROM chamados AS c
                                INNER JOIN usuario AS u
                                ON c.idUsuario = u.id
                                WHERE status = $status AND dataFechamento BETWEEN '$dataInicio' AND '$dataFim 23:59:59' ORDER BY idChamado DESC";
                    } else {
                        // select de chamados resolvidos, são puxados pela data de abertura
                        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data, c.dataFechamento
                                FROM chamados AS c
                                INNER JOIN usuario AS u
                                ON c.idUsuario = u.id
                                WHERE status = $status AND data BETWEEN '$dataInicio' AND '$dataFim 23:59:59' ORDER BY idChamado DESC";
                    }

                } else {
                    // SELECT PARA O FILTRO COM FORNECEDOR
                    if($status == 1){
                        // select de chamados resolvidos, são puxados pela data de Fechamento
                        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data, c.dataFechamento
                                FROM chamados AS c
                                INNER JOIN usuario AS u
                                ON c.idUsuario = u.id
                                WHERE status = $status AND dataFechamento BETWEEN '$dataInicio' AND '$dataFim 23:59:59'
                                AND u.razaoSocial BETWEEN '$fornecedorInicio' AND '$fornecedorFim'
                                AND u.razaoSocial LIKE '%$fornecedorInicio%' ORDER BY idChamado DESC";
                    }else{
                        // select de chamados resolvidos, são puxados pela data de abertura
                        $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
                                u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data, c.dataFechamento
                                FROM chamados AS c
                                INNER JOIN usuario AS u
                                ON c.idUsuario = u.id
                                WHERE status = $status AND data BETWEEN '$dataInicio' AND '$dataFim 23:59:59'
                                AND u.razaoSocial BETWEEN '$fornecedorInicio' AND '$fornecedorFim'
                                AND u.razaoSocial LIKE '%$fornecedorInicio%' ORDER BY idChamado DESC";
                    }

                }
                // executando a query
                $select = sqlsrv_query($pdoCon, $sql);
                $rows_affected = sqlsrv_rows_affected($select);
                $cont = 0;

                // verificando se a query foi executada
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
                        $listaChamados[$cont]->dataFechamento = $rs['dataFechamento'];
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

    // método que faz os selects que traz as informações
    // de Estatisticas (TOTALCHAMADOS, RESOLVIDOS, PENDENTES, RESPONDIDOS)
    public static function Estatisticas(){
        // RETORNA O TOTAL DE CHAMADOS
        $sqlTotal = "SELECT COUNT(*) AS totalChamados FROM chamados";
        // RETORNA TOTAL DE CHAMADOS RESOLVIDOS
        $sqlResolvidos = "SELECT COUNT(*) AS resolvidos FROM chamados WHERE status = 1";
        // RETORNA TOTAL DE CHAMADOS pendentes
        $sqlPendentes = "SELECT COUNT(*) AS pendentes FROM chamados WHERE status = 0";
        // RETORNA O TOTAL DE OBSERVAÇÕES
        $sqlObservacoes = "SELECT @@ROWCOUNT AS respostas FROM observacao GROUP BY idChamado";

        // conexao com o banco
        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        // executando no banco
        $selectTotal = sqlsrv_query($pdoCon, $sqlTotal);
        $selectResolvidos = sqlsrv_query($pdoCon, $sqlResolvidos);
        $selectPendentes = sqlsrv_query($pdoCon, $sqlPendentes);

        // resgatando total de chamados
        if($rsTotal = sqlsrv_fetch_array($selectTotal)){
            $totalChamados = $rsTotal['totalChamados'];
        }
        // resgatando quantidade de chamados resolvidos
        if($rsResolvidos = sqlsrv_fetch_array($selectResolvidos)){
            $resolvidos = $rsResolvidos['resolvidos'];
        }
        // resgatando total de chamados pendentes
        if($rsPendentes = sqlsrv_fetch_array($selectPendentes)){
            $pendentes = $rsPendentes['pendentes'];
        }
        // execuando no banco a query que verifia total de chamados respondidos
        for ($i=0; $i < 2; $i++) {
            $selectObservacoes = sqlsrv_query($pdoCon, $sqlObservacoes);
        }
        // resgatando quantidade de chamados respondidos
        if($rsObservacoes = sqlsrv_fetch_array($selectObservacoes)){
            $observacoes = $rsObservacoes['respostas'];
        } else {
            $observacoes = 0;
        }
        // caso exista ao menos um chamado, calcula as porcentagem de cada atributo
        if ($totalChamados != 0) {
            $resolvidos = $resolvidos * 100 / $totalChamados;
            $pendentes = $pendentes * 100 / $totalChamados;
            $observacoes = $observacoes * 100 / $totalChamados;
        }
        $con->Desconectar();
        // retornando um array com todas as informações resgatadas
        return array($totalChamados, $resolvidos, $pendentes, $observacoes);
    }

    // método que faz os selects que traz as informações
    // de Estatisticas (TOTALCHAMADOS, RESOLVIDOS, PENDENTES, RESPONDIDOS)
    public static function EstatisticasParciais($chamado, $tipoEstatistica){

        $dataInicio = $chamado->dtInicio;
        $dataFim = $chamado->dtFim;
        if ($tipoEstatistica == "filtro") {
            // RETORNA O TOTAL DE CHAMADOS
            $sqlTotal = "SELECT COUNT(*) AS totalChamados FROM chamados WHERE data BETWEEN '$dataInicio' AND '$dataFim 23:59:59'";
            // RETORNA TOTAL DE CHAMADOS RESOLVIDOS
            $sqlResolvidos = "SELECT COUNT(*) AS resolvidos FROM chamados WHERE status = 1 AND data BETWEEN '$dataInicio' AND '$dataFim 23:59:59'";
            // RETORNA TOTAL DE CHAMADOS pendentes
            $sqlPendentes = "SELECT COUNT(*) AS pendentes FROM chamados WHERE status = 0 AND data BETWEEN '$dataInicio' AND '$dataFim 23:59:59'";
            // RETORNA O TOTAL DE OBSERVAÇÕES
            $sqlObservacoes = "SELECT @@ROWCOUNT AS respostas FROM chamados
                                INNER JOIN observacao ON observacao.idchamado = chamados.id
                                WHERE data BETWEEN '$dataInicio' AND '$dataFim 23:59:59'
                                AND dataHora BETWEEN '$dataInicio' AND '$dataFim 23:59:59'
                                GROUP BY chamados.id";
        } else if ($tipoEstatistica == "dia") {
            // RETORNA O TOTAL DE CHAMADOS
            $sqlTotal = "SELECT COUNT(*) AS totalChamados FROM chamados WHERE data BETWEEN CONVERT(date, GETDATE()) AND GETDATE()";
            // RETORNA TOTAL DE CHAMADOS RESOLVIDOS
            $sqlResolvidos = "SELECT COUNT(*) AS resolvidos FROM chamados WHERE status = 1 AND data BETWEEN CONVERT(date, GETDATE()) AND GETDATE()";
            // RETORNA TOTAL DE CHAMADOS pendentes
            $sqlPendentes = "SELECT COUNT(*) AS pendentes FROM chamados WHERE status = 0 AND data BETWEEN CONVERT(date, GETDATE()) AND GETDATE()";
            // RETORNA O TOTAL DE OBSERVAÇÕES
            $sqlObservacoes = "SELECT @@ROWCOUNT AS respostas FROM chamados
                                INNER JOIN observacao ON observacao.idchamado = chamados.id
                                WHERE data BETWEEN CONVERT(date, GETDATE()) AND GETDATE()
                                AND dataHora BETWEEN CONVERT(date, GETDATE()) AND GETDATE()
                                GROUP BY chamados.id";
            // $sqlObservacoes = "SELECT @@ROWCOUNT AS respostas FROM observacao WHERE dataHora BETWEEN CONVERT(date, GETDATE()) AND GETDATE() GROUP BY idChamado";
        }


        // conexao com o banco
        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        // executando no banco
        $selectTotal = sqlsrv_query($pdoCon, $sqlTotal);
        $selectResolvidos = sqlsrv_query($pdoCon, $sqlResolvidos);
        $selectPendentes = sqlsrv_query($pdoCon, $sqlPendentes);

        // resgatando total de chamados
        if($rsTotal = sqlsrv_fetch_array($selectTotal)){
            $totalChamados = $rsTotal['totalChamados'];
        }
        // resgatando quantidade de chamados resolvidos
        if($rsResolvidos = sqlsrv_fetch_array($selectResolvidos)){
            $resolvidos = $rsResolvidos['resolvidos'];
        }
        // resgatando total de chamados pendentes
        if($rsPendentes = sqlsrv_fetch_array($selectPendentes)){
            $pendentes = $rsPendentes['pendentes'];
        }
        // execuando no banco a query que verifia total de chamados respondidos
        for ($i=0; $i < 2; $i++) {
            $selectObservacoes = sqlsrv_query($pdoCon, $sqlObservacoes);
        }
        // resgatando quantidade de chamados respondidos
        if($rsObservacoes = sqlsrv_fetch_array($selectObservacoes)){
            $observacoes = $rsObservacoes['respostas'];
        } else {
            $observacoes = 0;
        }
        // caso exista ao menos um chamado, calcula as porcentagem de cada atributo
        if ($totalChamados != 0) {
            $resolvidos = $resolvidos * 100 / $totalChamados;
            $pendentes = $pendentes * 100 / $totalChamados;
            $observacoes = $observacoes * 100 / $totalChamados;
        }
        $con->Desconectar();
        // retornando um array com todas as informações resgatadas
        return array($totalChamados, $resolvidos, $pendentes, $observacoes);
    }

    // função que busca todas as empresas cadastradas
    // para ser carregadas no select de filtro
    public static function listarEmpresas(){

        $sql = "SELECT razaoSocial FROM usuario GROUP BY razaoSocial ORDER BY razaoSocial ASC";

        $con = new Sql_db();
        $pdoCon = $con->Conectar();

        $select = sqlsrv_query($pdoCon, $sql);
        $rows_affected = sqlsrv_rows_affected($select);
        $cont = 0;

        if ($rows_affected === false) {
            echo "erro na chamada";
        } else if ($rows_affected == -1) {
            while($rs = sqlsrv_fetch_array($select)){
                $chamado[] = new Chamado();
                $chamado[$cont]->razaoSocial = $rs['razaoSocial'];
                $cont ++;
            }
            return $chamado;
        } else {
            return null;
        }
        $con->Desconectar();
    }

    // MÉTODO QUA FAZ UMA BUSCA DE TODOS OS CHAMADOS RELACIONADOS COM A EMPRESA DESEJADA
    // public function filtroPesquisaEmpresa($pesquisaEmpresa){
    //     $sql = "SELECT c.id AS idChamado, c.titulo, c.mensagem, c.status, c.idUsuario,
    //             u.id AS usuarioId, u.cnpj, u.razaoSocial, u.nome, c.data
    //             FROM chamados AS c
    //             INNER JOIN usuario AS u
    //             ON u.id = c.idUsuario
    //             WHERE u.razaoSocial LIKE '%$pesquisaEmpresa%'
    //             AND status = 0 ORDER BY c.id DESC";
    //
    //     $con = new Sql_db();
    //     $pdoCon = $con->Conectar();
    //
    //     $select = sqlsrv_query($pdoCon, $sql);
    //     $rows_affected = sqlsrv_rows_affected($select);
    //     $cont = 0;
    //
    //     if ($rows_affected === false) {
    //         echo "erro na chamada";
    //     } else if($rows_affected == -1){
    //         while ($rs = sqlsrv_fetch_array($select)){
    //             $chamado[] = new Chamado();
    //             $chamado[$cont]->idChamado = $rs['idChamado'];
    //             $chamado[$cont]->titulo = $rs['titulo'];
    //             $chamado[$cont]->mensagem = $rs['mensagem'];
    //             $chamado[$cont]->status = $rs['status'];
    //             $chamado[$cont]->dataAbertura = $rs['data'];
    //             $chamado[$cont]->idUsuario = $rs['usuarioId'];
    //             $chamado[$cont]->cnpj = $rs['cnpj'];
    //             $chamado[$cont]->razaoSocial = $rs['razaoSocial'];
    //             $chamado[$cont]->nomeUsuario = $rs['nome'];
    //             $cont++;
    //         }
    //         return $chamado;
    //     } else {
    //         return null;
    //     }
    //     $con->Desconectar();
    // }
}
 ?>
