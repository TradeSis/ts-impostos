<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
$notas = array();

$sql = "SELECT fisnota.*, 
        emitente.cpfCnpj AS emitente_cpfCnpj, emitente.nomePessoa AS emitente_nomePessoa, emitente.IE AS emitente_IE, emitente.municipio AS emitente_municipio, emitente.codigoEstado AS emitente_codigoEstado, emitente.pais AS emitente_pais, 
        destinatario.cpfCnpj AS destinatario_cpfCnpj, destinatario.nomePessoa AS destinatario_nomePessoa, destinatario.IE AS destinatario_IE, destinatario.municipio AS destinatario_municipio, destinatario.codigoEstado AS destinatario_codigoEstado, destinatario.pais AS destinatario_pais FROM fisnota
        LEFT JOIN pessoas AS emitente ON fisnota.idPessoaEmitente = emitente.idPessoa
        LEFT JOIN pessoas AS destinatario ON fisnota.idPessoaDestinatario = destinatario.idPessoa ";
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