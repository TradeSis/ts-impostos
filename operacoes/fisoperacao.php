<?php
//Lucas 13102023 novo padrao
// gabriel 060623 15:06
include_once(__DIR__ . '/../header.php');
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
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<style>
    ul {
        list-style-type: none;
    }
</style>

<body>

    <div class="container-fluid">

    <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <BR> <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row">
            <div class="col-6 order-1 col-sm-6  col-md-6 order-md-1 col-lg-1 order-lg-1">
                <button type="button" class="ts-btnFiltros btn btn-sm"><span class="material-symbols-outlined">
                        filter_alt
                    </span></button>

            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-2 order-lg-2">
                <h2 class="ts-tituloPrincipal">Operações Fiscais</h2>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-5 order-lg-3">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscanotas" placeholder="Buscar por id ou numero da nota">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button"><span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">
                                search
                            </span></button>
                    </span>
                </div>
            </div>


            <div class="col-6 order-2 col-sm-6 col-md-6 order-md-2 col-lg-4 order-lg-4 text-end" style=" margin-left:-30px ">
            <a href="fisoperacao_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>            </div>
        </div>

        <!-- <div class="row">
            <div class=" btnAbre">
                <span style="font-size: 25px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">
                    filter_alt
                </span>

            </div>

            <div class="col-sm-3 ml-2">
                <p class="ts-tituloPrincipal">Operações Fiscais</p>
            </div>

            <div class="col-sm" style="text-align:right">
                <a href="fisoperacao_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
            </div>
        </div> -->

            <div class="table mt-2 ts-divTabela ts-tableFiltros">
                <table class="table table-hover table-sm align-middle">
                    <thead class="ts-headertabelafixo">
                        <tr class="ts-headerTabelaLinhaCima">
                            <th>Operação</th>
                            <th >
                                <form action="" method="post">
                                    <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idAtividade"
                                        id="FiltroAtividade">
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
                            <th>
                                <form action="" method="post">
                                    <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idProcesso" id="FiltroProcesso">
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
                            <th>
                                <form action="" method="post">
                                    <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idNatureza" id="FiltroNatureza">
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
                            <th>idGrupoOper</th>
                            <th>idEntSai</th>
                            <th>xfop</th>
                            <th colspan="2">Ação</th>
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
                        linha = linha + "<TD>" + "<a class='btn btn-warning btn-sm' href='fisoperacao_alterar.php?idOperacao=" + object.idOperacao + "' role='button'><i class='bi bi-pencil-square'></i></i></a>" + "</TD>";
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

       /*  $('.btnAbre').click(function () {
            $('.menuFiltros').toggleClass('mostra');
            $('.diviFrame').toggleClass('mostra');
        }); */
        /* Novo script para menu filtros */
        $('.ts-btnFiltros').click(function() {
            $('.ts-menuFiltros').toggleClass('mostra');
            $('.ts-tableFiltros').toggleClass('mostra');
        });
    </script>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>