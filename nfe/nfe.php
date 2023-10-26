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
            <div class="tab" id="tab-xml">Carga NFe</div>
            <div class="line"></div>
            <div class="tabContent">
                <div class="container-fluid">
                    <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
                        <div class="col-3 text-start">
                            <!-- TITULO -->
                            <h2 class="ts-tituloPrincipal">Notas Fiscais</h2>
                        </div>
                        <div class="col-7">
                            <!-- FILTROS -->
                        </div>

                        <div class="col-2 text-end">
                        </div>
                    </div>
                    <div class="table mt-2 ts-divTabela ts-tableFiltros">
                        <table class="table table-hover table-sm">
                            <thead class="ts-headertabelafixo">
                                <tr>
                                    <th>Nota Fiscal</th>
                                    <th>Chave</th>
                                    <th>Valor Total</th>
                                    <th>Emissão</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($notas as $nota) { ?>
                                <tr>
                                    <td> <?php echo $nota['NF'] ?> </td>
                                    <td> <?php echo $nota['chaveNFe'] ?> </td>
                                    <td> <?php echo number_format($nota['valorProdutos'], 2, ',', '.') ?> </td>
                                    <td> <?php echo date('d/m/Y', strtotime($nota['dtEmissao']))  ?> </td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="visualizar.php?idNota=<?php echo $nota['idNota'] ?>" role="button"><i
                                                class="bi bi-eye-fill"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tabContent">
                <div class="container-fluid">
                    <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
                        <div class="col-3 text-start">
                            <!-- TITULO -->
                            <h2 class="ts-tituloPrincipal">Arquivos em /xml/</h2>
                        </div>
                        <div class="col-7">
                            <!-- FILTROS -->
                        </div>

                        <div class="col-2 text-end">
                            <form id="uploadForm" action="../database/fisnota.php?operacao=upload" method="POST" enctype="multipart/form-data">
                                <input type="file" id="myFile" class="custom-file-upload" name="file" style="color:#567381; display:none">
                                <label for="myFile">
                                    <a class="btn btn-primary">
                                        <i class="bi bi-file-earmark-arrow-down-fill" style="color:#fff"></i>&#32;<h7 style="color: #fff;">Anexos</h7>
                                    </a>
                                </label>
                            </form>
                        </div>
                    </div>
                    <div class="table mt-2 ts-divTabela ts-tableFiltros">
                        <table class="table table-hover table-sm">
                            <thead class="ts-headertabelafixo">
                                    <th>Arquivo</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <?php
                            $dir = ROOT . "/xml/";
                            $files = scandir($dir);
                            foreach ($files as $file) {
                                if (!in_array($file, [".", ".."]) && strpos($file, "carregado") !== 0) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $file ?>
                                        </td>
                                        <td>
                                            <button type="button" class="uparButton btn btn-success btn-sm" data-file="<?php echo $dir . $file ?>"><i class="bi bi-arrow-up-circle"></i></button>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
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
            $('#myFile').on('change', function() {
                var fileInput = document.getElementById('myFile');
                var file = fileInput.files[0];

                if (file) {
                    var formData = new FormData();
                    formData.append('file', file);

                    $.ajax({
                        type: 'POST',
                        url: "../database/fisnota.php?operacao=upload",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            refreshPage('xml');
                        },
                    });
                }
            });

            $('.uparButton').click(function () {
                var arquivo = $(this).data('file');
                console.log(arquivo);
                $.ajax({
                    url: "../database/fisnota.php?operacao=inserir",
                    method: "POST",
                    data: { 
                        arquivo: arquivo
                    },
                    success: function (msg) {
                        console.log(msg);
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