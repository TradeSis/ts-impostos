<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once __DIR__ . "/../conexao.php";

function buscarNota($idNota=null)
{

	$notas = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idNota' => $idNota,
		'idEmpresa' => $idEmpresa
	);
	$notas = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');
	return $notas;
}
function buscarNotaProduto($idNota=null)
{

	$notas = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idNota' => $idNota,
		'idEmpresa' => $idEmpresa
	);
	$notas = chamaAPI(null, '/impostos/fisnotaproduto', json_encode($apiEntrada), 'GET');
	return $notas;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {

		$xmlArquivo = file_get_contents($_FILES['file']['tmp_name']);

		$xml = simplexml_load_string($xmlArquivo);
		$NFe = $xml->NFe;
		$xmlContent = $NFe->asXML();

		$apiEntrada = array(
			'xml' => $xmlContent,
			'idEmpresa' => $_SESSION['idEmpresa'],
		);

		$nfe = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'PUT');
		
		echo json_encode($nfe);
		return $nfe;
		
	}

	if ($operacao == "buscar") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $_POST['idNota'],
		);
		
		$notas = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');

		echo json_encode($notas);
		return $notas;
	}



}