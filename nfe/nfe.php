<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';


$notas = buscarNota();
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="container-fluid">
        <div id="ts-tabs">
            <div class="tab whiteborder" id="tab-nfe">NFe</div>
            <div class="line"></div>
            <div class="tabContent">
                <div class="container-fluid">
                    <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
                        <div class="col-3 text-start">
                            <!-- TITULO -->
                            <h2 class="ts-tituloPrincipal">Notas Fiscais</h2>
                        </div>
                        <div class="col-6">
                            <!-- FILTROS -->
                        </div>

                        <div class="col-3 text-end">
                            <div class="row">
                                <div class="col-6">
                                    <a role="button" class="btn btn-warning processar-btn" title="Processar todos XMLs">Processar</a>
                                </div>
                                <div class="col-6">
                                    <form id="uploadForm" action="../database/fisnota.php?operacao=upload" method="POST" enctype="multipart/form-data">
                                        <input type="file" id="arquivo" class="custom-file-upload" name="file[]" style="color:#567381; display:none" multiple>
                                        <label for="arquivo">
                                            <a class="btn btn-primary">
                                                <i class="bi bi-file-earmark-arrow-down-fill" style="color:#fff"></i>&#32;<h7 style="color: #fff;">Arquivo</h7>
                                            </a>
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table mt-2 ts-divTabela ts-tableFiltros">
                        <table class="table table-hover table-sm">
                            <thead class="ts-headertabelafixo">
                                <tr>
                                    <th>nNF</th>
                                    <th>dhEmi</th>
                                    <th>emit</th>
                                    <th>emite</th>
                                    <th>total</th>
                                    <th>nomeStatusNota</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($notas as $nota) { ?>
                                <tr>
                                    <td> <?php echo $nota['NF'] ?> </td>
                                    <td> <?php echo date('d/m/Y', strtotime($nota['dtEmissao']))  ?> </td>
                                    <td> <?php echo $nota['emitente_cpfCnpj'] ?> </td>
                                    <td> <?php echo $nota['emitente_nomeFantasia'] ?> </td>
                                    <td> <?php echo number_format($nota['vNF'], 2, ',', '.') ?> </td>
                                    <td> <?php echo $nota['nomeStatusNota'] ?> </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="visualizar.php?idNota=<?php echo $nota['idNota'] ?>" role="button"><i class="bi bi-eye-fill"></i></a>
                                        <button type="button" class="btn btn-success btn-sm" id="baixar" data-idNota="<?php echo $nota['idNota'] ?>" title="Baixar XML"><i class="bi bi-download"></i></button>
                                        <?php if($nota['idStatusNota'] == 0){ ?>
                                        <button type="button" class="btn btn-warning btn-sm processar-btn" data-idNota="<?php echo $nota['idNota'] ?>" title="Processar XML"><i class="bi bi-check-circle-fill"></i></button>
                                        <?php } ?>
                                    </td>

                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        $(document).ready(function () {
            $('#arquivo').on('change', function() {
                $('body').css('cursor', 'progress');
                var fileInput = document.getElementById('arquivo');
                var files = fileInput.files;

                if (files.length > 0) {
                    var formData = new FormData();

                    for (var i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }

                    $.ajax({
                        type: 'POST',
                        url: "../database/fisnota.php?operacao=inserir",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (msg) {
                            console.log(msg);
                            $('body').css('cursor', 'default');
                            var message = JSON.parse(msg);
                            if (message.retorno === "ok") {
                                refreshPage('xml');
                            }
                            if (message.status === 400) {
                                alert(message.retorno);
                                refreshPage('xml');
                            }
                        }
                    });
                }
            });

            $('#baixar').click(function () {
                var idNota = $(this).attr("data-idNota");
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "../database/fisnota.php?operacao=buscarNota",
                    data: { 
                        idNota: idNota
                    },
                    success: function (msg) {
                        var xmlContent = msg.XML;
                        var blob = new Blob([xmlContent], { type: 'application/xml' }); 
                        var filename = msg.chaveNFe;
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        link.click();
                    }
                });
            });

            $('.processar-btn').click(function () {
                $('body').css('cursor', 'progress');
                var idNota = $(this).attr("data-idNota");
                
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "../database/fisnota.php?operacao=processarXML",
                    data: { idNota: idNota },
                    success: function (msg) {
                        $('body').css('cursor', 'default');
                        if (msg.retorno === "ok") {
                            refreshPage('xml');
                        }
                        if (msg.status === 400) {
                            alert(msg.retorno);
                            refreshPage('xml');
                        }
                    }
                });
            });

        });

        function refreshPage(tab) {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=' + tab;
            window.location.href = newUrl;
        }
    </script>
    <script>
        var tab;
        var tabContent;

        window.onload = function () {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'xml') {
                showTabsContent(1);
            }
        }

        document.getElementById('ts-tabs').onclick = function (event) {
            var target = event.target;
            if (target.className == 'tab') {
                for (var i = 0; i < tab.length; i++) {
                    if (target == tab[i]) {
                        showTabsContent(i);
                        break;
                    }
                }
            }
        }

        function hideTabsContent(a) {
            for (var i = a; i < tabContent.length; i++) {
                tabContent[i].classList.remove('show');
                tabContent[i].classList.add("hide");
                tab[i].classList.remove('whiteborder');
            }
        }

        function showTabsContent(b) {
            if (tabContent[b].classList.contains('hide')) {
                hideTabsContent(0);
                tab[b].classList.add('whiteborder');
                tabContent[b].classList.remove('hide');
                tabContent[b].classList.add('show');
            }
        }
    </script>
    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>