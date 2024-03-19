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

DEF VAR joCabecalho         AS  JsonObject NO-UNDO.
DEF VAR jaGrupos         AS  JsonArray NO-UNDO.
DEF VAR joGrupo             AS  JsonObject.
DEF VAR lcJsonauxiliar      AS   LONGCHAR NO-UNDO.
DEF VAR jaRegras            AS  JsonArray NO-UNDO.
DEF VAR joRegra             AS  JsonObject NO-UNDO.
DEF VAR jauFs            AS  JsonArray NO-UNDO. 
DEF VAR jouF             AS  JsonObject NO-UNDO.
DEF VAR joCFOP      AS  JsonObject NO-UNDO.
DEF VAR jacaracTib     AS  JsonArray NO-UNDO.
DEF VAR jocaracTib      AS  JsonObject NO-UNDO.


def /*input param*/ VAR vlcentrada as longchar. /* JSON ENTRADA */
def /*input param*/ VAR vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def TEMP-TABLE ttentrada NO-UNDO   /* JSON ENTRADA */
    field idEmpresa      AS INT
    field idGeralProduto      AS INT.
    
def temp-table ttapihistorico NO-UNDO 
    field sugestao  AS CHAR
    field amb  AS INT
    field cnpj  AS CHAR
    field dthr  AS CHAR
    field transacao  AS CHAR
    field mensagem  AS CHAR
    field prodEnv  AS INT
    field prodRet  AS INT
    field prodNaoRet  AS INT
    field comportamentosParceiro  AS CHAR
    field comportamentosCliente  AS CHAR
    field versao  AS CHAR
    field duracao  AS CHAR.

def temp-table ttgrupos no-undo serialize-name "fiscalgrupo"   
    LIKE fiscalgrupo.

//---------- REGRAS-------------
DEF TEMP-TABLE ttregra NO-UNDO
    field codRegra  AS CHAR
    field codExcecao  AS CHAR
    field dtVigIni  AS CHAR
    field dtVigFin  AS CHAR
    field cFOPCaracTrib  AS CHAR
    field cST  AS CHAR
    field cSOSN  AS CHAR
    field aliqIcmsInterna  AS DECIMAL
    field aliqIcmsInterestadual  AS DECIMAL
    field reducaoBcIcms  AS DECIMAL
    field reducaoBcIcmsSt  AS DECIMAL
    field redBcICMsInterestadual  AS DECIMAL
    field aliqIcmsSt  AS DECIMAL
    field iVA  AS DECIMAL
    field iVAAjust  AS DECIMAL
    field fCP  AS DECIMAL
    field codBenef  AS CHAR
    field pDifer  AS DECIMAL
    field pIsencao  AS DECIMAL
    field antecipado  AS CHAR
    field desonerado  AS CHAR
    field pICMSDeson  AS DECIMAL
    field isento  AS CHAR
    field tpCalcDifal  AS INT
    field ampLegal  AS CHAR
    //field Protocolo  AS CHAR
    //field Convenio  AS CHAR
    field regraGeral  AS CHAR.

//---------- OPERACAO-------------

def temp-table ttoperacao no-undo serialize-name "fiscaloperacao"   
    LIKE fiscaloperacao.                          

// -------------------TESTE ENTRADA   
CREATE  ttentrada.
ttentrada.idEmpresa = 1.
ttentrada.idGeralProduto = 5.

// -------------------FIM TESTE ENTRADA

DEF BUFFER bgeralpessoasfornecedor FOR geralpessoas.
 
DEF VAR vsimplesN AS CHAR.
DEF VAR vcodRegra AS CHAR.
DEF VAR vcodExcecao AS CHAR.
DEF VAR vidRegra AS INT.
DEF VAR vcodigoGrupo AS CHAR.
DEF VAR vcodigoEstado AS CHAR.
DEF VAR vcFOP AS CHAR.
DEF VAR vcodigoCaracTrib AS CHAR.
DEF VAR vfinalidade AS CHAR.
DEF VAR vidGrupo AS INT.
DEF VAR vmensagem AS CHAR.

//variaveis de contador
DEF VAR iGrupos AS INT.   
DEF VAR iRegras AS INT.
DEF VAR iuFs AS INT.
DEF VAR icaracTib AS INT.



hEntrada = temp-table ttentrada:HANDLE.
//lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


/* APIFISCAL */
FIND apifiscal WHERE apifiscal.idEmpresa = ttentrada.idempresa NO-LOCK.  

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

joImendes = NEW JsonObject().
joImendes:ADD("emit",joEmit).
joImendes:ADD("perfil",joPerfil).
joImendes:ADD("produtos",jaProdutos).




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
    
    joCabecalho = joResponse:GetJsonObject("Cabecalho").
    joCabecalho:Write(lcJsonauxiliar, TRUE).
    //MESSAGE STRING(lcJsonauxiliar) view-as alert-box.    

    //MESSAGE joCabecalho:GetCharacter("cnpj")  joCabecalho:GetCharacter("dthr") VIEW-AS ALERT-BOX.
        
    CREATE ttapihistorico.
    ttapihistorico.sugestao       =     joCabecalho:GetCharacter("sugestao").
    ttapihistorico.amb       =     joCabecalho:GetInteger("amb").
    ttapihistorico.cnpj       =     joCabecalho:GetCharacter("cnpj").
    ttapihistorico.dthr       =     joCabecalho:GetCharacter("dthr").
    ttapihistorico.transacao  =     joCabecalho:GetCharacter("transacao").
    ttapihistorico.mensagem  =     joCabecalho:GetCharacter("mensagem").
    ttapihistorico.prodEnv  =     joCabecalho:GetInteger("prodEnv").
    ttapihistorico.prodRet  =     joCabecalho:GetInteger("prodRet").
    ttapihistorico.prodNaoRet  =     joCabecalho:GetInteger("prodNaoRet").
    ttapihistorico.comportamentosParceiro  =     joCabecalho:GetCharacter("comportamentosParceiro").
    ttapihistorico.comportamentosCliente  =     joCabecalho:GetCharacter("comportamentosCliente").
    ttapihistorico.versao  =     joCabecalho:GetCharacter("versao").
    ttapihistorico.duracao  =     joCabecalho:GetCharacter("duracao").

     /* leitura de grupos */
    jaGrupos = joResponse:GetJsonArray("Grupos").
    //jaGrupos:Write(lcJsonauxiliar, TRUE).
    //MESSAGE jaGrupos:LENGTH STRING(lcJsonauxiliar) view-as alert-box.    
    DO iGrupos = 1 to jaGrupos:length on error undo, next:
        joGrupo = jaGrupos:GetJsonObject(iGrupos).
        //joGrupo:Write(lcJsonauxiliar, TRUE).
        //MESSAGE STRING(lcJsonauxiliar) view-as alert-box.   
         
        vcodigoGrupo = joGrupo:GetCharacter("codigo").
                 
        IF vcodigoGrupo = ?  
        THEN NEXT. /* Validacao Erro do Json */
         
        find fiscalgrupo where fiscalgrupo.codigoGrupo = vcodigoGrupo  no-lock no-error.
        if avail fiscalgrupo then do:
            vidgrupo = fiscalgrupo.idgrupo.            
        end.
        else do:
            CREATE ttgrupos.
            ttgrupos.codigoGrupo = joGrupo:GetCharacter("codigo").
            ttgrupos.nomeGrupo = joGrupo:GetCharacter("descricao").
            ttgrupos.codigoNcm = joGrupo:GetCharacter("nCM").
            ttgrupos.codigoCest = joGrupo:GetCharacter("cEST").
            ttgrupos.impostoImportacao = joGrupo:GetDecimal("impostoImportacao").   
            ttgrupos.piscofinscstEnt = joGrupo:GetJsonObject("pisCofins"):GetCharacter("cstEnt").
            ttgrupos.piscofinscstSai = joGrupo:GetJsonObject("pisCofins"):GetCharacter("cstSai").
            ttgrupos.aliqPis = joGrupo:GetJsonObject("pisCofins"):GetInteger("aliqPis").
            ttgrupos.aliqCofins = joGrupo:GetJsonObject("pisCofins"):GetInteger("aliqCofins").
            ttgrupos.nri = joGrupo:GetJsonObject("pisCofins"):GetCharacter("nri").
            ttgrupos.ampLegal = joGrupo:GetJsonObject("pisCofins"):GetCharacter("ampLegal").
            ttgrupos.redPis = joGrupo:GetJsonObject("pisCofins"):GetDecimal("redPis").
            ttgrupos.redCofins = joGrupo:GetJsonObject("pisCofins"):GetDecimal("redCofins").
            ttgrupos.ipicstEnt = joGrupo:GetJsonObject("iPI"):GetCharacter("cstEnt").
            ttgrupos.ipicstSai = joGrupo:GetJsonObject("iPI"):GetCharacter("cstSai").
            ttgrupos.aliqipi = joGrupo:GetJsonObject("iPI"):GetDecimal("aliqipi").  
            ttgrupos.codenq = joGrupo:GetJsonObject("iPI"):GetCharacter("codenq").
            ttgrupos.ipiex = joGrupo:GetJsonObject("iPI"):GetCharacter("ex").
            vidgrupo = 0.
            RUN impostos/database/fiscalgrupo-inc.p (input table ttentrada, 
                                                     output vidgrupo,
                                                     output vmensagem).
            DELETE ttgrupos.
            if vmensagem <> ? then do:
                message "ERRO AO CRIAR FISCALGRUPO - " vmensagem view-as alert-box.
                return.
            end.
            find fiscalgrupo where fiscalgrupo.idgrupo = vidgrupo no-lock.
        END.
        // avail fiscal grupo e vidgrupo                                                                 
           
            // INSERI GRUPOPRODUTO
            // ADICIONA REFRAFISCAL
            // ATUALIZAPRODUTO
            
         
        
        jaRegras = joGrupo:GetJsonArray("Regras").
        //jaRegras:Write(lcJsonauxiliar, TRUE).
         //MESSAGE STRING(lcJsonauxiliar) view-as alert-box.
        DO iRegras = 1 to jaRegras:length on error undo, next:
            joRegra = jaRegras:GetJsonObject(iRegras).
            //joRegra:Write(lcJsonauxiliar, TRUE).
            //MESSAGE STRING(lcJsonauxiliar) view-as alert-box.
            
            jauFs = joRegra:GetJsonArray("uFs").
            //jauFs:Write(lcJsonauxiliar, TRUE).
                 //MESSAGE STRING(lcJsonauxiliar) view-as alert-box.
            DO iuFs = 1 to jauFs:length on error undo, next:
                jouF = jauFs:GetJsonObject(iuFs).
                //jouF:Write(lcJsonauxiliar, TRUE).
                vcodigoEstado = jouF:GetCharacter("uF").                            
                        
                joCFOP = jouF:GetJsonObject("CFOP").
                //joCFOP:Write(lcJsonauxiliar, TRUE).
                vcFOP = joCFOP:GetCharacter("cFOP").                                
                            
                jacaracTib = joCFOP:GetJsonArray("CaracTrib").
                //jacaracTib:Write(lcJsonauxiliar, TRUE).
                                 
                DO icaracTib = 1 to jacaracTib:length on error undo, next:
                    jocaracTib = jacaracTib:GetJsonObject(icaracTib).
                    //jocaracTib:Write(lcJsonauxiliar, TRUE).
                                        
                    vcodigoCaracTrib = jocaracTib:GetCharacter("codigo").
                    vfinalidade = jocaracTib:GetCharacter("finalidade").
                    vcodRegra = jocaracTib:GetCharacter("codRegra").
                    vcodExcecao = STRING(jocaracTib:GetInteger("codExcecao")).
                                           
                    IF vcodRegra <> ? AND vcodExcecao <> ? 
                    THEN DO:
                        FIND fiscalregra where fiscalregra.codRegra = vcodRegra AND fiscalregra.codExcecao = vcodExcecao  no-lock no-error.
                        IF avail fiscalregra
                        then do:
                            vidRegra = fiscalregra.idRegra.
                        end.
                        else do:
                            CREATE ttregra.
                            ttregra.codRegra = jocaracTib:GetCharacter("codRegra").
                            ttregra.codExcecao = vcodExcecao.
                            ttregra.dtVigIni = jocaracTib:GetCharacter("dtVigIni").
                            ttregra.dtVigFin = jocaracTib:GetCharacter("dtVigFin").
                            ttregra.cFOPCaracTrib = jocaracTib:GetCharacter("cFOP").
                            ttregra.cST = jocaracTib:GetCharacter("cST").
                            ttregra.cSOSN = jocaracTib:GetCharacter("cSOSN").
                            ttregra.aliqIcmsInterna = jocaracTib:GetDecimal("aliqIcmsInterna").
                            ttregra.aliqIcmsInterestadual = jocaracTib:GetDecimal("aliqIcmsInterestadual").
                            ttregra.reducaoBcIcms = jocaracTib:GetDecimal("reducaoBcIcms").
                            ttregra.reducaoBcIcmsSt = jocaracTib:GetDecimal("reducaoBcIcmsSt").
                            ttregra.redBcICMsInterestadual = jocaracTib:GetDecimal("redBcICMsInterestadual").
                            ttregra.aliqIcmsSt = jocaracTib:GetDecimal("aliqIcmsSt").
                            ttregra.iVA = jocaracTib:GetDecimal("iVA").
                            ttregra.iVAAjust = jocaracTib:GetDecimal("iVAAjust").
                            ttregra.fCP = jocaracTib:GetDecimal("fCP").
                            ttregra.codBenef = jocaracTib:GetCharacter("codBenef").
                            ttregra.pDifer = jocaracTib:GetDecimal("pDifer").
                            ttregra.pIsencao = jocaracTib:GetDecimal("pIsencao").
                            ttregra.antecipado = jocaracTib:GetCharacter("antecipado").
                            ttregra.desonerado = jocaracTib:GetCharacter("desonerado").
                            ttregra.pICMSDeson = jocaracTib:GetDecimal("pICMSDeson").
                            ttregra.isento = jocaracTib:GetCharacter("isento").
                            ttregra.tpCalcDifal = jocaracTib:GetInteger("tpCalcDifal").
                            ttregra.ampLegal = jocaracTib:GetCharacter("ampLegal").
                            //ttregra.Protocolo = jocaracTib:GetCharacter("Protocolo").
                            //ttregra.Convenio = jocaracTib:GetCharacter("Convenio").
                            ttregra.regraGeral = jocaracTib:GetCharacter("regraGeral").
                            
                            vidRegra = 0.
                            RUN impostos/database/fiscalgrupo-inc.p (input table ttentrada, 
                                                                     output vidRegra,
                                                                     output vmensagem).
                            DELETE ttregra.
                            if vmensagem <> ? then do:
                                message "ERRO AO CRIAR REGRAFISAL - " vmensagem view-as alert-box.
                                return.
                            end.
                            find fiscalregra where fiscalregra.idRegra = vidRegra no-lock.
                        end.   
                    END.    
                    //MESSAGE vcodigoEstado vcFOP  vcodigoCaracTrib  vfinalidade view-as alert-box.
                                     
                    IF vidgrupo <> ? AND vcodigoEstado <> ? AND vcFOP <> ? AND vcodigoCaracTrib <> ? AND vfinalidade <> ?
                    THEN DO:
                        find fiscaloperacao where 
                                            fiscaloperacao.idGrupo = vidgrupo AND
                                            fiscaloperacao.codigoEstado = vcodigoEstado AND 
                                            fiscaloperacao.cFOP = vcFOP AND 
                                            fiscaloperacao.finalidade = vfinalidade  
                                            no-lock no-error.
                        IF avail fiscaloperacao
                        then do:
                            NEXT.
                        end.     
                        else do:
                            CREATE ttoperacao.
                            ttoperacao.idGrupo = vidgrupo.
                            ttoperacao.codigoEstado = vcodigoEstado.
                            ttoperacao.cFOP = vcFOP.
                            ttoperacao.codigoCaracTrib = vcodigoCaracTrib.
                            ttoperacao.finalidade = vfinalidade.
                            ttoperacao.idRegra = vidRegra.
            
                            RUN impostos/database/operacaofiscal-inc.p (input table ttentrada, 
                                                                        output vmensagem).
                            DELETE ttoperacao.
                            if vmensagem <> ? then do:
                                message "ERRO AO CRIAR OPERACAOFISCAL - " vmensagem view-as alert-box.
                                return.
                            end.
                        end.
                        
                        
                    END. 
                end.  /* icaracTib */  
            end.  /* iuFs */
        end. /* iRegras */
    end. /* iGrupos */ 
END.

/*
if type-of(netResponse:Entity, JsonArray) then do:
    jaResponse = CAST(netResponse:Entity, JsonArray).
    jaResponse:Write(lcJsonResponse).
END.
*/
/* criar ttsaida
put unformatted string(lcJsonResponse). 
*/

