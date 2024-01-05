<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "regra fiscal";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "regra fiscal" . date("dmY") . ".log", "a");
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
          \"codigo\": \"1996\",
          \"descricao\": \"TESTE112\",
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
                                      \"ampLegal\": \"''\",
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



$retornoImendes = json_decode($JSONFAKE, true);


foreach ($retornoImendes['Grupos'] as $grupos) {
    if (is_array($grupos) && isset($grupos['Regras'])) {

        foreach ($grupos['Regras'] as $regras) {

            foreach ($regras as $ufs) {

                foreach ($ufs as $dadosCFOP) {

                    $uF = isset($dadosCFOP['uF']) && $dadosCFOP['uF'] !== "null"    ? "'" . $dadosCFOP['uF'] . "'" : "null";
                    $cFOP = isset($dadosCFOP['CFOP']['cFOP']) && $dadosCFOP['CFOP']['cFOP'] !== "null"    ? "'" . $dadosCFOP['CFOP']['cFOP'] . "'" : "null";

                    foreach ($dadosCFOP['CFOP']['CaracTrib'] as $CaracTrib) {
                        $codigoGrupo = isset($grupos['codigo']) && $grupos['codigo'] !== "null"    ? "'" . $grupos['codigo'] . "'" : "null";

                        $codigoCaracTrib = isset($CaracTrib['codigo']) && $CaracTrib['codigo'] !== "null"    ? "'" . $CaracTrib['codigo'] . "'" : "null";
                        $finalidade = isset($CaracTrib['finalidade']) && $CaracTrib['finalidade'] !== "null"    ? "'" . $CaracTrib['finalidade'] . "'" : "null";
                        $codRegra = isset($CaracTrib['codRegra']) && $CaracTrib['codRegra'] !== "null"    ? "'" . $CaracTrib['codRegra'] . "'" : "null";
                        $codExcecao = isset($CaracTrib['codExcecao']) && $CaracTrib['codExcecao'] !== "null"    ? "'" . $CaracTrib['codExcecao'] . "'" : "null";

                        $dtVigIni = isset($CaracTrib['dtVigIni']) && $CaracTrib['dtVigIni'] !== ""    ? date('Ymd', strtotime($CaracTrib['dtVigIni'])) : "null";
                        $dtVigFin = isset($CaracTrib['dtVigFin']) && $CaracTrib['dtVigFin'] !== ""    ? date('Ymd', strtotime($CaracTrib['dtVigFin'])) : "null";

                        $cFOPCaracTrib = isset($CaracTrib['cFOP']) && $CaracTrib['cFOP'] !== "null"    ? "'" . $CaracTrib['cFOP'] . "'" : "null";
                        $cST = isset($CaracTrib['cST']) && $CaracTrib['cST'] !== "null"    ? "'" . $CaracTrib['cST'] . "'" : "null";
                        $cSOSN = isset($CaracTrib['cSOSN']) && $CaracTrib['cSOSN'] !== "null"    ? "'" . $CaracTrib['cSOSN'] . "'" : "null";
                        $aliqIcmsInterna = isset($CaracTrib['aliqIcmsInterna']) && $CaracTrib['aliqIcmsInterna'] !== "null"    ? "'" . $CaracTrib['aliqIcmsInterna'] . "'" : "null";
                        $aliqIcmsInterestadual = isset($CaracTrib['aliqIcmsInterestadual']) && $CaracTrib['aliqIcmsInterestadual'] !== "null"    ? "'" . $CaracTrib['aliqIcmsInterestadual'] . "'" : "null";
                        $reducaoBcIcms = isset($CaracTrib['reducaoBcIcms']) && $CaracTrib['reducaoBcIcms'] !== "null"    ? "'" . $CaracTrib['reducaoBcIcms'] . "'" : "null";
                        $reducaoBcIcmsSt = isset($CaracTrib['reducaoBcIcmsSt']) && $CaracTrib['reducaoBcIcmsSt'] !== "null"    ? "'" . $CaracTrib['reducaoBcIcmsSt'] . "'" : "null";
                        $redBcICMsInterestadual = isset($CaracTrib['redBcICMsInterestadual']) && $CaracTrib['redBcICMsInterestadual'] !== "null"    ? "'" . $CaracTrib['redBcICMsInterestadual'] . "'" : "null";
                        $aliqIcmsSt = isset($CaracTrib['aliqIcmsSt']) && $CaracTrib['aliqIcmsSt'] !== "null"    ? "'" . $CaracTrib['aliqIcmsSt'] . "'" : "null";
                        $iVA = isset($CaracTrib['iVA']) && $CaracTrib['iVA'] !== "null"    ? "'" . $CaracTrib['iVA'] . "'" : "null";
                        $iVAAjust = isset($CaracTrib['iVAAjust']) && $CaracTrib['iVAAjust'] !== "null"    ? "'" . $CaracTrib['iVAAjust'] . "'" : "null";
                        $fCP = isset($CaracTrib['fCP']) && $CaracTrib['fCP'] !== "null"    ? "'" . $CaracTrib['fCP'] . "'" : "null";
                        $codBenef = isset($CaracTrib['codBenef']) && $CaracTrib['codBenef'] !== "null"    ? "'" . $CaracTrib['codBenef'] . "'" : "null";
                        $pDifer = isset($CaracTrib['pDifer']) && $CaracTrib['pDifer'] !== "null"    ? "'" . $CaracTrib['pDifer'] . "'" : "null";
                        $pIsencao = isset($CaracTrib['pIsencao']) && $CaracTrib['pIsencao'] !== "null"    ? "'" . $CaracTrib['pIsencao'] . "'" : "null";
                        $antecipado = isset($CaracTrib['antecipado']) && $CaracTrib['antecipado'] !== "null"    ? "'" . $CaracTrib['antecipado'] . "'" : "'N'";
                        $desonerado = isset($CaracTrib['desonerado']) && $CaracTrib['desonerado'] !== "null"    ? "'" . $CaracTrib['desonerado'] . "'" : "'N'";
                        $pICMSDeson = isset($CaracTrib['pICMSDeson']) && $CaracTrib['pICMSDeson'] !== "null"    ? "'" . $CaracTrib['pICMSDeson'] . "'" : "null";
                        $isento = isset($CaracTrib['isento']) && $CaracTrib['isento'] !== "null"    ? "'" . $CaracTrib['isento'] . "'" : "'N'";
                        $tpCalcDifal = isset($CaracTrib['tpCalcDifal']) && $CaracTrib['tpCalcDifal'] !== "null"    ? "'" . $CaracTrib['tpCalcDifal'] . "'" : "null";
                        $ampLegal = str_replace("'", "", $CaracTrib['ampLegal']);
                        $ampLegal_formatada = isset($ampLegal) && $ampLegal !== "null"    ? "'" .  $ampLegal . "'" : "null";
                        //$Protocolo = isset($CaracTrib['Protocolo']) && $CaracTrib['Protocolo'] !== "null"    ? "'" . $CaracTrib['Protocolo'] . "'" : "null";
                        //$Convenio = isset($CaracTrib['Convenio']) && $CaracTrib['Convenio'] !== "null"    ? "'" . $CaracTrib['Convenio'] . "'" : "null";
                        $regraGeral = isset($CaracTrib['regraGeral']) && $CaracTrib['regraGeral'] !== "null"    ? "'" . $CaracTrib['regraGeral'] . "'" : "null";

                        $sql = " INSERT INTO regra_fiscal (codigoGrupo, uF, cFOP, codigoCaracTrib, finalidade, codRegra, codExcecao, dtVigIni,
                        dtVigFin, cFOPCaracTrib, cST, cSOSN, aliqIcmsInterna, aliqIcmsInterestadual, reducaoBcIcms, reducaoBcIcmsSt, redBcICMsInterestadual,
                        aliqIcmsSt, iVA, iVAAjust, fCP, codBenef, pDifer, pIsencao, antecipado, desonerado, pICMSDeson, isento, tpCalcDifal, ampLegal,
                        Protocolo, Convenio, regraGeral) 
                        VALUES ($codigoGrupo, $uF, $cFOP, $codigoCaracTrib, $finalidade, $codRegra, $codExcecao, $dtVigIni,
                        $dtVigFin , $cFOPCaracTrib, $cST, $cSOSN, $aliqIcmsInterna, $aliqIcmsInterestadual, $reducaoBcIcms, $reducaoBcIcmsSt, $redBcICMsInterestadual,
                        $aliqIcmsSt, $iVA, $iVAAjust, $fCP, $codBenef, $pDifer, $pIsencao, $antecipado, $desonerado, $pICMSDeson, $isento, $tpCalcDifal, $ampLegal_formatada,
                        null, null, $regraGeral) ";

                        //TRY-CATCH
                        try {

                            $inserir = mysqli_query($conexao, $sql);
                            if (!$inserir)
                                throw new Exception(mysqli_error($conexao));

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
                }
            }
        }
    } else {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "Faltaram parametros"
        );
    }
}
