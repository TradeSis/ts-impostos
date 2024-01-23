<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "grupoproduto_inserir";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "impostos_" . date("dmY") . ".log", "a");
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

$conexao = conectaMysql(null);
if (isset($jsonEntrada['codigoGrupo'])) {

    $codigoGrupo = isset($jsonEntrada['codigoGrupo']) && $jsonEntrada['codigoGrupo'] !== "" ? "'" . $jsonEntrada['codigoGrupo'] . "'" : "NULL";
    $nomeGrupo = isset($jsonEntrada['nomeGrupo']) && $jsonEntrada['nomeGrupo'] !== "" ? "'" . $jsonEntrada['nomeGrupo'] . "'" : "NULL";
    $codigoNcm = isset($jsonEntrada['codigoNcm']) && $jsonEntrada['codigoNcm'] !== "" ? "'" . $jsonEntrada['codigoNcm'] . "'" : "NULL";
	$codigoCest = isset($jsonEntrada['codigoCest']) && $jsonEntrada['codigoCest'] !== "" ? "'" . $jsonEntrada['codigoCest'] . "'" : "NULL";
	$impostoImportacao = isset($jsonEntrada['impostoImportacao']) && $jsonEntrada['impostoImportacao'] !== "" ? "'" . $jsonEntrada['impostoImportacao'] . "'" : "NULL";
    $piscofinscstEnt = isset($jsonEntrada['piscofinscstEnt']) && $jsonEntrada['piscofinscstEnt'] !== "" ? "'" . $jsonEntrada['piscofinscstEnt'] . "'" : "NULL";
    $piscofinscstSai = isset($jsonEntrada['piscofinscstSai']) && $jsonEntrada['piscofinscstSai'] !== "" ? "'" . $jsonEntrada['piscofinscstSai'] . "'" : "NULL";
	$aliqPis = isset($jsonEntrada['aliqPis']) && $jsonEntrada['aliqPis'] !== "" ? "'" . $jsonEntrada['aliqPis'] . "'" : "NULL";
    $aliqCofins = isset($jsonEntrada['aliqCofins']) && $jsonEntrada['aliqCofins'] !== "" ? "'" . $jsonEntrada['aliqCofins'] . "'" : "NULL";
    $nri = isset($jsonEntrada['nri']) && $jsonEntrada['nri'] !== "" ? "'" . $jsonEntrada['nri'] . "'" : "NULL";
	$ampLegal = str_replace("'", "", $jsonEntrada['ampLegal']);
    $ampLegal_formatada = isset($ampLegal) && $ampLegal !== "null"    ? "'" .  $ampLegal . "'" : "null";
    $redPIS = isset($jsonEntrada['redPIS']) && $jsonEntrada['redPIS'] !== "" ? "'" . $jsonEntrada['redPIS'] . "'" : "NULL";
    $redCofins = isset($jsonEntrada['redCofins']) && $jsonEntrada['redCofins'] !== "" ? "'" . $jsonEntrada['redCofins'] . "'" : "NULL";
	$ipicstEnt = isset($jsonEntrada['ipicstEnt']) && $jsonEntrada['ipicstEnt'] !== "" ? "'" . $jsonEntrada['ipicstEnt'] . "'" : "NULL";
	$ipicstSai = isset($jsonEntrada['ipicstSai']) && $jsonEntrada['ipicstSai'] !== "" ? "'" . $jsonEntrada['ipicstSai'] . "'" : "NULL";
    $aliqipi = isset($jsonEntrada['aliqipi']) && $jsonEntrada['aliqipi'] !== "" ? "'" . $jsonEntrada['aliqipi'] . "'" : "NULL";
    $codenq = isset($jsonEntrada['codenq']) && $jsonEntrada['codenq'] !== "" ? "'" . $jsonEntrada['codenq'] . "'" : "NULL";
    $ipiex = isset($jsonEntrada['ipiex']) && $jsonEntrada['ipiex'] !== ""    ? "'" . $jsonEntrada['ipiex'] . "'" : "NULL";

    $sql = "INSERT INTO `fiscalgrupo`(codigoGrupo, nomeGrupo, codigoNcm, codigoCest, impostoImportacao, piscofinscstEnt,
        piscofinscstSai, aliqPis, aliqCofins, nri, ampLegal, redPIS, redCofins, ipicstEnt, ipicstSai, aliqipi, codenq, ipiex) 
        VALUES ($codigoGrupo, $nomeGrupo, $codigoNcm, $codigoCest, $impostoImportacao, $piscofinscstEnt,
        $piscofinscstSai, $aliqPis, $aliqCofins, $nri, $ampLegal_formatada, $redPIS, $redCofins, $ipicstEnt, $ipicstSai, $aliqipi, $codenq, $ipiex)";
    

    //echo $sql;

    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 3) {
            fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
        }
    }
    //LOG

    //TRY-CATCH
    try {

        $atualizar = mysqli_query($conexao, $sql);
        if (!$atualizar)
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


} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parametros"
    );
}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
