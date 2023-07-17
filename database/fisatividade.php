<?php
// gabriel 060623 15:06

include_once('../conexao.php');

function buscaAtividade($idAtividade=null)
{
	
	$atividade = array();
	$apiEntrada = array(
		'idAtividade' => $idAtividade,
	);
	$atividade = chamaAPI(null, '/fiscal/fisatividade', json_encode($apiEntrada), 'GET');
	return $atividade;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao=="inserir") {
		$apiEntrada = array(
			'nomeAtividade' => $_POST['nomeAtividade']
		);
		$atividade = chamaAPI(null, '/fiscal/fisatividade', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idAtividade' => $_POST['idAtividade'],
			'nomeAtividade' => $_POST['nomeAtividade']
		);
		$atividade = chamaAPI(null, '/fiscal/fisatividade', json_encode($apiEntrada), 'POST');
	}
	
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idAtividade' => $_POST['idAtividade']
		);
		$atividade = chamaAPI(null, '/fiscal/fisatividade', json_encode($apiEntrada), 'DELETE');
	}



	header('Location: ../operacoes/fisatividade.php');	
	
}

?>

