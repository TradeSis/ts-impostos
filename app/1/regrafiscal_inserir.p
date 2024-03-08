def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalregra"   /* JSON ENTRADA */
    field codRegra like fiscalregra.codRegra
    field codExcecao like fiscalregra.codExcecao
    field dtVigIni like fiscalregra.dtVigIni
    field dtVigFin like fiscalregra.dtVigFin
    field cFOPCaracTrib like fiscalregra.cFOPCaracTrib
    field cST like fiscalregra.cST
    field cSOSN like fiscalregra.cSOSN
    field aliqIcmsInterna like fiscalregra.aliqIcmsInterna
    field aliqIcmsInterestadual like fiscalregra.aliqIcmsInterestadual
    field reducaoBcIcms like fiscalregra.reducaoBcIcms
    field reducaoBcIcmsSt like fiscalregra.reducaoBcIcmsSt
    field redBcICMsInterestadual like fiscalregra.redBcICMsInterestadual
    field aliqIcmsSt like fiscalregra.aliqIcmsSt
    field iVA like fiscalregra.iVA
    field iVAAjust like fiscalregra.iVAAjust
    field fCP like fiscalregra.fCP
    field codBenef like fiscalregra.codBenef
    field pDifer like fiscalregra.pDifer
    field pIsencao like fiscalregra.pIsencao
    field antecipado like fiscalregra.antecipado
    field desonerado like fiscalregra.desonerado
    field pICMSDeson like fiscalregra.pICMSDeson
    field isento like fiscalregra.isento
    field tpCalcDifal like fiscalregra.tpCalcDifal
    field ampLegal like fiscalregra.ampLegal
    field Protocolo like fiscalregra.Protocolo
    field Convenio like fiscalregra.Convenio
    field regraGeral like fiscalregra.regraGeral.

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

if ttentrada.codRegra = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada Invalidos".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find fiscalregra where fiscalregra.codRegra = ttentrada.codRegra no-lock no-error.
if avail fiscalregra
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Regra ja cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.


do on error undo:
    create fiscalregra.
    fiscalregra.codRegra = ttentrada.codRegra.
    fiscalregra.codExcecao = ttentrada.codExcecao.
    fiscalregra.dtVigIni = ttentrada.dtVigIni.
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
    fiscalregra.pICMSDeson = ttentrada.pICMSDeson.
    fiscalregra.isento = ttentrada.isento.
    fiscalregra.tpCalcDifal = ttentrada.tpCalcDifal.
    fiscalregra.ampLegal = ttentrada.ampLegal.
    fiscalregra.Protocolo = ttentrada.Protocolo.
    fiscalregra.Convenio = ttentrada.Convenio.
    fiscalregra.regraGeral = ttentrada.regraGeral.
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Regra criada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
