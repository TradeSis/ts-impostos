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
function buscarProdutos($idProduto=null, $chaveNFe=null)
{

	$xml = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
		$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idProduto' => $idProduto,
		'chaveNFe' => $chaveNFe,
		'idEmpresa' => $idEmpresa
	);
	$xml = chamaAPI(null, '/impostos/fisproduto', json_encode($apiEntrada), 'GET');
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

		//Nota fiscal
		$xml = $NFe;
		$chaveNFe = str_replace("NFe", "", $xml->infNFe['Id']);
	 	$NF = (string) $xml->infNFe->ide->nNF;
		$serie = (string) $xml->infNFe->ide->serie;
		$dtEmissao = date('Y-m-d', strtotime($xml->infNFe->ide->dhEmi));
		$naturezaOp = (string) $xml->infNFe->ide->natOp;
		$modelo = (string) $xml->infNFe->ide->mod;
		$baseCalculo = (string) $xml->infNFe->total->ICMSTot->vBC;
		$valorProdutos = (string) $xml->infNFe->total->ICMSTot->vProd;
		$pis = (string) $xml->infNFe->total->ICMSTot->vPIS;
		$cofins = (string) $xml->infNFe->total->ICMSTot->vCOFINS;
		//Emitente
		$emitente = (string) $xml->infNFe->emit->CNPJ;
		$emitenteNome = (string) $xml->infNFe->emit->xNome;
		$emitenteEnd = (string) $xml->infNFe->emit->enderEmit->xLgr . ' ' . $xml->infNFe->emit->enderEmit->nro;
		$emitenteIE = (string) $xml->infNFe->emit->IE;
		$emitenteMunicipio = (string) $xml->infNFe->emit->enderEmit->xMun;
		$emitenteUF = (string) $xml->infNFe->emit->enderEmit->UF;
		$emitentePais = (string) $xml->infNFe->emit->enderEmit->xPais;
		//Destinatario
		$destinatario = (string) $xml->infNFe->dest->CNPJ;
		$destinatarioNome = (string) $xml->infNFe->dest->xNome;
		$destinatarioEnd = (string) $xml->infNFe->dest->enderDest->xLgr . ' ' . $xml->infNFe->dest->enderDest->nro;
		$destinatarioIE = (string) $xml->infNFe->dest->IE;
		$destinatarioMunicipio = (string) $xml->infNFe->dest->enderDest->xMun;
		$destinatarioUF = (string) $xml->infNFe->dest->enderDest->UF;
		$destinatarioPais = (string) $xml->infNFe->dest->enderDest->xPais;

		$apiEntrada = array(
			'nomeXml' => $newFileName,
			'pathXml' => $pathAnexo,
			'chaveNFe' => $chaveNFe,
			'naturezaOp' => $naturezaOp,
			'modelo' => $modelo,
			'NF' => $NF,
			'serie' => $serie,
			'dtEmissao' => $dtEmissao,
			'emitente' => $emitente,
			'destinatario' => $destinatario,
			'baseCalculo' => $baseCalculo,
			'valorProdutos' => $valorProdutos,
			'pis' => $pis,
			'cofins' => $cofins,
			'idEmpresa' => $_SESSION['idEmpresa']
		);

		$emitente = array(
			'cpfCnpj' => $emitente,
			'nome' => $emitenteNome,
			'IE' => $emitenteIE,
			'municipio' => $emitenteMunicipio,
			'UF' => $emitenteUF,
			'pais' => $emitentePais,
			'endereco' => $emitenteEnd
		);
		
		$destinatario = array(
			'cpfCnpj' => $destinatario,
			'nome' => $destinatarioNome,
			'IE' => $destinatarioIE,
			'municipio' => $destinatarioMunicipio,
			'UF' => $destinatarioUF,
			'pais' => $destinatarioPais,
			'endereco' => $destinatarioEnd
		);
		
		$arrayEntradaPessoa = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			$emitente,
			$destinatario
		);

		//Produtos
		$arrayEntradaProdutos = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'produtos' => array(),
		);
		
		foreach ($xml->infNFe->det as $item) {
			$arrayEntradaProdutos['produtos'][] = array(
				'codigoProduto' => (string)$item->prod->cProd,
				'nomeProduto' => (string)$item->prod->xProd,
				'quantidade' => (string)$item->prod->qCom, 
				'unidCom' => (string)$item->prod->uCom,
				'valorUnidade' => (string)$item->prod->vUnCom,
				'valorTotal' => (string)$item->prod->vProd, 
				'cfop' => (string)$item->prod->CFOP, 
				'ncm' => (string)$item->prod->NCM,
				'cest' => (string)$item->prod->CEST,
				'chaveNFe' => $chaveNFe
			);
		}
		
		$xml = chamaAPI(null, '/impostos/fisnota', json_encode($apiEntrada), 'PUT');
		$pessoas = chamaAPI(null, '/impostos/pessoa', json_encode($arrayEntradaPessoa), 'PUT');
		$fisproduto = chamaAPI(null, '/impostos/fisproduto', json_encode($arrayEntradaProdutos), 'PUT');
		echo json_encode($arrayEntradaProdutos);
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