/** Carrega bibliotecas necessarias **/
using Progress.Json.ObjectModel.JsonObject.
using Progress.Json.ObjectModel.JsonArray.
using Progress.Json.ObjectModel.ObjectModelParser.


define VARIABLE omParser  as ObjectModelParser no-undo.
define variable joEntrada  AS JsonObject no-undo.
define variable joNFE  AS JsonObject no-undo.
define variable joinfNFe  AS JsonObject no-undo.
define variable joide  AS JsonObject no-undo.
define variable joemit  AS JsonObject no-undo.
define variable jodest  AS JsonObject no-undo.
define variable jototal  AS JsonObject no-undo.


def /*input PARAM */ VAR vlcentrada as longchar. /* JSON ENTRADA */
def /*input PARAM */ VAR vtmp       as char.     /* CAMINHO PROGRESS_TMP */


def var vlcsaida   as longchar.         /* JSON SAIDA */
DEF VAR lcAuxiliar AS LONGCHAR.

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

DEF VAR vidNota AS INT.
DEF VAR vmensagem AS CHAR.
DEF VAR vidPessoaEmitente AS INT.
DEF VAR vidPessoaDestinatario AS INT.


DEF VAR vidEmpresa AS INT.

def temp-table ttsaida  no-undo SERIALIZE-NAME "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int SERIALIZE-NAME "status"
    field descricaoStatus      as CHAR.

//---------- geralpessoas -------------    
def temp-table ttgeralpessoas no-undo serialize-name "geralpessoas"   
    LIKE geralpessoas.
    
//---------- pessoas -------------    
def temp-table ttpessoas no-undo serialize-name "pessoas"   
    LIKE pessoas.
    
//---------- fisnota -------------    
def temp-table ttfisnota no-undo serialize-name "fisnota"   
    LIKE fisnota.

    
fix-codepage(vlcentrada) = "UTF-8".
copy-lob from file "segundojson.json" to vlcentrada.

omParser = new Progress.Json.ObjectModel.ObjectModelParser().
joEntrada = cast(omParser:Parse(vlcentrada), PROGRESS.Json.ObjectModel.JsonObject).


joNFE = joEntrada:GetJsonObject("NFe").
joinfNFe = joNFE:GetJsonObject("infNFe").
joide = joinfNFe:GetJsonObject("ide").
joemit = joinfNFe:GetJsonObject("emit").
jodest = joinfNFe:GetJsonObject("dest").
jototal = joinfNFe:GetJsonObject("total").


//joemit:Write(lcAuxiliar).
//MESSAGE STRING(lcAuxiliar) VIEW-AS ALERT-BOX.
vidEmpresa = 1.

