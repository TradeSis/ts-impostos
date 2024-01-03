<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "imposto_inserir";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "fisnota_" . date("dmY") . ".log", "a");
        }
    }
}
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL == 1) {
        fwrite($arquivo, $identificacao . "\n");
    }
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
    }
}
//LOG

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);

if (isset($jsonEntrada['xml'])) {
    $xml = simplexml_load_string($jsonEntrada['xml']);
    $infNFe = $xml->infNFe;

    $vTotTrib = isset($infNFe->total->ICMSTot->vTotTrib) && $infNFe->total->ICMSTot->vTotTrib !== "" && $infNFe->total->ICMSTot->vTotTrib !== "" ? "'" . (string) $infNFe->total->ICMSTot->vTotTrib . "'" : "null";
    foreach ($infNFe->det as $item) {
        $idNota = isset($jsonEntrada['idNota']) && $jsonEntrada['idNota'] !== "" ? "'" . $jsonEntrada['idNota'] . "'" : "null";
        $nItem = isset($item['nItem']) && $item['nItem'] !== "" ? "'" . (string) $item['nItem'] . "'" : "null";

        if (isset($item->imposto)) {
            foreach ($item->imposto->children() as $filho) {
                $imposto = "'" . $filho->getName() . "'";
                $nomeImposto = $filho->children()->count() > 0 ? $filho->children()->getName() : null;

                if ($imposto == "'ICMS'") {
                    if ($nomeImposto) {
                        $ICMS = $filho->$nomeImposto;

                        $orig = isset($ICMS->orig) ? "'" . (string) $ICMS->orig . "'" : "null";
                        $CSOSN = isset($ICMS->CSOSN) ? "'" . (string) $ICMS->CSOSN . "'" : "null";
                        $modBCST = isset($ICMS->modBCST) ? "'" . (string) $ICMS->modBCST . "'" : "null";
                        $pMVAST = isset($ICMS->pMVAST) ? "'" . (string) $ICMS->pMVAST . "'" : "null";
                        $vBCST = isset($ICMS->vBCST) ? "'" . (string) $ICMS->vBCST . "'" : "null";
                        $pICMSST = isset($ICMS->pICMSST) ? "'" . (string) $ICMS->pICMSST . "'" : "null";
                        $vICMSST = isset($ICMS->vICMSST) ? "'" . (string) $ICMS->vICMSST . "'" : "null";

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