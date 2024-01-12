<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fisnotaproduimposto";
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
$impostos = array();

$sql = "SELECT fisnotaproduimposto.*, produtos.nomeProduto FROM fisnotaproduimposto 
        LEFT JOIN fisnotaproduto ON fisnotaproduto.idNota = fisnotaproduimposto.idNota and fisnotaproduto.nItem = fisnotaproduimposto.nItem
        LEFT JOIN produtos ON fisnotaproduto.idProduto = produtos.idProduto ";
$where = " where ";
if (isset($jsonEntrada["idNota"])) {
    $sql = $sql . $where . " fisnotaproduimposto.idNota = " . $jsonEntrada["idNota"];
    $where = " and ";
}

if (isset($jsonEntrada["nItem"])) {
    $sql = $sql . $where . " fisnotaproduimposto.nItem = " . $jsonEntrada["nItem"];
    $where = " and ";
}

if (isset($jsonEntrada["imposto"])) {
    $sql = $sql . $where . " fisnotaproduimposto.imposto = " . "'" . $jsonEntrada["imposto"] . "'";
    $where = " and ";
}

//echo "-SQL->".$sql."\n"; 
//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 3) {
        fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
    }
}
//LOG

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($impostos, $row);
    $rows = $rows + 1;
}

/* if (isset($jsonEntrada["idNota"]) && $rows==1) {
  $impostos = $impostos[0];
} */
$jsonSaida = $impostos;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG