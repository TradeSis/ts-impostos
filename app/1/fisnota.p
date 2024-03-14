def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field chaveNFe  like fisnota.chaveNFe
    field idNota  like fisnota.idNota.

def temp-table ttfisnota  no-undo serialize-name "fisnota"  /* JSON SAIDA */
    like fisnota
    field emitente_cpfCnpj  like geralpessoas.cpfCnpj
    field emitente_IE  like geralpessoas.IE
    field emitente_nomePessoa  like geralpessoas.nomePessoa
    field emitente_nomeFantasia  like geralpessoas.nomeFantasia
    field emitente_municipio  like geralpessoas.municipio
    field emitente_codigoEstado  like geralpessoas.codigoEstado
    field emitente_pais  like geralpessoas.pais 
    field destinatario_cpfCnpj  like geralpessoas.cpfCnpj
    field destinatario_IE  like geralpessoas.IE
    field destinatario_nomePessoa  like geralpessoas.nomePessoa
    field destinatario_nomeFantasia  like geralpessoas.nomeFantasia
    field destinatario_municipio  like geralpessoas.municipio
    field destinatario_codigoEstado  like geralpessoas.codigoEstado
    field destinatario_pais  like geralpessoas.pais 
    field nomeStatusNota  like fisnotastatus.nomeStatusNota.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidNota like ttentrada.idNota.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidNota = 0.
if avail ttentrada
then do:
    vidNota = ttentrada.idNota.
    if vidNota = ? then vidNota = 0.
end.
if ttentrada.chaveNFe = ""
then do:
    ttentrada.chaveNFe = ?.
end.
if ttentrada.idNota = 0
then do:
    ttentrada.idNota = ?.
end.


IF ttentrada.idNota <> ? OR (ttentrada.idNota = ? AND ttentrada.chaveNFe = ?)
THEN DO:
    for each fisnota where
        (if vidNota = 0
        then true /* TODOS */
        ELSE fisnota.idNota = vidNota)
        no-lock.

       if avail fisnota
       then do:
            RUN criaNotas.
       end.

    end.
END.

IF ttentrada.chaveNFe <> ?
THEN DO:
    find fisnota where 
        fisnota.chaveNFe =  ttentrada.chaveNFe 
        NO-LOCK no-error.
       
       if avail fisnota
       then do:
            RUN criaNotas.
       end.
END.
    

  

find first ttfisnota no-error.

if not avail ttfisnota
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnota:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

PROCEDURE criaNotas.

    create ttfisnota.
    BUFFER-COPY fisnota TO ttfisnota.
        
        FIND pessoas WHERE pessoas.idPessoa = fisnota.idPessoaEmitente NO-LOCK no-error.
        FIND geralpessoas WHERE geralpessoas.cpfCnpj = pessoas.cpfCnpj NO-LOCK no-error.
            IF AVAILABLE geralpessoas 
            THEN DO:
                ttfisnota.emitente_cpfCnpj = geralpessoas.cpfCnpj.
                ttfisnota.emitente_IE = geralpessoas.IE.
                ttfisnota.emitente_nomePessoa = geralpessoas.nomePessoa.
                ttfisnota.emitente_nomeFantasia = geralpessoas.nomeFantasia.
                ttfisnota.emitente_municipio = geralpessoas.municipio.
                ttfisnota.emitente_codigoEstado = geralpessoas.codigoEstado.
                ttfisnota.emitente_pais = geralpessoas.pais.
            END.
            
        FIND pessoas WHERE pessoas.idPessoa = fisnota.idPessoaDestinatario NO-LOCK no-error.
        FIND geralpessoas WHERE geralpessoas.cpfCnpj = pessoas.cpfCnpj NO-LOCK no-error.
        IF AVAILABLE geralpessoas 
        THEN DO:
            ttfisnota.destinatario_cpfCnpj = geralpessoas.cpfCnpj.
            ttfisnota.destinatario_IE = geralpessoas.IE.
            ttfisnota.destinatario_nomePessoa = geralpessoas.nomePessoa.
            ttfisnota.destinatario_nomeFantasia = geralpessoas.nomeFantasia.
            ttfisnota.destinatario_municipio = geralpessoas.municipio.
            ttfisnota.destinatario_codigoEstado = geralpessoas.codigoEstado.
            ttfisnota.destinatario_pais = geralpessoas.pais.
        END.

        FIND fisnotastatus WHERE fisnotastatus.idStatusNota = fisnota.idStatusNota NO-LOCK no-error.
        IF AVAILABLE fisnotastatus 
        THEN DO:
            ttfisnota.nomeStatusNota = fisnotastatus.nomeStatusNota.
        END.

END.
