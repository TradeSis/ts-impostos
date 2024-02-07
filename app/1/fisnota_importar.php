<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_importar";
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
$conexaogeral = conectaMysql(null);

// Pega XML puro
if (isset($jsonEntrada['xml'])) {

    $xmlArquivos = $jsonEntrada['xml'];
    function verificaEmpresa($conexaogeral, $conexao, $idEmpresa, $emitCpfCnpj, $destCpfCnpj)
    {
        //Verifica se NFE é relacionada a empresa Padrão
        $sql_empresa = "SELECT empresa.idPessoa FROM empresa WHERE idEmpresa = $idEmpresa";
        $buscar_empresa = mysqli_query($conexaogeral, $sql_empresa);
        $row_empresa = mysqli_fetch_array($buscar_empresa, MYSQLI_ASSOC);

        $sql_pessoaEmpresa = "SELECT pessoas.cpfCnpj FROM pessoas WHERE idPessoa = " . $row_empresa["idPessoa"];
        $busca_pessoaEmpresa = mysqli_query($conexao, $sql_pessoaEmpresa);
        $row_pessoaEmpresa = mysqli_fetch_array($busca_pessoaEmpresa, MYSQLI_ASSOC);

        if ($emitCpfCnpj == $row_pessoaEmpresa["cpfCnpj"] || $destCpfCnpj == $row_pessoaEmpresa["cpfCnpj"]) {
            $resposta = true;
        } else {
            $resposta = false;
        }
        return $resposta;
    }

    foreach ($xmlArquivos as $xmlContent) {
        $xml = simplexml_load_string($xmlContent);
        $infNFe = $xml->NFe->infNFe;

        if ($infNFe == null) {
            $infNFe = $xml->nfeProc->NFe->infNFe;
        }

        if (isset($infNFe)) {



            //********************************************PESSOAS
            if (verificaEmpresa($conexaogeral, $conexao, $idEmpresa, (string) $infNFe->emit->CNPJ, (string) $infNFe->dest->CNPJ)) {
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

                        //Verifica se já tem Pessoa
                        $sql_pessoa = "SELECT pessoas.idPessoa FROM pessoas WHERE cpfCnpj = $cpfCnpj";
                        $buscar_pessoa = mysqli_query($conexao, $sql_pessoa);
                        $dadosPessoa = mysqli_fetch_array($buscar_pessoa, MYSQLI_ASSOC);
                        if (mysqli_num_rows($buscar_pessoa) == 1) {
                            if ($campos == "emit") {
                                $idPessoaEmitente = $dadosPessoa["idPessoa"];
                            } elseif ($campos == "dest") {
                                $idPessoaDestinatario = $dadosPessoa["idPessoa"];
                            }
                        } else {
                            $sql_geralpessoas = "SELECT geralpessoas.cpfCnpj FROM geralpessoas WHERE cpfCnpj = $cpfCnpj";
                            $buscar_geralpessoas = mysqli_query($conexaogeral, $sql_geralpessoas);
                            if (mysqli_num_rows($buscar_geralpessoas) == 0) {
                                $dadosEnder = ($campos == "emit") ? $dados->enderEmit : $dados->enderDest;

                                $geralPessoasEntrada = array(
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

                                $geralPessoasRetorno = chamaAPI(null, '/cadastros/geralpessoas', json_encode($geralPessoasEntrada), 'PUT');
                            }

                            $pessoasEntrada = array(
                                'idEmpresa' => $idEmpresa,
                                'cpfCnpj' => $cpfCnpj
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
                $buscaNFE = "SELECT fisnota.chaveNFe FROM fisnota WHERE chaveNFe = $chaveNFe";
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
                    $XMLentrada = isset($xmlContent) && $xmlContent !== "" ? "'" . $xmlContent . "'" : "null";
                    $idStatusNota = '0'; //Aberto

                    $sqlNota = "INSERT INTO fisnota(chaveNFe,naturezaOp,modelo,XML,serie,NF,dtEmissao,idPessoaEmitente,idPessoaDestinatario,idStatusNota) 
                            VALUES ($chaveNFe,$naturezaOp,$modelo,$XMLentrada,$serie,$NF,$dtEmissao,$idPessoaEmitente,$idPessoaDestinatario,$idStatusNota)";

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
                }
            } else {
                $jsonSaida = array(
                    "status" => 400,
                    "retorno" => "Somente NFE da empresa Padrão é permitida"
                );
            }
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