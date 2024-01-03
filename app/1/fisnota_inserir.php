<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_inserir";
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

    //Nota fiscal
    $chaveNFe = "'" . str_replace("NFe", "", $infNFe['Id']) . "'";

    $sql2 = "SELECT chaveNFe FROM fisnota WHERE chaveNFe = $chaveNFe";
    $resultado = mysqli_query($conexao, $sql2);

    if (mysqli_num_rows($resultado) > 0) {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "NFe ja cadastrada"
        );
    } else {

        $NF = "'" . (string) $infNFe->ide->nNF . "'";
        $serie = "'" . (string) $infNFe->ide->serie . "'";
        $dtEmissao = "'" . date('Y-m-d', strtotime($xml->infNFe->ide->dhEmi)) . "'";
        $naturezaOp = "'" . (string) $infNFe->ide->natOp . "'";
        $modelo = "'" . (string) $infNFe->ide->mod . "'";
        $baseCalculo = "'" . (string) $infNFe->total->ICMSTot->vBC . "'";
        $valorProdutos = "'" . (string) $infNFe->total->ICMSTot->vProd . "'";
        $pis = "'" . (string) $infNFe->total->ICMSTot->vPIS . "'";
        $cofins = "'" . (string) $infNFe->total->ICMSTot->vCOFINS . "'";
        $XMLentrada = "'" . $jsonEntrada['xml'] . "'";
        
        $idPessoaEmitente = isset($jsonEntrada['idPessoaEmitente']) && $jsonEntrada['idPessoaEmitente'] !== "" ? "'" . $jsonEntrada['idPessoaEmitente'] . "'" : "NULL";
        $idPessoaDestinatario = isset($jsonEntrada['idPessoaDestinatario']) && $jsonEntrada['idPessoaDestinatario'] !== "" ? "'" . $jsonEntrada['idPessoaDestinatario'] . "'" : "NULL";


        $sql = "INSERT INTO fisnota(chaveNFe,naturezaOp,modelo,XML,serie,NF,dtEmissao,idPessoaEmitente,idPessoaDestinatario,baseCalculo,valorProdutos,pis,cofins) VALUES 
        ($chaveNFe,$naturezaOp,$modelo,$XMLentrada,$serie,$NF,$dtEmissao,$idPessoaEmitente,$idPessoaDestinatario,$baseCalculo,$valorProdutos,$pis,$cofins)";
        $atualizar = mysqli_query($conexao, $sql);

        //LOG
        if (isset($LOG_NIVEL)) {
            if ($LOG_NIVEL >= 3) {
                fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
            }
        }
        //LOG

        $idNotaInserido = mysqli_insert_id($conexao);

        if ($atualizar) {
            $jsonSaida = array(
                "status" => 200,
                "retorno" => "ok",
                "idNota" => $idNotaInserido
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