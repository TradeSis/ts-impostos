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

function buscarNotaImpostos($idNota=null)
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
	$notas = chamaAPI(null, '/impostos/fisnotatotal', json_encode($apiEntrada), 'GET');
	return $notas;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {
		$xmlArquivos = array();
	
		foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
			$xmlArquivo = file_get_contents($tmpName);
			$xmlArquivos[] = $xmlArquivo;
		}
	
		// Envia XML puro
		$apiEntrada = array(
			'xml' => $xmlArquivos,
			'idEmpresa' => $_SESSION['idEmpresa'],
		);
	
		$nfe = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'PUT');
	
		echo json_encode($nfe);
		return $nfe;
	}

	if ($operacao == "buscarNota") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $_POST['idNota'],
		);
		
		$notas = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');

		echo json_encode($notas);
		return $notas;
	}

	if ($operacao == "buscarNotaProduto") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $_POST['idNota'],
		);
		
		$produ = chamaAPI(null, '/impostos/fisnotaproduto', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}

	if ($operacao == "buscarProduImposto") {
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idNota' => $_POST['idNota'],
			'nItem' => $_POST['nItem']
		);
		
		$produ = chamaAPI(null, '/impostos/fisnotaproduimposto', json_encode($apiEntrada), 'GET');

		echo json_encode($produ);
		return $produ;
	}

	



}