<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
if (isset($jsonEntrada['cnpj'])) {
    $cnpj = isset($jsonEntrada['cnpj']) && $jsonEntrada['cnpj'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['cnpj']) . "'" : "NULL";
    $nome = isset($jsonEntrada['nome']) && $jsonEntrada['nome'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['nome']) . "'" : "NULL";
    $endereco = isset($jsonEntrada['endereco']) && $jsonEntrada['endereco'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['endereco']) . "'" : "NULL";


    $sql = "INSERT INTO fispessoa(cnpj, nome, endereco) VALUES ($cnpj, $nome, $endereco)";
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