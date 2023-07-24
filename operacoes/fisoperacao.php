<?php
// gabriel 060623 15:06
include_once(__DIR__ . '/../head.php');
include_once(__DIR__ . '/../database/fisoperacao.php');
include_once(__DIR__ . '/../database/fisatividade.php');
include_once(__DIR__ . '/../database/fisnatureza.php');
include_once(__DIR__ . '/../database/fisprocesso.php');

$atividades = buscaAtividade();
$processos = buscaProcesso();
$naturezas = buscaNatureza();
$operacoes = buscaOperacao();

$filtroEntrada = null;
$dadosOp = null;
$FiltroTipoOp = null;
$idAtividade = null;
$idProcesso = null;
$idNatureza = null;


if (isset($_SESSION['filtro_operacao'])) {
    $filtroEntrada = $_SESSION['filtro_operacao'];
    $FiltroTipoOp = $filtroEntrada['FiltroTipoOp'];
    $dadosOp = $filtroEntrada['dadosOp'];
    $idAtividade = $filtroEntrada['idAtividade'];
    $idProcesso = $filtroEntrada['idProcesso'];
    $idNatureza = $filtroEntrada['idNatureza'];
}


?>

<style>
    ul {
        list-style-type: none;
    }
</style>

<body class="bg-transparent">

    <div class="container-fluid text-center mt-4">

        <div class="row">
            <div class=" btnAbre">
                <span style="font-size: 25px" class="material-symbols-outlined">
                    filter_alt
                </span>

            </div>

            <div class="col-sm-3 ml-2">
                <p class="tituloTabela">Operações Fiscais</p>
            </div>

            <div class="col-sm" style="text-align:right">
                <a href="fisoperacao_inserir.php" role="button" class="btn btn-success">Adicionar Operação</a>
            </div>
        </div>

        <div class="card mt-2">
            <div class="table table-sm table-hover table-striped table-wrapper-scroll-y my-custom-scrollbar diviFrame">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Operação</th>
                            <th class="text-center">
                                <form class="d-flex" action="" method="post">
                                    <select class="form-control fonteSelect text-center" name="idAtividade"
                                        id="FiltroAtividade" style="font-size: 14px;">
                                        <option value="<?php echo null ?>"><?php echo " Atividade" ?></option>
                                        <?php
                                        foreach ($atividades as $atividade) {
                                            ?>
                                            <option <?php
                                            if ($atividade['idAtividade'] == $idAtividade) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $atividade['idAtividade'] ?>"><?php echo $atividade['nomeAtividade'] ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </th>
                            <th class="text-center">
                                <form class="d-flex" action="" method="post">
                                    <select class="form-control text-center" name="idProcesso" id="FiltroProcesso"
                                        style="font-size: 14px;">
                                        <option value="<?php echo null ?>"><?php echo " Processo" ?></option>
                                        <?php
                                        foreach ($processos as $processo) {
                                            ?>
                                            <option <?php
                                            if ($processo['idProcesso'] == $idProcesso) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $processo['idProcesso'] ?>"><?php echo $processo['nomeProcesso'] ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </th>
                            <th class="text-center">
                                <form class="d-flex" action="" method="post">
                                    <select class="form-control text-center" name="idNatureza" id="FiltroNatureza"
                                        style="font-size: 14px;">
                                        <option value="<?php echo null ?>"><?php echo " Natureza" ?></option>
                                        <?php
                                        foreach ($naturezas as $natureza) {
                                            ?>
                                            <option <?php
                                            if ($natureza['idNatureza'] == $idNatureza) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $natureza['idNatureza'] ?>"><?php echo $natureza['nomeNatureza'] ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </th>
                            <th class="text-center">idGrupoOper</th>
                            <th class="text-center">idEntSai</th>
                            <th class="text-center">xfop</th>
                            <th class="text-center" colspan="2">Ação</th>
                        </tr>
                    </thead>

                    <tbody id='dados' class="fonteCorpo">

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());

        function limpar() {
            buscar(null, null, null, null, null);
            window.location.reload();
        }

        function buscar(FiltroTipoOp, dadosOp, idAtividade, idProcesso, idNatureza) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/fisoperacao.php?operacao=filtrar',
                beforeSend: function () {
                    $("#dados").html("Carregando...");
                },
                data: {
                    FiltroTipoOp: FiltroTipoOp,
                    dadosOp: dadosOp,
                    idAtividade: idAtividade,
                    idProcesso: idProcesso,
                    idNatureza: idNatureza
                },
                success: function (msg) {
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<TR>";
                        linha = linha + "<TD>" + object.nomeOperacao + "</TD>";
                        linha = linha + "<TD>" + object.nomeAtividade + "</TD>";
                        linha = linha + "<TD>" + object.nomeProcesso + "</TD>";
                        linha = linha + "<TD>" + object.nomeNatureza + "</TD>";
                        linha = linha + "<TD>" + object.idGrupoOper + "</TD>";
                        linha = linha + "<TD>" + object.idEntSai + "</TD>";
                        linha = linha + "<TD>" + object.xfop + "</TD>";
                        linha = linha + "<TD>" + "<a class='btn btn-primary btn-sm' href='fisoperacao_alterar.php?idOperacao=" + object.idOperacao + "' role='button'><i class='bi bi-pencil-square'></i></i></a>" + "</TD>";
                        linha = linha + "<TD>" + "<a class='btn btn-danger btn-sm' href='fisoperacao_excluir.php?idOperacao=" + object.idOperacao + "' role='button'><i class='bi bi-trash'></i></i></a>" + "</TD>";
                        linha = linha + "</TR>";
                    }

                    $("#dados").html(linha);
                },
                error: function (e) {
                    alert('Erro: ' + JSON.stringify(e));
                }
            });
        }

        $("#FiltroAtividade").change(function () {
            buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
        });

        $("#FiltroProcesso").change(function () {
            buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
        });

        $("#FiltroNatureza").change(function () {
            buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
        });

        $(document).ready(function () {
            $("#buscar").click(function () {
                buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
            });

            $(document).keypress(function (e) {
                if (e.key === "Enter") {
                    buscar(null, null, $("#FiltroAtividade").val(), $("#FiltroProcesso").val(), $("#FiltroNatureza").val());
                }
            });
        });

        $('.btnAbre').click(function () {
            $('.menuFiltros').toggleClass('mostra');
            $('.diviFrame').toggleClass('mostra');
        });
    </script>


</body>

</html>