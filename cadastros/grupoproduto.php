<?php
//Lucas 29022024 - id862 Empresa Administradora
// lucas 27122023 criado
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
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!--<BR> BOTOES AUXILIARES -->
        </div>
        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

            <div class="col-6 col-lg-6">
                <h2 class="ts-tituloPrincipal">Grupo Produto</h2>
            </div>

            
            <div class="col-6 col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaGrupoProduto" placeholder="Buscar por cÃ³digo ou nome">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
                    <!-- Lucas 29022024 - condição Administradora -->
                    <?php if ($_SESSION['administradora'] == 1) { ?> 
                    <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirGrupoProdutoModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                    <?php } ?>
                </div>
            </div>

        </div>

        <div class="modal fade bd-example-modal-lg" id="visualizarGrupoProdutoModal" tabindex="-1" aria-labelledby="visualizarGrupoProdutoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Grupo - </h5>&nbsp;<h5 class="modal-title" id="textoCodigoGrupo"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label ts-label">codigoGrupo</label>
                                <input type="text" class="form-control ts-input" name="codigoGrupo" id="codigoGrupo" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">nomeGrupo</label>
                                <input type="text" class="form-control ts-input" name="nomeGrupo" id="nomeGrupo" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label ts-label">codigoNcm</label>
                                <input type="text" class="form-control ts-input" name="codigoNcm" id="codigoNcm" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">codigoCest</label>
                                <input type="text" class="form-control ts-input" name="codigoCest" id="codigoCest" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">impostoImportacao</label>
                                <input type="text" class="form-control ts-input" name="impostoImportacao" id="impostoImportacao" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">piscofinscstEnt</label>
                                <input type="text" class="form-control ts-input" name="piscofinscstEnt" id="piscofinscstEnt" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">piscofinscstSai</label>
                                <input type="text" class="form-control ts-input" name="piscofinscstSai" id="piscofinscstSai" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">aliqPis</label>
                                <input type="text" class="form-control ts-input" name="aliqPis" id="aliqPis" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">aliqCofins</label>
                                <input type="text" class="form-control ts-input" name="aliqCofins" id="aliqCofins" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">nri</label>
                                <input type="text" class="form-control ts-input" name="nri" id="nri" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">ampLegal</label>
                                <input type="text" class="form-control ts-input" name="ampLegal" id="ampLegal" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">redPIS</label>
                                <input type="text" class="form-control ts-input" name="redPIS" id="redPIS" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">redCofins</label>
                                <input type="text" class="form-control ts-input" name="redCofins" id="redCofins" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">ipicstEnt</label>
                                <input type="text" class="form-control ts-input" name="ipicstEnt" id="ipicstEnt" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">ipicstSai</label>
                                <input type="text" class="form-control ts-input" name="ipicstSai" id="ipicstSai" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md">
                                <label class="form-label ts-label">aliqipi</label>
                                <input type="text" class="form-control ts-input" name="aliqipi" id="aliqipi" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">codenq</label>
                                <input type="text" class="form-control ts-input" name="codenq" id="codenq" readonly>
                            </div>
                            <div class="col-md">
                                <label class="form-label ts-label">ipiex</label>
                                <input type="text" class="form-control ts-input" name="ipiex" id="ipiex" readonly>
                            </div>
                        </div>

                    </div><!--body-->

                </div>
            </div>
        </div>

        <!--------- INSERIR --------->
        <div class="modal fade bd-example-modal-lg" id="inserirGrupoProdutoModal" tabindex="-1" aria-labelledby="inserirGrupoProdutoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir Grupo Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="form-inserirGrupoProduto">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label ts-label">codigoGrupo</label>
                                    <input type="text" class="form-control ts-input" name="codigoGrupo">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">nomeGrupo</label>
                                    <input type="text" class="form-control ts-input" name="nomeGrupo">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label ts-label">codigoNcm</label>
                                    <input type="text" class="form-control ts-input" name="codigoNcm">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md">
                                    <label class="form-label ts-label">codigoCest</label>
                                    <input type="text" class="form-control ts-input" name="codigoCest">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">impostoImportacao</label>
                                    <input type="text" class="form-control ts-input" name="impostoImportacao">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">piscofinscstEnt</label>
                                    <input type="text" class="form-control ts-input" name="piscofinscstEnt">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md">
                                    <label class="form-label ts-label">piscofinscstSai</label>
                                    <input type="text" class="form-control ts-input" name="piscofinscstSai">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">aliqPis</label>
                                    <input type="text" class="form-control ts-input" name="aliqPis">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">aliqCofins</label>
                                    <input type="text" class="form-control ts-input" name="aliqCofins">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md">
                                    <label class="form-label ts-label">nri</label>
                                    <input type="text" class="form-control ts-input" name="nri">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">ampLegal</label>
                                    <input type="text" class="form-control ts-input" name="ampLegal">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">redPIS</label>
                                    <input type="text" class="form-control ts-input" name="redPIS">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md">
                                    <label class="form-label ts-label">redCofins</label>
                                    <input type="text" class="form-control ts-input" name="redCofins">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">ipicstEnt</label>
                                    <input type="text" class="form-control ts-input" name="ipicstEnt">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">ipicstSai</label>
                                    <input type="text" class="form-control ts-input" name="ipicstSai">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md">
                                    <label class="form-label ts-label">aliqipi</label>
                                    <input type="text" class="form-control ts-input" name="aliqipi">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">codenq</label>
                                    <input type="text" class="form-control ts-input" name="codenq">
                                </div>
                                <div class="col-md">
                                    <label class="form-label ts-label">ipiex</label>
                                    <input type="text" class="form-control ts-input" name="ipiex">
                                </div>
                            </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="btn-formInserir">Cadastrar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>id</th>
                        <th>codigoGrupo</th>
                        <th>nomeGrupo</th>
                        <th>codigoNcm</th>
                        <th>codigoCest</th>
                        <!-- Lucas 29022024 - condição Administradora -->
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <th></th>
                        <?php } ?>
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
        buscar($("#buscaGrupoProduto").val());

        function limpar() {
            buscar(null, null, null, null);
            window.location.reload();
        }

        function buscar(buscaGrupoProduto) {
            //alert(buscaGrupoProduto);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo URLROOT ?>/impostos/database/grupoproduto.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    buscaGrupoProduto: buscaGrupoProduto
                },
                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.idGrupo + "</td>";
                        linha = linha + "<td>" + object.codigoGrupo + "</td>";
                        linha = linha + "<td>" + object.nomeGrupo + "</td>";
                        linha = linha + "<td>" + object.codigoNcm + "</td>";
                        linha = linha + "<td>" + object.codigoCest + "</td>";
                        // Lucas 29022024 - condição Administradora 
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        linha = linha + "<td>" + "<button type='button' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#visualizarGrupoProdutoModal' data-codigoGrupo='" + object.codigoGrupo + "'><i class='bi bi-eye'></i></button> ";
                        <?php } ?>
                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscaGrupoProduto").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscaGrupoProduto").val());
            }
        });

        $(document).on('click', 'button[data-bs-target="#visualizarGrupoProdutoModal"]', function() {
            var codigoGrupo = $(this).attr("data-codigoGrupo");

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/impostos/database/grupoproduto.php?operacao=buscar',
                data: {
                    codigoGrupo: codigoGrupo
                },
                success: function(data) {
                    //alert(data)
                    $('#codigoGrupo').val(data.codigoGrupo);
                    $vcodigoGrupo = data.codigoGrupo;
                    var texto = $("#textoCodigoGrupo");
                    texto.html($vcodigoGrupo);

                    $('#nomeGrupo').val(data.nomeGrupo);
                    $('#codigoNcm').val(data.codigoNcm);
                    $('#codigoCest').val(data.codigoCest);
                    $('#impostoImportacao').val(data.impostoImportacao);
                    $('#piscofinscstEnt').val(data.piscofinscstEnt);
                    $('#piscofinscstSai').val(data.piscofinscstSai);
                    $('#aliqPis').val(data.aliqPis);
                    $('#aliqCofins').val(data.aliqCofins);
                    $('#nri').val(data.nri);
                    $('#ampLegal').val(data.ampLegal);
                    $('#redPIS').val(data.redPIS);
                    $('#redCofins').val(data.redCofins);
                    $('#ipicstEnt').val(data.ipicstEnt);
                    $('#ipicstSai').val(data.ipicstSai);
                    $('#aliqipi').val(data.aliqipi);
                    $('#codenq').val(data.codenq);
                    $('#ipiex').val(data.ipiex);
                    $('#visualizarGrupoProdutoModal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("ERRO=" + JSON.stringify(error));
                }
            });
        });


        $(document).ready(function() {
            $("#form-inserirGrupoProduto").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/grupoproduto.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                    error: function(xhr, status, error) {
                        alert("ERRO=" + JSON.stringify(error));
                    }
                });
            });



            function refreshPage() {
                window.location.reload();
            }

        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>