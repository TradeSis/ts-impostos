<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';


$notas = buscaXML();
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>
    <style>
        #tabs .tab {
            display: inline-block;
            padding: 5px 10px;
            cursor: pointer;
            position: relative;
            z-index: 5;
            border-radius: 3px 3px 0 0;
            background-color: #567381;
            color: #EEEEEE;
        }

        #tabs .whiteborder {
            border: 1px solid #707070;
            border-bottom: 1px solid #fff;
            border-radius: 3px 3px 0 0;
            background-color: #EEEEEE;
            color: #567381;
        }

        #tabs .tabContent {
            position: relative;
            top: -1px;
            z-index: 1;
            padding: 10px;
            border-radius: 0 0 3px 3px;
            color: black;
        }

        #tabs .hide {
            display: none;
        }

        #tabs .show {
            display: block;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <div id="tabs">
            <div class="tab whiteborder" id="tab-nfe">NFe</div>
            <div class="tab" id="tab-xml">Pasta /xml/</div>
            <div class="line"></div>
            <div class="tabContent">
                <div class="row align-items-center">
                    <div class="col-3 text-start">
                        <!-- TITULO -->
                        <h2 class="tituloTabela">Lista NFe</h2>
                    </div>
                    <div class="col-7">
                    </div>

                    <div class="col-2 text-end">
                        <button type="button" class="btn btn-success mr-4" data-toggle="modal"
                            data-target="#inserirXML"><i class="bi bi-plus-square"></i>&nbsp Upload</button>
                    </div>
                </div>
                <div class="table mt-2 divtabela">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="cabecalhoTabela">
                            <tr id="titulodetabelafixo">
                                <th>Arquivo</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($notas as $nota) { ?>
                            <tr>
                                <td>
                                    <?php echo $nota['nomeXml'] ?>
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="visualizar.php?arquivo=<?php echo $nota['pathXml'] ?>" role="button"><i
                                            class="bi bi-eye-fill"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <div class="tabContent">
                <div class="table mt-2 divtabela">
                    <h4>Arquivos em /xml/</h4>
                    <table class="table table-hover table-sm align-middle">
                        <thead class="cabecalhoTabela">
                            <tr id="titulodetabelafixo">
                                <th>Arquivo </th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <?php
                        $dir = "xmlnfe/xml/";
                        $files = scandir($dir);
                        foreach ($files as $file) {
                            if (!in_array($file, [".", ".."])) { ?>
                                <tr>
                                    <td>
                                        <?php echo $file ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="visualizar.php?arquivo=<?php echo $dir . $file ?>"
                                            role="button"><i class="bi bi-eye-fill"></i></a>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!--------- MODAL INSERIR --------->
    <div class="modal fade " id="inserirXML" tabindex="-1" role="dialog" aria-labelledby="inserirXMLLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir XML </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <form method="post" id="inserirForm" enctype="multipart/form-data">
                        <input type="file" id="myFile" class="custom-file-upload" name="nomeAnexo"
                            onchange="myFunction()" style="color:#567381; display:none">
                        <label for="myFile">
                            <a class="btn btn-primary"><i class="bi bi-file-earmark-arrow-down-fill"
                                    style="color:#fff"></i>&#32;<h7 style="color: #fff;">Anexos</h7></a>
                        </label>
                        <p id="mostraNomeAnexo"></p>
                </div>
                <div class="card-footer bg-transparent" style="text-align:right">
                    <button type="submit" class="btn btn-success" id="inserirBtn">Inserir</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        function myFunction() {
            var x = document.getElementById("myFile");
            var txt = "";
            if ('files' in x) {
                if (x.files.length == 0) {
                    txt = "";
                } else {
                    for (var i = 0; i < x.files.length; i++) {
                        /* txt += "<br><strong>" + (i+1) + ". file</strong><br>"; */
                        var file = x.files[i];
                        if ('name' in file) {
                            txt += "Arquivo a ser anexado: " + "</br>" + "<i>" + file.name + "</i>" + "<br>";
                        }
                    }
                }
            }
            document.getElementById("mostraNomeAnexo").innerHTML = txt;
        }

        $(document).ready(function () {

            $("#inserirForm").submit(function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/fisnota.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        var message = JSON.parse(msg);
                        if (message.retorno === "ok") {
                            window.location.reload();
                        }
                        if (message.status === 400) {
                            alert(message.retorno);
                        }
                    }
                });
            });

        });
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

        document.getElementById('tabs').onclick = function (event) {
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