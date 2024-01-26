<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
$conexao2 = conectaMysql(null);
$notas = array();

$sql = "SELECT fisnota.*, 
        emitente.cpfCnpj AS emitente_cpfCnpj, destinatario.cpfCnpj AS destinatario_cpfCnpj FROM fisnota
        LEFT JOIN pessoas AS emitente ON fisnota.idPessoaEmitente = emitente.idPessoa
        LEFT JOIN pessoas AS destinatario ON fisnota.idPessoaDestinatario = destinatario.idPessoa";
$where = " where ";
if (isset($jsonEntrada["idNota"])) {
  $sql = $sql . $where . " fisnota.idNota = " . $jsonEntrada["idNota"];
  $where = " and ";
}

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  $emitente_cpfCnpj = $row['emitente_cpfCnpj'];
  $destinatario_cpfCnpj = $row['destinatario_cpfCnpj'];

  $sql2_emitente = "SELECT geralpessoas.* FROM geralpessoas WHERE geralpessoas.cpfCnpj = $emitente_cpfCnpj";
  $buscar2_emitente = mysqli_query($conexao2, $sql2_emitente);

  while ($row2_emitente = mysqli_fetch_array($buscar2_emitente, MYSQLI_ASSOC)) {
      foreach ($row2_emitente as $dadosRow => $dadosEmitente) {
          $row["emitente_" . $dadosRow] = $dadosEmitente;
      }
  }

  $sql2_destinatario = "SELECT geralpessoas.* FROM geralpessoas WHERE geralpessoas.cpfCnpj = $destinatario_cpfCnpj";
  $buscar2_destinatario = mysqli_query($conexao2, $sql2_destinatario);

  while ($row2_destinatario = mysqli_fetch_array($buscar2_destinatario, MYSQLI_ASSOC)) {
      foreach ($row2_destinatario as $dadosRow => $dadosDestinatario) {
          $row["destinatario_" . $dadosRow] = $dadosDestinatario;
      }
  }

  array_push($notas, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idNota"]) && $rows==1) {
  $notas = $notas[0];
}
$jsonSaida = $notas;

?>