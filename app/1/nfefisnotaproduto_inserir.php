<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
foreach ($jsonEntrada['produtos'] as $data) {
    if (is_array($data) && isset($data['idNota'])) {
        $refProduto = isset($data['refProduto']) && $data['refProduto'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['refProduto']) . "'" : "NULL";

        $sql2 = "SELECT * FROM produtos WHERE refProduto = $refProduto";
        $buscar = mysqli_query($conexao, $sql2);
        $row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
        $idProduto = $row["idProduto"];

        
        $idNota = isset($data['idNota']) && $data['idNota'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['idNota']) . "'" : "NULL";
        $nItem = isset($data['nItem']) && $data['nItem'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['nItem']) . "'" : "NULL";
        $quantidade = isset($data['quantidade']) && $data['quantidade'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['quantidade']) . "'" : "NULL";
        $unidCom = isset($data['unidCom']) && $data['unidCom'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['unidCom']) . "'" : "NULL";
        $valorUnidade = isset($data['valorUnidade']) && $data['valorUnidade'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['valorUnidade']) . "'" : "NULL";
        $valorTotal = isset($data['valorTotal']) && $data['valorTotal'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['valorTotal']) . "'" : "NULL";
        $cfop = isset($data['cfop']) && $data['cfop'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['cfop']) . "'" : "NULL";
        $codigoNcm = isset($data['codigoNcm']) && $data['codigoNcm'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['codigoNcm']) . "'" : "NULL";
        $codigoCest = isset($data['codigoCest']) && $data['codigoCest'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['codigoCest']) . "'" : "NULL";

        $sql = "INSERT INTO fisnotaproduto(idNota, nItem, idProduto, quantidade, unidCom, valorUnidade, valorTotal, cfop, codigoNcm, codigoCest) VALUES ($idNota, $nItem, $idProduto, $quantidade, $unidCom, $valorUnidade, $valorTotal, $cfop, $codigoNcm, $codigoCest)";
        $atualizar = mysqli_query($conexao, $sql);

        if ($atualizar) {
            $jsonSaida = array(
                "status" => 200,
                "retorno" => "ok"
            );
        } else {
            $jsonSaida = array(
                "status" => 500,
                "retorno" => "erro no mysql"
            );
        }
    } else {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "Faltaram parâmetros"
        );
    }
}
unset($jsonSaida['idEmpresa']);