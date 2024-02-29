<?php
//Helio 05102023 padrao novo
//Lucas 04042023 criado
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
                <h2 class="ts-tituloPrincipal">Status Notas</h2>
            </div>
     
            <div class="col-6 col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaStatus" placeholder="Buscar por Status">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
                    <button type="button" class="ms-4 btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirStatusModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                </div>
            </div>

        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>ID</th>
                        <th>Status</th>
                        <th colspan="2">Ação</th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>


        <!--------- INSERIR --------->
        <div class="modal fade bd-example-modal-lg" id="inserirStatusModal" tabindex="-1" aria-labelledby="inserirStatusModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir Status Nota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="form-inserirStatus">
                            <div class="row">
                                <div class="col-md">
                                    <label class="form-label ts-label">Status</label>
                                    <input type="text" class="form-control ts-input" name="nomeStatusNota">
                                </div>
                            </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!--------- ALTERAR --------->
        <div class="modal fade bd-example-modal-lg" id="alterarStatusModal" tabindex="-1" aria-labelledby="alterarStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Alterar Status Nota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                
                    </div>
                    <div class="modal-body">
                        <form method="post" id="form-alterarStatus">
                            <div class="row">
                                <div class="col-md">
                                    <label class="form-label ts-label">Status</label>
                                    <input type="text" class="form-control ts-input" name="nomeStatusNota" id="nomeStatusNota">
                                    <input type="hidden" class="form-control ts-input" name="idStatusNota" id="idStatusNota">
                                </div>
                            </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        buscar($("#buscaStatus").val());

        function limpar() {
            buscar(null, null, null, null);
            window.location.reload();
        }

        function buscar(buscaStatus) {
            //alert (buscaStatus);
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo URLROOT ?>/impostos/database/fisnotastatus.php?operacao=buscarStatusNota',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    nomeStatusNota: buscaStatus
                },
                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.idStatusNota + "</td>";
                        linha = linha + "<td>" + object.nomeStatusNota + "</td>";

                        linha = linha + "<td>" + "<button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#alterarStatusModal' data-idStatusNota='" + object.idStatusNota + "'><i class='bi bi-pencil-square'></i></button> "
                        linha = linha + "</tr>";
                    }
                    $("#dados").html(linha);
                }
            });
        }

        $("#buscar").click(function() {
            buscar($("#buscaStatus").val());
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#buscaStatus").val());
            }
        });

        $(document).on('click', 'button[data-bs-target="#alterarStatusModal"]', function() {
                var idStatusNota = $(this).attr("data-idStatusNota");
                //alert(idFornecimento)
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '../database/fisnotastatus.php?operacao=buscarStatusNota',
                    data: {
                        idStatusNota: idStatusNota
                    },
                    success: function(data) {
                        $('#idStatusNota').val(data.idStatusNota);
                        $('#nomeStatusNota').val(data.nomeStatusNota);
                        $('#alterarStatusModal').modal('show');
                    }
                });
            });

        $(document).ready(function() {
            $("#form-inserirStatus").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fisnotastatus.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            $("#form-alterarStatus").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fisnotastatus.php?operacao=alterar",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

        });

        function refreshPage() {
            window.location.reload();
        }

    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>