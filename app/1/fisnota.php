<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
$notas = array();

$sql = "SELECT fisnota.*, 
        emitente.cpfCnpj AS emitente_cpfCnpj, emitente.nome AS emitente_nome, emitente.IE AS emitente_IE, emitente.municipio AS emitente_municipio, emitente.UF AS emitente_UF, emitente.pais AS emitente_pais, 
        destinatario.cpfCnpj AS destinatario_cpfCnpj, destinatario.nome AS destinatario_nome, destinatario.IE AS destinatario_IE, destinatario.municipio AS destinatario_municipio, destinatario.UF AS destinatario_UF, destinatario.pais AS destinatario_pais FROM fisnota
        LEFT JOIN pessoa AS emitente ON fisnota.emitente = emitente.cpfCnpj
        LEFT JOIN pessoa AS destinatario ON fisnota.destinatario = destinatario.cpfCnpj ";
$where = " where ";
if (isset($jsonEntrada["idNota"])) {
  $sql = $sql . $where . " fisnota.idNota = " . $jsonEntrada["idNota"];
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