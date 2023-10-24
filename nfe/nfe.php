<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';


$notas = buscaXML();
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>
    <link rel="stylesheet" href="<?php URLROOT ?>/services/css/tabs_visualizar.css">

</head>

<body>
    <div class="container-fluid mt-2">
        <div id="tabs">
            <div class="tab whiteborder" id="tab-nfe">Carregados</div>
            <div class="tab" id="tab-xml">Arquivos Pasta</div>
            <div class="line"></div>
            <div class="tabContent">
                <div class="table ts-divTabela ts-tableFiltros table-striped table-hover">
                    <h4>XML Carregados</h4>
                    <table class="table table-sm">
                        <thead class="ts-headertabelafixo">
                            <tr class="ts-headerTabelaLinhaCima">
                                <th>Nota Fiscal</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($notas as $nota) { ?>
                            <tr>
                                <td>
                                    <?php echo $nota['chaveNFe'] ?>
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
                <div class="table ts-divTabela ts-tableFiltros table-striped table-hover">
                    <h4>Arquivos em /xml/</h4>
                    <table class="table table-sm">
                        <thead class="ts-headertabelafixo">
                            <tr class="ts-headerTabelaLinhaCima">
                                <th>Arquivo </th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <?php
                        $dir = ROOT . "/xml/";
                        $files = scandir($dir);
                        foreach ($files as $file) {
                            if (!in_array($file, [".", ".."]) && strpos($file, "carregado_") !== 0) { ?>
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


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        $(document).ready(function () {

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