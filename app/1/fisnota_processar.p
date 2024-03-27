/** Carrega bibliotecas necessarias **/
using Progress.Json.ObjectModel.JsonObject.
using Progress.Json.ObjectModel.JsonArray.
using Progress.Json.ObjectModel.ObjectModelParser.


define VARIABLE omParser  as ObjectModelParser no-undo.
define variable joEntrada  AS JsonObject no-undo.
define variable joNFE  AS JsonObject no-undo.
define variable joinfNFe  AS JsonObject no-undo.
define variable jodet  AS JsonObject no-undo.
define variable jototal  AS JsonObject no-undo.
define variable joICMSTot  AS JsonObject no-undo.
define variable joimposto  AS JsonObject no-undo.
define variable joICMS  AS JsonObject no-undo.


def /*input PARAM */ VAR vlcentrada as longchar. /* JSON ENTRADA */
def /*input PARAM */ VAR vtmp       as char.     /* CAMINHO PROGRESS_TMP */


def var vlcsaida   as longchar.         /* JSON SAIDA */
DEF VAR lcAuxiliar AS LONGCHAR.

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */


DEF VAR vmensagem AS CHAR.
DEF VAR vidPessoaEmitente AS INT.
DEF VAR vidProduto AS INT.
DEF VAR vidGeralProduto AS INT.
DEF VAR vidNota AS INT.


DEF VAR vidEmpresa AS INT.


def temp-table ttsaida  no-undo SERIALIZE-NAME "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int SERIALIZE-NAME "status"
    field descricaoStatus      as CHAR.

    
//---------- fisnota -------------    
def temp-table ttfisnota no-undo serialize-name "fisnota"   
LIKE fisnota.

//---------- produtos -------------    
def temp-table ttprodutos no-undo serialize-name "produtos"   
    LIKE produtos
    field eanProduto like geralprodutos.eanProduto.
    
//---------- geralprodutos -------------    
def temp-table ttgeralprodutos no-undo serialize-name "geralprodutos"   
    LIKE geralprodutos.   

//---------- geralfornecimento -------------    
def temp-table ttgeralfornecimento no-undo serialize-name "geralfornecimento"   
    LIKE geralfornecimento.     

//---------- fisnotatotal -------------    
def temp-table ttfisnotatotal no-undo serialize-name "fisnotatotal"   
    LIKE fisnotatotal.
    
//---------- fisnotaproduto -------------    
def temp-table ttfisnotaproduto no-undo serialize-name "fisnotaproduto"   
    LIKE fisnotaproduto.
    
//---------- ttfisnotaproduicms -------------    
def temp-table ttfisnotaproduicms no-undo serialize-name "fisnotaproduicms"   
    LIKE fisnotaproduicms.

//---------- ttfisnotaproduimposto -------------    
def temp-table ttfisnotaproduimposto no-undo serialize-name "fisnotaproduimposto"   
    LIKE fisnotaproduimposto.

    
fix-codepage(vlcentrada) = "UTF-8".
copy-lob from file "json.json" to vlcentrada.

omParser = new Progress.Json.ObjectModel.ObjectModelParser().
joEntrada = cast(omParser:Parse(vlcentrada), PROGRESS.Json.ObjectModel.JsonObject).


joNFE = joEntrada:GetJsonObject("NFe").
joinfNFe = joNFE:GetJsonObject("infNFe").
jototal = joinfNFe:GetJsonObject("total").
joICMSTot = jototal:GetJsonObject("ICMSTot").
jodet = joinfNFe:GetJsonObject("det").
joimposto = jodet:GetJsonObject("imposto").
joICMS = joimposto:GetJsonObject("ICMS").


