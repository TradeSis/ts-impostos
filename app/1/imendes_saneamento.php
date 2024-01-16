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

//APIFISCA
$sql_apifiscal = "SELECT apifiscal.login,apifiscal.senha,apifiscal.tpAmb,apifiscal.cfopEntrada,apifiscal.finalidade FROM apifiscal WHERE idEmpresa = $idEmpresa ";
$buscar_apifiscal = mysqli_query($conexao, $sql_apifiscal);
$row_apifiscal = mysqli_fetch_array($buscar_apifiscal, MYSQLI_ASSOC);
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-APIFISCAL->" . json_encode($row_apifiscal) . "\n");
  }
}

$login = $row_apifiscal['login'];
if (!$row_apifiscal['login']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.login não informado"
  );
  return;  
}
$senha = $row_apifiscal['senha'];
if (!$row_apifiscal['senha']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.senha não informado"
  );
  return;  
}
$amb = $row_apifiscal['tpAmb'];
if (!$row_apifiscal['tpAmb']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.tpAmb não informado"
  );
  return;  
}
$cfopEntrada = $row_apifiscal['cfopEntrada'];
if (!$row_apifiscal['cfopEntrada']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.cfopEntrada não informado"
  );
  return;  
}
$finalidade = isset($row_apifiscal['finalidade']) && $row_apifiscal['finalidade'] !== "null" ? (int)$row_apifiscal['finalidade'] : "null";
if ($row_apifiscal['finalidade'] == null) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "apifiscal.finalidade não informado"
  );
  return;  
}

//EMPRESA
$sql_empresa = "SELECT empresa.idPessoa FROM empresa WHERE idEmpresa = $idEmpresa ";
$buscar_empresa = mysqli_query(conectaMysql(null), $sql_empresa);
$row_empresa = mysqli_fetch_array($buscar_empresa, MYSQLI_ASSOC);

$idPessoaEmpresa = $row_empresa['idPessoa'];

//PESSOAS
$sql_empresaPessoa = "SELECT pessoas.codigoEstado, pessoas.cpfCnpj, pessoas.cnae, pessoas.regimeEspecial, pessoas.regimeTrib, pessoas.crt, pessoas.origem FROM pessoas WHERE idPessoa = $idPessoaEmpresa "; //empresaPessoa
$buscar_empresaPessoa = mysqli_query($conexao, $sql_empresaPessoa);
$row_empresaPessoa = mysqli_fetch_array($buscar_empresaPessoa, MYSQLI_ASSOC);
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-empresaPESSOA->" . json_encode($row_empresaPessoa) . "\n");
  }
}

$cpfCnpj = $row_empresaPessoa['cpfCnpj'];
$cnae = $row_empresaPessoa['cnae'];
if (!$row_empresaPessoa['cnae']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "empresaPessoa.cnae não informado"
  );
  return;  
}
$regimeEspecial = $row_empresaPessoa['regimeEspecial'];
if (!$row_empresaPessoa['regimeEspecial']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "empresaPessoa.regimeEspecial não informado"
  );
  return;  
}
$regimeTrib = $row_empresaPessoa['regimeTrib'];
if (!$row_empresaPessoa['regimeTrib']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "empresaPessoa.regimeTrib não informado"
  );
  return;  
}
$codigoEstado = $row_empresaPessoa['codigoEstado'];
$crt = (int)$row_empresaPessoa['crt'];
if (!$row_empresaPessoa['crt']) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "empresaPessoa.crt não informado"
  );
  return;  
}
$origem = $row_empresaPessoa['origem'];
if (!isset($origem)) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "empresaPessoa.origem  não informado"
  );
  return;  
}
if ($regimeTrib == 'SN') {
  $simplesN = 'S';
} else {
  $simplesN = 'N';
}


if (isset($jsonEntrada["idProduto"])) {
  //echo 'idProduto ' . $jsonEntrada["idProduto"];
  //PRODUTOS
  $sql_produtos = "SELECT produtos.nomeProduto, produtos.codigoNcm, produtos.codigoNcm, produtos.eanProduto, produtos.idPessoaFornecedor FROM produtos WHERE idProduto = " . $jsonEntrada["idProduto"] . " ";
  $buscar_produtos = mysqli_query($conexao, $sql_produtos);
  $row_produtos = mysqli_fetch_array($buscar_produtos, MYSQLI_ASSOC);
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
      fwrite($arquivo, $identificacao . "-produtos->" . json_encode($row_produtos) . "\n");
    }
  }
  
  $nomeProduto = $row_produtos['nomeProduto'];
  $codigoNcm = $row_produtos['codigoNcm'];
  $eanProduto = $row_produtos['eanProduto'];
  $idPessoaFornecedor = $row_produtos['idPessoaFornecedor'];
}

//PESSOA FORNECEDOR
$sql_pessoaFornecedor = "SELECT pessoas.codigoEstado, pessoas.caracTrib FROM pessoas WHERE idPessoa = $idPessoaFornecedor ";
$buscar_pessoaFornecedor = mysqli_query($conexao, $sql_pessoaFornecedor);
$row_pessoaFornecedor = mysqli_fetch_array($buscar_pessoaFornecedor, MYSQLI_ASSOC);

if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-pessoaFORNECEDOR->" . json_encode($row_pessoaFornecedor) . "\n");
  }
}

