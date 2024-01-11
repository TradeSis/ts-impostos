<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}

	if ($operacao == "buscar") {

		$idRegraFiscal = $_POST["idRegraFiscal"];

		if ($idRegraFiscal == ""){
			$idRegraFiscal = null;
		}

	
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idRegraFiscal' => $idRegraFiscal,
		);
		
		$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'GET');

		echo json_encode($regra);
		return $regra;
	}

	if ($operacao == "filtrar") {

		$codigoGrupo = $_POST["codigoGrupo"];

		if ($codigoGrupo == ""){
			$codigoGrupo = null;
		}

	
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'codigoGrupo' => $codigoGrupo,
		);
		
		$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'GET');

		echo json_encode($regra);
		return $regra;

	}



}

?>