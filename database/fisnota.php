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
		$xml = $NFe;

		//Nota fiscal
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
		$pessoaEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			$emitente,
			$destinatario
		);
		//Inserir Pessoa
		$pessoas = chamaAPI(null, '/impostos/nfepessoa', json_encode($pessoaEntrada), 'PUT');
		foreach ($pessoas as $id => $pessoaResponse) {
			if ($pessoaResponse["status"] === 200) {
				$idPessoaInserido = $pessoaResponse["idPessoaInserido"];
				if ($id == 0) {
					$idPessoaEmitente = $idPessoaInserido;
				} elseif ($id == 1) {
					$idPessoaDestinatario = $idPessoaInserido;
				}
			}
		}

		$notaEntrada = array(
			'chaveNFe' => $chaveNFe,
			'naturezaOp' => $naturezaOp,
			'modelo' => $modelo,
			'NF' => $NF,
			'serie' => $serie,
			'dtEmissao' => $dtEmissao,
			'idPessoaEmitente' => $idPessoaEmitente,
			'idPessoaDestinatario' => $idPessoaDestinatario,
			'baseCalculo' => $baseCalculo,
			'valorProdutos' => $valorProdutos,
			'pis' => $pis,
			'cofins' => $cofins,
			'idEmpresa' => $_SESSION['idEmpresa']
		);
		//Inserir Nota
		$nfe = chamaAPI(null, '/impostos/fisnota', json_encode($notaEntrada), 'PUT');
		$idNota = $nfe['idNotaInserido'];

		//Produtos
		$produtoEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'produtos' => array(),
		);
		foreach ($xml->infNFe->det as $item) {
			$produtoEntrada['produtos'][] = array(
				'idPessoaEmitente' => $idPessoaEmitente,
				'nomeProduto' => (string)$item->prod->xProd,
				'idNota' => $idNota,
				'nItem' => (string)$item['nItem'],
				'refProduto' => (string)$item->prod->cProd,
				'quantidade' => (string)$item->prod->qCom, 
				'unidCom' => (string)$item->prod->uCom,
				'valorUnidade' => (string)$item->prod->vUnCom,
				'valorTotal' => (string)$item->prod->vProd, 
				'cfop' => (string)$item->prod->CFOP, 
				'codigoNcm' => (string)$item->prod->NCM,
				'codigoCest' => (string)$item->prod->CEST
			);
		}
		//Inserir fisNotaProduto e Produto
		$produtos = chamaAPI(null, '/impostos/nfeprodutos', json_encode($produtoEntrada), 'PUT');
		$fisnotaproduto = chamaAPI(null, '/impostos/nfefisnotaproduto', json_encode($produtoEntrada), 'PUT');

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



}