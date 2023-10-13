<?php
//Lucas 13102023 novo padrao
// gabriel 060623 15:06

include_once('../header.php');
include_once('../database/fisoperacao.php');
include_once('../database/fisatividade.php');
include_once('../database/fisnatureza.php');
include_once('../database/fisprocesso.php');

$atividades = buscaAtividade();
$processos = buscaProcesso();
$naturezas = buscaNatureza();
$operacao = buscaOperacao($_GET['idOperacao']);

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
                <h2 class="ts-tituloPrincipal">Alterar Operação</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="fisoperacao.php" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form action="../database/fisoperacao.php?operacao=alterar" method="post">

            <input type="text" class="form-control" name="idOperacao" value="<?php echo $operacao['idOperacao'] ?>" style="display: none">

            <label class="labelForm">Nome da operação</label>
            <input type="text" class="form-control" name="nomeOperacao" value="<?php echo $operacao['nomeOperacao'] ?>">

            <div class="row">
                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="labelForm">Atividade</label>
                    <select class="select form-control" name="idAtividade">
                        <?php
                        foreach ($atividades as $atividade) {
                        ?>
                            <option <?php
                                    if ($atividade['idAtividade'] == $operacao['idAtividade']) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $atividade['idAtividade'] ?>"><?php echo $atividade['nomeAtividade'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="labelForm">Processo</label>
                    <select class="select form-control" name="idProcesso">
                        <?php
                        foreach ($processos as $processo) {
                        ?>
                            <option <?php
                                    if ($processo['idProcesso'] == $operacao['idProcesso']) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $processo['idProcesso'] ?>"><?php echo $processo['nomeProcesso'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md form-group-select" style="margin-top: 37px;">
                    <label class="labelForm">Natureza</label>
                    <select class="select form-control" name="idNatureza">
                        <?php
                        foreach ($naturezas as $natureza) {
                        ?>
                            <option <?php
                                    if ($natureza['idNatureza'] == $operacao['idNatureza']) {
                                        echo "selected";
                                    }
                                    ?> value="<?php echo $natureza['idNatureza'] ?>"><?php echo $natureza['nomeNatureza'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="labelForm">idGrupoOper</label>
                    <input type="text" class="form-control" name="idGrupoOper" value="<?php echo $operacao['idGrupoOper'] ?>">
                </div>

                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="labelForm">idEntSai</label>
                    <input type="text" class="form-control" name="idEntSai" value="<?php echo $operacao['idEntSai'] ?>">
                </div>

                <div class="col-md form-group" style="margin-top: 37px;">
                    <label class="labelForm">xfop</label>
                    <input type="text" class="form-control" name="xfop" value="<?php echo $operacao['xfop'] ?>">
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