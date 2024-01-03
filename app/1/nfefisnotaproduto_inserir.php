<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_notaproduto_inserir";
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

    foreach ($infNFe->det as $item) {
        $eanProduto = isset($item->prod->cEAN) && $item->prod->cEAN !== "" && $item->prod->cEAN !== "" ? "'" .  (string) $item->prod->cEAN . "'" : "null";
        $refProduto = isset($item->prod->cProd) && $item->prod->cProd !== "" && $item->prod->cProd !== "" ? "'" .  (string) $item->prod->cProd . "'" : "null";

        if($refProduto == $eanProduto) {
            $refProduto = "NULL";
        }
        if($eanProduto == "'SEM GTIN'") {
            $eanProduto = "NULL";
        }
        
        if ($eanProduto === "NULL") {
            $sql2 = "SELECT * FROM produtos WHERE eanProduto is NULL AND refProduto = $refProduto";
        } else {
            $sql2 = "SELECT * FROM produtos WHERE eanProduto = $eanProduto";
        }
        $buscar = mysqli_query($conexao, $sql2);
        $row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
        $idProduto = $row["idProduto"];
        
        
        $idNota = isset($jsonEntrada['idNota']) && $jsonEntrada['idNota'] !== "" && $jsonEntrada['idNota'] !== "" ? "'" .  $jsonEntrada['idNota'] . "'" : "null";
        $nItem = isset($item['nItem']) && $item['nItem'] !== "" && $item['nItem'] !== "" ? "'" .  (string) $item['nItem'] . "'" : "null";
        $quantidade = isset($item->prod->qCom) && $item->prod->qCom !== "" && $item->prod->qCom !== "" ? "'" .  (string) $item->prod->qCom . "'" : "null";
        $unidCom = isset($item->prod->uCom) && $item->prod->uCom !== "" && $item->prod->uCom !== "" ? "'" .  (string) $item->prod->uCom . "'" : "null";
        $valorUnidade = isset($item->prod->vUnCom) && $item->prod->vUnCom !== "" && $item->prod->vUnCom !== "" ? "'" .  (string) $item->prod->vUnCom . "'" : "null";
        $valorTotal = isset($item->prod->vProd) && $item->prod->vProd !== "" && $item->prod->vProd !== "" ? "'" .  (string) $item->prod->vProd . "'" : "null";
        $cfop = isset($item->prod->CFOP) && $item->prod->CFOP !== "" && $item->prod->CFOP !== "" ? "'" .  (string) $item->prod->CFOP . "'" : "null";
        $codigoNcm = isset($item->prod->NCM) && $item->prod->NCM !== "" && $item->prod->NCM !== "" ? "'" .  (string) $item->prod->NCM . "'" : "null";
        $codigoCest = isset($item->prod->CEST) && $item->prod->CEST !== "" && $item->prod->CEST !== "" ? "'" .  (string) $item->prod->CEST . "'" : "null";


        $sql = "INSERT INTO fisnotaproduto(idNota,nItem,idProduto,quantidade,unidCom,valorUnidade,valorTotal,cfop,codigoNcm,codigoCest)
                VALUES($idNota,$nItem,$idProduto,$quantidade,$unidCom,$valorUnidade,$valorTotal,$cfop,$codigoNcm,$codigoCest)";
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