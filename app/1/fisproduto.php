<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
$produtos = array();

$sql = "SELECT * FROM fisproduto ";
$where = " where ";
if (isset($jsonEntrada["idProduto"])) {
  $sql = $sql . $where . " fisproduto.idProduto = " . $jsonEntrada["idProduto"];
  $where = " and ";
}
if (isset($jsonEntrada["chaveNFe"])) {
  $sql = $sql . $where . " fisproduto.chaveNFe = " . $jsonEntrada["chaveNFe"];
  $where = " and ";
}
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($produtos, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idProduto"]) && $rows==1) {
  $produtos = $produtos[0];
}
$jsonSaida = $produtos;

?>