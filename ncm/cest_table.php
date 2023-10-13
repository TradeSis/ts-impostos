<?php
//Lucas 13102023 padrao novo
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/ncm.php');

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
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<style>
    .nav-link.active:any-link {
        background-color: #567381;
        border: 1px solid #DFDFDF;
        border-radius: 5px 5px 0px 0px;
        color: #fff;
    }

    .line {
        width: 100%;
        border-bottom: 1px solid #707070;
    }
</style>

<body>


    <div class="container-fluid">
        <div class="mt-3 text-center">
            <ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item mr-1">
                    <a class="nav-link active" href="ncm_table.php">NCM</a>
                </li>
                <li class="nav-item mr-1">
                    <a class="nav-link active" style="color: #1B4D60; background-color: #EEEEEE" href="#">Cest</a>
                </li>
                <li class="nav-item mr-1">
                    <a class="nav-link active" href="fisoperacao_table.php">Operação</a>
                </li>
            </ul>
            <div class="line"></div>
            <div class="row">
                <BR> <!-- MENSAGENS/ALERTAS -->
            </div>
            <div class="row">
                <BR> <!-- BOTOES AUXILIARES -->
            </div>
            <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
                <div class="col-3 text-start">
                    <!-- TITULO -->
                    <h2 class="tituloTabela">Cest</h2>
                </div>
                <div class="col-3">
                    <form class="d-flex" action="" method="post" style="text-align: right;">
                        <select class="form-control" name="FiltroTipoCest" id="FiltroTipoCest">
                            <option <?php if ($FiltroTipoCest == "nomeCest") {
                                        echo "selected";
                                    } ?> value="nomeCest">Nome Cest</option>
                            <option <?php if ($FiltroTipoCest == "codigoNcm") {
                                        echo "selected";
                                    } ?> value="codigoNcm">Código Ncm</option>
                            <option <?php if ($FiltroTipoCest == "codigoCest") {
                                        echo "selected";
                                    } ?> value="codigoCest">Código Cest</option>
                        </select>
                    </form>
                </div>
                <div class="col-4">
                    <!-- FILTROS -->
                    <div class="input-group">
                        <?php if (!empty($dadosCest)) { ?>
                            <input type="text" class="form-control" id="dadosCest" value="<?php echo $dadosCest ?>">
                        <?php } else { ?>
                            <input type="text" class="form-control" id="dadosCest" placeholder="Codigo">
                        <?php } ?>

                        <button class="btn btn-primary" id="buscar" type="button" style="margin-top:10px;">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </div>

                    <div class="col-2 text-end">

                    </div>
                </div>


                <div class="table mt-2 ts-divTabela">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="ts-headertabelafixo">

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

        <!-- LOCAL PARA COLOCAR OS JS -->

        <?php include_once ROOT . "/vendor/footer_js.php"; ?>

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
                    beforeSend: function() {
                        $("#dados").html("Carregando...");
                    },
                    data: {
                        FiltroTipoCest: FiltroTipoCest,
                        dadosCest: dadosCest
                    },
                    success: function(msg) {
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
                    error: function(e) {
                        alert('Erro: ' + JSON.stringify(e));
                    }
                });
            }

            $(document).ready(function() {
                $("#buscar").click(function() {
                    if ($("#dadosCest").val() === "") {
                        alert("Campo Codigo vazio!");
                    } else {
                        buscar($("#FiltroTipoCest").val(), $("#dadosCest").val());
                    }
                });

                $(document).keypress(function(e) {
                    if (e.key === "Enter") {
                        buscar($("#FiltroTipoCest").val(), $("#dadosCest").val());
                    }
                });
            });
        </script>

        <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>