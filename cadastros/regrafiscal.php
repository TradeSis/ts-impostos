<?php
// lucas 15012024 criado
include_once(__DIR__ . '/../header.php');

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="container-fluid">

        <div class="row ">
            <BR>
            <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <BR> <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

            <div class="col-6 col-lg-6">
                <h2 class="ts-tituloPrincipal">Regra Fiscal</h2>
            </div>

            <div class="col-6 col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaCodigoGrupo" placeholder="Buscar por código">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
                    <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirGrupoProdutoModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                </div>
            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalRegraFiscal" tabindex="-1" aria-labelledby="modalRegraFiscalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Regra Fiscal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md">
                                <label class="form-label ts-label">codRegra</label>
                                <input type="text" class="form-control ts-input" name="codRegra" id="codRegra_regrafiscal" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">codExcecao</label>
                                <input type="text" class="form-control ts-input" name="codExcecao" id="codExcecao_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">dtVigIni</label>
                                <input type="text" class="form-control ts-input" name="dtVigIni" id="dtVigIni_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">dtVigFin</label>
                                <input type="text" class="form-control ts-input" name="dtVigFin" id="dtVigFin_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">cFOPCaracTrib</label>
                                <input type="text" class="form-control ts-input" name="cFOPCaracTrib" id="cFOPCaracTrib_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">cST</label>
                                <input type="text" class="form-control ts-input" name="cST" id="cST_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">cSOSN</label>
                                <input type="text" class="form-control ts-input" name="cSOSN" id="cSOSN_regrafiscal" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">aliqIcmsInterna</label>
                                <input type="text" class="form-control ts-input" name="aliqIcmsInterna" id="aliqIcmsInterna_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">aliqIcmsInterestadual</label>
                                <input type="text" class="form-control ts-input" name="aliqIcmsInterestadual" id="aliqIcmsInterestadual_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">reducaoBcIcms</label>
                                <input type="text" class="form-control ts-input" name="reducaoBcIcms" id="reducaoBcIcms_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">reducaoBcIcmsSt</label>
                                <input type="text" class="form-control ts-input" name="reducaoBcIcmsSt" id="reducaoBcIcmsSt_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">redBcICMsInterestadual</label>
                                <input type="text" class="form-control ts-input" name="redBcICMsInterestadual" id="redBcICMsInterestadual_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">aliqIcmsSt</label>
                                <input type="text" class="form-control ts-input" name="aliqIcmsSt" id="aliqIcmsSt_regrafiscal" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">iVA</label>
                                <input type="text" class="form-control ts-input" name="iVA" id="iVA_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">iVAAjust</label>
                                <input type="text" class="form-control ts-input" name="iVAAjust" id="iVAAjust_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">fCP</label>
                                <input type="text" class="form-control ts-input" name="fCP" id="fCP_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">codBenef</label>
                                <input type="text" class="form-control ts-input" name="codBenef" id="codBenef_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">pDifer</label>
                                <input type="text" class="form-control ts-input" name="pDifer" id="pDifer_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">pIsencao</label>
                                <input type="text" class="form-control ts-input" name="pIsencao" id="pIsencao_regrafiscal" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">antecipado</label>
                                <input type="text" class="form-control ts-input" name="antecipado" id="antecipado_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">desonerado</label>
                                <input type="text" class="form-control ts-input" name="desonerado" id="desonerado_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">pICMSDeson</label>
                                <input type="text" class="form-control ts-input" name="pICMSDeson" id="pICMSDeson_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">isento</label>
                                <input type="text" class="form-control ts-input" name="isento" id="isento_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">tpCalcDifal</label>
                                <input type="text" class="form-control ts-input" name="tpCalcDifal" id="tpCalcDifal_regrafiscal" readonly>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label ts-label">ampLegal</label>
                                <input type="text" class="form-control ts-input" name="ampLegal" id="ampLegal_regrafiscal_regrafiscal" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label ts-label">Protocolo</label>
                                <input type="text" class="form-control ts-input" name="Protocolo" id="Protocolo_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">Convenio</label>
                                <input type="text" class="form-control ts-input" name="Convenio" id="Convenio_regrafiscal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">regraGeral</label>
                                <input type="text" class="form-control ts-input" name="regraGeral" id="regraGeral_regrafiscal" readonly>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>codRegra</th>
                        <th>codExcecao</th>
                        <th>cFOPCaracTrib</th>
                        <th>cST</th>
                        <th>cSOSN</th>
                        <th>aliqIcmsInterna</th>
                        <th>aliqIcmsInterestadual</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>


    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        buscar($("#buscaCodigoGrupo").val());

        function limpar() {
            buscar(null, null, null, null);
            window.location.reload();
        }

        function buscar(buscaCodigoGrupo) {
            //alert(buscaCodigoGrupo);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/regrafiscal.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    codRegra: buscaCodigoGrupo
                },
                success: function(msg) {
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.codRegra + "</td>";
                        linha = linha + "<td>" + object.codExcecao + "</td>";
                        linha = linha + "<td>" + object.cFOPCaracTrib + "</td>";
                        linha = linha + "<td>" + object.cST + "</td>";
                        linha = linha + "<td>" + object.cSOSN + "</td>";
                        linha = linha + "<td>" + object.aliqIcmsInterna + "</td>";
                        linha = linha + "<td>" + object.aliqIcmsInterestadual + "</td>";
                        linha = linha + "<td>" + "<button type='button' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#modalRegraFiscal' data-codRegra='" + object.codRegra + "'><i class='bi bi-eye'></i></button> ";

                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscaCodigoGrupo").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscaCodigoGrupo").val());
            }
        });

        $(document).on('click', 'button[data-bs-target="#modalRegraFiscal"]', function() {
            var codRegra = $(this).attr("data-codRegra");

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/regrafiscal.php?operacao=filtrar',
                data: {
                    codRegra: codRegra
                },
                success: function(data) {
                    $('#codRegra_regrafiscal').val(data.codRegra);
                    $('#codExcecao_regrafiscal').val(data.codExcecao);
                    $('#dtVigIni_regrafiscal').val(data.dtVigIniFormatada);
                    $('#dtVigFin_regrafiscal').val(data.dtVigFinFormatada);
                    $('#cFOPCaracTrib_regrafiscal').val(data.cFOPCaracTrib);
                    $('#cST_regrafiscal').val(data.cST);
                    $('#cSOSN_regrafiscal').val(data.cSOSN);
                    $('#aliqIcmsInterna_regrafiscal').val(data.aliqIcmsInterna);
                    $('#aliqIcmsInterestadual_regrafiscal').val(data.aliqIcmsInterestadual);
                    $('#reducaoBcIcms_regrafiscal').val(data.reducaoBcIcms);
                    $('#reducaoBcIcmsSt_regrafiscal').val(data.reducaoBcIcmsSt);
                    $('#redBcICMsInterestadual_regrafiscal').val(data.redBcICMsInterestadual);
                    $('#aliqIcmsSt_regrafiscal').val(data.aliqIcmsSt);
                    $('#iVA_regrafiscal').val(data.iVA);
                    $('#iVAAjust_regrafiscal').val(data.iVAAjust);
                    $('#fCP_regrafiscal').val(data.fCP);
                    $('#codBenef_regrafiscal').val(data.codBenef);
                    $('#pDifer_regrafiscal').val(data.pDifer);
                    $('#pIsencao_regrafiscal').val(data.pIsencao);
                    $('#antecipado_regrafiscal').val(data.antecipado);
                    $('#desonerado_regrafiscal').val(data.desonerado);
                    $('#pICMSDeson_regrafiscal').val(data.pICMSDeson);
                    $('#isento_regrafiscal').val(data.isento);
                    $('#tpCalcDifal_regrafiscal').val(data.tpCalcDifal);
                    $('#ampLegal_regrafiscal_regrafiscal').val(data.ampLegal);
                    $('#Protocolo_regrafiscal').val(data.Protocolo);
                    $('#Convenio_regrafiscal').val(data.Convenio);
                    $('#regraGeral_regrafiscal').val(data.regraGeral);

                    $('#modalRegraFiscal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("ERRO=" + JSON.stringify(error));
                }

            });
        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>