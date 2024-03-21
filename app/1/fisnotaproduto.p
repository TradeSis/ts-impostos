def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotaproduto.idNota.

def temp-table ttfisnotaproduto  no-undo serialize-name "fisnotaproduto"  /* JSON SAIDA */
    like fisnotaproduto
    field eanProduto    like geralprodutos.eanProduto
    field refProduto    like produtos.refProduto
    field nomeProduto   like geralprodutos.nomeProduto.

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


IF ttentrada.idNota <> ? OR (ttentrada.idNota = ?)
THEN DO:
    for each fisnotaproduto where
        (if vidNota = 0
        then true /* TODOS */
        ELSE fisnotaproduto.idNota = vidNota)
        no-lock.

       if avail fisnotaproduto
       then do:
            create ttfisnotaproduto.
            BUFFER-COPY fisnotaproduto TO ttfisnotaproduto.
                
            FIND produtos WHERE produtos.idProduto = fisnotaproduto.idProduto NO-LOCK no-error.
            FIND geralprodutos WHERE geralprodutos.idGeralProduto = produtos.idGeralProduto NO-LOCK no-error.
                IF AVAILABLE geralprodutos 
                THEN DO:
                    ttfisnotaproduto.refProduto = produtos.refProduto.
                    ttfisnotaproduto.eanProduto = geralprodutos.eanProduto.
                    ttfisnotaproduto.nomeProduto = geralprodutos.nomeProduto.
                END.
       end.

    end.
END.


  

find first ttfisnotaproduto no-error.

if not avail ttfisnotaproduto
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Nota nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnotaproduto:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


    

