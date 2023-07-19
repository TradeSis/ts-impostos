<?php

include_once '../head.php';
include_once '../database/ncm.php';

$filtroEntrada = null;
$dadosNcm = null;
$FiltroTipoNcm = null;


if (isset($_SESSION['filtro_ncm'])) {
    $filtroEntrada = $_SESSION['filtro_ncm'];
    $FiltroTipoNcm = $filtroEntrada['FiltroTipoNcm'];
    $dadosNcm = $filtroEntrada['dadosNcm'];
}


?>


<body class="bg-transparent">


    <div class="container-fluid">
        <div class="mt-3">
            <div class="card mt-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" style="color:blue" href="#">NCM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cest_table.php">Cest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="fisoperacao_table.php">Operação</a>
                    </li>
                </ul>

                <div class="row justify-content-center">
                    <div class="col-sm-2">
                        <form class="d-flex" action="" method="post" style="text-align: right;">
                            <select class="form-control" name="FiltroTipoNcm" id="FiltroTipoNcm">
                                <option <?php if ($FiltroTipoNcm == "Descricao") {
                                    echo "selected";
                                } ?>
                                    value="Descricao">Descrição</option>
                                <option <?php if ($FiltroTipoNcm == "codigoNcm") {
                                    echo "selected";
                                } ?>
                                    value="codigoNcm">Código Ncm</option>
                            </select>
                        </form>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group">
                            <?php if (!empty($dadosNcm)) { ?>
                                <input type="text" class="form-control" id="dadosNcm" value="<?php echo $dadosNcm ?>">
                            <?php } else { ?>
                                <input type="text" class="form-control" id="dadosNcm" placeholder="Codigo">
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <button class="btn btn-primary w-50 mt-3" id="buscar" type="button">Pesquisar</button>
                    </div>
                </div>

                <div class="table table-sm table-bordered table-wrapper-scroll-y my-custom-scrollbar diviFrame mt-2">
                    <table class="table" id="myIframe">
                        <thead class="thead-light">

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
    </div>

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
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    FiltroTipoNcm: FiltroTipoNcm,
                    dadosNcm: dadosNcm
                },
                success: function (msg) {
                    var json = JSON.parse(msg);

                    json.sort(function (a, b) {
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
                error: function (e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }

        $(document).ready(function () {
            $("#buscar").click(function () {
                if ($("#dadosNcm").val() === "") {
                    alert("Campo Codigo vazio!");
                } else {
                    buscar($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
                }
            });

            $(document).keypress(function (e) {
                if (e.key === "Enter") {
                    buscar($("#FiltroTipoNcm").val(), $("#dadosNcm").val());
                }
            });
        });
    </script>








</body>

</html>