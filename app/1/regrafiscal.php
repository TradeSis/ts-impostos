<?php
// Lucas 22032023 adicionado if de tituloContrato
// Lucas 21032023 ajustado estrutura dentro do else, adicionado $where;
// Lucas 20032023 adicionar if de idCliente
// Lucas 17022023 adicionado condição else para idContratoStatus
// Lucas 07022023 criacao

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "regra fiscal";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "regra_fiscal_" . date("dmY") . ".log", "a");
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

$regra = array();

$sql = "SELECT * ,'' AS dtVigIniFormatada ,'' AS dtVigFinFormatada FROM regrafiscal  ";

if (isset($jsonEntrada["codigoGrupo"])) {
    $sql = $sql . " where regrafiscal.codigoGrupo = " . $jsonEntrada["codigoGrupo"];
}

if (isset($jsonEntrada["idRegraFiscal"])) {
    $sql = $sql . " where regrafiscal.idRegraFiscal = " . "'"  . $jsonEntrada["idRegraFiscal"] . "'" ;
}



//echo "-SQL->" . $sql . "\n";
//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 3) {
        fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
    }
}
//LOG

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($regra, $row);

    if(isset($regra[$rows]["dtVigIni"])){
        $dtVigIniFormatada = date('d/m/Y', strtotime($regra[$rows]["dtVigIni"]));
        $regra[$rows]["dtVigIniFormatada"] = $dtVigIniFormatada;
    }
    if(isset($regra[$rows]["dtVigFin"])){
        $dtVigFinFormatada = date('d/m/Y', strtotime($regra[$rows]["dtVigFin"]));
        $regra[$rows]["dtVigFinFormatada"] = $dtVigFinFormatada;
    }

    
    

    $rows = $rows + 1;
}

if (isset($jsonEntrada["codigoGrupo"]) && $rows == 1) {
    $regra = $regra[0];
}
if (isset($jsonEntrada["idRegraFiscal"]) && $rows == 1) {
    $regra = $regra[0];
}
$jsonSaida = $regra;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG