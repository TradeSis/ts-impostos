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
$conexaogeral = conectaMysql(null);

$operacao = array();

$progr = new chamaprogress();
$retorno = $progr->executarprogress("impostos/app/1/imendes_saneamento",json_encode($jsonEntrada));
fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
$operacao = json_decode($retorno,true);

$imendesEntrada= $operacao;
echo "IMENDES\n".json_encode($imendesEntrada)."\n";
return;
/* echo "IMENDES\n".json_encode($imendesEntrada)."\n";
return;
 */
/* $login = $imendesEntrada["headers"]["login"];
$senha = $imendesEntrada["headers"]["senha"];
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
); */
/* echo "IMENDES\n".json_encode($JSON)."\n";
return; */

$produtoNaoRetornado = $JSON['Cabecalho']['prodNaoRet'];
if ($produtoNaoRetornado == 1) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "Nenhum produto retornado.",
    "mensagem" => true
  );
}
//echo "IMENDES\n".json_encode($JSON)."\n";
//return;
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-imendesSaida->" . json_encode($JSON) . "\n");
  }
}



function atualizaProduto($conexaogeral, $conexao, $eanProduto, $codigoNcm, $codigoCest, $idGrupo)
{
  //Atualiza Geral Produtos
  $sql_geralProdutos = "SELECT geralprodutos.idGeralProduto FROM geralprodutos WHERE eanProduto = $eanProduto ";
  $buscar_geralProdutos = mysqli_query($conexaogeral, $sql_geralProdutos);
  $row_geralProdutos = mysqli_fetch_array($buscar_geralProdutos, MYSQLI_ASSOC);

  if ($row_geralProdutos !== null) {
    $idGeralProduto = $row_geralProdutos["idGeralProduto"];

    $update_geralProdutos = "UPDATE geralprodutos SET idGrupo=$idGrupo, dataAtualizacaoTributaria=CURRENT_TIMESTAMP()
    WHERE idGeralProduto = $idGeralProduto";

    $atualizar = mysqli_query($conexaogeral, $update_geralProdutos);

//Atualiza Produtos
    $produtos = array();
    $sql_produtos = "SELECT produtos.idProduto FROM produtos WHERE idGeralProduto = $idGeralProduto";

    $rows = 0;
    $buscar = mysqli_query($conexao, $sql_produtos);
    while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
      array_push($produtos, $row);
      $rows = $rows + 1;
    }

    foreach ($produtos as $produto) {
      $idProduto = $produto["idProduto"];
      $update_produtos = "UPDATE produtos SET codigoNcm=$codigoNcm, codigoCest=$codigoCest WHERE idProduto = $idProduto";

      $atualizar2 = mysqli_query($conexao, $update_produtos);
      if (isset($atualizar2)) {
        $atualizar2 = "ok";
      }
    }
  } else {
    $atualizar = " Produto nÃ£o encontrado ";
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

function adicionaRegraFiscal($conexaogeral, $regras, $idGrupo)
{

  $returnRegraFiscal = "";

  foreach ($regras as $regra) {

    foreach ($regra as $ufs) {

      foreach ($ufs as $dadosCFOP) {

        $codigoEstado = isset($dadosCFOP['uF']) && $dadosCFOP['uF'] !== "null"    ?  $dadosCFOP['uF'] : "null";
        $cFOP = isset($dadosCFOP['CFOP']['cFOP']) && $dadosCFOP['CFOP']['cFOP'] !== "null"    ?  $dadosCFOP['CFOP']['cFOP'] : "null";

        foreach ($dadosCFOP['CFOP']['CaracTrib'] as $CaracTrib) {

          $codigoCaracTrib = isset($CaracTrib['codigo']) && $CaracTrib['codigo'] !== "null"  ?  $CaracTrib['codigo'] : "null";
          $finalidade = isset($CaracTrib['finalidade']) && $CaracTrib['finalidade'] !== "null"  ?  $CaracTrib['finalidade'] : "null";
          $codRegra = isset($CaracTrib['codRegra']) && $CaracTrib['codRegra'] !== "null"    ?  $CaracTrib['codRegra'] : "null";
          $codExcecao = isset($CaracTrib['codExcecao']) && $CaracTrib['codExcecao'] !== "null"    ?  $CaracTrib['codExcecao'] : "null";
          $dtVigIni = isset($CaracTrib['dtVigIni']) && $CaracTrib['dtVigIni'] !== ""    ? date('Y-m-d', strtotime($CaracTrib['dtVigIni'])) : null;
          $dtVigFin = isset($CaracTrib['dtVigFin']) && $CaracTrib['dtVigFin'] !== ""    ? date('Y-m-d', strtotime($CaracTrib['dtVigFin'])) : null;
         
          $apiEntrada = array(
            'idRegra' => null,
            'codRegra' => $codRegra,
            'codExcecao' => $codExcecao
          );
        
          $buscaregras = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'GET');
         
          
          if(isset($buscaregras[0])){
            $idRegra = $buscaregras[0]["idRegra"];
          }else{
           
            $apiEntrada = array(
              'codRegra' => $codRegra,
              'codExcecao' => $codExcecao,
              'dtVigIni' =>  $dtVigIni,
              'dtVigFin' =>  $dtVigFin,
              'cFOPCaracTrib' =>  $CaracTrib['cFOP'],
              'cST' => $CaracTrib['cST'],
              'cSOSN' => $CaracTrib['cSOSN'],
              'aliqIcmsInterna' => $CaracTrib['aliqIcmsInterna'],
              'aliqIcmsInterestadual' => $CaracTrib['aliqIcmsInterestadual'],
              'reducaoBcIcms' => $CaracTrib['reducaoBcIcms'],
              'reducaoBcIcmsSt' => $CaracTrib['reducaoBcIcmsSt'],
              'redBcICMsInterestadual' => $CaracTrib['redBcICMsInterestadual'],
              'aliqIcmsSt' => $CaracTrib['aliqIcmsSt'],
              'iVA' => $CaracTrib['iVA'],
              'iVAAjust' => $CaracTrib['iVAAjust'],
              'fCP' => $CaracTrib['fCP'],
              'codBenef' => $CaracTrib['codBenef'],
              'pDifer' => $CaracTrib['pDifer'],
              'pIsencao' => $CaracTrib['pIsencao'],
              'antecipado' => $CaracTrib['antecipado'],
              'desonerado' => $CaracTrib['desonerado'],
              'pICMSDeson' => $CaracTrib['pICMSDeson'],
              'isento' => $CaracTrib['isento'],
              'tpCalcDifal' => $CaracTrib['tpCalcDifal'],
              'ampLegal' => $CaracTrib['ampLegal'],
              'Protocolo' => "",//$CaracTrib['Protocolo'],
              'Convenio' => "",//$CaracTrib['Convenio'],
              'regraGeral' => $CaracTrib['regraGeral'],
            );

            $inserirRegra = chamaAPI(null, '/impostos/regrafiscal', json_encode($apiEntrada), 'PUT');
            $idRegra = $inserirRegra['idRegra'];
          }
          
        //OPERAÇÂO
        
          $apiEntrada = array(
            "idGrupo"=> $idGrupo,
            "codigoEstado"=> $codigoEstado,
            "cFOP"=> $cFOP,
            "codigoCaracTrib"=> $codigoCaracTrib,
            "finalidade"=> $finalidade 
          );
          
          $buscaoperacao = chamaAPI(null, '/impostos/operacaofiscal', json_encode($apiEntrada), 'GET');
          if ($buscaoperacao == null) {
            $apiEntrada = array(
              "idGrupo"=> $idGrupo,
              "codigoEstado"=> $codigoEstado,
              "cFOP"=> $cFOP,
              "codigoCaracTrib"=> $codigoCaracTrib,
              "finalidade"=> $finalidade,  
              "idRegra" => $idRegra
            );

            $inserirOperacaoFiscal = chamaAPI(null, '/impostos/operacaofiscal', json_encode($apiEntrada), 'PUT');
          
          }
          
        }
      }
    }
  }

  return $returnRegraFiscal;
}

