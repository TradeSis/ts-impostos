<?php
foreach ($infNFe->det as $item) {

    

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
        $nomeProduto = "'" . (string) $item->prod->xProd . "'";

        if($eanProduto == "NULL"){
            $buscaGeralProdutos = "SELECT * FROM geralprodutos WHERE eanProduto = $eanProduto";
        } else {
            $buscaGeralProdutos = "SELECT * FROM geralprodutos WHERE nomeProduto = $nomeProduto";
        }
        $geralprodutos = mysqli_query($conexaogeral, $buscaGeralProdutos);
        $dadosGeralprodutos = mysqli_fetch_array($geralprodutos, MYSQLI_ASSOC);
        if (mysqli_num_rows($geralprodutos) == 0) {
                $geralProdutosEntrada = array(
                    'eanProduto' => str_replace("'", "", $eanProduto),
                    'nomeProduto' => (string) $item->prod->xProd
                );
                        
                $geralProdutosRetorno = chamaAPI(null, '/sistema/geralprodutos', json_encode($geralProdutosEntrada), 'PUT');
                $idGeralProduto = $geralProdutosRetorno['idGeralProduto'];

                //**GeralFornecimento
                $buscaPessoas = "SELECT * FROM pessoas WHERE idPessoa = $idPessoaEmitente";
                $buscar = mysqli_query($conexao, $buscaPessoas);
                $dadosPessoa = mysqli_fetch_array($buscar, MYSQLI_ASSOC);

                $geralFornecimentoEntrada = array(
                    'Cnpj' => $dadosPessoa["cpfCnpj"],
                    'refProduto' => str_replace("'", "", $refProduto),
                    'idGeralProduto' => $idGeralProduto,
                    'valorCompra' => (string) $item->prod->vUnCom
                );
                        
                $geralFornecimentoRetorno = chamaAPI(null, '/sistema/geralfornecimento', json_encode($geralFornecimentoEntrada), 'PUT');
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
            if ($filho->getName() === "IPI") {
                foreach ($filho->children() as $ipi) {
                    if ($ipi->getName() !== "cEnq") {
                        $nomeImposto = "'" . $ipi->getName() . "'";
                        $campos = $ipi;
                    }
                }
            } else {
                $nomeImposto = $filho->children()->count() > 0 ? $filho->children()->getName() : null;
                $campos = $filho->$nomeImposto;
                $nomeImposto = "'" . $nomeImposto . "'";
            }

            if ($imposto == "'ICMS'" ) {
                $orig = isset($campos->orig) ? "'" . (string) $campos->orig . "'" : "null";
                $CSOSN = isset($campos->CSOSN) ? "'" . (string) $campos->CSOSN . "'" : "null";
                $modBCST = isset($campos->modBCST) ? "'" . (string) $campos->modBCST . "'" : "null";
                $pMVAST = isset($campos->pMVAST) ? "'" . (string) $campos->pMVAST . "'" : "null";
                $vBCST = isset($campos->vBCST) ? "'" . (string) $campos->vBCST . "'" : "null";
                $pICMSST = isset($campos->pICMSST) ? "'" . (string) $campos->pICMSST . "'" : "null";
                $vICMSST = isset($campos->vICMSST) ? "'" . (string) $campos->vICMSST . "'" : "null";
                $CST = isset($campos->CST) ? "'" . (string) $campos->CST . "'" : "null";
                $modBC = isset($campos->modBC) ? "'" . (string) $campos->modBC . "'" : "null";
                $vBC = isset($campos->vBC) ? "'" . (string) $campos->vBC . "'" : "null";
                $pICMS = isset($campos->pICMS) ? "'" . (string) $campos->pICMS . "'" : "null";
                $vICMS = isset($campos->vICMS) ? "'" . (string) $campos->vICMS . "'" : "null";

                $sqlImposto = "INSERT INTO fisnotaproduicms(idNota,nItem,imposto,nomeImposto,vTotTrib,orig,CSOSN,modBCST,pMVAST,vBCST,pICMSST,vICMSST,CST,modBC,vBC,pICMS,vICMS) 
                               VALUES ($idNota,$nItem,$imposto,$nomeImposto,$vTotTribIMPOSTO,$orig,$CSOSN,$modBCST,$pMVAST,$vBCST,$pICMSST,$vICMSST,$CST,$modBC,$vBC,$pICMS,$vICMS) ";


            } else {

                $cEnq = isset($filho->cEnq) ? "'" . (string) $filho->cEnq . "'" : "null";
                $CST = isset($campos->CST) ? "'" . (string) $campos->CST . "'" : "null";
                $vBC = isset($campos->vBC) ? "'" . (string) $campos->vBC . "'" : "null";

                $percentual = isset($campos->{"p".$filho->getName()}) ? "'" . (string) $campos->{"p".$filho->getName()} . "'" : "null";
                $valor = isset($campos->{"v".$filho->getName()}) ? "'" . (string) $campos->{"v".$filho->getName()} . "'" : "null";

                $sqlImposto = "INSERT INTO fisnotaproduimposto(idNota,nItem,imposto,nomeImposto,cEnq,CST,vBC,percentual,valor) 
                               VALUES ($idNota,$nItem,$imposto,$nomeImposto,$cEnq,$CST,$vBC,$percentual,$valor) ";


            }
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