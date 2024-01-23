<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {

		$apiEntrada = array(
			'idGrupo' => $_POST['idGrupo'],
			'codigoEstado' => $_POST['codigoEstado'],
			'cFOP' => $_POST['cFOP'],
			'codigoCaracTrib' => $_POST['codigoCaracTrib'],
			'finalidade' => $_POST['finalidade'],
			'idRegra' => $_POST['idRegra'],
		);

		$regra = chamaAPI(null, '/impostos/operacaofiscal', json_encode($apiEntrada), 'PUT');
		return $regra;

	}

	if ($operacao == "buscar") {

		$idRegraFiscal = $_POST["idRegraFiscal"];

		if ($idRegraFiscal == ""){
			$idRegraFiscal = null;
		}

	
		$apiEntrada = array(
			'idRegraFiscal' => $idRegraFiscal,
		);
		
		$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'GET');

		echo json_encode($regra);
		return $regra;
	}

	if ($operacao == "filtrar") {

		$apiEntrada = array(
			'idoperacaofiscal' => null,
		);
		
		$regra = chamaAPI(null, '/impostos/operacaofiscal', json_encode($apiEntrada), 'GET');

		echo json_encode($regra);
		return $regra;

	}



}

?>