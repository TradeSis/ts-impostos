

/** Carrega bibliotecas necessarias **/
using OpenEdge.Net.HTTP.IHttpClientLibrary.
using OpenEdge.Net.HTTP.ConfigBuilder.
using OpenEdge.Net.HTTP.ClientBuilder.
using OpenEdge.Net.HTTP.Credentials.
using OpenEdge.Net.HTTP.IHttpClient.
using OpenEdge.Net.HTTP.IHttpRequest.
using OpenEdge.Net.HTTP.RequestBuilder.
using OpenEdge.Net.URI.
using OpenEdge.Net.HTTP.IHttpResponse.
using Progress.Json.ObjectModel.JsonObject.
using Progress.Json.ObjectModel.JsonArray.
using Progress.Json.ObjectModel.ObjectModelParser.

def VAR netClient        AS IHttpClient        no-undo.
def VAR netUri           as URI                no-undo.
def VAR netRequest       as IHttpRequest       no-undo.
def VAR netResponse      as IHttpResponse      no-undo.

DEFINE VARIABLE hRequest AS HANDLE NO-UNDO.
DEFINE VARIABLE hResponse AS HANDLE NO-UNDO.

DEFINE VARIABLE joRequest AS JsonObject NO-UNDO.
DEFINE VARIABLE jaRequest AS JsonArray NO-UNDO.
DEFINE VARIABLE joResponse AS JsonObject NO-UNDO.
DEFINE VARIABLE jaResponse AS JsonArray NO-UNDO.
DEFINE VARIABLE lReturnValue AS LOGICAL NO-UNDO.
DEFINE VARIABLE lcJsonRequest    AS LONGCHAR NO-UNDO.
DEFINE VARIABLE lcJsonResponse   AS LONGCHAR NO-UNDO.
  
   
DEFINE VARIABLE joImendes      AS JsonObject NO-UNDO.
DEFINE VARIABLE joEmit         AS JsonObject NO-UNDO.
DEFINE VARIABLE joPerfil       AS JsonObject NO-UNDO.                                                  
DEFINE VARIABLE jaUF           AS JsonArray NO-UNDO.
DEFINE VARIABLE jaProdutos     AS JsonArray NO-UNDO.                                                  
DEFINE VARIABLE joProduto      AS JsonObject NO-UNDO.
DEFINE VARIABLE jaCarac        AS JsonArray NO-UNDO.
DEFINE VARIABLE joHeaders   AS JsonObject NO-UNDO.

def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def TEMP-TABLE ttentrada NO-UNDO   /* JSON ENTRADA */
    field idEmpresa      AS INT
    field idGeralProduto      AS INT.

DEF VAR vsimplesN AS CHAR.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


/* VARIAVEL TEMPORARIA*/
  /* DEF VAR vidEmpresa AS INT.
  DEF VAR vidGeralProduto AS INT.   */
  /* PARAMETROS DE TESTE */
  /* vidEmpresa = 1.
  vidGeralProduto = 188. */

/* APIFISCAL */
FIND apifiscal WHERE apifiscal.idEmpresa = ttentrada.idEmpresa NO-LOCK.  /* ttentrada.idEmpresa */
 
 /* IF apifiscal.login = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "apifiscal.login não informado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.
    
   IF apifiscal.senha = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "apifiscal.senha não informado".
        DISP ttsaida.

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.
    
   IF apifiscal.tpAmb = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "apifiscal.tpAmb não informado".
        DISP ttsaida.

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.
    
   IF apifiscal.cfopEntrada = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "apifiscal.cfopEntrada não informado".
        DISP ttsaida.

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.
    
   IF apifiscal.finalidade = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "apifiscal.finalidade não informado".
        DISP ttsaida.

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end. */

/* EMPRESA */
FIND empresa WHERE empresa.idEmpresa = ttentrada.idEmpresa NO-LOCK.  /* ttentrada.idEmpresa */
 
/* GERAL PESSOAS */
FIND geralpessoas WHERE geralpessoas.cpfCnpj = empresa.cnpj NO-LOCK.

