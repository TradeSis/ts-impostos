<?php

include_once '../head.php';
include_once '../database/ncm.php';

$filtroEntrada = null;
$dadosCest = null;
$FiltroTipoCest = null;


if (isset($_SESSION['filtro_cest'])) {
    $filtroEntrada = $_SESSION['filtro_cest'];
    $FiltroTipoCest = $filtroEntrada['FiltroTipoCest'];
    $dadosCest = $filtroEntrada['dadosCest'];
}

if (isset($_GET['codigoNcm'])) {
    $FiltroTipoCest = "codigoNcm";
    $dadosCest = $_GET['codigoNcm'];
}

?>

<body class="bg-transparent">


    <div class="container-fluid">
        <div class="mt-3">
            <div class="card mt-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="ncm_table.php">NCM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" style="color:blue" href="#">Cest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="fisoperacao_table.php">Operação</a>
                    </li>
                </ul>

                <div class="row justify-content-center">
                    <div class="col-sm-2">
                        <form class="d-flex" action="" method="post" style="text-align: right;">
                            <select class="form-control" name="FiltroTipoCest" id="FiltroTipoCest">
                                <option <?php if ($FiltroTipoCest == "nomeCest") { echo "selected"; } ?> value="nomeCest">Nome Cest</option>
                                <option <?php if ($FiltroTipoCest == "codigoNcm") { echo "selected"; } ?> value="codigoNcm">Código Ncm</option>
                                <option <?php if ($FiltroTipoCest == "codigoCest") { echo "selected"; } ?> value="codigoCest">Código Cest</option>
                            </select>
                        </form>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group">
                            <?php if (!empty($dadosCest)){ ?>
                                <input type="text" class="form-control" id="dadosCest" value="<?php echo $dadosCest ?>">
                            <?php } else { ?>
                                <input type="text" class="form-control" id="dadosCest" placeholder="Codigo">
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <button class="btn btn-primary w-50 mt-3" id="buscar" type="button">Pesquisar</button>
                    </div>
                </div>

                <div
                    class="table table-sm table-hover table-striped table-bordered table-wrapper-scroll-y my-custom-scrollbar diviFrame mt-2">
                    <table class="table" id="myIframe">
                        <thead class="thead-light">

                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Cest</th>
                                <th>superior</th>
                                <th>ncm</th>
                            </tr>
                        </thead>
                        <tbody id='dados' class="fonteCorpo">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        <?php if (!empty($dadosCest)) { ?>
            buscar($("#FiltroTipoCest").val(), $("#dadosCest").val());
        <?php } ?>

        function limpar() {
            buscar(null, null);
            window.location.reload();
        }

        function buscar(FiltroTipoCest, dadosCest) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/cest.php?operacao=filtrar',
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    FiltroTipoCest: FiltroTipoCest,
                    dadosCest: dadosCest
                },
                success: function (msg) {
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var i = 0; i < json.length; i++) {
                        var object = json[i];

                        linha += "<tr>";
                        linha += "<td>" + object.codigoCest + "</td>";
                        linha += "<td>" + object.nomeCest + "</td>";
                        linha += "<td>" + object.cest + "</td>";
                        linha += "<td>" + object.superior + "</td>";
                        linha += "<td>" + object.codigoNcm + "</td>";
                        linha += "</tr>";
                    }

                    $("#dados").html(linha);
                },
                error: function (e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }

        $(document).ready(function () {
            $("#buscar").click(function () {
                if ($("#dadosCest").val() === "") {
                    alert("Campo Codigo vazio!");
                } else {
                    buscar($("#FiltroTipoCest").val(), $("#dadosCest").val());
                }
            });

            $(document).keypress(function (e) {
                if (e.key === "Enter") {
                    buscar($("#FiltroTipoCest").val(), $("#dadosCest").val());
                }
            });
        });
    </script>


</body>

</html>