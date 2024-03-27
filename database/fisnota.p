
// Programa especializado em CRAR a tabela fisnota
def temp-table ttentrada no-undo serialize-name "fisnota"   /* JSON ENTRADA */
    LIKE fisnota.

  
def input param vAcao as char.
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vidNota as INT.
def output param vmensagem as char.

vidNota = ?.
vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada nao encontrados".
    return.    
end.


if vAcao = "PUT"
THEN DO:
    if ttentrada.chaveNFe = ? or ttentrada.chaveNFe = ""
    then do:
        vmensagem = "Dados de Entrada Invalidos".
        return.
    end.

    find fisnota where fisnota.chaveNFe = ttentrada.chaveNFe no-lock no-error.
    if avail fisnota
    then do:
        vmensagem = "NFE ja cadastrada".
        return.
    end.
    
    
    do on error undo:
        create fisnota.
        vidNota = fisnota.idNota.
        BUFFER-COPY ttentrada EXCEPT idNota TO  fisnota .
    end.
END.
IF vAcao = "POST" 
THEN DO:

    if ttentrada.idNota = ? or ttentrada.idNota = 0
    then do:
        vmensagem = "Dados de Entrada Invalidos".
        return.
    end.

    find fisnota where fisnota.idNota = ttentrada.idNota no-lock no-error.
    if not avail fisnota
    then do:
        vmensagem = "NFE nao cadastrada".
        return.
    end.
    
    do on error undo:   
        find fisnota where fisnota.idNota = ttentrada.idNota exclusive no-error.
        fisnota.idStatusNota = 1.
        vidNota = fisnota.idNota.
    end.
    
END.
   
