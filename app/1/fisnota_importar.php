<?php

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "nfe_importar";
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


// Pega XML puro
if (isset($jsonEntrada['xml'])) {

    $xmlArquivos = $jsonEntrada['xml'];
 

    foreach ($xmlArquivos as $xmlContent) {
        $xml = simplexml_load_string($xmlContent);
        echo json_encode($xml);
        fwrite($arquivo, $identificacao . "-JSON->" . json_encode($xml) . "\n");
        /*
        $progr = new chamaprogress();
        $retorno = $progr->executarprogress("impostos/app/1/fisnota_importar",json_encode($jsonEntrada));
        fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");
        $operacao = json_decode($retorno,true);
        if (isset($operacao["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
            $operacao = $operacao["conteudoSaida"][0];
        } else {
          
           if (!isset($operacao["fisnota"][1])) {  // Verifica se tem mais de 1 registro
            $operacao = $operacao["fisnota"][0]; // Retorno sem array
          } else {
            $operacao = $operacao["fisnota"]; 
          }
        
        }*/
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