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
        $eanProduto = "'" . (string) $item->prod->cEAN . "'";
        $refProduto = "'" . (string) $item->prod->cProd . "'";

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
        
        
        $idNota = "'" . $jsonEntrada['idNota'] . "'";
        $nItem = "'" . (string) $item['nItem'] . "'";
        $quantidade = "'" . (string) $item->prod->qCom . "'";
        $unidCom = "'" . (string) $item->prod->uCom . "'";
        $valorUnidade = "'" . (string) $item->prod->vUnCom . "'";
        $valorTotal = "'" . (string) $item->prod->vProd . "'";
        $cfop = "'" . (string) $item->prod->CFOP . "'";
        $codigoNcm = "'" . (string) $item->prod->NCM . "'";
        $codigoCest = "'" . (string) $item->prod->CEST . "'";

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