$retornoImendes = $JSON;

$historico = adicionaHistorico($conexao, $retornoImendes);


foreach ($retornoImendes['Grupos'] as $grupo) {
  if (is_array($grupo) && isset($grupo['codigo'])) {
    $codigoGrupo = $grupo['codigo'];
    $eanProdutos = $grupo['prodEan'];
     
    $apiEntrada = array(
      "codigoGrupo" => $codigoGrupo,
      "buscaGrupoProduto" => null
    );
    
    $buscagrupos = chamaAPI(null, '/impostos/grupoproduto', json_encode($apiEntrada), 'GET');
    
    if(isset($buscagrupos["idGrupo"])){
      if ($buscagrupos["codigoGrupo"] != "null") {
        $idGrupo = $buscagrupos["idGrupo"];
        $codigoGrupo = isset($buscagrupos["codigoGrupo"]) && $buscagrupos["codigoGrupo"] !== "null"    ? "'" . $buscagrupos["codigoGrupo"] . "'" : "null";
        $codigoNcm = isset($buscagrupos["codigoNcm"]) && $buscagrupos["codigoNcm"] !== "null"    ? "'" . $buscagrupos["codigoNcm"] . "'" : "null";
        $codigoCest = isset($buscagrupos["codigoCest"]) && $buscagrupos["codigoCest"] !== "null"    ? "'" . $buscagrupos["codigoCest"] . "'" : "null";

        foreach ($eanProdutos as $eanProduto) {
          $atualizaProduto = atualizaProduto($conexaogeral, $conexao, $eanProduto, $codigoNcm, $codigoCest, $idGrupo);
        }
        $regrafiscal = adicionaRegraFiscal($conexaogeral, $grupo['Regras'], $idGrupo);
        $jsonSaida = array(
          "status" => 200,
          "retorno" => "codigo do Grupo existente",
          "codigoGrupo" => $codigoGrupo
        );
      }
    } else {
      
      $apiEntrada = array(
        'codigoGrupo' => $codigoGrupo,
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

      if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 2) {
          fwrite($arquivo, $identificacao . "-GRUPOINSERIR->" . json_encode($apiEntrada) . "\n");
        }
      } 

      $inserirGrupo = chamaAPI(null, '/impostos/grupoproduto', json_encode($apiEntrada), 'PUT');
      $idGrupo = $inserirGrupo['idGrupo'];

      $codigoCest = "'" . $grupo['cEST'] .  "'";
      $codigoNcm = "'" . $grupo['nCM'] . "'";

      $regrafiscal = adicionaRegraFiscal($conexaogeral, $grupo['Regras'], $idGrupo);

      foreach ($eanProdutos as $eanProduto) {
        $atualizaProduto = atualizaProduto($conexaogeral, $conexao, $eanProduto, $codigoNcm, $codigoCest, $idGrupo);
      }

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
