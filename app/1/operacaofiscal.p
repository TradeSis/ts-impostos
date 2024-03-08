def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscaloperacao"   /* JSON ENTRADA */
    field idoperacaofiscal  like fiscaloperacao.idoperacaofiscal.

def temp-table ttfiscaloperacao  no-undo serialize-name "fiscaloperacao"  /* JSON SAIDA */
    LIKE  fiscaloperacao.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidoperacaofiscal like ttentrada.idoperacaofiscal.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidoperacaofiscal = 0.
if avail ttentrada
then do:
    vidoperacaofiscal = ttentrada.idoperacaofiscal.
    if vidoperacaofiscal = ? then vidoperacaofiscal = 0. 
end.

for each fiscaloperacao where
    (if vidoperacaofiscal = 0 
    then true /* TODOS */
    ELSE fiscaloperacao.idoperacaofiscal = vidoperacaofiscal)
    no-lock.
        
    create ttfiscaloperacao.
    ttfiscaloperacao.idoperacaofiscal = fiscaloperacao.idoperacaofiscal.
    ttfiscaloperacao.idGrupo   = fiscaloperacao.idGrupo.
    ttfiscaloperacao.codigoEstado   = fiscaloperacao.codigoEstado.
    ttfiscaloperacao.cFOP   = fiscaloperacao.cFOP.
    ttfiscaloperacao.codigoCaracTrib   = fiscaloperacao.codigoCaracTrib.
    ttfiscaloperacao.finalidade   = fiscaloperacao.finalidade.
    ttfiscaloperacao.idRegra   = fiscaloperacao.idRegra.
end.
    

find first ttfiscaloperacao no-error.

if not avail ttfiscaloperacao
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Operação fiscal nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfiscaloperacao:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
