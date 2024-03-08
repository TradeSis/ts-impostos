def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalgrupo"   /* JSON ENTRADA */
    field idGrupo  like fiscalgrupo.idGrupo
    field codigoGrupo  like fiscalgrupo.codigoGrupo
    FIELD buscaGrupoProduto AS CHAR.

def temp-table ttfiscalgrupo  no-undo serialize-name "fiscalgrupo"  /* JSON SAIDA */
    field idGrupo  like fiscalgrupo.idGrupo
    field codigoGrupo  like fiscalgrupo.codigoGrupo
    field nomeGrupo  like fiscalgrupo.nomeGrupo
    field codigoNcm  like fiscalgrupo.codigoNcm
    field codigoCest  like fiscalgrupo.codigoCest
    field impostoImportacao  like fiscalgrupo.impostoImportacao
    field piscofinscstEnt  like fiscalgrupo.piscofinscstEnt
    field piscofinscstSai  like fiscalgrupo.piscofinscstSai
    field aliqPis  like fiscalgrupo.aliqPis
    field aliqCofins  like fiscalgrupo.aliqCofins
    field nri  like fiscalgrupo.nri
    field ampLegal  like fiscalgrupo.ampLegal
    field redPIS  like fiscalgrupo.redPIS
    field redCofins  like fiscalgrupo.redCofins
    field ipicstEnt  like fiscalgrupo.ipicstEnt
    field ipicstSai  like fiscalgrupo.ipicstSai
    field aliqipi  like fiscalgrupo.aliqipi
    field codenq  like fiscalgrupo.codenq
    field ipiex  like fiscalgrupo.ipiex.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vcodigoGrupo like ttentrada.codigoGrupo.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vcodigoGrupo = ?.
if avail ttentrada
then do:
    vcodigoGrupo = ttentrada.codigoGrupo.  
    if vcodigoGrupo = "" then vcodigoGrupo = ?. 
end.
 
IF ttentrada.codigoGrupo = ? AND ttentrada.buscaGrupoProduto = ?
THEN DO:
    for each fiscalgrupo WHERE 
        no-lock.

        RUN criaGrupos. 

    end.
END.

IF ttentrada.codigoGrupo <> ?
THEN DO:
      for EACH fiscalgrupo WHERE 
        fiscalgrupo.codigoGrupo = ttentrada.codigoGrupo
        no-lock.
        
        RUN criaGrupos.

    end. 
END.

IF ttentrada.buscaGrupoProduto <> ? 
THEN DO: 
      for each fiscalgrupo WHERE 
        fiscalgrupo.codigoGrupo MATCHES "*" + ttentrada.buscaGrupoProduto + "*" OR 
        fiscalgrupo.nomeGrupo MATCHES "*" + ttentrada.buscaGrupoProduto + "*"
        no-lock.
        
        RUN criaGrupos.

    end.
END.

find first ttfiscalgrupo no-error.

if not avail ttfiscalgrupo
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Grupo fiscal nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfiscalgrupo:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

PROCEDURE criaGrupos.

    create ttfiscalgrupo.
    ttfiscalgrupo.idGrupo = fiscalgrupo.idGrupo.
    ttfiscalgrupo.codigoGrupo  = fiscalgrupo.codigoGrupo.
    ttfiscalgrupo.nomeGrupo  = fiscalgrupo.nomeGrupo.
    ttfiscalgrupo.codigoNcm  = fiscalgrupo.codigoNcm.
    ttfiscalgrupo.codigoCest  = fiscalgrupo.codigoCest.
    ttfiscalgrupo.impostoImportacao  = fiscalgrupo.impostoImportacao.
    ttfiscalgrupo.piscofinscstEnt  = fiscalgrupo.piscofinscstEnt.
    ttfiscalgrupo.piscofinscstSai  = fiscalgrupo.piscofinscstSai.
    ttfiscalgrupo.aliqPis  = fiscalgrupo.aliqPis.
    ttfiscalgrupo.aliqCofins  = fiscalgrupo.aliqCofins.
    ttfiscalgrupo.nri  = fiscalgrupo.nri.
    ttfiscalgrupo.ampLegal  = fiscalgrupo.ampLegal.
    ttfiscalgrupo.redPIS  = fiscalgrupo.redPIS.
    ttfiscalgrupo.redCofins  = fiscalgrupo.redCofins.
    ttfiscalgrupo.ipicstEnt  = fiscalgrupo.ipicstEnt.
    ttfiscalgrupo.ipicstSai  = fiscalgrupo.ipicstSai.
    ttfiscalgrupo.aliqipi  = fiscalgrupo.aliqipi.
    ttfiscalgrupo.codenq  = fiscalgrupo.codenq.
    ttfiscalgrupo.ipiex  = fiscalgrupo.ipiex.

END.