FIND empresa WHERE empresa.idEmpresa = vidEmpresa NO-LOCK NO-ERROR.
IF empresa.cnpj = joemit:GetCharacter("CNPJ") OR empresa.cnpj = jodest:GetCharacter("CNPJ")
THEN DO:
    IF joide:GetCharacter("tpNF") = "1" OR joide:GetCharacter("finNFe") = "1"
    THEN DO:
        //---------- EMITENTE -------------
        FIND pessoas WHERE pessoas.cpfCnpj = joemit:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
        IF NOT AVAIL pessoas 
        THEN DO:
            FIND geralpessoas WHERE geralpessoas.cpfCnpj = joemit:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
            IF NOT AVAIL geralpessoas
            THEN DO:
                CREATE ttgeralpessoas.
                ttgeralpessoas.cpfCnpj = joemit:GetCharacter("CNPJ").
                ttgeralpessoas.tipoPessoa = "J".
                ttgeralpessoas.nomePessoa = joemit:GetCharacter("xNome").
                ttgeralpessoas.nomeFantasia = joemit:GetCharacter("xFant") NO-ERROR.
                ttgeralpessoas.IE = joemit:GetCharacter("IE") NO-ERROR.
                ttgeralpessoas.municipio = joemit:GetJsonObject("enderEmit"):GetCharacter("xMun") NO-ERROR.
                ttgeralpessoas.codigoCidade = int(joemit:GetJsonObject("enderEmit"):GetCharacter("cMun")) NO-ERROR.
                ttgeralpessoas.codigoEstado = joemit:GetJsonObject("enderEmit"):GetCharacter("UF") NO-ERROR.
                ttgeralpessoas.pais = joemit:GetJsonObject("enderEmit"):GetCharacter("xPais") NO-ERROR.
                ttgeralpessoas.bairro = joemit:GetJsonObject("enderEmit"):GetCharacter("xBairro") NO-ERROR.
                ttgeralpessoas.endereco = joemit:GetJsonObject("enderEmit"):GetCharacter("xLgr") NO-ERROR.
                ttgeralpessoas.endNumero = int(joemit:GetJsonObject("enderEmit"):GetCharacter("nro")) NO-ERROR.
                ttgeralpessoas.CEP = joemit:GetJsonObject("enderEmit"):GetCharacter("CEP") NO-ERROR.
                ttgeralpessoas.telefone = joemit:GetJsonObject("enderEmit"):GetCharacter("fone") NO-ERROR.
                ttgeralpessoas.crt = int(joemit:GetCharacter("CRT")) NO-ERROR.
            
                RUN sistema/database/geralpessoas.p (INPUT "PUT", 
                                                     input table ttgeralpessoas,
                                                     output vmensagem).
                DELETE ttgeralpessoas.
                if vmensagem <> ? then do:
                    RUN montasaida (400,vmensagem).
                    RETURN.
                end.      
            END.
                
            CREATE ttpessoas.
            ttpessoas.cpfCnpj = joemit:GetCharacter("CNPJ").
                       RUN cadastros/database/pessoas.p (INPUT "PUT", 
                                                         input table ttpessoas,
                                                         output vidPessoaEmitente,
                                                         output vmensagem).
            DELETE ttpessoas.
            if vmensagem <> ? then do:
                RUN montasaida (400,vmensagem).
                RETURN.
            end. 
        END.
        ELSE DO:
            vidPessoaEmitente = pessoas.idPessoa.
        END.

        //---------- DESTINATARIO -------------
        FIND pessoas WHERE pessoas.cpfCnpj = jodest:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
        IF NOT AVAIL pessoas 
        THEN DO: 
            FIND geralpessoas WHERE geralpessoas.cpfCnpj = jodest:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
            IF NOT AVAIL geralpessoas
            THEN DO:
                CREATE ttgeralpessoas.
                ttgeralpessoas.cpfCnpj = jodest:GetCharacter("CNPJ").
                ttgeralpessoas.tipoPessoa = "J".
                ttgeralpessoas.nomePessoa = jodest:GetCharacter("xNome").
                ttgeralpessoas.nomeFantasia = jodest:GetCharacter("xFant") NO-ERROR.
                ttgeralpessoas.IE = jodest:GetCharacter("IE") NO-ERROR.
                ttgeralpessoas.municipio = jodest:GetJsonObject("enderDest"):GetCharacter("xMun") NO-ERROR.
                ttgeralpessoas.codigoCidade = int(jodest:GetJsonObject("enderDest"):GetCharacter("cMun")) NO-ERROR.
                ttgeralpessoas.codigoEstado = jodest:GetJsonObject("enderDest"):GetCharacter("UF") NO-ERROR.
                ttgeralpessoas.pais = jodest:GetJsonObject("enderDest"):GetCharacter("xPais") NO-ERROR.
                ttgeralpessoas.bairro = jodest:GetJsonObject("enderDest"):GetCharacter("xBairro") NO-ERROR.
                ttgeralpessoas.endereco = jodest:GetJsonObject("enderDest"):GetCharacter("xLgr") NO-ERROR.
                ttgeralpessoas.endNumero = int(jodest:GetJsonObject("enderDest"):GetCharacter("nro")) NO-ERROR.
                ttgeralpessoas.CEP = jodest:GetJsonObject("enderDest"):GetCharacter("CEP") NO-ERROR.
                ttgeralpessoas.telefone = jodest:GetJsonObject("enderDest"):GetCharacter("fone") NO-ERROR.
                ttgeralpessoas.crt = int(jodest:GetCharacter("CRT")) NO-ERROR.

                RUN sistema/database/geralpessoas.p (INPUT "PUT", 
                                                     input table ttgeralpessoas,
                                                     output vmensagem).
                DELETE ttgeralpessoas.
                if vmensagem <> ? then do:
                    RUN montasaida (400,vmensagem).
                    RETURN.
                end. 
            END. 
                
            CREATE ttpessoas.
            ttpessoas.cpfCnpj = jodest:GetCharacter("CNPJ").
                        RUN cadastros/database/pessoas.p (INPUT "PUT", 
                                                          input table ttpessoas,
                                                          output vidPessoaDestinatario,
                                                          output vmensagem).
            DELETE ttpessoas.
            if vmensagem <> ? then do:
                RUN montasaida (400,vmensagem).
                RETURN.
            end. 
        END.
        ELSE DO:
            vidPessoaDestinatario = pessoas.idPessoa.
        END.


        //---------- FISNOTA -------------
        CREATE ttfisnota.
        ttfisnota.chaveNFe       =     joinfNFe:GetJsonObject("@attributes"):GetCharacter("Id").
        ttfisnota.naturezaOp       =     joide:GetCharacter("natOp").
        ttfisnota.modelo       =     joide:GetCharacter("mod").
        ttfisnota.XML  =     "/xampp/htdocs/xml/carregado_43210131725974000166550020000158901001773920.xml".  
        ttfisnota.serie  =     joide:GetCharacter("serie").
        ttfisnota.NF  =     joide:GetCharacter("nNF").
        ttfisnota.dtEmissao  =     joide:GetDateTime("dhEmi"). 
        ttfisnota.idPessoaEmitente  =  vidPessoaEmitente.
        ttfisnota.idPessoaDestinatario  = vidPessoaDestinatario.
        ttfisnota.vNF  =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vNF")).
        ttfisnota.vProd  =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vProd")).
        ttfisnota.vFrete  =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vFrete")).
        ttfisnota.vSeg  =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vSeg")).
        ttfisnota.vDesc  =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vDesc")).
        ttfisnota.vOutro  =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vOutro")).
        vidNota = 0.
        
        RUN impostos/database/fisnota.p (INPUT "PUT", 
                                         input table ttfisnota,
                                         output vidNota,
                                         output vmensagem).
        DELETE ttfisnota.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end. 
    END.        
    ELSE DO:
       RUN montasaida (400,"NFE fora do padrao").
       RETURN.
    END.
END.    
ELSE DO:
   RUN montasaida (400,"Somente NFE da empresa Padrao permitido").
   RETURN.    
END.     

    

    
    
    
procedure montasaida.
DEF INPUT PARAM tstatus AS INT.
DEF INPUT PARAM tdescricaoStatus AS CHAR.

create ttsaida.
ttsaida.tstatus = tstatus.
ttsaida.descricaoStatus = tdescricaoStatus.

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

END PROCEDURE.
