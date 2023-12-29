<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);


if (isset($jsonEntrada['xml'])) {

    $ativoProduto = 1; //Ativo
    $propagandaProduto = 0; //Inativo
    $xml = simplexml_load_string($jsonEntrada['xml']);
    $infNFe = $xml->infNFe;

    foreach ($infNFe->det as $item) {
        $eanProduto = "'" . (string) $item->prod->cEAN . "'";
        $nomeProduto = "'" . (string) $item->prod->xProd . "'";
        $valorCompra = "'" . (string) $item->prod->vUnCom . "'";
        $precoProduto = "'" . (string) $item->prod->uCom . "'";
        $codigoNcm = "'" . (string) $item->prod->NCM . "'";
        $codigoCest = "'" . (string) $item->prod->CEST . "'";
        $idPessoaFornecedor = "'" . $jsonEntrada['idPessoaEmitente'] . "'";
        $refProduto = "'" . (string) $item->prod->cProd . "'";

        $sql = "INSERT INTO produtos(eanProduto,nomeProduto,valorCompra,precoProduto,codigoNcm,codigoCest,ativoProduto,propagandaProduto,idPessoaFornecedor,refProduto)
                VALUES($eanProduto,$nomeProduto,$valorCompra,$precoProduto,$codigoNcm,$codigoCest,$ativoProduto,$propagandaProduto,$idPessoaFornecedor,$refProduto)";

        $atualizar = mysqli_query($conexao, $sql);

        //LOG
        if (isset($LOG_NIVEL)) {
            if ($LOG_NIVEL >= 3) {
                fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
            }
        }
        //LOG

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

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG