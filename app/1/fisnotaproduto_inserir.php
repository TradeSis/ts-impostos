<?php
foreach ($infNFe->det as $item) {

    

    $idNota = isset($idNotaInserido) && $idNotaInserido !== "" ? "'" . $idNotaInserido . "'" : "NULL";
    $nItem = isset($item['nItem']) && $item['nItem'] !== "" ? "'" . (string) $item['nItem'] . "'" : "NULL";
    $quantidade = isset($item->prod->qCom) && $item->prod->qCom !== "" ? "'" . (string) $item->prod->qCom . "'" : "NULL";
    $unidCom = isset($item->prod->uCom) && $item->prod->uCom !== "" ? "'" . (string) $item->prod->uCom . "'" : "NULL";
    $valorUnidade = isset($item->prod->vUnCom) && $item->prod->vUnCom !== "" ? "'" . (string) $item->prod->vUnCom . "'" : "NULL";
    $valorTotal = isset($item->prod->vProd) && $item->prod->vProd !== "" ? "'" . (string) $item->prod->vProd . "'" : "NULL";
    $cfop = isset($item->prod->CFOP) && $item->prod->CFOP !== "" ? "'" . (string) $item->prod->CFOP . "'" : "NULL";
    $codigoNcm = isset($item->prod->NCM) && $item->prod->NCM !== "" ? "'" . (string) $item->prod->NCM . "'" : "NULL";
    $codigoCest = isset($item->prod->CEST) && $item->prod->CEST !== "" ? "'" . (string) $item->prod->CEST . "'" : "NULL";
    $eanProduto = isset($item->prod->cEAN) && $item->prod->cEAN !== "" ? "'" . (string) $item->prod->cEAN . "'" : "NULL";
    $refProduto = isset($item->prod->cProd) && $item->prod->cProd !== "" ? "'" . (string) $item->prod->cProd . "'" : "NULL";

    
    if ($eanProduto == "'SEM GTIN'" || $eanProduto == "''") {
        $eanProduto = "NULL";
    }

    $buscaProduto = "SELECT * FROM produtos WHERE idPessoaFornecedor = $idPessoaEmitente AND refProduto = $refProduto";
    $buscar = mysqli_query($conexao, $buscaProduto);
    if (mysqli_num_rows($buscar) == 1) {

        $dadosProduto = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
        $idProduto = $dadosProduto["idProduto"];

    } else {
        $buscaGeralProdutos = "SELECT * FROM geralprodutos WHERE eanProduto = $eanProduto";
        $geralprodutos = mysqli_query($conexaogeral, $buscaGeralProdutos);
        $dadosGeralprodutos = mysqli_fetch_array($geralprodutos, MYSQLI_ASSOC);
        if (mysqli_num_rows($geralprodutos) == 0) {
            $dadosEnder = ($campos == "emit") ? $dados->enderEmit : $dados->enderDest;
    
            $geralProdutosEntrada = array(
                    'eanProduto' => str_replace("'", "", $eanProduto),
                    'nomeProduto' => (string) $item->prod->xProd,
                    'refProduto' => str_replace("'", "", $refProduto)
                );
                    
                $geralProdutosRetorno = chamaAPI(null, '/cadastros/geralprodutos', json_encode($geralProdutosEntrada), 'PUT');
                $idGeralProduto = $geralProdutosRetorno['idGeralProduto'];
            }

        $produEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idGeralProduto' => $idGeralProduto,
            'idPessoaFornecedor' => $idPessoaEmitente,
            'refProduto' => str_replace("'", "", $refProduto),
            'nomeProduto' => (string) $item->prod->xProd,
            'valorCompra' => (string) $item->prod->vUnCom,
            'codigoNcm' => (string) $item->prod->NCM,
            'codigoCest' => (string) $item->prod->CEST
        );
        $produRetorno = chamaAPI(null, '/cadastros/produtos', json_encode($produEntrada), 'PUT');
        $idProduto = $produRetorno['idProduto'];

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
        $vTotTribIMPOSTO = isset($item->imposto->vTotTrib) && $item->imposto->vTotTrib !== "" ? "'" . (string) $item->imposto->vTotTrib . "'" : "null";
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
            $CST = isset($campos->CST) ? "'" . (string) $campos->CST . "'" : "null";
            $modBC = isset($campos->modBC) ? "'" . (string) $campos->modBC . "'" : "null";
            $vBC = isset($campos->vBC) ? "'" . (string) $campos->vBC . "'" : "null";
            $pICMS = isset($campos->pICMS) ? "'" . (string) $campos->pICMS . "'" : "null";
            $vICMS = isset($campos->vICMS) ? "'" . (string) $campos->vICMS . "'" : "null";
            $pFCP = isset($campos->pFCP) ? "'" . (string) $campos->pFCP . "'" : "null";
            $vFCP = isset($campos->vFCP) ? "'" . (string) $campos->vFCP . "'" : "null";
            $qBCMono = isset($campos->qBCMono) ? "'" . (string) $campos->qBCMono . "'" : "null";
            $vICMSMono = isset($campos->vICMSMono) ? "'" . (string) $campos->vICMSMono . "'" : "null";
            $vBCFCP = isset($campos->vBCFCP) ? "'" . (string) $campos->vBCFCP . "'" : "null";
            $pRedBCST = isset($campos->pRedBCST) ? "'" . (string) $campos->pRedBCST . "'" : "null";
            $vICMSST = isset($campos->vICMSST) ? "'" . (string) $campos->vICMSST . "'" : "null";
            $vBCFCPST = isset($campos->vBCFCPST) ? "'" . (string) $campos->vBCFCPST . "'" : "null";
            $pFCPST = isset($campos->pFCPST) ? "'" . (string) $campos->pFCPST . "'" : "null";
            $vFCPST = isset($campos->vFCPST) ? "'" . (string) $campos->vFCPST . "'" : "null";
            $vICMSSTDeson = isset($campos->vICMSSTDeson) ? "'" . (string) $campos->vICMSSTDeson . "'" : "null";
            $pRedBC = isset($campos->pRedBC) ? "'" . (string) $campos->pRedBC . "'" : "null";
            $vICMSDeson = isset($campos->vICMSDeson) ? "'" . (string) $campos->vICMSDeson . "'" : "null";
            $motDesICMS = isset($campos->motDesICMS) ? "'" . (string) $campos->motDesICMS . "'" : "null";
            $nomeImposto = "'" . $nomeImposto . "'";

            $sqlImposto = "INSERT INTO fisnotaproduimposto(idNota,nItem,imposto,nomeImposto,vTotTrib,orig,CSOSN,modBCST,pMVAST,vBCST,pICMSST,vICMSST,CST,modBC,vBC,pICMS,vICMS,pFCP,vFCP,qBCMono,vICMSMono,vBCFCP,pRedBCST,vBCFCPST,pFCPST,vFCPST,vICMSSTDeson,pRedBC,vICMSDeson,motDesICMS) 
                           VALUES ($idNota,$nItem,$imposto,$nomeImposto,$vTotTribIMPOSTO,$orig,$CSOSN,$modBCST,$pMVAST,$vBCST,$pICMSST,$vICMSST,$CST,$modBC,$vBC,$pICMS,$vICMS,$pFCP,$vFCP,$qBCMono,$vICMSMono,$vBCFCP,$pRedBCST,$vBCFCPST,$pFCPST,$vFCPST,$vICMSSTDeson,$pRedBC,$vICMSDeson,$motDesICMS) ";

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