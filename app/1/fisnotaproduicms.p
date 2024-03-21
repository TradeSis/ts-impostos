def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotaproduicms.idNota
    field nItem  like fisnotaproduicms.nItem.

def temp-table ttfisnotaproduicms  no-undo serialize-name "fisnotaproduicms"  /* JSON SAIDA */
    like fisnotaproduicms.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


IF ttentrada.idNota <> ? AND ttentrada.nItem <> ?
THEN DO:
    find fisnotaproduicms where 
        fisnotaproduicms.idNota = ttentrada.idNota AND
        fisnotaproduicms.nItem = ttentrada.nItem 
        no-lock no-error.

        if avail fisnotaproduicms
        then do:
            create ttfisnotaproduicms.
            BUFFER-COPY fisnotaproduicms TO ttfisnotaproduicms.
            create ttfisnotaproduicms.
            ttfisnotaproduicms.idNota = fisnotaproduicms.idNota * 2. 
            ttfisnotaproduicms.imposto = "calculado_" + string(fisnotaproduicms.imposto).
            ttfisnotaproduicms.nomeImposto = fisnotaproduicms.nomeImposto.
            ttfisnotaproduicms.vTotTrib = fisnotaproduicms.vTotTrib * 2. 
            ttfisnotaproduicms.orig = fisnotaproduicms.orig * 2. 
            ttfisnotaproduicms.CSOSN = fisnotaproduicms.CSOSN * 2. 
            ttfisnotaproduicms.modBCST = fisnotaproduicms.modBCST * 2.
            ttfisnotaproduicms.pMVAST = fisnotaproduicms.pMVAST * 2. 
            ttfisnotaproduicms.vBCST = fisnotaproduicms.vBCST * 2. 
            ttfisnotaproduicms.pICMSST = fisnotaproduicms.pICMSST * 2. 
            ttfisnotaproduicms.vICMSST = fisnotaproduicms.vICMSST * 2. 
            ttfisnotaproduicms.CST = fisnotaproduicms.CST. 
            ttfisnotaproduicms.modBC = fisnotaproduicms.modBC. 
            ttfisnotaproduicms.vBC = fisnotaproduicms.vBC * 2. 
            ttfisnotaproduicms.pICMS = fisnotaproduicms.pICMS * 2. 
            ttfisnotaproduicms.vICMS = fisnotaproduicms.vICMS * 2. 
        end.
END.


  

find first ttfisnotaproduicms no-error.

if not avail ttfisnotaproduicms
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnotaproduicms:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


    

