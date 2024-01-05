<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_inserir";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "fisnota_" . date("dmY") . ".log", "a");
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

if (isset($jsonEntrada['xml'])) {

    $xml = simplexml_load_string($jsonEntrada['xml']);
    $infNFe = $xml->infNFe;


//********************************************PESSOAS

    foreach ($infNFe->children() as $id => $dados) {
        $campos = $dados->getName();
        if ($campos == "emit" || $campos == "dest") {
            if (isset($dados->CNPJ)) {
                $cpfCnpj = isset($dados->CNPJ) && $dados->CNPJ !== "" ? (string) $dados->CNPJ : "null";
                $tipoPessoa = "J";
            } else {
                $cpfCnpj = isset($dados->CPF) && $dados->CPF !== "" ? (string) $dados->CPF : "null";
                $tipoPessoa = "F";
            }
            $buscaPessoa = "SELECT * FROM pessoas WHERE cpfCnpj = $cpfCnpj";
            $buscar = mysqli_query($conexao, $buscaPessoa);
            $dadosPessoa = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
            if (mysqli_num_rows($buscar) == 1) {
                if ($campos == "emit") {
                    $idPessoaEmitente = $dadosPessoa["idPessoa"];
                } elseif ($campos == "dest") {
                    $idPessoaDestinatario = $dadosPessoa["idPessoa"];
                }
            } else {

                $nomePessoa = isset($dados->xNome) && $dados->xNome !== "" ? (string) $dados->xNome : "null";
                $IE = isset($dados->IE) && $dados->IE !== "" ? (string) $dados->IE : "null";
                $CRT = isset($dados->CRT) && $dados->CRT !== "" ? (string) $dados->CRT : "null";
                $dadosEnder = ($campos == "emit") ? $dados->enderEmit : $dados->enderDest;
                $municipio = isset($dadosEnder->xMun) && $dadosEnder->xMun !== "" ? (string) $dadosEnder->xMun : "null";
                $codigoCidade = isset($dadosEnder->cMun) && $dadosEnder->cMun !== "" ? (string) $dadosEnder->cMun : "null";
                $codigoEstado = isset($dadosEnder->UF) && $dadosEnder->UF !== "" ? (string) $dadosEnder->UF : "null";
                $pais = isset($dadosEnder->xPais) && $dadosEnder->xPais !== "" ? (string) $dadosEnder->xPais : "null";
                $bairro = isset($dadosEnder->xBairro) && $dadosEnder->xBairro !== "" ? (string) $dadosEnder->xBairro : "null";
                $endereco = isset($dadosEnder->xLgr) && $dadosEnder->xLgr !== "" ? (string) $dadosEnder->xLgr : "null";
                $endNumero = isset($dadosEnder->nro) && $dadosEnder->nro !== "" ? (string) $dadosEnder->nro : "null";
                $CEP = isset($dadosEnder->CEP) && $dadosEnder->CEP !== "" ? (string) $dadosEnder->CEP : "null";
                $telefone = isset($dadosEnder->fone) && $dadosEnder->fone !== "" ? (string) $dadosEnder->fone : "null";

                $pessoasEntrada = array(
                    'idEmpresa' => $idEmpresa,
                    'cpfCnpj' => $cpfCnpj,
                    'tipoPessoa' => $tipoPessoa,
                    'nomePessoa' => $nomePessoa,
                    'IE' => $IE,
                    'municipio' => $municipio,
                    'codigoCidade' => $codigoCidade,
                    'codigoEstado' => $codigoEstado,
                    'pais' => $pais,
                    'bairro' => $bairro,
                    'endereco' => $endereco,
                    'endNumero' => $endNumero,
                    'CEP' => $CEP,
                    'telefone' => $telefone,
                    'CRT' => $CRT
                );
                
                $pessoasRetorno = chamaAPI(null, '/cadastros/pessoas', json_encode($pessoasEntrada), 'PUT');
                if ($campos == "emit") {
                    $idPessoaEmitente = $pessoasRetorno["idPessoa"];
                } elseif ($campos == "dest") {
                    $idPessoaDestinatario = $pessoasRetorno["idPessoa"];
                }

            }
        }
    }

//********************************************NOTA FISCAL

    $chaveNFe = isset($infNFe['Id']) && $infNFe['Id'] !== "" && $infNFe['Id'] !== "" ? "'" . str_replace("NFe", "", $infNFe['Id']) . "'" : "null";

    $buscaNFE = "SELECT chaveNFe FROM fisnota WHERE chaveNFe = $chaveNFe";
    $resultado = mysqli_query($conexao, $buscaNFE);

    if (mysqli_num_rows($resultado) > 0) {
        $jsonSaida = array(
            "status" => 400,
            "retorno" => "NFe ja cadastrada"
        );
    } else {

        $NF = isset($infNFe->ide->nNF) && $infNFe->ide->nNF !== "" ? "'" . (string) $infNFe->ide->nNF . "'" : "null";
        $serie = isset($infNFe->ide->serie) && $infNFe->ide->serie !== "" ? "'" . (string) $infNFe->ide->serie . "'" : "null";
        $dtEmissao = isset($xml->infNFe->ide->dhEmi) && $xml->infNFe->ide->dhEmi !== "" ? "'" . date('Y-m-d', strtotime($xml->infNFe->ide->dhEmi)) . "'" : "null";
        $naturezaOp = isset($infNFe->ide->natOp) && $infNFe->ide->natOp !== "" ? "'" . (string) $infNFe->ide->natOp . "'" : "null";
        $modelo = isset($infNFe->ide->mod) && $infNFe->ide->mod !== "" ? "'" . (string) $infNFe->ide->mod . "'" : "null";
        $baseCalculo = isset($infNFe->total->ICMSTot->vBC) && $infNFe->total->ICMSTot->vBC !== "" ? "'" . (string) $infNFe->total->ICMSTot->vBC . "'" : "null";
        $valorProdutos = isset($infNFe->total->ICMSTot->vProd) && $infNFe->total->ICMSTot->vProd !== "" ? "'" . (string) $infNFe->total->ICMSTot->vProd . "'" : "null";
        $pis = isset($infNFe->total->ICMSTot->vPIS) && $infNFe->total->ICMSTot->vPIS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vPIS . "'" : "null";
        $cofins = isset($infNFe->total->ICMSTot->vCOFINS) && $infNFe->total->ICMSTot->vCOFINS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vCOFINS . "'" : "null";
        $XMLentrada = isset($jsonEntrada['xml']) && $jsonEntrada['xml'] !== "" ? "'" . $jsonEntrada['xml'] . "'" : "null";
        $idPessoaEmitente = isset($idPessoaEmitente) && $idPessoaEmitente !== "" ? "'" . $idPessoaEmitente . "'" : "NULL";
        $idPessoaDestinatario = isset($idPessoaDestinatario) && $idPessoaDestinatario !== "" ? "'" . $idPessoaDestinatario . "'" : "NULL";

        $sqlNota = "INSERT INTO fisnota(chaveNFe,naturezaOp,modelo,XML,serie,NF,dtEmissao,idPessoaEmitente,idPessoaDestinatario,baseCalculo,valorProdutos,pis,cofins) 
                    VALUES ($chaveNFe,$naturezaOp,$modelo,$XMLentrada,$serie,$NF,$dtEmissao,$idPessoaEmitente,$idPessoaDestinatario,$baseCalculo,$valorProdutos,$pis,$cofins)";

        //LOG
        if (isset($LOG_NIVEL)) {
            if ($LOG_NIVEL >= 3) {
                fwrite($arquivo, $identificacao . "-SQL_Nota->" . $sqlNota . "\n");
            }
        }
        //LOG

        $atualizarNota = mysqli_query($conexao, $sqlNota);

        $idNotaInserido = mysqli_insert_id($conexao);

        if ($atualizarNota) {
            $jsonSaidaNFE = array(
                "status" => 200,
                "retorno" => "ok"
            );
        } else {
            $jsonSaidaNFE = array(
                "status" => 500,
                "retorno" => "erro no mysql"
            );
        } 


//********************************************FISNOTAPRODUTOS

        include 'fisnotaproduto_inserir.php';


        if ($atualizarNota) {
            $jsonSaida = array(
                "status" => 200,
                "retorno" => "ok"
            );
        } else {
            $jsonSaida = array(
                "status" => 500,
                "retorno" => "erro no mysql"
            );
        }
    } 
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parâmetros"
    );
} 

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG