<?php
 foreach ($infNFe->det as $item) {
    $eanProduto = isset($item->prod->cEAN) && $item->prod->cEAN !== "" && $item->prod->cEAN !== "" ? "'" . (string) $item->prod->cEAN . "'" : "null";
    $refProduto = isset($item->prod->cProd) && $item->prod->cProd !== "" && $item->prod->cProd !== "" ? "'" . (string) $item->prod->cProd . "'" : "null";

    if ($refProduto == $eanProduto) {
        $refProduto = "NULL";
    }
    if ($eanProduto == "'SEM GTIN'" || $eanProduto == "''") {
        $eanProduto = "NULL";
    }

    if ($eanProduto === "NULL") {
        $buscaProduto2 = "SELECT * FROM produtos WHERE eanProduto is NULL AND refProduto = $refProduto";
    } else {
        $buscaProduto2 = "SELECT * FROM produtos WHERE eanProduto = $eanProduto";
    }
    $buscar = mysqli_query($conexao, $buscaProduto2);
    $dadosProduto = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
    $idProduto = $dadosProduto["idProduto"];


    $idNota = isset($idNotaInserido) && $idNotaInserido !== "" && $idNotaInserido !== "" ? "'" . $idNotaInserido . "'" : "null";
    $nItem = isset($item['nItem']) && $item['nItem'] !== "" && $item['nItem'] !== "" ? "'" . (string) $item['nItem'] . "'" : "null";
    $quantidade = isset($item->prod->qCom) && $item->prod->qCom !== "" && $item->prod->qCom !== "" ? "'" . (string) $item->prod->qCom . "'" : "null";
    $unidCom = isset($item->prod->uCom) && $item->prod->uCom !== "" && $item->prod->uCom !== "" ? "'" . (string) $item->prod->uCom . "'" : "null";
    $valorUnidade = isset($item->prod->vUnCom) && $item->prod->vUnCom !== "" && $item->prod->vUnCom !== "" ? "'" . (string) $item->prod->vUnCom . "'" : "null";
    $valorTotal = isset($item->prod->vProd) && $item->prod->vProd !== "" && $item->prod->vProd !== "" ? "'" . (string) $item->prod->vProd . "'" : "null";
    $cfop = isset($item->prod->CFOP) && $item->prod->CFOP !== "" && $item->prod->CFOP !== "" ? "'" . (string) $item->prod->CFOP . "'" : "null";
    $codigoNcm = isset($item->prod->NCM) && $item->prod->NCM !== "" && $item->prod->NCM !== "" ? "'" . (string) $item->prod->NCM . "'" : "null";
    $codigoCest = isset($item->prod->CEST) && $item->prod->CEST !== "" && $item->prod->CEST !== "" ? "'" . (string) $item->prod->CEST . "'" : "null";

    $buscaNotaProdu = "SELECT * FROM fisnotaproduto WHERE idNota = $idNota AND nItem = $nItem";
    $buscar = mysqli_query($conexao, $buscaNotaProdu);
    $produto = mysqli_fetch_array($buscar, MYSQLI_ASSOC);

    if (mysqli_num_rows($buscar) == 1) {
        $jsonSaidaNotaProdu[] = array(
            "status" => 200,
            "retorno" => "NotaProduto existente"
        );
    } else {
        $sqlNotaProduto = "INSERT INTO fisnotaproduto(idNota,nItem,idProduto,quantidade,unidCom,valorUnidade,valorTotal,cfop,codigoNcm,codigoCest)
        VALUES($idNota,$nItem,$idProduto,$quantidade,$unidCom,$valorUnidade,$valorTotal,$cfop,$codigoNcm,$codigoCest)";
        $atualizarNotaProduto = mysqli_query($conexao, $sqlNotaProduto);

        //LOG
        if (isset($LOG_NIVEL)) {
            if ($LOG_NIVEL >= 3) {
                fwrite($arquivo, $identificacao . "-SQL_NotaProduto->" . $sqlNotaProduto . "\n");
            }
        }
        //LOG

        if ($atualizarNotaProduto) {
            $jsonSaidaNotaProdu[] = array(
                "status" => 200,
                "retorno" => "ok"
            );
        } else {
            $jsonSaidaNotaProdu[] = array(
                "status" => 500,
                "retorno" => "erro no mysql"
            );
        }
    }
}

?>