<?php
//Lucas 13102023 novo padrao
// gabriel 060623 15:06

include_once('../header.php');
include_once('../database/fisatividade.php');
include_once('../database/fisnatureza.php');
include_once('../database/fisprocesso.php');

$atividades = buscaAtividade();
$processos = buscaProcesso();
$naturezas = buscaNatureza();

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

    <div class="container-fluid">

        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <BR> <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Inserir Operação</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="fisoperacao.php" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form action="../database/fisoperacao.php?operacao=inserir" method="post">

            <label class="labelForm">Nome da operação</label>
            <input type="text" name="nomeOperacao" class="form-control" autocomplete="off">

            <div class="row">
                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="labelForm">Atividade</label>
                    <select class="select form-control" name="idAtividade">
                        <?php
                        foreach ($atividades as $atividade) {
                        ?>
                            <option value="<?php echo $atividade['idAtividade'] ?>"><?php echo $atividade['nomeAtividade'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="labelForm">Processo</label>
                    <select class="select form-control" name="idProcesso">
                        <?php
                        foreach ($processos as $processo) {
                        ?>
                            <option value="<?php echo $processo['idProcesso'] ?>"><?php echo $processo['nomeProcesso'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="labelForm">Natureza</label>
                    <select class="select form-control" name="idNatureza">
                        <?php
                        foreach ($naturezas as $natureza) {
                        ?>
                            <option value="<?php echo $natureza['idNatureza'] ?>"><?php echo $natureza['nomeNatureza'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="labelForm">idGrupoOper</label>
                    <input type="number" name="idGrupoOper" class="form-control" autocomplete="off">
                </div>

                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="labelForm">idEntSai</label>
                    <input type="number" name="idEntSai" class="form-control" autocomplete="off">
                </div>

                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="labelForm">xfop</label>
                    <input type="text" name="xfop" class="form-control" autocomplete="off">
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
            </div>
        </form>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->


</body>

</html>