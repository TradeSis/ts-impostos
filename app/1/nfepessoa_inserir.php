<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_pessoa_inserir";
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
    
    // Emitente
    $emitente = (string) $infNFe->emit->CNPJ;
    $emitenteNomePessoa = (string) $infNFe->emit->xNome;
    $emitenteIE = (string) $infNFe->emit->IE;
    $emitenteMunicipio = (string) $infNFe->emit->enderEmit->xMun;
    $emitenteCodigoCidade = (string) $infNFe->emit->enderEmit->cMun;
    $emitenteCodigoEstado = (string) $infNFe->emit->enderEmit->UF;
    $emitentePais = (string) $infNFe->emit->enderEmit->xPais;
    $emitenteBairro = (string) $infNFe->emit->enderEmit->xBairro;
    $emitenteEnd = (string) $infNFe->emit->enderEmit->xLgr;
    $emitenteNro = (string) $infNFe->emit->enderEmit->nro;
    $emitenteCEP = (string) $infNFe->emit->enderEmit->CEP;
    $emitenteFone = (string) $infNFe->emit->enderEmit->fone;
    $emitenteCRT = (string) $infNFe->emit->CRT;
    
    // Destinatario
    $destinatario = (string) $infNFe->dest->CNPJ;
    $destinatarioNomePessoa = (string) $infNFe->dest->xNome;
    $destinatarioIE = (string) $infNFe->dest->IE;
    $destinatarioMunicipio = (string) $infNFe->dest->enderDest->xMun;
    $destinatarioCodigoCidade = (string) $infNFe->dest->enderDest->cMun;
    $destinatarioCodigoEstado = (string) $infNFe->dest->enderDest->UF;
    $destinatarioPais = (string) $infNFe->dest->enderDest->xPais;
    $destinatarioBairro = (string) $infNFe->dest->enderDest->xBairro;
    $destinatarioEnd = (string) $infNFe->dest->enderDest->xLgr;
    $destinatarioNro = (string) $infNFe->dest->enderDest->nro;
    $destinatarioCEP = (string) $infNFe->dest->enderDest->CEP;
    $destinatarioFone = (string) $infNFe->dest->enderDest->fone;
    $destinatarioCRT = (string) $infNFe->dest->CRT;

    $emitente = array(
        'cpfCnpj' => $emitente,
        'nomePessoa' => $emitenteNomePessoa,
        'IE' => $emitenteIE,
        'municipio' => $emitenteMunicipio,
        'codigoCidade' => $emitenteCodigoCidade,
        'codigoEstado' => $emitenteCodigoEstado,
        'pais' => $emitentePais,
        'bairro' => $emitenteBairro,
        'endereco' => $emitenteEnd,
        'endNumero' => $emitenteNro,
        'CEP' => $emitenteCEP,
        'telefone' => $emitenteFone,
        'CRT' => $emitenteCRT
    );
    $destinatario = array(
        'cpfCnpj' => $destinatario,
        'nomePessoa' => $destinatarioNomePessoa,
        'IE' => $destinatarioIE,
        'municipio' => $destinatarioMunicipio,
        'codigoCidade' => $destinatarioCodigoCidade,
        'codigoEstado' => $destinatarioCodigoEstado,
        'pais' => $destinatarioPais,
        'bairro' => $destinatarioBairro,
        'endereco' => $destinatarioEnd,
        'endNumero' => $destinatarioNro,
        'CEP' => $destinatarioCEP,
        'telefone' => $destinatarioFone,
        'CRT' => $destinatarioCRT
    );
    $pessoaEntrada = array(
        $emitente,
        $destinatario
    );

    foreach ($pessoaEntrada as $id => $data) {
        if ($id !== 'idEmpresa' && is_array($data) && isset($data['cpfCnpj'])) {
            $cpfCnpj = isset($data['cpfCnpj']) && $data['cpfCnpj'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['cpfCnpj']) . "'" : "NULL";

            $sql2 = "SELECT * FROM pessoas WHERE cpfCnpj = $cpfCnpj";
            $buscar = mysqli_query($conexao, $sql2);
            $row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);

            if (mysqli_num_rows($buscar) == 1) {
                $idPessoa = $row["idPessoa"];

                $jsonSaida[$id] = array(
                    "status" => 200,
                    "retorno" => "cpfCnpj existente",
                    "idPessoaInserido" => $idPessoa
                );
            } else {
                $nomePessoa = isset($data['nomePessoa']) && $data['nomePessoa'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['nomePessoa']) . "'" : "NULL";
                $IE = isset($data['IE']) && $data['IE'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['IE']) . "'" : "NULL";
                $municipio = isset($data['municipio']) && $data['municipio'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['municipio']) . "'" : "NULL";
                $codigoCidade = isset($data['codigoCidade']) && $data['codigoCidade'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['codigoCidade']) . "'" : "NULL";
                $codigoEstado = isset($data['codigoEstado']) && $data['codigoEstado'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['codigoEstado']) . "'" : "NULL";
                $pais = isset($data['pais']) && $data['pais'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['pais']) . "'" : "NULL";
                $bairro = isset($data['bairro']) && $data['bairro'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['bairro']) . "'" : "NULL";
                $endereco = isset($data['endereco']) && $data['endereco'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['endereco']) . "'" : "NULL";
                $endNumero = isset($data['endNumero']) && $data['endNumero'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['endNumero']) . "'" : "NULL";
                $CEP = isset($data['CEP']) && $data['CEP'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['CEP']) . "'" : "NULL";
                $telefone = isset($data['telefone']) && $data['telefone'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['telefone']) . "'" : "NULL";
                $CRT = isset($data['CRT']) && $data['CRT'] !== "" ? "'" . mysqli_real_escape_string($conexao, $data['CRT']) . "'" : "NULL";

                $sql = "INSERT INTO pessoas(cpfCnpj,nomePessoa,IE,municipio,codigoCidade,codigoEstado,pais,bairro,endereco,endNumero,CEP,telefone,CRT)
                        VALUES($cpfCnpj,$nomePessoa,$IE,$municipio,$codigoCidade,$codigoEstado,$pais,$bairro,$endereco,$endNumero,$CEP,$telefone,$CRT)";
                $atualizar = mysqli_query($conexao, $sql);

                //LOG
                if (isset($LOG_NIVEL)) {
                    if ($LOG_NIVEL >= 3) {
                        fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
                    }
                }
                //LOG

                $idPessoaInserido = mysqli_insert_id($conexao);

                if ($atualizar) {
                    $jsonSaida[$id] = array(
                        "status" => 200,
                        "retorno" => "ok",
                        "idPessoaInserido" => $idPessoaInserido
                    );
                } else {
                    $jsonSaida[$id] = array(
                        "status" => 500,
                        "retorno" => "erro no mysql"
                    );
                }
            }
        } else {
            $jsonSaida[$id] = array(
                "status" => 400,
                "retorno" => "Faltaram parâmetros"
            );
        }
    }
}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