$codigoEstadoFornecedor = $row_pessoaFornecedor['codigoEstado'];

$caracTrib = isset($row_pessoaFornecedor['caracTrib']) && $row_pessoaFornecedor['caracTrib'] !== "null" ? (int)$row_pessoaFornecedor['caracTrib'] : "null";
if ($row_pessoaFornecedor['caracTrib'] == null) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "empresaPessoa.caracTrib não informado"
  );
  return;  
}

$emit = array(
  'amb' => $amb,
  'cnpj' => $cpfCnpj,
  'crt' => $crt, //- Para o CRT 3, informe o campo 'regimeTrib' igual a 'LR' ou 'LP'."
  'regimeTrib' => $regimeTrib,
  'uf' => $codigoEstado,
  'cnae' => $cnae,
  'regimeEspecial' => $regimeEspecial,
  'substICMS' => "N", // - Verificar com Daniel
  'interdependente' => "N", // - Verificar com Daniel
);

$ufPerfil = array(
  $codigoEstadoFornecedor
);

$caracTrib = array(
  $caracTrib
);
$perfil = array(
  'uf' => $ufPerfil,
  'cfop' => $cfopEntrada, //"1101"
  'caracTrib' => $caracTrib,
  'finalidade' => $finalidade,
  'simplesN' => $simplesN,
  'origem' => $origem,
  'substICMS' => "N",
  'prodZFM' => "N"
);

$produto = array(
  'codigo' => $eanProduto,
  'codInterno' => "N",
  'descricao' => $nomeProduto,
  'ncm' => $codigoNcm
);

$produtos = array(
  $produto
);

$imendesEntrada = array(
  'emit' => $emit,
  'perfil' => $perfil,
  'produtos' => $produtos
);

$apiHeaders = array(
  "Content-Type: application/json",
  "login: $login",
  "senha: $senha"
);

if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-imendesEntrada->" . json_encode($imendesEntrada) . "\n");
  }
}


// CHAMADA IMENDES
$JSON = chamaAPI(
  "http://consultatributos.com.br:8080",
  "/api/v3/public/SaneamentoGrades",
  json_encode($imendesEntrada),
  "POST",
  $apiHeaders
);
//prodNaoRet

$produtoNaoRetornado = $JSON['Cabecalho']['prodNaoRet'];
//echo json_encode($produtoNaoRetornado);
if($produtoNaoRetornado == 1){
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "Nenhum produto encontrado.",
    "mensagem" => true
  );
}

if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-imendesSaida->" . json_encode($JSON) . "\n");
  }
}

//echo "IMENDES\n".json_encode($JSON)."\n";

function atualizaProduto($conexao, $eanProduto, $codigoNcm, $codigoCest, $codigoGrupo)
{
  //Atualiza Produto
  $sql_consulta = "SELECT * FROM produtos WHERE eanProduto = $eanProduto ";
  $buscar_consulta = mysqli_query($conexao, $sql_consulta);
  $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);


  if ($row_consulta !== null) {
    $idProduto = $row_consulta["idProduto"];
    $update_produtos = "UPDATE produtos SET codigoNcm=$codigoNcm, codigoCest=$codigoCest, codigoGrupo=$codigoGrupo, dataAtualizacaoTributaria=CURRENT_TIMESTAMP()
    WHERE idProduto = $idProduto";

    $atualizar = mysqli_query($conexao, $update_produtos);
  } else {
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

function adicionaRegraFiscal($conexao, $regras, $codigoGrupo)
{
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
          $sql_consulta = "SELECT * FROM regrafiscal WHERE codigoGrupo = $codigoGrupo AND codigoEstado = $codigoEstado AND cFOP = $cFOP AND codigoCaracTrib = $codigoCaracTrib";
          $buscar_consulta = mysqli_query($conexao, $sql_consulta);
          $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);

          if ($row_consulta == null) {
            $sql = " INSERT INTO regrafiscal (codigoGrupo, codigoEstado, cFOP, codigoCaracTrib, finalidade, codRegra, codExcecao, dtVigIni,
            dtVigFin, cFOPCaracTrib, cST, cSOSN, aliqIcmsInterna, aliqIcmsInterestadual, reducaoBcIcms, reducaoBcIcmsSt, redBcICMsInterestadual,
            aliqIcmsSt, iVA, iVAAjust, fCP, codBenef, pDifer, pIsencao, antecipado, desonerado, pICMSDeson, isento, tpCalcDifal, ampLegal,
            Protocolo, Convenio, regraGeral) 
            VALUES ($codigoGrupo, $codigoEstado, $cFOP, $codigoCaracTrib, $finalidade, $codRegra, $codExcecao, $dtVigIni,
            $dtVigFin , $cFOPCaracTrib, $cST, $cSOSN, $aliqIcmsInterna, $aliqIcmsInterestadual, $reducaoBcIcms, $reducaoBcIcmsSt, $redBcICMsInterestadual,
            $aliqIcmsSt, $iVA, $iVAAjust, $fCP, $codBenef, $pDifer, $pIsencao, $antecipado, $desonerado, $pICMSDeson, $isento, $tpCalcDifal, $ampLegal_formatada,
            null, null, $regraGeral) ";

            $adicionaregraFiscal = mysqli_query($conexao, $sql);
          } else {
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
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n");
  }
}
//LOG
