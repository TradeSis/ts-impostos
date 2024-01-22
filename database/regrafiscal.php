<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "filtrar") {

		$codRegra = $_POST["codRegra"];
		if ($codRegra == ""){
			$codRegra = null;
		}

		$apiEntrada = array(
			'codRegra' => $codRegra,
		);
		
		$regra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'GET');

		echo json_encode($regra);
		return $regra;

	}



}

?>