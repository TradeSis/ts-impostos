<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
if (isset($jsonEntrada['chaveNFe'])) {
    $chaveNFe = isset($jsonEntrada['chaveNFe']) && $jsonEntrada['chaveNFe'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['chaveNFe']) . "'" : "NULL";
    $sql2 = "SELECT chaveNFe FROM fisnota WHERE chaveNFe = $chaveNFe";
    $resultado = mysqli_query($conexao, $sql2);

    if (mysqli_num_rows($resultado) > 0) {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "NFe ja cadastrada"
        );
    } else {
        $naturezaOp = isset($jsonEntrada['naturezaOp']) && $jsonEntrada['naturezaOp'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['naturezaOp']) . "'" : "NULL";
        $modelo = isset($jsonEntrada['modelo']) && $jsonEntrada['modelo'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['modelo']) . "'" : "NULL";
        $NF = isset($jsonEntrada['NF']) && $jsonEntrada['NF'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['NF']) . "'" : "NULL";
        $serie = isset($jsonEntrada['serie']) && $jsonEntrada['serie'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['serie']) . "'" : "NULL";
        $dtEmissao = isset($jsonEntrada['dtEmissao']) && $jsonEntrada['dtEmissao'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['dtEmissao']) . "'" : "NULL";
        $idPessoaEmitente = isset($jsonEntrada['idPessoaEmitente']) && $jsonEntrada['idPessoaEmitente'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['idPessoaEmitente']) . "'" : "NULL";
        $idPessoaDestinatario = isset($jsonEntrada['idPessoaDestinatario']) && $jsonEntrada['idPessoaDestinatario'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['idPessoaDestinatario']) . "'" : "NULL";
        $baseCalculo = isset($jsonEntrada['baseCalculo']) && $jsonEntrada['baseCalculo'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['baseCalculo']) . "'" : "NULL";
        $valorProdutos = isset($jsonEntrada['valorProdutos']) && $jsonEntrada['valorProdutos'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['valorProdutos']) . "'" : "NULL";
        $pis = isset($jsonEntrada['pis']) && $jsonEntrada['pis'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['pis']) . "'" : "NULL";
        $cofins = isset($jsonEntrada['cofins']) && $jsonEntrada['cofins'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['cofins']) . "'" : "NULL";


        $sql = "INSERT INTO fisnota(chaveNFe, naturezaOp, modelo, NF, serie, dtEmissao, idPessoaEmitente, idPessoaDestinatario, baseCalculo, valorProdutos, pis, cofins) VALUES 
        ($chaveNFe, $naturezaOp, $modelo, $NF, $serie, $dtEmissao, $idPessoaEmitente, $idPessoaDestinatario, $baseCalculo, $valorProdutos, $pis, $cofins)";
        $atualizar = mysqli_query($conexao, $sql);

        $idNotaInserido = mysqli_insert_id($conexao);
        if ($atualizar) {
            $jsonSaida = array(
                "status" => 200,
                "retorno" => "ok",
                "idNotaInserido" => $idNotaInserido
            );
        } else {
            $jsonSaida = array(
                "status" => 500,
                "retorno" => "erro no mysql"
            );
        }
    }
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parâmetros"
    );
}