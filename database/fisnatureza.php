<?php
// gabriel 060623 15:06

include_once('../conexao.php');

function buscaNatureza($idNatureza=null)
{
	
	$natureza = array();
	$apiEntrada = array(
		'idNatureza' => $idNatureza,
	);
	$natureza = chamaAPI(null, '/fiscal/fisnatureza', json_encode($apiEntrada), 'GET');
	return $natureza;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {
		$apiEntrada = array(
			'nomeNatureza' => $_POST['nomeNatureza']
		);
		$natureza = chamaAPI(null, '/fiscal/fisnatureza', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idNatureza' => $_POST['idNatureza'],
			'nomeNatureza' => $_POST['nomeNatureza']
		);
		$natureza = chamaAPI(null, '/fiscal/fisnatureza', json_encode($apiEntrada), 'POST');
	}
	
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idNatureza' => $_POST['idNatureza']
		);
		$natureza = chamaAPI(null, '/fiscal/fisnatureza', json_encode($apiEntrada), 'DELETE');
	}



	header('Location: ../operacoes/fisnatureza.php');	
	
}

?>

