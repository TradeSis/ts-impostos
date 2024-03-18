

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

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def TEMP-TABLE ttentrada NO-UNDO   /* JSON ENTRADA */
    field idEmpresa      AS INT
    field idGeralProduto      AS INT.
                        
DEF BUFFER bgeralpessoasfornecedor FOR geralpessoas.

DEF VAR vsimplesN AS CHAR.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

/* APIFISCAL */
FIND apifiscal WHERE apifiscal.idEmpresa = ttentrada.idEmpresa NO-LOCK.  

/* EMPRESA */
FIND empresa WHERE empresa.idEmpresa = ttentrada.idEmpresa NO-LOCK.  
 
/* GERAL PESSOAS */
FIND geralpessoas WHERE geralpessoas.cpfCnpj = empresa.cnpj NO-LOCK.


vsimplesN = "".

IF  geralpessoas.regimeTrib = 'SN' 
THEN DO:
    vsimplesN = "S".
END.
ELSE DO:
   vsimplesN = "N".
END.    

/* GERAL PRODUTOS */
FIND geralprodutos WHERE geralprodutos.idGeralProduto = ttentrada.idGeralProduto NO-LOCK. 


/* JSON DE REQUEST */       
joEmit = NEW JsonObject().
joEmit:ADD("amb",apifiscal.tpAmb).
joEmit:ADD("cnpj",geralpessoas.cpfCnpj).
joEmit:ADD("crt",geralpessoas.crt).
joEmit:ADD("regimeTrib",geralpessoas.regimeTrib). 
joEmit:ADD("uf",geralpessoas.codigoEstado).
joEmit:ADD("cnae",geralpessoas.cnae).
joEmit:ADD("regimeEspecial",geralpessoas.regimeEspecial).
joEmit:ADD("substlCMS","N").  // - Verificar com Daniel
joEmit:ADD("interdependente","N").  // - Verificar com Daniel                                                   

jaUF = NEW JsonArray().
jaCarac = NEW JsonArray().

/* GERAL FORNECIMENTO */
FOR EACH geralfornecimento WHERE geralfornecimento.idGeralProduto = geralprodutos.idGeralProduto NO-LOCK.
    
    FIND bgeralpessoasfornecedor WHERE bgeralpessoasfornecedor.cpfCnpj = geralfornecimento.Cnpj NO-LOCK.
        jaUF:ADD(bgeralpessoasfornecedor.codigoEstado).
        jaCarac:ADD(bgeralpessoasfornecedor.caracTrib).
        
END.
 

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
joProduto:ADD("codigo","7891960708166"). /* 7891960708166 */
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


/* INI - requisicao web */
ASSIGN netClient   = ClientBuilder:Build():Client       
       netUri      = new URI("http", "consultatributos.com.br",8080) /* URI("metodo", "dominio", "porta") */
       netUri:Path = "/api/v3/public/SaneamentoGrades".     


//FAZ A REQUISIÇÃO
// ANTIGO netRequest  = RequestBuilder:POST(netUri, joImendes):REQUEST.
netRequest = RequestBuilder:POST (netUri, joImendes)
                     :AcceptJson()
                     :AddHeader("login", apifiscal.login)
                     :AddHeader("senha", apifiscal.senha)
                     :ContentType("application/json":U)
                     :REQUEST.

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

put unformatted string(lcJsonResponse). 

