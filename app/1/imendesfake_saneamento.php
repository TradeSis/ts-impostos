<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "imendesfake";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "imendesfake_Saneamento_" . date("dmY") . ".log", "a");
    }
  }
}
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
  $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);

// CHAMADA IMENDES

$JSONFAKE = "{
  \"Cabecalho\": {
      \"sugestao\": \"\",
      \"amb\": 1,
      \"cnpj\": \"03521642000124\",
      \"dthr\": \"2024-01-03T11:58:03.6497049+00:00\",
      \"transacao\": \"148699938429190218428532\",
      \"mensagem\": \"OK\",
      \"prodEnv\": 1,
      \"prodRet\": 1,
      \"prodNaoRet\": 0,
      \"comportamentosParceiro\": \"102;106;108\",
      \"comportamentosCliente\": \"\",
      \"versao\": \"2.26.13.0\",
      \"duracao\": \"00:00:00.0753184\"
  },
  \"Grupos\": [
      {
          \"codigo\": \"1995\",
          \"descricao\": \"TESTE11\",
          \"nCM\": \"39174090\",
          \"cEST\": \"10.006.00\",
          \"dtVigIni\": \"01/01/1900\",
          \"dtVigFin\": \"\",
          \"lista\": \"\",
          \"tipo\": \"\",
          \"codAnp\": \"\",
          \"passivelPMC\": \"N\",
          \"impostoImportacao\": 16.00,
          \"pisCofins\": {
              \"cstEnt\": \"50\",
              \"cstSai\": \"01\",
              \"aliqPis\": 1.65,
              \"aliqCofins\": 7.6,
              \"nri\": \"\",
              \"ampLegal\": \"''\",
              \"redPis\": 0,
              \"redCofins\": 0
          },
          \"iPI\": {
              \"cstEnt\": \"03\",
              \"cstSai\": \"53\",
              \"aliqipi\": 0,
              \"codenq\": \"999\",
              \"ex\": \"\"
          },
          \"Regras\": [
              {
                  \"uFs\": [
                      {
                          \"uF\": \"SC\",
                          \"CFOP\": {
                              \"cFOP\": \"2101\",
                              \"CaracTrib\": [
                                  {
                                      \"codigo\": \"3\",
                                      \"finalidade\": \"0\",
                                      \"codRegra\": \"6350\",
                                      \"codExcecao\": 0,
                                      \"dtVigIni\": \"01/01/2022\",
                                      \"dtVigFin\": \"\",
                                      \"cFOP\": \"2403\",
                                      \"cST\": \"10\",
                                      \"cSOSN\": \"\",
                                      \"aliqIcmsInterna\": 17.00,
                                      \"aliqIcmsInterestadual\": 12.00,
                                      \"reducaoBcIcms\": 0,
                                      \"reducaoBcIcmsSt\": 0,
                                      \"redBcICMsInterestadual\": 0,
                                      \"aliqIcmsSt\": 17.00,
                                      \"iVA\": 83.00,
                                      \"iVAAjust\": 94.02,
                                      \"fCP\": 0,
                                      \"codBenef\": \"\",
                                      \"pDifer\": 0.0,
                                      \"pIsencao\": 0,
                                      \"antecipado\": \"N\",
                                      \"desonerado\": \"N\",
                                      \"pICMSDeson\": 0,
                                      \"isento\": \"N\",
                                      \"tpCalcDifal\": 0,
                                      \"ampLegal\": \"\",
                                      \"Protocolo\": {},
                                      \"Convenio\": {},
                                      \"regraGeral\": \"N\"
                                  }
                              ]
                          },
                          \"mensagem\": \"OK\"
                      },
                      {
                          \"uF\": \"RS\",
                          \"CFOP\": {
                              \"cFOP\": \"1101\",
                              \"CaracTrib\": [
                                  {
                                      \"codigo\": \"3\",
                                      \"finalidade\": \"0\",
                                      \"codRegra\": \"6350\",
                                      \"codExcecao\": 0,
                                      \"dtVigIni\": \"01/01/2022\",
                                      \"dtVigFin\": \"\",
                                      \"cFOP\": \"1403\",
                                      \"cST\": \"60\",
                                      \"cSOSN\": \"\",
                                      \"aliqIcmsInterna\": 17.00,
                                      \"aliqIcmsInterestadual\": 0.00,
                                      \"reducaoBcIcms\": 0,
                                      \"reducaoBcIcmsSt\": 0,
                                      \"redBcICMsInterestadual\": 0,
                                      \"aliqIcmsSt\": 17.00,
                                      \"iVA\": 83.00,
                                      \"iVAAjust\": 0,
                                      \"fCP\": 0,
                                      \"codBenef\": \"RS052434\",
                                      \"pDifer\": 0,
                                      \"pIsencao\": 0,
                                      \"antecipado\": \"N\",
                                      \"desonerado\": \"N\",
                                      \"pICMSDeson\": 0,
                                      \"isento\": \"N\",
                                      \"tpCalcDifal\": 0,
                                      \"ampLegal\": \"'BASE LEGAL DA SUBSTITUICAO TRIBUTARIA - RICMS/RS, APENDICE II, SECAO III, ITEM XXVI, NUMERO 6'\",
                                      \"Protocolo\": {},
                                      \"Convenio\": {},
                                      \"regraGeral\": \"N\"
                                  }
                              ]
                          },
                          \"mensagem\": \"OK\"
                      }
                  ]
              }
          ],
          \"prodEan\": [
              \"7899830001153\",
              \"07891960708166\"
          ],
          \"Mensagem\": \"OK\"
      }
  ],
  \"SemRetorno\": [],
  \"BaixaSimilaridade\": []
}";

function atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo)
{
  //Atualiza Produto
  $sql_consulta = "SELECT * FROM produtos WHERE eanProduto = $eanProduto ";
  $buscar_consulta = mysqli_query($conexao, $sql_consulta);
  $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
  $idProduto = $row_consulta["idProduto"];

  $update_produtos = "UPDATE produtos SET codigoNcm=$codigoNcm, codigoCest=$codigoCest, codigoGrupo=$codigoGrupo, dataAtualizacaoTributaria=CURRENT_TIMESTAMP()
    WHERE idProduto = $idProduto";

  $atualizar = mysqli_query($conexao, $update_produtos);
  return $atualizar;
}

$retornoImendes = json_decode($JSONFAKE, true);

foreach ($retornoImendes['Grupos'] as $grupo) {
  if (is_array($grupo) && isset($grupo['codigo'])) {

    $codigoGrupo = $grupo['codigo'];
    $eanProdutos = $grupo['prodEan'];

    //Verifica se já tem codigoGrupo
    $sql_consulta = "SELECT * FROM grupoproduto WHERE codigoGrupo = $codigoGrupo ";
    $buscar_consulta = mysqli_query($conexao, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
    $codigoGrupo = isset($row_consulta["codigoGrupo"]) && $row_consulta["codigoGrupo"] !== "null"    ? "'" . $row_consulta["codigoGrupo"] . "'" : "null";
    $codigoNcm = isset($row_consulta["codigoNcm"]) && $row_consulta["codigoNcm"] !== "null"    ? "'" . $row_consulta["codigoNcm"] . "'" : "null";
    $codigoCest = isset($row_consulta["codigoCest"]) && $row_consulta["codigoCest"] !== "null"    ? "'" . $row_consulta["codigoCest"] . "'" : "null";


    if ($codigoGrupo != "null") {
      foreach ($eanProdutos as $eanProduto) {
        $atualizaProduto = atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo);
      }
      $jsonSaida = array(
        "status" => 200,
        "retorno" => "codigo do Grupo existente",
        "codigoGrupo" => $codigoGrupo
      );
    } else {
      $codigoGrupo = "'" . $grupo['codigo'] . "'";
      $codigoCest = "'" . $grupo['cEST'] .  "'";
      $codigoNcm = "'" . $grupo['nCM'] . "'";

      foreach ($eanProdutos as $eanProduto) {
        $atualizaProduto = atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo);
      }

      $apiEntrada = array(
        'idEmpresa' => $idEmpresa,
        'codigoGrupo' => $grupo['codigo'],
        'nomeGrupo' => $grupo['descricao'],
        'codigoNcm' => $grupo['nCM'],
        'codigoCest' => $grupo['cEST'],
        'impostoImportacao' => $grupo['impostoImportacao'],
        'piscofinscstEnt' => $grupo['pisCofins']['cstEnt'],
        'piscofinscstSai' => $grupo['pisCofins']['cstSai'],
        'aliqPis' => $grupo['pisCofins']['aliqPis'],
        'aliqCofins' => $grupo['pisCofins']['aliqCofins'],
        'nri' => $grupo['pisCofins']['nri'],
        'ampLegal' => $grupo['pisCofins']['ampLegal'],
        'redPIS' => $grupo['pisCofins']['redPis'],
        'redCofins' => $grupo['pisCofins']['redCofins'],
        'ipicstEnt' => $grupo['iPI']['cstEnt'],
        'ipicstSai' => $grupo['iPI']['cstSai'],
        'aliqipi' => $grupo['iPI']['aliqipi'],
        'codenq' => $grupo['iPI']['codenq'],
        'ipiex' => $grupo['iPI']['ex']
      );

      $inserirGrupo = chamaAPI(null, '/cadastros/grupoproduto', json_encode($apiEntrada), 'PUT');


      //TRY-CATCH
      try {
        $jsonSaida = array(
          "status" => 200,
          "retorno" => "ok"
        );
      } catch (Exception $e) {
        $jsonSaida = array(
          "status" => 500,
          "retorno" => $e->getMessage()
        );
        if ($LOG_NIVEL >= 1) {
          fwrite($arquivo, $identificacao . "-ERRO->" . $e->getMessage() . "\n");
        }
      } finally {
        // ACAO EM CASO DE ERRO (CATCH), que mesmo assim precise
      }
      //TRY-CATCH
    }
  } else {
    $jsonSaida = array(
      "status" => 400,
      "retorno" => "Faltaram parametros"
    );
  }
}


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG
