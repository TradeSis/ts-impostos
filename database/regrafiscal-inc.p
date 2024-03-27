// Programa especializado em CRAR a tabela fiscalregra
def temp-table ttentrada no-undo serialize-name "fiscalregra"   /* JSON ENTRADA */
    LIKE fiscalregra.

  
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vidregra like fiscalregra.idregra.
def output param vmensagem as char.

vidregra = ?.
vmensagem = ?.

if ttentrada.codRegra = ? OR ttentrada.codExcecao = ?
then do:
	vmensagem = "Dados de Entrada Invalidos".
    return.
end.

find fiscalregra where fiscalregra.codRegra = ttentrada.codRegra AND fiscalregra.codExcecao = ttentrada.codExcecao no-lock no-error.
if avail fiscalregra
then do:
	vmensagem = "Regra ja cadastrada".
    return.
end.


do on error undo:
    create fiscalregra.   
    vidregra = fiscalregra.idregra.
    BUFFER-COPY ttentrada EXCEPT idRegra TO fiscalregra.
end.