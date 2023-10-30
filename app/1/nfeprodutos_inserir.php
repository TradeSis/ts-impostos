<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
foreach ($jsonEntrada['produtos'] as $data) {
    if (is_array($data) && isset($data['refProduto'])) {
        $idPessoaEmitente = isset($data['idPessoaEmitente']) && $data['idPessoaEmitente'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['idPessoaEmitente']) . "'" : "NULL";
        $refProduto = isset($data['refProduto']) && $data['refProduto'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['refProduto']) . "'" : "NULL";
        $nomeProduto = isset($data['nomeProduto']) && $data['nomeProduto'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['nomeProduto']) . "'" : "NULL";
        $valorCompra = isset($data['valorUnidade']) && $data['valorUnidade'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['valorUnidade']) . "'" : "NULL";
        $valorTotal = isset($data['valorTotal']) && $data['valorTotal'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['valorTotal']) . "'" : "NULL";
        $codigoNcm = isset($data['codigoNcm']) && $data['codigoNcm'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['codigoNcm']) . "'" : "NULL";
        $codigoCest = isset($data['codigoCest']) && $data['codigoCest'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['codigoCest']) . "'" : "NULL";

        $sql = "INSERT INTO produtos(idPessoaEmitente, refProduto, nomeProduto, valorCompra, codigoNcm, codigoCest) VALUES ($idPessoaEmitente, $refProduto, $nomeProduto, $valorCompra, $codigoNcm, $codigoCest)";
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