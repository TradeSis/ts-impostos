def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalgrupo"   /* JSON ENTRADA */
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


do on error undo:
    find fiscalgrupo where fiscalgrupo.idGrupo = ttentrada.idGrupo exclusive no-error.
    fiscalgrupo.codigoGrupo  = ttentrada.codigoGrupo.
    fiscalgrupo.nomeGrupo  = ttentrada.nomeGrupo.
    fiscalgrupo.codigoNcm  = ttentrada.codigoNcm.
    fiscalgrupo.codigoCest  = ttentrada.codigoCest.
    fiscalgrupo.impostoImportacao  = ttentrada.impostoImportacao.
    fiscalgrupo.piscofinscstEnt  = ttentrada.piscofinscstEnt.
    fiscalgrupo.piscofinscstSai  = ttentrada.piscofinscstSai.
    fiscalgrupo.aliqPis  = ttentrada.aliqPis.
    fiscalgrupo.aliqCofins  = ttentrada.aliqCofins.
    fiscalgrupo.nri  = ttentrada.nri.
    fiscalgrupo.ampLegal  = ttentrada.ampLegal.
    fiscalgrupo.redPIS  = ttentrada.redPIS.
    fiscalgrupo.redCofins  = ttentrada.redCofins.
    fiscalgrupo.ipicstEnt  = ttentrada.ipicstEnt.
    fiscalgrupo.ipicstSai  = ttentrada.ipicstSai.
    fiscalgrupo.aliqipi  = ttentrada.aliqipi.
    fiscalgrupo.codenq  = ttentrada.codenq.
    fiscalgrupo.ipiex  = ttentrada.ipiex.
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Grupo alterado com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
