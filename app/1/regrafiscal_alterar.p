def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalregra"   /* JSON ENTRADA */
    LIKE fiscalregra.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.



hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


if not avail ttentrada
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada nao encontrados".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

if ttentrada.idRegra = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada Invalidos".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find fiscalregra where fiscalregra.idRegra = ttentrada.idRegra no-lock no-error.
if not avail fiscalregra
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Regra nao cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.


do on error undo:
    find fiscalregra where fiscalregra.idRegra = ttentrada.idRegra exclusive no-error.
    /* fiscalregra.codRegra = ttentrada.codRegra. INDICE UNICO*/
    /* fiscalregra.codExcecao = ttentrada.codExcecao. INDICE UNICO*/
    /* fiscalregra.dtVigIni = ttentrada.dtVigIni.
    fiscalregra.dtVigFin = ttentrada.dtVigFin.
    fiscalregra.cFOPCaracTrib = ttentrada.cFOPCaracTrib.
    fiscalregra.cST = ttentrada.cST.
    fiscalregra.cSOSN = ttentrada.cSOSN.
    fiscalregra.aliqIcmsInterna = ttentrada.aliqIcmsInterna.
    fiscalregra.aliqIcmsInterestadual = ttentrada.aliqIcmsInterestadual.
    fiscalregra.reducaoBcIcms = ttentrada.reducaoBcIcms.
    fiscalregra.reducaoBcIcmsSt = ttentrada.reducaoBcIcmsSt.
    fiscalregra.redBcICMsInterestadual = ttentrada.redBcICMsInterestadual.
    fiscalregra.aliqIcmsSt = ttentrada.aliqIcmsSt.
    fiscalregra.iVA = ttentrada.iVA.
    fiscalregra.iVAAjust = ttentrada.iVAAjust.
    fiscalregra.fCP = ttentrada.fCP.
    fiscalregra.codBenef = ttentrada.codBenef.
    fiscalregra.pDifer = ttentrada.pDifer.
    fiscalregra.pIsencao = ttentrada.pIsencao.
    fiscalregra.antecipado = ttentrada.antecipado.
    fiscalregra.desonerado = ttentrada.desonerado.
    fiscalregra.pICMSDeson = ttentrada.pICMSDeson .
    fiscalregra.isento = ttentrada.isento.         
    fiscalregra.tpCalcDifal = ttentrada.tpCalcDifal.
    fiscalregra.ampLegal = ttentrada.ampLegal.
    fiscalregra.Protocolo = ttentrada.Protocolo.
    fiscalregra.Convenio = ttentrada.Convenio.
    fiscalregra.regraGeral = ttentrada.regraGeral. */
    BUFFER-COPY ttentrada TO fiscalregra.
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Regra Fiscal alterada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
