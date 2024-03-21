
// Programa especializado em CRAR a tabela fiscalgrupo
def temp-table ttentrada no-undo serialize-name "fiscalgrupo"   /* JSON ENTRADA */
    LIKE fiscalgrupo.

DEF INPUT PARAM vacao AS CHAR.   
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vidgrupo like fiscalgrupo.idgrupo.
def output param vmensagem as char.

vidgrupo = ?.
vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada fiscalgrupo nao encontrados".
    return.    
end.

if ttentrada.nomeGrupo = ?
then do:
    vmensagem = "Dados de Entrada Invalidos".
    return.
end.

IF vacao = "PUT"
THEN DO:
    do on error undo:
        create fiscalgrupo.
        vidgrupo = fiscalgrupo.idgrupo.
        BUFFER-COPY ttentrada EXCEPT idGrupo TO fiscalgrupo.
    end. 
END.

