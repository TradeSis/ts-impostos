<?php

//echo "metodo=".$metodo."\n";
//echo "funcao=".$funcao."\n";
//echo "parametro=".$parametro."\n";

if ($metodo == "GET") {

  switch ($funcao) {

      case "fisatividade":
      include 'fisatividade.php';
      break;

    case "fisprocesso":
      include 'fisprocesso.php';
      break;

    case "fisnatureza":
      include 'fisnatureza.php';
      break;

    case "fisoperacao":
      include 'fisoperacao.php';
      break;

    case "ncm":
      include 'ncm.php';
      break;

    case "cest":
      include 'cest.php';
      break;

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}

if ($metodo == "PUT") {
  switch ($funcao) {

    case "fisatividade":
      include 'fisatividade_inserir.php';
      break;

    case "fisprocesso":
      include 'fisprocesso_inserir.php';
      break;

    case "fisnatureza":
      include 'fisnatureza_inserir.php';
      break;

    case "fisoperacao":
      include 'fisoperacao_inserir.php';
      break;

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}

if ($metodo == "POST") {

  switch ($funcao) {

    case "fisatividade":
      include 'fisatividade_alterar.php';
      break;

    case "fisprocesso":
      include 'fisprocesso_alterar.php';
      break;

    case "fisnatureza":
      include 'fisnatureza_alterar.php';
      break;

    case "fisoperacao":
      include 'fisoperacao_alterar.php';
      break;

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}

if ($metodo == "DELETE") {
  switch ($funcao) {
    
    case "fisatividade":
      include 'fisatividade_excluir.php';
      break;

    case "fisprocesso":
      include 'fisprocesso_excluir.php';
      break;

    case "fisnatureza":
      include 'fisnatureza_excluir.php';
      break;

    case "fisoperacao":
      include 'fisoperacao_excluir.php';
      break;

    default:
      $jsonSaida = json_decode(json_encode(
        array(
          "status" => "400",
          "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
        )
      ), TRUE);
      break;
  }
}