/* IF geralpessoas.cnae = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralpessoas.cnae não informado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.

IF geralpessoas.regimeEspecial = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralpessoas.regimeEspecial não informado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.
    
IF geralpessoas.regimeTrib = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralpessoas.regimeTrib não informado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.
    
IF geralpessoas.crt = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralpessoas.crt não informado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.
 
IF geralpessoas.origem = ?
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "geralpessoas.origem não informado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end. 
     */
vsimplesN = "".

IF  geralpessoas.regimeTrib = 'SN' 
THEN DO:
    vsimplesN = "S".
END.
ELSE DO:
   vsimplesN = "N".
END.    

/* GERAL PRODUTOS  OBS PODE TER VARIOS PRODUTOS*/
FIND geralprodutos WHERE geralprodutos.idGeralProduto = ttentrada.idGeralProduto NO-LOCK. /* ttentrada.idGeralProduto */

/* JSON DE REQUEST */       
joEmit = NEW JsonObject().
joEmit:ADD("amb",apifiscal.tpAmb).
joEmit:ADD("cnpj",geralpessoas.cpfCnpj).
joEmit:ADD("crt",geralpessoas.crt).
joEmit:ADD("regimeTrib",geralpessoas.regimeTrib). /* tratar */
joEmit:ADD("uf",geralpessoas.codigoEstado).
joEmit:ADD("cnae",geralpessoas.cnae).
joEmit:ADD("regimeEspecial",geralpessoas.regimeEspecial).
joEmit:ADD("substlCMS","N").  // - Verificar com Daniel
joEmit:ADD("interdependente","N").  // - Verificar com Daniel                                                   

jaUF = NEW JsonArray().
jaUF:ADD(geralpessoas.codigoEstado).

jaCarac = NEW JsonArray().
jaCarac:ADD(geralpessoas.caracTrib). 

joPerfil = NEW JsonObject().
joPerfil:ADD("uf",jaUF).
joPerfil:ADD("cfop",apifiscal.cfopEntrada).
joPerfil:ADD("caracTrib",jaCarac). 
joPerfil:ADD("finalidade",apifiscal.finalidade).
joPerfil:ADD("simplesN",vsimplesN).
joPerfil:ADD("origem",geralpessoas.origem).
joPerfil:ADD("substlCMS","N").
joPerfil:ADD("prodZFM","N").

joProduto = NEW JsonObject().
joProduto:ADD("codigo",""). /* 7891960708166 */
joProduto:ADD("codInterno","N").
joProduto:ADD("descricao",geralprodutos.nomeProduto).
joProduto:ADD("ncm","111111").

jaProdutos = NEW JsonArray().
jaProdutos:ADD(joProduto).

joHeaders = NEW JsonObject().
joHeaders:ADD("Content-Type","application/json").
joHeaders:ADD("login",apifiscal.login).
joHeaders:ADD("senha",apifiscal.senha).

joImendes = NEW JsonObject().
joImendes:ADD("emit",joEmit).
joImendes:ADD("perfil",joPerfil).
joImendes:ADD("produtos",jaProdutos).
joImendes:ADD("headers",joHeaders).



joImendes:Write(lcJsonRequest).
put unformatted string(lcJsonRequest).
/* MESSAGE string(lcJsonRequest). */

 

/******************************
/* INI - requisicao web */
ASSIGN netClient   = ClientBuilder:Build():Client       
       netUri      = new URI("http", "localhost") /* URI("metodo", "dominio", "porta") */
       netUri:Path = "/api/sistema/estados".     

//FAZ A REQUISIÇÃO
netRequest  = RequestBuilder:GET(netUri, joRequest):REQUEST.
netResponse = netClient:EXECUTE(netRequest).

//TRATA RETORNO
if type-of(netResponse:Entity, JsonObject) then do:
    joResponse = CAST(netResponse:Entity, JsonObject).
    joResponse:Write(lcJsonResponse).
END.
if type-of(netResponse:Entity, JsonArray) then do:
    jaResponse = CAST(netResponse:Entity, JsonArray).
    jaResponse:Write(lcJsonResponse).
END.

MESSAGE string(lcJsonResponse) VIEW-AS ALERT-BOX.

hResponse = TEMP-TABLE ttestados:HANDLE.   
lReturnValue = hResponse:READ-JSON("longchar", lcJsonResponse, "EMPTY") NO-ERROR.

FOR EACH ttestados:
    DISP ttestados.
END.
********************************/    


