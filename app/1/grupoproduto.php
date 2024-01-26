<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "grupoproduto";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "impostos_" . date("dmY") . ".log", "a");
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


$conexao = conectaMysql(null);
$grupoproduto = array();

$sql = "SELECT * FROM fiscalgrupo ";
if (isset($jsonEntrada["codigoGrupo"])) {
  $sql = $sql . " where fiscalgrupo.codigoGrupo = " . $jsonEntrada["codigoGrupo"];
}
$where = " where ";
if (isset($jsonEntrada["buscaGrupoProduto"])) {
  $sql = $sql . $where . " fiscalgrupo.codigoGrupo like " . "'%" . $jsonEntrada["buscaGrupoProduto"] . "%'
    OR fiscalgrupo.nomeGrupo like " . "'%" . $jsonEntrada["buscaGrupoProduto"] . "%' " ;
  $where = " and ";
}
if (isset($jsonEntrada["codigo"])) {
  $sql = $sql . $where . " fiscalgrupo.codigoGrupo IS NOT NULL " ;
  $where = " and ";
}

//echo $sql;
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
  array_push($grupoproduto, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["codigoGrupo"]) && $rows == 1) {
  $grupoproduto = $grupoproduto[0];
}
$jsonSaida = $grupoproduto;


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG
