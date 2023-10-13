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

        <div class="row" style="margin-left: 10vw">
            <div class="col-sm-3 ml-2">

            </div>

            <div class="col-sm-4 ml-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="Descricao" placeholder="Descricao">
                </div>

                <div class="input-group">
                    <input type="text" class="form-control" id="Codigo" placeholder="Codigo">
                </div>

                <div class="col-sm">

                    <button class="btn btn-primary w-50 mt-3" id="buscar" type="button">Pesquisar</button>

                </div>
            </div>

        </div>

        <div class="" style="margin-left: 10vw; text-align:left">
            <label class="tituloTabela pl-4">NCM</label>
        </div>

    
            <div class="table mt-2 ts-divTabela">
                <table class="table table-hover table-sm align-middle">
                    <thead class="ts-headertabelafixo">

                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <!-- <th>Data Inicio</th>
                          <th>Data Fim</th> -->
                            <th>Tipo Ato</th>
                            <th>Numero Ato</th>
                            <th>Ano Ato</th>
                            <th>Superior</th>
                            <th>nivel</th>
                        </tr>
                    </thead>
                    <tbody id='dados' class="fonteCorpo">

                    </tbody>
                </table>
            </div>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        /* buscar($("#Descricao").val(), $("#Codigo").val()); */

        function limpar() {
            buscar(null, null);
            window.location.reload();
        }

        function buscar(Descricao, Codigo) {
            //alert(Descricao);
            //alert(Codigo);


            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/ncm.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    Descricao: Descricao,
                    Codigo: Codigo
                },


                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);
                    //alert("terceiro alert: " + JSON.stringify(json));
                    /* alert(JSON.stringify(msg)); */
                    /* $("#dados").html(msg); */

                    var linha = "";
                    // Loop over each object
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];


                        // alert("quarto alert: " + JSON.stringify(object))
                        /*  alert(object); */
                        linha = linha + "<tr>";
                        linha = linha + "<td>" + object.Codigo + "</td>";
                        linha = linha + "<td>" + object.Descricao + "</td>";
                        /* linha = linha + "<td>" + object.Data_Inicio + "</td>";
                        linha = linha + "<td>" + object.Data_Fim + "</td>"; */
                        linha = linha + "<td>" + object.Tipo_Ato + "</td>";
                        linha = linha + "<td>" + object.Numero_Ato + "</td>";
                        linha = linha + "<td>" + object.Ano_Ato + "</td>";
                        linha = linha + "<td>" + object.superior + "</td>";
                        linha = linha + "<td>" + object.nivel + "</td>";
                        linha = linha + "</tr>";
                    }

                    //alert(linha);
                    $("#dados").html(linha);

                },
                error: function(e) {
                    alert('Erro: ' + JSON.stringify(e));

                    return null;
                }
            });

        }

        $("#buscar").click(function() {
            if ($("#Descricao").val() == "" && $("#Codigo").val() == "") {
                alert("Preencher o campo de Descrição ou Codigo!")
            }
           else{
            buscar($("#Descricao").val(), $("#Codigo").val());
            
           }
        })

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#Descricao").val(), $("#Codigo").val());
            }
        });

        $('.btnAbre').click(function() {
            $('.menuFiltros').toggleClass('mostra');
            $('.diviFrame').toggleClass('mostra');
        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>