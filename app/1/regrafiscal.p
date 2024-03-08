def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalregra"   /* JSON ENTRADA */
    field idRegra like fiscalregra.idRegra
    field codRegra like fiscalregra.codRegra.

def temp-table ttfiscalregra  no-undo serialize-name "fiscalregra"  /* JSON SAIDA */
    field idRegra like fiscalregra.idRegra
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

def VAR vidRegra like ttentrada.idRegra.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidRegra = 0.
if avail ttentrada
then do:
    vidRegra = ttentrada.idRegra.
    if vidRegra = ? then vidRegra = 0.
end.

IF ttentrada.idRegra <> ? OR (ttentrada.idRegra = ? AND ttentrada.codRegra = ? /* AND ttentrada.codigo = ? */ )
THEN DO:
    for each fiscalregra where
        (if vidRegra = 0
         then true /* TODOS */
         else fiscalregra.idRegra = vidRegra)
         no-lock.

         RUN criaRegras.

    end.
END.

IF ttentrada.codRegra <> ?
THEN DO:
      for each fiscalregra WHERE 
        fiscalregra.codRegra = ttentrada.codRegra
        no-lock.
        
        RUN criaRegras.

    end. 
END.

find first ttfiscalregra no-error.

if not avail ttfiscalregra
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Regra nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfiscalregra:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


PROCEDURE criaRegras.

    create ttfiscalregra.
    ttfiscalregra.idRegra = fiscalregra.idRegra.
    ttfiscalregra.codRegra = fiscalregra.codRegra.
    ttfiscalregra.codExcecao = fiscalregra.codExcecao.
    ttfiscalregra.dtVigIni = fiscalregra.dtVigIni.
    ttfiscalregra.dtVigFin = fiscalregra.dtVigFin.
    ttfiscalregra.cFOPCaracTrib = fiscalregra.cFOPCaracTrib.
    ttfiscalregra.cST = fiscalregra.cST.
    ttfiscalregra.cSOSN = fiscalregra.cSOSN.
    ttfiscalregra.aliqIcmsInterna = fiscalregra.aliqIcmsInterna.
    ttfiscalregra.aliqIcmsInterestadual = fiscalregra.aliqIcmsInterestadual.
    ttfiscalregra.reducaoBcIcms = fiscalregra.reducaoBcIcms.
    ttfiscalregra.reducaoBcIcmsSt = fiscalregra.reducaoBcIcmsSt.
    ttfiscalregra.redBcICMsInterestadual = fiscalregra.redBcICMsInterestadual.
    ttfiscalregra.aliqIcmsSt = fiscalregra.aliqIcmsSt.
    ttfiscalregra.iVA = fiscalregra.iVA.
    ttfiscalregra.iVAAjust = fiscalregra.iVAAjust.
    ttfiscalregra.fCP = fiscalregra.fCP.
    ttfiscalregra.codBenef = fiscalregra.codBenef.
    ttfiscalregra.pDifer = fiscalregra.pDifer.
    ttfiscalregra.pIsencao = fiscalregra.pIsencao.
    ttfiscalregra.antecipado = fiscalregra.antecipado.
    ttfiscalregra.desonerado = fiscalregra.desonerado.
    ttfiscalregra.pICMSDeson = fiscalregra.pICMSDeson.
    ttfiscalregra.isento = fiscalregra.isento.
    ttfiscalregra.tpCalcDifal = fiscalregra.tpCalcDifal.
    ttfiscalregra.ampLegal = fiscalregra.ampLegal.
    ttfiscalregra.Protocolo = fiscalregra.Protocolo.
    ttfiscalregra.Convenio = fiscalregra.Convenio.
    ttfiscalregra.regraGeral = fiscalregra.regraGeral.

END.
