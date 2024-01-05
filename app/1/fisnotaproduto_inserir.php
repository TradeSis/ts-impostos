<?php
foreach ($infNFe->det as $item) {

    $idNota = isset($idNotaInserido) && $idNotaInserido !== "" ? "'" . $idNotaInserido . "'" : "null";
    $nItem = isset($item['nItem']) && $item['nItem'] !== "" ? "'" . (string) $item['nItem'] . "'" : "null";
    $quantidade = isset($item->prod->qCom) && $item->prod->qCom !== "" ? "'" . (string) $item->prod->qCom . "'" : "null";
    $unidCom = isset($item->prod->uCom) && $item->prod->uCom !== "" ? "'" . (string) $item->prod->uCom . "'" : "null";
    $valorUnidade = isset($item->prod->vUnCom) && $item->prod->vUnCom !== "" ? "'" . (string) $item->prod->vUnCom . "'" : "null";
    $valorTotal = isset($item->prod->vProd) && $item->prod->vProd !== "" ? "'" . (string) $item->prod->vProd . "'" : "null";
    $cfop = isset($item->prod->CFOP) && $item->prod->CFOP !== "" ? "'" . (string) $item->prod->CFOP . "'" : "null";
    $codigoNcm = isset($item->prod->NCM) && $item->prod->NCM !== "" ? "'" . (string) $item->prod->NCM . "'" : "null";
    $codigoCest = isset($item->prod->CEST) && $item->prod->CEST !== "" ? "'" . (string) $item->prod->CEST . "'" : "null";
    $eanProduto = isset($item->prod->cEAN) && $item->prod->cEAN !== "" ? "'" . (string) $item->prod->cEAN . "'" : "null";
    $refProduto = isset($item->prod->cProd) && $item->prod->cProd !== "" ? "'" . (string) $item->prod->cProd . "'" : "null";

    if ($refProduto == $eanProduto) {
        $refProduto = "'NULL'";
    }
    if ($eanProduto == "'SEM GTIN'" || $eanProduto == "''") {
        $eanProduto = "'NULL'";
    }

    if ($eanProduto === "NULL") {
        $buscaProduto2 = "SELECT * FROM produtos WHERE idPessoaFornecedor = $idPessoaEmitente AND refProduto = $refProduto";
    } else {
        $buscaProduto2 = "SELECT * FROM produtos WHERE eanProduto = $eanProduto";
    }

    $buscar = mysqli_query($conexao, $buscaProduto2);

    if (mysqli_num_rows($buscar) == 0) {

        $produEntrada = array(
            'idEmpresa' => $idEmpresa,
            'eanProduto' => str_replace("'", "", $eanProduto),
            'nomeProduto' => (string) $item->prod->xProd,
            'valorCompra' => (string) $item->prod->vUnCom,
            'precoProduto' => (string) $item->prod->uCom,
            'codigoNcm' => (string) $item->prod->NCM,
            'codigoCest' => (string) $item->prod->CEST,
            'ativoProduto' => "1", //Ativo
            'propagandaProduto' => "0", //Inativo
            'idPessoaFornecedor' => str_replace("'", "", $idPessoaEmitente),
            'refProduto' => str_replace("'", "", $refProduto)
        );
        $produRetorno = chamaAPI(null, '/cadastros/produtos', json_encode($produEntrada), 'PUT');
        $idProduto = $produRetorno['idProduto'];

    } else {

        $dadosProduto = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
        $idProduto = $dadosProduto["idProduto"];

    }

    $sqlNotaProduto = "INSERT INTO fisnotaproduto(idNota,nItem,idProduto,quantidade,unidCom,valorUnidade,valorTotal,cfop,codigoNcm,codigoCest)
        VALUES($idNota,$nItem,$idProduto,$quantidade,$unidCom,$valorUnidade,$valorTotal,$cfop,$codigoNcm,$codigoCest)";

    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 3) {
            fwrite($arquivo, $identificacao . "-SQL_NotaProduto->" . $sqlNotaProduto . "\n");
        }
    }
    //LOG

    $atualizarNotaProduto = mysqli_query($conexao, $sqlNotaProduto);

//********************************************FISNOTAPRODUTOSIMPOSTO
    if (isset($item->imposto)) {
        $vTotTrib = isset($item->imposto->vTotTrib) && $item->imposto->vTotTrib !== "" ? "'" . (string) $item->imposto->vTotTrib . "'" : "null";
        foreach ($item->imposto->children() as $filho) {
            $imposto = "'" . $filho->getName() . "'";
            $nomeImposto = $filho->children()->count() > 0 ? $filho->children()->getName() : null;

            $campos = $filho->$nomeImposto;

            $orig = isset($campos->orig) ? "'" . (string) $campos->orig . "'" : "null";
            $CSOSN = isset($campos->CSOSN) ? "'" . (string) $campos->CSOSN . "'" : "null";
            $modBCST = isset($campos->modBCST) ? "'" . (string) $campos->modBCST . "'" : "null";
            $pMVAST = isset($campos->pMVAST) ? "'" . (string) $campos->pMVAST . "'" : "null";
            $vBCST = isset($campos->vBCST) ? "'" . (string) $campos->vBCST . "'" : "null";
            $pICMSST = isset($campos->pICMSST) ? "'" . (string) $campos->pICMSST . "'" : "null";
            $vICMSST = isset($campos->vICMSST) ? "'" . (string) $campos->vICMSST . "'" : "null";
            $nomeImposto = "'" . $nomeImposto . "'";

            $sqlImposto = "INSERT INTO fisnotaproduimposto(idNota,nItem,imposto,nomeImposto,vTotTrib,orig,CSOSN,modBCST,pMVAST,vBCST,pICMSST,vICMSST) 
                        VALUES ($idNota,$nItem,$imposto,$nomeImposto,$vTotTrib,$orig,$CSOSN,$modBCST,$pMVAST,$vBCST,$pICMSST,$vICMSST)";

            //LOG
            if (isset($LOG_NIVEL)) {
                if ($LOG_NIVEL >= 3) {
                    fwrite($arquivo, $identificacao . "-SQL_Imposto->" . $sqlImposto . "\n");
                }
            }
            //LOG

            $atualizarImposto = mysqli_query($conexao, $sqlImposto);
        }
    }
}

?>