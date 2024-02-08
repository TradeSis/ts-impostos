<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_processar";
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
$conexaogeral = conectaMysql(null);

// Pega XML puro
if (isset($jsonEntrada['idEmpresa'])) {

    $sql_fisnota = "SELECT fisnota.idNota, fisnota.XML, fisnota.idPessoaEmitente FROM fisnota WHERE idStatusNota = 0";
    if (isset($jsonEntrada["idNota"])) {
    $sql_fisnota = $sql_fisnota . " and fisnota.idNota = " . $jsonEntrada["idNota"];
    }
    $buscar_fisnota = mysqli_query($conexao, $sql_fisnota);
    if (mysqli_num_rows($buscar_fisnota) == 0) {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "Todas NFEs estão processadas"
        );
    } else {

        while ($row_fisnota = mysqli_fetch_array($buscar_fisnota, MYSQLI_ASSOC)) {

            $idPessoaEmitente = $row_fisnota['idPessoaEmitente'];
            $idNota = $row_fisnota['idNota'];


            $xmlContent = $row_fisnota['XML'];
            $xml = simplexml_load_string($xmlContent);
            $infNFe = $xml->NFe->infNFe;

            if ($infNFe == null) {
                $infNFe = $xml->nfeProc->NFe->infNFe;
            }

            if (isset($infNFe)) {

                //********************************************NOTA FISCAL


                $vBC = isset($infNFe->total->ICMSTot->vBC) && $infNFe->total->ICMSTot->vBC !== "" ? "'" . (string) $infNFe->total->ICMSTot->vBC . "'" : "null";
                $vProd = isset($infNFe->total->ICMSTot->vProd) && $infNFe->total->ICMSTot->vProd !== "" ? "'" . (string) $infNFe->total->ICMSTot->vProd . "'" : "null";
                $vPIS = isset($infNFe->total->ICMSTot->vPIS) && $infNFe->total->ICMSTot->vPIS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vPIS . "'" : "null";
                $vCOFINS = isset($infNFe->total->ICMSTot->vCOFINS) && $infNFe->total->ICMSTot->vCOFINS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vCOFINS . "'" : "null";


                $idStatusNota = '1'; //Processado

                $sqlNota = "UPDATE fisnota SET idStatusNota=$idStatusNota WHERE idNota = $idNota";
                //LOG
                if (isset($LOG_NIVEL)) {
                    if ($LOG_NIVEL >= 3) {
                        fwrite($arquivo, $identificacao . "-SQL_Nota->" . $sqlNota . "\n");
                    }
                }
                //LOG

                $atualizarNota = mysqli_query($conexao, $sqlNota);

                if ($atualizarNota) {
                    $jsonSaida = array(
                        "status" => 200,
                        "retorno" => "ok"
                    );
                } else {
                    $jsonSaida = array(
                        "status" => 501,
                        "retorno" => "erro no mysql"
                    );
                    return;
                }

                $nomeTotal = "'" . $infNFe->total->ICMSTot->getName() . "'";
                $vICMS = isset($infNFe->total->ICMSTot->vICMS) && $infNFe->total->ICMSTot->vICMS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMS . "'" : "null";
                $vICMS = isset($infNFe->total->ICMSTot->vICMS) && $infNFe->total->ICMSTot->vICMS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMS . "'" : "null";
                $vICMSDeson = isset($infNFe->total->ICMSTot->vICMSDeson) && $infNFe->total->ICMSTot->vICMSDeson !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMSDeson . "'" : "null";
                $vFCPUFDest = isset($infNFe->total->ICMSTot->vFCPUFDest) && $infNFe->total->ICMSTot->vFCPUFDest !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCPUFDest . "'" : "null";
                $vICMSUFRemet = isset($infNFe->total->ICMSTot->vICMSUFRemet) && $infNFe->total->ICMSTot->vICMSUFRemet !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMSUFRemet . "'" : "null";
                $vFCP = isset($infNFe->total->ICMSTot->vFCP) && $infNFe->total->ICMSTot->vFCP !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCP . "'" : "null";
                $vBCST = isset($infNFe->total->ICMSTot->vBCST) && $infNFe->total->ICMSTot->vBCST !== "" ? "'" . (string) $infNFe->total->ICMSTot->vBCST . "'" : "null";
                $vST = isset($infNFe->total->ICMSTot->vST) && $infNFe->total->ICMSTot->vST !== "" ? "'" . (string) $infNFe->total->ICMSTot->vST . "'" : "null";
                $vFCPST = isset($infNFe->total->ICMSTot->vFCPST) && $infNFe->total->ICMSTot->vFCPST !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCPST . "'" : "null";
                $vFCPSTRet = isset($infNFe->total->ICMSTot->vFCPSTRet) && $infNFe->total->ICMSTot->vFCPSTRet !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCPSTRet . "'" : "null";
                $vFrete = isset($infNFe->total->ICMSTot->vFrete) && $infNFe->total->ICMSTot->vFrete !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFrete . "'" : "null";
                $vSeg = isset($infNFe->total->ICMSTot->vSeg) && $infNFe->total->ICMSTot->vSeg !== "" ? "'" . (string) $infNFe->total->ICMSTot->vSeg . "'" : "null";
                $vDesc = isset($infNFe->total->ICMSTot->vDesc) && $infNFe->total->ICMSTot->vDesc !== "" ? "'" . (string) $infNFe->total->ICMSTot->vDesc . "'" : "null";
                $vII = isset($infNFe->total->ICMSTot->vII) && $infNFe->total->ICMSTot->vII !== "" ? "'" . (string) $infNFe->total->ICMSTot->vII . "'" : "null";
                $vIPI = isset($infNFe->total->ICMSTot->vIPI) && $infNFe->total->ICMSTot->vIPI !== "" ? "'" . (string) $infNFe->total->ICMSTot->vIPI . "'" : "null";
                $vIPIDevol = isset($infNFe->total->ICMSTot->vIPIDevol) && $infNFe->total->ICMSTot->vIPIDevol !== "" ? "'" . (string) $infNFe->total->ICMSTot->vIPIDevol . "'" : "null";
                $vOutro = isset($infNFe->total->ICMSTot->vOutro) && $infNFe->total->ICMSTot->vOutro !== "" ? "'" . (string) $infNFe->total->ICMSTot->vOutro . "'" : "null";
                $vNF = isset($infNFe->total->ICMSTot->vNF) && $infNFe->total->ICMSTot->vNF !== "" ? "'" . (string) $infNFe->total->ICMSTot->vNF . "'" : "null";
                $vTotTribTOTAL = isset($infNFe->total->ICMSTot->vTotTrib) && $infNFe->total->ICMSTot->vTotTrib !== "" ? "'" . (string) $infNFe->total->ICMSTot->vTotTrib . "'" : "null";

                $sqlNota = "INSERT INTO fisnotatotal(idNota,nomeTotal,vBC,vICMS,vICMSDeson,vFCPUFDest,vICMSUFRemet,vFCP,vBCST,vST,vFCPST,vFCPSTRet,vProd,vFrete,vSeg,vDesc,vII,vIPI,vIPIDevol,vPIS,vCOFINS,vOutro,vNF,vTotTrib)
                                    VALUES($idNota,$nomeTotal,$vBC,$vICMS,$vICMSDeson,$vFCPUFDest,$vICMSUFRemet,$vFCP,$vBCST,$vST,$vFCPST,$vFCPSTRet,$vProd,$vFrete,$vSeg,$vDesc,$vII,$vIPI,$vIPIDevol,$vPIS,$vCOFINS,$vOutro,$vNF,$vTotTribTOTAL)";

                //LOG
                if (isset($LOG_NIVEL)) {
                    if ($LOG_NIVEL >= 3) {
                        fwrite($arquivo, $identificacao . "-SQL_Nota->" . $sqlNota . "\n");
                    }
                }
                //LOG

                $atualizarNota = mysqli_query($conexao, $sqlNota);



                //********************************************FISNOTAPRODUTOS

                include 'fisnotaproduto_inserir.php';



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