<?php
// lucas 27122023 - criado

include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {

		$apiEntrada = array(
			'codigoGrupo' => $_POST['codigoGrupo'],
			'nomeGrupo' => $_POST['nomeGrupo'],
			'codigoNcm' => $_POST['codigoNcm'],
			'codigoCest' => $_POST['codigoCest'],
			'impostoImportacao' => $_POST['impostoImportacao'],
			'piscofinscstEnt' => $_POST['piscofinscstEnt'],
			'piscofinscstSai' => $_POST['piscofinscstSai'],
			'aliqPis' => $_POST['aliqPis'],
			'aliqCofins' => $_POST['aliqCofins'],
			'nri' => $_POST['nri'],
			'ampLegal' => $_POST['ampLegal'],
			'redPIS' => $_POST['redPIS'],
			'redCofins' => $_POST['redCofins'],
			'ipicstEnt' => $_POST['ipicstEnt'],
			'ipicstSai' => $_POST['ipicstSai'],
			'aliqipi' => $_POST['aliqipi'],
			'codenq' => $_POST['codenq'],
			'ipiex' => $_POST['ipiex']
		);
		$grupoproduto = chamaAPI(null, '/impostos/grupoproduto', json_encode($apiEntrada), 'PUT');
		//echo json_encode($apiEntrada);
		return $grupoproduto;

	}

	if ($operacao == "buscar") {
		$apiEntrada = array(
			'codigoGrupo' => $_POST['codigoGrupo']
		);
		$grupoproduto = chamaAPI(null, '/impostos/grupoproduto', json_encode($apiEntrada), 'GET');

		echo json_encode($grupoproduto);
		return $grupoproduto;
	}

	if ($operacao == "filtrar") {

		$buscaGrupoProduto = $_POST["buscaGrupoProduto"];

		if ($buscaGrupoProduto == "") {
			$buscaGrupoProduto = null;
		}

		$apiEntrada = array(
			'codigoGrupo' => null,
			'buscaGrupoProduto' => $buscaGrupoProduto
		);

		$grupoproduto = chamaAPI(null, '/impostos/grupoproduto', json_encode($apiEntrada), 'GET');

		echo json_encode($grupoproduto);
		return $grupoproduto;
	}
}

?>

