
// Programa especializado em CRAR a tabela apifiscalhistorico
def temp-table ttentrada no-undo serialize-name "apifiscalhistorico"   /* JSON ENTRADA */
    LIKE apifiscalhistorico.

  
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vidHistorico like apifiscalhistorico.idHistorico.
def output param vmensagem as char.

vidHistorico = ?.
vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada nao encontrados".
    return.    
end.


do on error undo:
    create apifiscalhistorico.
    vidHistorico = apifiscalhistorico.idHistorico.
    BUFFER-COPY ttentrada EXCEPT idHistorico TO apifiscalhistorico.
end.
