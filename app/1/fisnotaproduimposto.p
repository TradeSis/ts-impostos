def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotaproduimposto.idNota
    field nItem  like fisnotaproduimposto.nItem.

def temp-table ttfisnotaproduimposto  no-undo serialize-name "fisnotaproduimposto"  /* JSON SAIDA */
    like fisnotaproduimposto.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


IF ttentrada.idNota <> ? AND ttentrada.nItem <> ?
THEN DO:
    for each fisnotaproduimposto where 
        fisnotaproduimposto.idNota = ttentrada.idNota AND
        fisnotaproduimposto.nItem = ttentrada.nItem 
        no-lock.

        if avail fisnotaproduimposto
        then do:
            create ttfisnotaproduimposto.
            BUFFER-COPY fisnotaproduimposto TO ttfisnotaproduimposto.
            create ttfisnotaproduimposto.
            ttfisnotaproduimposto.idNota = fisnotaproduimposto.idNota * 2. 
            ttfisnotaproduimposto.imposto = "calculado_" + string(fisnotaproduimposto.imposto).
            ttfisnotaproduimposto.nomeImposto = fisnotaproduimposto.nomeImposto.
            ttfisnotaproduimposto.cEnq = fisnotaproduimposto.cEnq * 2. 
            ttfisnotaproduimposto.CST = fisnotaproduimposto.CST * 2. 
            ttfisnotaproduimposto.vBC = fisnotaproduimposto.vBC * 2. 
            ttfisnotaproduimposto.percentual = fisnotaproduimposto.percentual * 2.
            ttfisnotaproduimposto.valor = fisnotaproduimposto.valor * 2. 
        end.
    end.
END.


  

find first ttfisnotaproduimposto no-error.

if not avail ttfisnotaproduimposto
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnotaproduimposto:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


    

