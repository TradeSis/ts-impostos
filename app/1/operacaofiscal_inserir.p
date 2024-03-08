def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscaloperacao"   /* JSON ENTRADA */
    field idGrupo like fiscaloperacao.idGrupo
    field codigoEstado like fiscaloperacao.codigoEstado
    field cFOP like fiscaloperacao.cFOP
    field codigoCaracTrib like fiscaloperacao.codigoCaracTrib
    field finalidade like fiscaloperacao.finalidade
    field idRegra like fiscaloperacao.idRegra.
 

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

if ttentrada.idGrupo = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada Invalidos".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find fiscaloperacao where fiscaloperacao.idGrupo = ttentrada.idGrupo AND
fiscaloperacao.codigoEstado = ttentrada.codigoEstado AND
fiscaloperacao.cFOP = ttentrada.cFOP AND
fiscaloperacao.codigoCaracTrib = ttentrada.codigoCaracTrib AND
fiscaloperacao.finalidade = ttentrada.finalidade
no-lock no-error.

if avail fiscaloperacao
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Operacao ja cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.


do on error undo:
    create fiscaloperacao.
    fiscaloperacao.idGrupo = ttentrada.idGrupo.
    fiscaloperacao.codigoEstado = ttentrada.codigoEstado.
    fiscaloperacao.cFOP = ttentrada.cFOP.
    fiscaloperacao.codigoCaracTrib = ttentrada.codigoCaracTrib.
    fiscaloperacao.finalidade = ttentrada.finalidade.
    fiscaloperacao.idRegra = ttentrada.idRegra.
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Regra criada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
