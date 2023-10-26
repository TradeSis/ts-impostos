<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);
foreach ($jsonEntrada as $id => $data) {
    if ($id !== 'idEmpresa' && is_array($data) && isset($data['cpfCnpj'])) {
        $cpfCnpj = isset($data['cpfCnpj']) && $data['cpfCnpj'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['cpfCnpj']) . "'" : "NULL";
        $nome = isset($data['nome']) && $data['nome'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['nome']) . "'" : "NULL";
        $IE = isset($data['IE']) && $data['IE'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['IE']) . "'" : "NULL";
        $municipio = isset($data['municipio']) && $data['municipio'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['municipio']) . "'" : "NULL";
        $UF = isset($data['UF']) && $data['UF'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['UF']) . "'" : "NULL";
        $pais = isset($data['pais']) && $data['pais'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['pais']) . "'" : "NULL";
        $endereco = isset($data['endereco']) && $data['endereco'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['endereco']) . "'" : "NULL";

        $sql = "INSERT INTO pessoa(cpfCnpj, nome, IE, municipio, UF, pais, endereco) VALUES ($cpfCnpj, $nome, $IE, $municipio, $UF, $pais, $endereco)";
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