<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
$conexaogeral = conectaMysql(null);

$notaproduto = array();

$sql = "SELECT fisnotaproduto.*, produtos.* FROM fisnotaproduto
        LEFT JOIN produtos ON fisnotaproduto.idProduto = produtos.idProduto ";
$where = " where ";
if (isset($jsonEntrada["idNota"])) {
  $sql = $sql . $where . " fisnotaproduto.idNota = " . $jsonEntrada["idNota"];
  $where = " and ";
}
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  $idGeralProduto = $row['idGeralProduto'];

  $sql2 = "SELECT geralprodutos.* FROM geralprodutos WHERE geralprodutos.idGeralProduto = $idGeralProduto";
  $buscar2 = mysqli_query($conexaogeral, $sql2);

  while ($row2 = mysqli_fetch_array($buscar2, MYSQLI_ASSOC)) {
      $mergedRow = array_merge($row, $row2);
      array_push($notaproduto, $mergedRow);
      $rows = $rows + 1;
  }
}

/* if (isset($jsonEntrada["idNota"]) && $rows==1) {
  $notas = $notas[0];
} */
$jsonSaida = $notaproduto;

?>