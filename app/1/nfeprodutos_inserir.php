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
        $eanProduto = isset($item->prod->cEAN) && $item->prod->cEAN !== "" && $item->prod->cEAN !== "" ? "'" .  (string) $item->prod->cEAN . "'" : "null";
        $nomeProduto = isset($item->prod->xProd) && $item->prod->xProd !== "" && $item->prod->xProd !== "" ? "'" .  (string) $item->prod->xProd . "'" : "null";
        $valorCompra = isset($item->prod->vUnCom) && $item->prod->vUnCom !== "" && $item->prod->vUnCom !== "" ? "'" .  (string) $item->prod->vUnCom . "'" : "null";
        $precoProduto = isset($item->prod->uCom) && $item->prod->uCom !== "" && $item->prod->uCom !== "" ? "'" .  (string) $item->prod->uCom . "'" : "null";
        $codigoNcm = isset($item->prod->NCM) && $item->prod->NCM !== "" && $item->prod->NCM !== "" ? "'" .  (string) $item->prod->NCM . "'" : "null";
        $codigoCest = isset($item->prod->CEST) && $item->prod->CEST !== "" && $item->prod->CEST !== "" ? "'" .  (string) $item->prod->CEST . "'" : "null";
        $idPessoaFornecedor = isset($jsonEntrada['idPessoaEmitente']) && $jsonEntrada['idPessoaEmitente'] !== "" && $jsonEntrada['idPessoaEmitente'] !== "" ? "'" .  $jsonEntrada['idPessoaEmitente'] . "'" : "null";
        $refProduto = isset($item->prod->cProd) && $item->prod->cProd !== "" && $item->prod->cProd !== "" ? "'" .  (string) $item->prod->cProd . "'" : "null";

        if($refProduto == $eanProduto) {
            $refProduto = "NULL";
        }
        if($eanProduto == "'SEM GTIN'") {
            $eanProduto = "NULL";
        }

        if ($eanProduto === "NULL") {
            $sql2 = "SELECT * FROM produtos WHERE idPessoaFornecedor = $idPessoaFornecedor AND refProduto = $refProduto";
        } else {
            $sql2 = "SELECT * FROM produtos WHERE eanProduto = $eanProduto";
        }
        $buscar = mysqli_query($conexao, $sql2);
        $produto = mysqli_fetch_array($buscar, MYSQLI_ASSOC);

        if (mysqli_num_rows($buscar) == 1) {
            $idProduto = $produto["idProduto"];

            $jsonSaida[] = array(
                "status" => 200,
                "retorno" => "Produto existente",
                "idProduto" => $idProduto
            );
        } else {

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
                $jsonSaida[] = array(
                    "status" => 200,
                    "retorno" => "ok"
                );
            } else {
                $jsonSaida[] = array(
                    "status" => 500,
                    "retorno" => "erro no mysql"
                );
            }
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