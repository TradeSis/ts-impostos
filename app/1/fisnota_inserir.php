<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_inserir";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "fisnota_inserir" . date("dmY") . ".log", "a");
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

// Pega XML puro
if (isset($jsonEntrada['xml'])) {

    $xml = simplexml_load_string($jsonEntrada['xml']);
    $infNFe = $xml->NFe->infNFe;
    if ($infNFe == null) {
        $infNFe = $xml->nfeProc->NFe->infNFe;
    }
    
}

if (isset($infNFe)) {

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

                $dadosEnder = ($campos == "emit") ? $dados->enderEmit : $dados->enderDest;

                $pessoasEntrada = array(
                    'idEmpresa' => $idEmpresa,
                    'cpfCnpj' => $cpfCnpj,
                    'tipoPessoa' => $tipoPessoa,
                    'nomePessoa' => (string) $dados->xNome,
                    'IE' => (string) $dados->IE,
                    'municipio' => (string) $dadosEnder->xMun,
                    'codigoCidade' => (string) $dadosEnder->cMun,
                    'codigoEstado' => (string) $dadosEnder->UF,
                    'pais' => (string) $dadosEnder->xPais,
                    'bairro' => (string) $dadosEnder->xBairro,
                    'endereco' => (string) $dadosEnder->xLgr,
                    'endNumero' => (string) $dadosEnder->nro,
                    'CEP' => (string) $dadosEnder->CEP,
                    'telefone' => (string) $dadosEnder->fone,
                    'CRT' => (string) $dados->CRT
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
        $dtEmissao = isset($infNFe->ide->dhEmi) && $infNFe->ide->dhEmi !== "" ? "'" . date('Y-m-d', strtotime($infNFe->ide->dhEmi)) . "'" : "null";
        $naturezaOp = isset($infNFe->ide->natOp) && $infNFe->ide->natOp !== "" ? "'" . (string) $infNFe->ide->natOp . "'" : "null";
        $modelo = isset($infNFe->ide->mod) && $infNFe->ide->mod !== "" ? "'" . (string) $infNFe->ide->mod . "'" : "null";
        $XMLentrada = isset($jsonEntrada['xml']) && $jsonEntrada['xml'] !== "" ? "'" . $jsonEntrada['xml'] . "'" : "null";
        $vBC = isset($infNFe->total->ICMSTot->vBC) && $infNFe->total->ICMSTot->vBC !== "" ? "'" . (string) $infNFe->total->ICMSTot->vBC . "'" : "null";
        $vProd = isset($infNFe->total->ICMSTot->vProd) && $infNFe->total->ICMSTot->vProd !== "" ? "'" . (string) $infNFe->total->ICMSTot->vProd . "'" : "null";
        $vPIS = isset($infNFe->total->ICMSTot->vPIS) && $infNFe->total->ICMSTot->vPIS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vPIS . "'" : "null";
        $vCOFINS = isset($infNFe->total->ICMSTot->vCOFINS) && $infNFe->total->ICMSTot->vCOFINS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vCOFINS . "'" : "null";
        
        $sqlNota = "INSERT INTO fisnota(chaveNFe,naturezaOp,modelo,XML,serie,NF,dtEmissao,idPessoaEmitente,idPessoaDestinatario,baseCalculo,valorProdutos,pis,cofins) 
                    VALUES ($chaveNFe,$naturezaOp,$modelo,'',$serie,$NF,$dtEmissao,$idPessoaEmitente,$idPessoaDestinatario,$vBC,$vProd,$vPIS,$vCOFINS)";

        //LOG
        if (isset($LOG_NIVEL)) {
            if ($LOG_NIVEL >= 3) {
                fwrite($arquivo, $identificacao . "-SQL_Nota->" . $sqlNota . "\n");
            }
        }
        //LOG

        $atualizarNota = mysqli_query($conexao, $sqlNota);
        
        if ($atualizarNota) {
            $jsonSaida = array(
                "status" => 200,
                "retorno" => "ok"
            );
        } else {
            $jsonSaida = array(
                "status" => 501,
                "retorno" => "erro no mysql"
            );
            return;
        }
        $idNotaInserido = mysqli_insert_id($conexao);

        $nomeTotal = "'" . $infNFe->total->ICMSTot->getName() . "'";
        $vICMS = isset($infNFe->total->ICMSTot->vICMS) && $infNFe->total->ICMSTot->vICMS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMS . "'" : "null";
        $vICMS = isset($infNFe->total->ICMSTot->vICMS) && $infNFe->total->ICMSTot->vICMS !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMS . "'" : "null";
        $vICMSDeson = isset($infNFe->total->ICMSTot->vICMSDeson) && $infNFe->total->ICMSTot->vICMSDeson !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMSDeson . "'" : "null";
        $vFCPUFDest = isset($infNFe->total->ICMSTot->vFCPUFDest) && $infNFe->total->ICMSTot->vFCPUFDest !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCPUFDest . "'" : "null";
        $vICMSUFRemet = isset($infNFe->total->ICMSTot->vICMSUFRemet) && $infNFe->total->ICMSTot->vICMSUFRemet !== "" ? "'" . (string) $infNFe->total->ICMSTot->vICMSUFRemet . "'" : "null";
        $vFCP = isset($infNFe->total->ICMSTot->vFCP) && $infNFe->total->ICMSTot->vFCP !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCP . "'" : "null";
        $vBCST = isset($infNFe->total->ICMSTot->vBCST) && $infNFe->total->ICMSTot->vBCST !== "" ? "'" . (string) $infNFe->total->ICMSTot->vBCST . "'" : "null";
        $vST = isset($infNFe->total->ICMSTot->vST) && $infNFe->total->ICMSTot->vST !== "" ? "'" . (string) $infNFe->total->ICMSTot->vST . "'" : "null";
        $vFCPST = isset($infNFe->total->ICMSTot->vFCPST) && $infNFe->total->ICMSTot->vFCPST !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCPST . "'" : "null";
        $vFCPSTRet = isset($infNFe->total->ICMSTot->vFCPSTRet) && $infNFe->total->ICMSTot->vFCPSTRet !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFCPSTRet . "'" : "null";
        $vFrete = isset($infNFe->total->ICMSTot->vFrete) && $infNFe->total->ICMSTot->vFrete !== "" ? "'" . (string) $infNFe->total->ICMSTot->vFrete . "'" : "null";
        $vSeg = isset($infNFe->total->ICMSTot->vSeg) && $infNFe->total->ICMSTot->vSeg !== "" ? "'" . (string) $infNFe->total->ICMSTot->vSeg . "'" : "null";
        $vDesc = isset($infNFe->total->ICMSTot->vDesc) && $infNFe->total->ICMSTot->vDesc !== "" ? "'" . (string) $infNFe->total->ICMSTot->vDesc . "'" : "null";
        $vII = isset($infNFe->total->ICMSTot->vII) && $infNFe->total->ICMSTot->vII !== "" ? "'" . (string) $infNFe->total->ICMSTot->vII . "'" : "null";
        $vIPI = isset($infNFe->total->ICMSTot->vIPI) && $infNFe->total->ICMSTot->vIPI !== "" ? "'" . (string) $infNFe->total->ICMSTot->vIPI . "'" : "null";
        $vIPIDevol = isset($infNFe->total->ICMSTot->vIPIDevol) && $infNFe->total->ICMSTot->vIPIDevol !== "" ? "'" . (string) $infNFe->total->ICMSTot->vIPIDevol . "'" : "null";
        $vOutro = isset($infNFe->total->ICMSTot->vOutro) && $infNFe->total->ICMSTot->vOutro !== "" ? "'" . (string) $infNFe->total->ICMSTot->vOutro . "'" : "null";
        $vNF = isset($infNFe->total->ICMSTot->vNF) && $infNFe->total->ICMSTot->vNF !== "" ? "'" . (string) $infNFe->total->ICMSTot->vNF . "'" : "null";
        $vTotTribTOTAL = isset($infNFe->total->ICMSTot->vTotTrib) && $infNFe->total->ICMSTot->vTotTrib !== "" ? "'" . (string) $infNFe->total->ICMSTot->vTotTrib . "'" : "null";

        $sqlNota = "INSERT INTO fisnotatotal(idNota,nomeTotal,vBC,vICMS,vICMSDeson,vFCPUFDest,vICMSUFRemet,vFCP,vBCST,vST,vFCPST,vFCPSTRet,vProd,vFrete,vSeg,vDesc,vII,vIPI,vIPIDevol,vPIS,vCOFINS,vOutro,vNF,vTotTrib)
                    VALUES($idNotaInserido,$nomeTotal,$vBC,$vICMS,$vICMSDeson,$vFCPUFDest,$vICMSUFRemet,$vFCP,$vBCST,$vST,$vFCPST,$vFCPSTRet,$vProd,$vFrete,$vSeg,$vDesc,$vII,$vIPI,$vIPIDevol,$vPIS,$vCOFINS,$vOutro,$vNF,$vTotTribTOTAL)";

        //LOG
        if (isset($LOG_NIVEL)) {
            if ($LOG_NIVEL >= 3) {
                fwrite($arquivo, $identificacao . "-SQL_Nota->" . $sqlNota . "\n");
            }
        }
        //LOG

        $atualizarNota = mysqli_query($conexao, $sqlNota);
       


//********************************************FISNOTAPRODUTOS

        include 'fisnotaproduto_inserir.php';


       
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