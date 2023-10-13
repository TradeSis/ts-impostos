<?php
//Lucas 13102023 padrao novo
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/ncm.php');

$filtroEntrada = null;
$dadosNcm = null;
$FiltroTipoNcm = null;


if (isset($_SESSION['filtro_ncm'])) {
    $filtroEntrada = $_SESSION['filtro_ncm'];
    $FiltroTipoNcm = $filtroEntrada['FiltroTipoNcm'];
    $dadosNcm = $filtroEntrada['dadosNcm'];
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
                    <a class="nav-link active" style="color: #1B4D60; background-color: #EEEEEE" href="#">NCM</a>
                </li>
                <li class="nav-item mr-1">
                    <a class="nav-link active" href="cest_table.php">Cest</a>
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
                    <h2 class="tituloTabela">NCM</h2>
                </div>
                <div class="col-3">
                    <form class="d-flex" action="" method="post" style="text-align: right;">
                        <select class="form-control" name="FiltroTipoNcm" id="FiltroTipoNcm">
                            <option <?php if ($FiltroTipoNcm == "Descricao") {
                                        echo "selected";
                                    } ?> value="Descricao">Descrição</option>
                            <option <?php if ($FiltroTipoNcm == "codigoNcm") {
                                        echo "selected";
                                    } ?> value="codigoNcm">Código Ncm</option>
                        </select>
                    </form>
                </div>
                <div class="col-4">
                    <!-- FILTROS -->
                    <div class="input-group">
                        <?php if (!empty($dadosNcm)) { ?>
                            <input type="text" class="form-control" id="dadosNcm" value="<?php echo $dadosNcm ?>">
                        <?php } else { ?>
                            <input type="text" class="form-control" id="dadosNcm" placeholder="Codigo">
                        <?php } ?>

                        <button class="btn btn-primary" id="buscar" type="button" style="margin-top:10px;">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </div>

                <div class="col-2 text-end">
                    <a href="aplicativo_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
                </div>
            </div>

            <div class="table mt-2 ts-divTabela">
                <table class="table table-hover table-sm align-middle">
                    <thead class="ts-headertabelafixo">

                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Superior</th>
                            <th>nivel</th>
                            <th>CEST</th>
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
        <?php if (!empty($dadosNcm)) { ?>
            buscar($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
        <?php } ?>

        function limpar() {
            buscar(null, null);
            window.location.reload();
        }

        function buscar(FiltroTipoNcm, dadosNcm) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/ncm.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    FiltroTipoNcm: FiltroTipoNcm,
                    dadosNcm: dadosNcm
                },
                success: function(msg) {
                    var json = JSON.parse(msg);

                    json.sort(function(a, b) {
                        if (a.codigoNcm === b.codigoNcm) {
                            return a.nivel - b.nivel;
                        } else {
                            return a.codigoNcm.localeCompare(b.codigoNcm);
                        }
                    });

                    var linha = "";
                    for (var i = 0; i < json.length; i++) {
                        var object = json[i];

                        var spacesDescricao = "&nbsp;&nbsp;".repeat((object.nivel - 1) * 2);
                        var spacesCodigoNcm = "&nbsp;&nbsp;".repeat((object.nivel - 1) * 2);


                        linha += "<tr>";
                        linha += "<td>" + spacesCodigoNcm + object.ncm + "</td>";
                        if ((dadosNcm && object.Descricao.toLowerCase().includes(dadosNcm.toLowerCase())) || object.pesquisado) {
                            linha += "<td><span style='font-weight: bold; white-space: pre;'>" + spacesDescricao + object.Descricao + "</span></td>";
                        } else {
                            linha += "<td>" + spacesDescricao + object.Descricao + "</td>";
                        }
                        linha += "<td>" + object.superior + "</td>";
                        linha += "<td>" + object.nivel + "</td>";
                        if (object.codigoCest) {
                            var codigoCestArray = object.codigoCest.split(',');
                            if (codigoCestArray.length > 1) {
                                linha += "<td><a href='cest_table.php?codigoNcm=" + object.codigoNcm + "'>CEST</a></td>";
                            } else {
                                linha += "<td><a href='cest_table.php?codigoNcm=" + object.codigoNcm + "'>" + codigoCestArray[0] + "</a></td>";
                            }
                        } else {
                            linha += "<td></td>";
                        }
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
                if ($("#dadosNcm").val() === "") {
                    alert("Campo Codigo vazio!");
                } else {
                    buscar($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
                }
            });

            $(document).keypress(function(e) {
                if (e.key === "Enter") {
                    buscar($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
                }
            });
        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>