<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
foreach ($jsonEntrada['produtos'] as $data) {
    if (is_array($data) && isset($data['codigoProduto'])) {
        $codigoProduto = isset($data['codigoProduto']) && $data['codigoProduto'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['codigoProduto']) . "'" : "NULL";
        $nomeProduto = isset($data['nomeProduto']) && $data['nomeProduto'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['nomeProduto']) . "'" : "NULL";
        $quantidade = isset($data['quantidade']) && $data['quantidade'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['quantidade']) . "'" : "NULL";
        $unidCom = isset($data['unidCom']) && $data['unidCom'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['unidCom']) . "'" : "NULL";
        $valorUnidade = isset($data['valorUnidade']) && $data['valorUnidade'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['valorUnidade']) . "'" : "NULL";
        $valorTotal = isset($data['valorTotal']) && $data['valorTotal'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['valorTotal']) . "'" : "NULL";
        $cfop = isset($data['cfop']) && $data['cfop'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['cfop']) . "'" : "NULL";
        $ncm = isset($data['ncm']) && $data['ncm'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['ncm']) . "'" : "NULL";
        $cest = isset($data['cest']) && $data['cest'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['cest']) . "'" : "NULL";
        $chaveNFe = isset($data['chaveNFe']) && $data['chaveNFe'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['chaveNFe']) . "'" : "NULL";

        $sql = "INSERT INTO fisproduto(codigoProduto, nomeProduto, quantidade, unidCom, valorUnidade, valorTotal, cfop, ncm, cest, chaveNFe) VALUES ($codigoProduto, $nomeProduto, $quantidade, $unidCom, $valorUnidade, $valorTotal, $cfop, $ncm, $cest, $chaveNFe)";
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