<?php

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "imendes";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "imendes_Saneamento_" . date("dmY") . ".log", "a");
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


$apiEntrada = ' {
  "idEmpresa": 1,
"emit": {
  "amb": 1,
  "cnpj": "03521642000124",
  "crt": 3,
  "regimeTrib": "LR",
  "uf": "RS",
  "cnae": "9877895",
  "regimeEspecial": "",
  "substICMS ": "N",
  "interdependente": "N"
},
"perfil": {
  "uf": [
    "SC","RS"
  ],
  "cfop": "1101",
  "caracTrib": [
    3
  ],
  "finalidade": 0,
  "simplesN": "N",
  "origem": "0",
  "substICMS": "N",
  "prodZFM": "N"
},
"produtos": [
 {
    "codigo": "7891960708166",
    "codInterno": "N",
    "descricao": "LUVA 25 AG AMANCO",
    "ncm": "1111111"
  }
]
} ';

$apiHeaders = array(
  "Content-Type: application/json",
  "login: 03521642000124",
  "senha: fp7pvBfMt7D2"
);

// CHAMADA IMENDES
$JSON = chamaAPI ("http://consultatributos.com.br:8080",
                  "/api/v3/public/SaneamentoGrades",
                  $apiEntrada,
                  "POST",
                  $apiHeaders);

function atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo)
{
  //Atualiza Produto
  $sql_consulta = "SELECT * FROM produtos WHERE eanProduto = $eanProduto ";
  $buscar_consulta = mysqli_query($conexao, $sql_consulta);
  $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);


  if($row_consulta !== null){
    $idProduto = $row_consulta["idProduto"];
    $update_produtos = "UPDATE produtos SET codigoNcm=$codigoNcm, codigoCest=$codigoCest, codigoGrupo=$codigoGrupo, dataAtualizacaoTributaria=CURRENT_TIMESTAMP()
    WHERE idProduto = $idProduto";

    $atualizar = mysqli_query($conexao, $update_produtos);
  }else{
    $atualizar = " Produto não encontrado ";
  }

  return $atualizar;
}

