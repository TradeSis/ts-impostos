<?php

$vTotTrib = isset($infNFe->total->ICMSTot->vTotTrib) && $infNFe->total->ICMSTot->vTotTrib !== "" && $infNFe->total->ICMSTot->vTotTrib !== "" ? "'" . (string) $infNFe->total->ICMSTot->vTotTrib . "'" : "null";
foreach ($infNFe->det as $item) {
    $idNota = isset($idNotaInserido) && $idNotaInserido !== "" ? "'" . $idNotaInserido . "'" : "null";
    $nItem = isset($item['nItem']) && $item['nItem'] !== "" ? "'" . (string) $item['nItem'] . "'" : "null";

    if (isset($item->imposto)) {
        foreach ($item->imposto->children() as $filho) {
            $imposto = "'" . $filho->getName() . "'";
            $nomeImposto = $filho->children()->count() > 0 ? $filho->children()->getName() : null;

            if ($nomeImposto) {
                $campos = $filho->$nomeImposto;

                $orig = isset($campos->orig) ? "'" . (string) $campos->orig . "'" : "null";
                $CSOSN = isset($campos->CSOSN) ? "'" . (string) $campos->CSOSN . "'" : "null";
                $modBCST = isset($campos->modBCST) ? "'" . (string) $campos->modBCST . "'" : "null";
                $pMVAST = isset($campos->pMVAST) ? "'" . (string) $campos->pMVAST . "'" : "null";
                $vBCST = isset($campos->vBCST) ? "'" . (string) $campos->vBCST . "'" : "null";
                $pICMSST = isset($campos->pICMSST) ? "'" . (string) $campos->pICMSST . "'" : "null";
                $vICMSST = isset($campos->vICMSST) ? "'" . (string) $campos->vICMSST . "'" : "null";

                $sql2 = "SELECT * FROM fisnotaproduimposto WHERE idNota = $idNota AND nItem = $nItem AND imposto = $imposto";
                $buscar = mysqli_query($conexao, $sql2);
                $produto = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
                if (mysqli_num_rows($buscar) == 1) {
                    $jsonSaida[] = array(
                        "status" => 200,
                        "retorno" => "Imposto do Produto existente"
                    );
                } else {
                    $sql = "INSERT INTO fisnotaproduimposto(idNota,nItem,imposto,nomeImposto,vTotTrib,orig,CSOSN,modBCST,pMVAST,vBCST,pICMSST,vICMSST) 
                        VALUES ($idNota,$nItem,$imposto,'$nomeImposto',$vTotTrib,$orig,$CSOSN,$modBCST,$pMVAST,$vBCST,$pICMSST,$vICMSST)";
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

            }
        }

    }
}
?>