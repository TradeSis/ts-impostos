def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalgrupo"   /* JSON ENTRADA */
    LIKE fiscalgrupo.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

DEF BUFFER bfiscalgrupo FOR fiscalgrupo.

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

find fiscalgrupo where fiscalgrupo.idGrupo = ttentrada.idGrupo no-lock no-error.
if not avail fiscalgrupo
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Grupo nao cadastrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

IF ttentrada.codigoGrupo <> ? AND ttentrada.codigoGrupo <> fiscalgrupo.codigoGrupo
THEN DO:
    find bfiscalgrupo where bfiscalgrupo.codigoGrupo = ttentrada.codigoGrupo no-lock no-error.
    IF avail bfiscalgrupo
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "Grupo já cadastrado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.    
    
END.

do on error undo:
    find fiscalgrupo where fiscalgrupo.idGrupo = ttentrada.idGrupo exclusive no-error.
    BUFFER-COPY ttentrada 
            EXCEPT idGrupo codigoGrupo
            TO fiscalgrupo.
    IF ttentrada.codigoGrupo <> ? 
    THEN DO:
        fiscalgrupo.codigoGrupo = ttentrada.codigoGrupo.
    END.
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Grupo alterado com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