function adicionaHistorico($conexao, $retornoImendes)
{
  $sugestao = isset($retornoImendes['Cabecalho']['sugestao']) && $retornoImendes['Cabecalho']['sugestao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['sugestao'] . "'" : "null";
  $amb = isset($retornoImendes['Cabecalho']['amb']) && $retornoImendes['Cabecalho']['amb'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['amb'] . "'" : "null";
  $cnpj = isset($retornoImendes['Cabecalho']['cnpj']) && $retornoImendes['Cabecalho']['cnpj'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['cnpj'] . "'" : "null";
  $dthr = isset($retornoImendes['Cabecalho']['dthr']) && $retornoImendes['Cabecalho']['dthr'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['dthr'] . "'" : "null";
  $transacao = isset($retornoImendes['Cabecalho']['transacao']) && $retornoImendes['Cabecalho']['transacao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['transacao'] . "'" : "null";
  $mensagem = isset($retornoImendes['Cabecalho']['mensagem']) && $retornoImendes['Cabecalho']['mensagem'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['mensagem'] . "'" : "null";
  $prodEnv = isset($retornoImendes['Cabecalho']['prodEnv']) && $retornoImendes['Cabecalho']['prodEnv'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['prodEnv'] . "'" : "null";
  $prodRet = isset($retornoImendes['Cabecalho']['prodRet']) && $retornoImendes['Cabecalho']['prodRet'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['prodRet'] . "'" : "null";
  $prodNaoRet = isset($retornoImendes['Cabecalho']['prodNaoRet']) && $retornoImendes['Cabecalho']['prodNaoRet'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['prodNaoRet'] . "'" : "null";
  $comportamentosParceiro = isset($retornoImendes['Cabecalho']['comportamentosParceiro']) && $retornoImendes['Cabecalho']['comportamentosParceiro'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['comportamentosParceiro'] . "'" : "null";
  $comportamentosCliente = isset($retornoImendes['Cabecalho']['comportamentosCliente']) && $retornoImendes['Cabecalho']['comportamentosCliente'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['comportamentosCliente'] . "'" : "null";
  $versao = isset($retornoImendes['Cabecalho']['versao']) && $retornoImendes['Cabecalho']['versao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['versao'] . "'" : "null";
  $duracao = isset($retornoImendes['Cabecalho']['duracao']) && $retornoImendes['Cabecalho']['duracao'] !== "null"    ? "'" . $retornoImendes['Cabecalho']['duracao'] . "'" : "null";

  $inseirHistorico = " INSERT INTO apifiscalhistorico (dtHistorico, sugestao, amb, cnpj, dthr, transacao, mensagem, prodEnv, prodRet, prodNaoRet, comportamentosParceiro, 
  comportamentosCliente, versao, duracao) 
  VALUES (CURRENT_TIMESTAMP(), $sugestao, $amb , $cnpj, $dthr, $transacao, $mensagem, $prodEnv, $prodRet, $prodNaoRet, $comportamentosParceiro, 
  $comportamentosCliente, $versao, $duracao) ";

  $adicionaHistorico = mysqli_query($conexao, $inseirHistorico);

  return $adicionaHistorico;
}

function adicionaRegraFiscal($conexao, $regras, $codigoGrupo){
  foreach ($regras as $regra) {

    foreach ($regra as $ufs) {

      foreach ($ufs as $dadosCFOP) {

        $codigoEstado = isset($dadosCFOP['uF']) && $dadosCFOP['uF'] !== "null"    ? "'" . $dadosCFOP['uF'] . "'" : "null";
        $cFOP = isset($dadosCFOP['CFOP']['cFOP']) && $dadosCFOP['CFOP']['cFOP'] !== "null"    ? "'" . $dadosCFOP['CFOP']['cFOP'] . "'" : "null";

        foreach ($dadosCFOP['CFOP']['CaracTrib'] as $CaracTrib) {

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

          //Verifica se tem regra
          $sql_consulta = "SELECT * FROM regrafiscal WHERE codigoGrupo = $codigoGrupo AND codigoEstado = $codigoEstado AND cFOP = $cFOP AND codigoCaracTrib = $codigoCaracTrib" ;
          $buscar_consulta = mysqli_query($conexao, $sql_consulta);
          $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
  
          if($row_consulta == null){
            $sql = " INSERT INTO regrafiscal (codigoGrupo, codigoEstado, cFOP, codigoCaracTrib, finalidade, codRegra, codExcecao, dtVigIni,
            dtVigFin, cFOPCaracTrib, cST, cSOSN, aliqIcmsInterna, aliqIcmsInterestadual, reducaoBcIcms, reducaoBcIcmsSt, redBcICMsInterestadual,
            aliqIcmsSt, iVA, iVAAjust, fCP, codBenef, pDifer, pIsencao, antecipado, desonerado, pICMSDeson, isento, tpCalcDifal, ampLegal,
            Protocolo, Convenio, regraGeral) 
            VALUES ($codigoGrupo, $codigoEstado, $cFOP, $codigoCaracTrib, $finalidade, $codRegra, $codExcecao, $dtVigIni,
            $dtVigFin , $cFOPCaracTrib, $cST, $cSOSN, $aliqIcmsInterna, $aliqIcmsInterestadual, $reducaoBcIcms, $reducaoBcIcmsSt, $redBcICMsInterestadual,
            $aliqIcmsSt, $iVA, $iVAAjust, $fCP, $codBenef, $pDifer, $pIsencao, $antecipado, $desonerado, $pICMSDeson, $isento, $tpCalcDifal, $ampLegal_formatada,
            null, null, $regraGeral) ";

            $adicionaregraFiscal = mysqli_query($conexao, $sql);
          }else{
            $adicionaregraFiscal = " Regra existente ";
          }
        }
      }
    }
  }

  return $adicionaregraFiscal;
}

$retornoImendes = $JSON;

$historico = adicionaHistorico($conexao, $retornoImendes);


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
      $regrafiscal = adicionaRegraFiscal($conexao, $grupo['Regras'], $grupo['codigo']);
      $jsonSaida = array(
        "status" => 200,
        "retorno" => "codigo do Grupo existente",
        "codigoGrupo" => $codigoGrupo
      );
    } else {

      $regrafiscal = adicionaRegraFiscal($conexao, $grupo['Regras'], $grupo['codigo']);
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