//joemit:Write(lcAuxiliar).
//MESSAGE STRING(lcAuxiliar) VIEW-AS ALERT-BOX.
vidEmpresa = 1.
FIND fisnota WHERE fisnota.chaveNFE = joinfNFe:GetJsonObject("@attributes"):GetCharacter("Id") NO-LOCK NO-ERROR.

        //---------- FISNOTATOTAL -------------
        CREATE ttfisnotatotal.
        ttfisnotatotal.idNota       =  fisnota.idNota.
        ttfisnotatotal.nomeTotal    =  "ICMSTot".
        ttfisnotatotal.vBC          =  decimal(joICMSTot:GetCharacter("vBC")) NO-ERROR.
        ttfisnotatotal.vICMS        =  decimal(joICMSTot:GetCharacter("vICMS")) NO-ERROR.
        ttfisnotatotal.vICMSDeson   =  decimal(joICMSTot:GetCharacter("vICMSDeson")) NO-ERROR.
        ttfisnotatotal.vFCPUFDest   =  decimal(joICMSTot:GetCharacter("vFCPUFDest")) NO-ERROR.
        ttfisnotatotal.vICMSUFRemet =  decimal(joICMSTot:GetCharacter("vICMSUFRemet")) NO-ERROR.
        ttfisnotatotal.vFCP         =  decimal(joICMSTot:GetCharacter("vFCP")) NO-ERROR.
        ttfisnotatotal.vBCST        =  decimal(joICMSTot:GetCharacter("vBCST")) NO-ERROR.
        ttfisnotatotal.vST          =  decimal(joICMSTot:GetCharacter("vST")) NO-ERROR.
        ttfisnotatotal.vFCPST       =  decimal(joICMSTot:GetCharacter("vFCPST")) NO-ERROR.
        ttfisnotatotal.vFCPSTRet    =  decimal(joICMSTot:GetCharacter("vFCPSTRet")) NO-ERROR.
        ttfisnotatotal.vProd        =  decimal(joICMSTot:GetCharacter("vProd")) NO-ERROR.
        ttfisnotatotal.vFrete       =  decimal(joICMSTot:GetCharacter("vFrete")) NO-ERROR.
        ttfisnotatotal.vSeg         =  decimal(joICMSTot:GetCharacter("vSeg")) NO-ERROR.
        ttfisnotatotal.vDesc        =  decimal(joICMSTot:GetCharacter("vDesc")) NO-ERROR.
        ttfisnotatotal.vII          =  decimal(joICMSTot:GetCharacter("vII")) NO-ERROR.
        ttfisnotatotal.vIPI         =  decimal(joICMSTot:GetCharacter("vIPI")) NO-ERROR.
        ttfisnotatotal.vIPIDevol    =  decimal(joICMSTot:GetCharacter("vIPIDevol")) NO-ERROR.
        ttfisnotatotal.vPIS         =  decimal(joICMSTot:GetCharacter("vPIS")) NO-ERROR.
        ttfisnotatotal.vOutro       =  decimal(joICMSTot:GetCharacter("vOutro")) NO-ERROR.
        ttfisnotatotal.vCOFINS      =  decimal(joICMSTot:GetCharacter("vCOFINS")) NO-ERROR.
        ttfisnotatotal.vNF          =  decimal(joICMSTot:GetCharacter("vNF")) NO-ERROR.
        ttfisnotatotal.vTotTrib     =  decimal(joICMSTot:GetCharacter("vTotTrib")) NO-ERROR.
        
        RUN impostos/database/fisnotatotal.p (INPUT "PUT", 
                                              input table ttfisnotatotal,
                                              output vmensagem).
        DELETE ttfisnotatotal.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.        


        //---------- PRODUTO -------------
        FIND geralprodutos WHERE 
            geralprodutos.eanProduto = int(jodet:GetJsonObject("prod"):GetCharacter("cEAN")) OR
            geralprodutos.nomeProduto = jodet:GetJsonObject("prod"):GetCharacter("xProd") 
            NO-LOCK NO-ERROR.
        
            IF NOT AVAIL geralprodutos 
            THEN DO:
                CREATE ttgeralprodutos.
                ttgeralprodutos.eanProduto    =  int(jodet:GetJsonObject("prod"):GetCharacter("cEAN")) NO-ERROR.
                ttgeralprodutos.nomeProduto   =  jodet:GetJsonObject("prod"):GetCharacter("xProd") NO-ERROR.
                
                RUN sistema/database/geralprodutos.p (INPUT "PUT", 
                                                       input table ttgeralprodutos,
                                                       output vidGeralProduto,
                                                       output vmensagem).
                DELETE ttgeralprodutos.
                if vmensagem <> ? then do:
                    RUN montasaida (400,vmensagem).
                    RETURN.
                end.   
                CREATE ttgeralfornecimento.
                ttgeralfornecimento.Cnpj    =  "31725974000166".
                ttgeralfornecimento.refProduto   =  jodet:GetJsonObject("prod"):GetCharacter("cProd") NO-ERROR.
                ttgeralfornecimento.idGeralProduto   =  vidGeralProduto.
                ttgeralfornecimento.valorCompra   =  decimal(jodet:GetJsonObject("prod"):GetCharacter("vUnCom")) NO-ERROR.
                
                RUN sistema/database/geralfornecimento.p (INPUT "PUT", 
                                                   input table ttgeralfornecimento,
                                                   output vmensagem).
                DELETE ttgeralfornecimento.
                if vmensagem <> ? then do:
                    RUN montasaida (400,vmensagem).
                    RETURN.
                end.  
            
                
            END.
            ELSE DO:
                vidGeralProduto = geralprodutos.idGeralProduto.
            END.
            
        CREATE ttprodutos.
        ttprodutos.idGeralProduto   =  vidGeralProduto.
        ttprodutos.idPessoaFornecedor =  fisnota.idPessoaEmitente.
        ttprodutos.refProduto       =  jodet:GetJsonObject("prod"):GetCharacter("cProd") NO-ERROR.
        ttprodutos.nomeProduto      =  jodet:GetJsonObject("prod"):GetCharacter("xProd") NO-ERROR.
        ttprodutos.valorCompra      =  decimal(jodet:GetJsonObject("prod"):GetCharacter("vUnCom")) NO-ERROR.
        ttprodutos.codigoNcm        =  jodet:GetJsonObject("prod"):GetCharacter("NCM") NO-ERROR.
        ttprodutos.codigoCest       =  jodet:GetJsonObject("prod"):GetCharacter("CEST") NO-ERROR.
        
                
        RUN cadastros/database/produtos.p (INPUT "PUT", 
                                           input table ttprodutos,
                                           output vidProduto,
                                           output vmensagem).
        DELETE ttprodutos.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.   
            
        //---------- FISNOTAPRODUTO -------------
        CREATE ttfisnotaproduto.
        ttfisnotaproduto.idNota         =  fisnota.idNota.
        ttfisnotaproduto.nItem          =  int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).
        ttfisnotaproduto.idProduto      =  vidProduto.
        ttfisnotaproduto.quantidade     =  decimal(jodet:GetJsonObject("prod"):GetCharacter("qCom")) NO-ERROR.
        ttfisnotaproduto.unidCom        =  jodet:GetJsonObject("prod"):GetCharacter("uCom") NO-ERROR.
        ttfisnotaproduto.valorUnidade   =  decimal(jodet:GetJsonObject("prod"):GetCharacter("vUnCom")) NO-ERROR.
        ttfisnotaproduto.valorTotal     =  decimal(jodet:GetJsonObject("prod"):GetCharacter("vProd")) NO-ERROR.
        ttfisnotaproduto.cfop           =  jodet:GetJsonObject("prod"):GetCharacter("CFOP") NO-ERROR.
        ttfisnotaproduto.codigoNcm      =  jodet:GetJsonObject("prod"):GetCharacter("NCM") NO-ERROR.
        ttfisnotaproduto.codigoCest     =  jodet:GetJsonObject("prod"):GetCharacter("CEST") NO-ERROR.
        
        
        RUN impostos/database/fisnotaproduto.p (INPUT "PUT", 
                                             input table ttfisnotaproduto,
                                             output vmensagem).
        DELETE ttfisnotaproduto.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.       
      
         
        //---------- FISNOTAPRODUICMS -------------
        /*  for each impostos jodet>joimposto>children() as imposto (getname)
        if imposto = "ICMS" */
        CREATE ttfisnotaproduicms.
        ttfisnotaproduicms.idNota       = fisnota.idNota.
        ttfisnotaproduicms.nItem        = int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).   
        //ttfisnotaproduicms.imposto      = joimposto:GetJsonObject("ICMS"):GetCharacter("ICMS") NO-ERROR.
        //ttfisnotaproduicms.nomeImposto  = joimposto:GetJsonObject("ICMS"):GetCharacter("ICMSSN102") NO-ERROR.
        ttfisnotaproduicms.vTotTrib     = int(joimposto:GetCharacter("vTotTrib")) NO-ERROR.
        ttfisnotaproduicms.orig         = int(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("orig")) NO-ERROR.
        ttfisnotaproduicms.CSOSN        = int(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("CSOSN")) NO-ERROR.
        ttfisnotaproduicms.modBCST      = int(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("modBCST")) NO-ERROR.
        ttfisnotaproduicms.pMVAST       = decimal(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("pMVAST")) NO-ERROR.
        ttfisnotaproduicms.vBCST        = decimal(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("vBCST")) NO-ERROR.
        ttfisnotaproduicms.pICMSST      = decimal(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("pICMSST")) NO-ERROR.
        ttfisnotaproduicms.vICMSST      = decimal(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("vICMSST")) NO-ERROR.
        ttfisnotaproduicms.CST          = joICMS:GetJsonObject("ICMSSN102"):GetCharacter("CST") NO-ERROR.
        ttfisnotaproduicms.modBC        = joimposto:GetJsonObject("ICMSSN102"):GetCharacter("modBC") NO-ERROR.
        ttfisnotaproduicms.vBC          = decimal(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("vBC")) NO-ERROR.
        ttfisnotaproduicms.pICMS        = decimal(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("pICMS")) NO-ERROR.
        ttfisnotaproduicms.vICMS        = decimal(joICMS:GetJsonObject("ICMSSN102"):GetCharacter("vICMS")) NO-ERROR. 
            
        RUN impostos/database/fisnotaproduicms.p (INPUT "PUT", 
                                             input table ttfisnotaproduicms,
                                             output vmensagem).
        DELETE ttfisnotaproduicms.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.     


        //---------- FISNOTAPRODUIMPOSTO -------------
        /* else */
        
        //---------- IPI -------------
        CREATE ttfisnotaproduimposto.
        ttfisnotaproduimposto.idNota        = fisnota.idNota.
        ttfisnotaproduimposto.nItem         = int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).
        //ttfisnotaproduimposto.imposto       = joimposto:GetJsonObject("IPI"):GetCharacter("IPI") NO-ERROR.
        //ttfisnotaproduimposto.nomeImposto   = joimposto:GetJsonObject("IPI"):GetCharacter("IPINT") NO-ERROR
        ttfisnotaproduimposto.cEnq          = decimal(joimposto:GetJsonObject("IPI"):GetCharacter("cEnq")) NO-ERROR.
        ttfisnotaproduimposto.CST           = decimal(joimposto:GetJsonObject("IPI"):GetJsonObject("IPINT"):GetCharacter("CST")) NO-ERROR.
        ttfisnotaproduimposto.vBC           = decimal(joimposto:GetJsonObject("IPI"):GetJsonObject("IPINT"):GetCharacter("vBC")) NO-ERROR.
        ttfisnotaproduimposto.percentual    = decimal(joimposto:GetJsonObject("IPI"):GetCharacter("pIPI")) NO-ERROR.
        ttfisnotaproduimposto.valor         = decimal(joimposto:GetJsonObject("IPI"):GetCharacter("vIPI")) NO-ERROR. 
        

        RUN impostos/database/fisnotaproduimposto.p (INPUT "PUT", 
                                             input table ttfisnotaproduimposto,
                                             output vmensagem).
        DELETE ttfisnotaproduimposto.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.   
        
        
        //---------- PIS -------------
        CREATE ttfisnotaproduimposto.
        ttfisnotaproduimposto.idNota        = fisnota.idNota.
        ttfisnotaproduimposto.nItem         = int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).
        //ttfisnotaproduimposto.imposto       = joimposto:GetJsonObject("PIS"):GetCharacter("PIS") NO-ERROR.
        //ttfisnotaproduimposto.nomeImposto   = joimposto:GetJsonObject("PIS"):GetCharacter("PISNT") NO-ERROR
        ttfisnotaproduimposto.cEnq          = decimal(joimposto:GetJsonObject("PIS"):GetCharacter("cEnq")) NO-ERROR.
        ttfisnotaproduimposto.CST           = decimal(joimposto:GetJsonObject("PIS"):GetJsonObject("PISNT"):GetCharacter("CST")) NO-ERROR.
        ttfisnotaproduimposto.vBC           = decimal(joimposto:GetJsonObject("PIS"):GetJsonObject("PISNT"):GetCharacter("vBC")) NO-ERROR.
        ttfisnotaproduimposto.percentual    = decimal(joimposto:GetJsonObject("PIS"):GetCharacter("PISNT")) NO-ERROR.
        ttfisnotaproduimposto.valor         = decimal(joimposto:GetJsonObject("PIS"):GetCharacter("vPIS")) NO-ERROR.   

        RUN impostos/database/fisnotaproduimposto.p (INPUT "PUT", 
                                             input table ttfisnotaproduimposto,
                                             output vmensagem).
        DELETE ttfisnotaproduimposto.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.   
        
        //---------- COFINS -------------
        CREATE ttfisnotaproduimposto.
        ttfisnotaproduimposto.idNota        = fisnota.idNota.
        ttfisnotaproduimposto.nItem         = int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).
        //ttfisnotaproduimposto.imposto       = joimposto:GetJsonObject("COFINS"):GetCharacter("COFINS") NO-ERROR.
        //ttfisnotaproduimposto.nomeImposto   = joimposto:GetJsonObject("COFINS"):GetCharacter("COFINSNT") NO-ERROR
        ttfisnotaproduimposto.cEnq          = decimal(joimposto:GetJsonObject("COFINS"):GetCharacter("cEnq")) NO-ERROR.
        ttfisnotaproduimposto.CST           = decimal(joimposto:GetJsonObject("COFINS"):GetJsonObject("COFINSNT"):GetCharacter("CST")) NO-ERROR.
        ttfisnotaproduimposto.vBC           = decimal(joimposto:GetJsonObject("COFINS"):GetJsonObject("COFINSNT"):GetCharacter("vBC")) NO-ERROR.
        ttfisnotaproduimposto.percentual    = decimal(joimposto:GetJsonObject("COFINS"):GetCharacter("pCOFINS")) NO-ERROR.
        ttfisnotaproduimposto.valor         = decimal(joimposto:GetJsonObject("COFINS"):GetCharacter("vCOFINS")) NO-ERROR.    
        
        

        RUN impostos/database/fisnotaproduimposto.p (INPUT "PUT", 
                                             input table ttfisnotaproduimposto,
                                             output vmensagem).
        DELETE ttfisnotaproduimposto.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.    
        
        
    //---------- FISNOTA -------------
    CREATE ttfisnota.
    ttfisnota.idNota = fisnota.idNota.

    RUN impostos/database/fisnota.p (INPUT "POST", 
                                    input table ttfisnota,
                                    output vidNota,
                                    output vmensagem).
    DELETE ttfisnota.
    if vmensagem <> ? then do:
        RUN montasaida (400,vmensagem).
        RETURN.
    end.             

    

    
    
    
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
