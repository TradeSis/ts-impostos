<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once __DIR__ . "/../conexao.php";

function buscaXML($idNota=null)
{

	$xml = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idNota' => $idNota,
		'idEmpresa' => $idEmpresa
	);
	$xml = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'GET');
	return $xml;
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

		$xml = $NFe;
		$chaveNFe = str_replace("NFe", "", $xml->infNFe['Id']);
	 	$NF = (string) $xml->infNFe->ide->nNF;
		$serie = (string) $xml->infNFe->ide->serie;
		$dtEmissao = date('Y/m/d', strtotime($xml->infNFe->ide->dEmi));
		$emitente = (string) $xml->infNFe->emit->CNPJ;
		$emitenteNome = (string) $xml->infNFe->emit->xNome;
		$emitenteEnd = (string) $xml->infNFe->emit->enderEmit->xLgr . ' ' . $xml->infNFe->emit->enderEmit->nro;
		$destinatario = (string) $xml->infNFe->dest->CNPJ;
		$destinatarioNome = (string) $xml->infNFe->dest->xNome;
		$destinatarioEnd = (string) $xml->infNFe->dest->enderDest->xLgr . ' ' . $xml->infNFe->dest->enderDest->nro;

		$apiEntrada = array(
			'nomeXml' => $newFileName,
			'pathXml' => $pathAnexo,
			'chaveNFe' => $chaveNFe,
			'NF' => $NF,
			'serie' => $serie,
			'dtEmissao' => $dtEmissao,
			'emitente' => $emitente,
			'destinatario' => $destinatario,
			'idEmpresa' => $_SESSION['idEmpresa']
		);

		$apiEntrada2 = array(
			'cpfCnpj' => $emitente,
			'nome' => $emitenteNome,
			'endereco' => $emitenteEnd,
			'idEmpresa' => $_SESSION['idEmpresa']
		);

		$apiEntrada3 = array(
			'cpfCnpj' => $destinatario,
			'nome' => $destinatarioNome,
			'endereco' => $destinatarioEnd,
			'idEmpresa' => $_SESSION['idEmpresa']
		);

		$xml = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'PUT');
		$pessoa1 = chamaAPI(null, '/impostos/pessoa', json_encode($apiEntrada2), 'PUT');
		$pessoa2 = chamaAPI(null, '/impostos/pessoa', json_encode($apiEntrada3), 'PUT');
		echo json_encode($xml);
		return $xml;
		
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



}