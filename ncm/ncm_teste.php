<?php
//Lucas 13102023 padrao novo
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/ncm.php');

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>


    <div class="container-fluid">
        <div class="mt-3">
            <div class="card mt-3">
                <label class="ts-tituloPrincipal pl-4 mt-3">Tabela NCM</label>

                <div class="row justify-content-center">
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="Descricao" placeholder="Descricao">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="codigoNcm" placeholder="Codigo">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <button class="btn btn-primary w-50 mt-3" id="buscar" type="button">Pesquisar</button>
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
                                <th>Ultimo Nivel</th>
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

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        function limpar() {
            buscar(null, null);
            window.location.reload();
        }

        function buscar(Descricao, codigoNcm) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/ncm.php?operacao=filtrar',
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    Descricao: Descricao,
                    codigoNcm: codigoNcm
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

                        var rowClass = object.pesquisado ? "bold-row" : "";

                        linha += "<tr class='" + rowClass + "'>";
                        linha += "<td>" + spacesCodigoNcm + object.codigoNcm + "</td>";
                        linha += "<td><span style='white-space: pre;'>" + spacesDescricao + "</span>" + object.Descricao + "</td>";
                        linha += "<td>" + object.superior + "</td>";
                        linha += "<td>" + object.nivel + "</td>";
                        linha += "<td>" + object.ultimonivel + "</td>";
                        linha += "<td>" + object.ncm + "</td>";
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
                if ($("#Descricao").val() === "" && $("#codigoNcm").val() === "") {
                    alert("Preencher o campo de Descrição ou Codigo!");
                } else {
                    buscar($("#Descricao").val(), $("#codigoNcm").val());
                }
            });

            $(document).keypress(function (e) {
                if (e.key === "Enter") {
                    buscar($("#Descricao").val(), $("#codigoNcm").val());
                }
            });
        });
    </script>

    <style>
        .bold-row {
            font-weight: bold;
        }
    </style>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>