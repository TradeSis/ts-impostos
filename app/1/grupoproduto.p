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
    LIKE fiscalgrupo.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vcodigoGrupo like ttentrada.codigoGrupo.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vcodigoGrupo = "".
if avail ttentrada
then do:
    vcodigoGrupo = ttentrada.codigoGrupo.  
    if vcodigoGrupo = ? then vcodigoGrupo = "". 
end.
 
IF ttentrada.codigoGrupo <> ? OR (ttentrada.codigoGrupo = ? AND ttentrada.buscaGrupoProduto = ?)
THEN DO:
      for EACH fiscalgrupo WHERE
      (if vcodigoGrupo = ""
        then true /* TODOS */
        ELSE fiscalgrupo.codigoGrupo = vcodigoGrupo) 
        no-lock.
        
        RUN criaGrupos.

    end. 
END.

IF ttentrada.buscaGrupoProduto <> ? 
THEN DO: 
      for each fiscalgrupo WHERE 
        fiscalgrupo.codigoGrupo = ttentrada.buscaGrupoProduto OR 
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
    BUFFER-COPY fiscalgrupo TO ttfiscalgrupo.
  

END.
