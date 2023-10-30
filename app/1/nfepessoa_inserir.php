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
        
        $sql2 = "SELECT * FROM pessoa WHERE cpfCnpj = $cpfCnpj";
        $buscar = mysqli_query($conexao, $sql2);
        $row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
        
        if (mysqli_num_rows($buscar) == 1) {
            $idPessoa = $row["idPessoa"];
            
            $jsonSaida[$id] = array(
                "status" => 200,
                "retorno" => "cpfCnpj existente",
                "idPessoaInserido" => $idPessoa
            );
        } else {
            $nome = isset($data['nome']) && $data['nome'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['nome']) . "'" : "NULL";
            $IE = isset($data['IE']) && $data['IE'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['IE']) . "'" : "NULL";
            $municipio = isset($data['municipio']) && $data['municipio'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['municipio']) . "'" : "NULL";
            $UF = isset($data['UF']) && $data['UF'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['UF']) . "'" : "NULL";
            $pais = isset($data['pais']) && $data['pais'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['pais']) . "'" : "NULL";
            $endereco = isset($data['endereco']) && $data['endereco'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['endereco']) . "'" : "NULL";

            $sql = "INSERT INTO pessoa(cpfCnpj, nome, IE, municipio, UF, pais, endereco) VALUES ($cpfCnpj, $nome, $IE, $municipio, $UF, $pais, $endereco)";
            $atualizar = mysqli_query($conexao, $sql);

            $idPessoaInserido = mysqli_insert_id($conexao);
            if ($atualizar) {
                $jsonSaida[$id] = array(
                    "status" => 200,
                    "retorno" => "ok",
                    "idPessoaInserido" => $idPessoaInserido
                );
            } else {
                $jsonSaida[$id] = array(
                    "status" => 500,
                    "retorno" => "erro no mysql"
                );
            }
        }
    } else {
        $jsonSaida[$id] = array(
            "status" => 400,
            "retorno" => "Faltaram parâmetros"
        );
    }
}
unset($jsonSaida['idEmpresa']);