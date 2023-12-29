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

		$anexo = $_POST["arquivo"];

	
		if ($anexo !== null) {
			$fileInfo = pathinfo($anexo);
			$oldFileName = $fileInfo['filename']; 
			$fileExtension = $fileInfo['extension']; 

			if ($fileExtension === 'xml') {
				$pasta = ROOT . "/xml/carregados/";
				$newFileName = "carregado_" . $oldFileName . "." . $fileExtension;
				
				$pathAnexo = 'http://' . $_SERVER["HTTP_HOST"] . '/xml/carregados/' . $newFileName;

				$newFilePath = $pasta . $newFileName;
				if (rename($anexo, $newFilePath)) {
					if (file_exists($anexo)) {
						unlink($anexo);
					}
				}
			}
		}
		
		$xml = simplexml_load_file($newFilePath);
		$NFe = $xml->NFe;
		$xmlContent = $NFe->asXML();

		$apiEntrada = array(
			'xml' => $xmlContent,
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idPessoaEmitente' => null,
			'idPessoaDestinatario' => null,
			'idNota' => null
		);

		//Inserir Pessoa
		$pessoas = chamaAPI(null, '/impostos/nfepessoa', json_encode($apiEntrada), 'PUT');
		foreach ($pessoas as $id => $pessoaResponse) {
			if ($pessoaResponse["status"] === 200) {
				$idPessoaInserido = $pessoaResponse["idPessoaInserido"];
				if ($id == 0) {
					$apiEntrada['idPessoaEmitente'] = $idPessoaInserido;
				} elseif ($id == 1) {
					$apiEntrada['idPessoaDestinatario'] = $idPessoaInserido;
				}
			}
		}
		
		//Inserir Nota
		$nfe = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'PUT');
		if ($pessoaResponse["status"] === 200) {
			$idNota = $nfe['idNotaInserido'];
			$apiEntrada['idNota'] = $idNota;
		}
		
		//Inserir fisNotaProduto e Produto
		$produtos = chamaAPI(null, '/impostos/nfeprodutos', json_encode($apiEntrada), 'PUT');
		$fisnotaproduto = chamaAPI(null, '/impostos/nfefisnotaproduto', json_encode($apiEntrada), 'PUT');

		echo json_encode($nfe);
		return $nfe;
		
	}
	if ($operacao == "upload") {
		$anexo = $_FILES['file'];
	
		if ($anexo !== null) {
			$ext = pathinfo($anexo["name"], PATHINFO_EXTENSION);
			
			if (strtolower($ext) === "xml") {
				$pasta = ROOT . "/xml/";
				$pathAnexo = 'http://' . $_SERVER["HTTP_HOST"] . '/xml/' . $anexo["name"];
				
				move_uploaded_file($anexo['tmp_name'], $pasta . $anexo["name"]);
			} 
		} 
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