<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
$notas = array();

$sql = "SELECT * FROM fisnotatotal ";
$where = " where ";
if (isset($jsonEntrada["idNota"])) {
  $sql = $sql . $where . " fisnotatotal.idNota = " . $jsonEntrada["idNota"];
  $where = " and ";
}
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($notas, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idNota"]) && $rows==1) {
  $notas = $notas[0];
}
$jsonSaida = $notas;

?>