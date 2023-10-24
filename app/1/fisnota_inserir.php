<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
if (isset($jsonEntrada['nomeXml'])) {
    $chaveNFe = isset($jsonEntrada['chaveNFe']) && $jsonEntrada['chaveNFe'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['chaveNFe']) . "'" : "NULL";
    $sql2 = "SELECT chaveNFe FROM fisnota WHERE chaveNFe = $chaveNFe";
    $resultado = mysqli_query($conexao, $sql2);

    if (mysqli_num_rows($resultado) > 0) {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "NFe ja cadastrada"
        );
    } else {
        $NF = isset($jsonEntrada['NF']) && $jsonEntrada['NF'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['NF']) . "'" : "NULL";
        $serie = isset($jsonEntrada['serie']) && $jsonEntrada['serie'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['serie']) . "'" : "NULL";
        $dtEmissao = isset($jsonEntrada['dtEmissao']) && $jsonEntrada['dtEmissao'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['dtEmissao']) . "'" : "NULL";
        $emitente = isset($jsonEntrada['emitente']) && $jsonEntrada['emitente'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['emitente']) . "'" : "NULL";
        $destinatario = isset($jsonEntrada['destinatario']) && $jsonEntrada['destinatario'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['destinatario']) . "'" : "NULL";
        $pathXml = isset($jsonEntrada['pathXml']) && $jsonEntrada['pathXml'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['pathXml']) . "'" : "NULL";
        $nomeXml = isset($jsonEntrada['nomeXml']) && $jsonEntrada['nomeXml'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['nomeXml']) . "'" : "NULL";


        $sql = "INSERT INTO fisnota(chaveNFe, NF, serie, dtEmissao, emitente, destinatario, pathXml, nomeXml) VALUES ($chaveNFe, $NF, $serie, $dtEmissao, $emitente, $destinatario, $pathXml, $nomeXml)";
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
    }
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parâmetros"
    );
}