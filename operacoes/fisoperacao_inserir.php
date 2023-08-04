<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisatividade.php');
include_once('../database/fisnatureza.php');
include_once('../database/fisprocesso.php');

$atividades = buscaAtividade();
$processos = buscaProcesso();
$naturezas = buscaNatureza();

?>


<body class="bg-transparent">

    <div class="container" style="margin-top:10px">


        <div class="col-sm mt-4" style="text-align:right">
            <a href="fisoperacao.php" role="button" class="btn btn-primary"><i
                    class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
        </div>
        <div class="col-sm">
            <spam class="col titulo">Inserir Operação</spam>
        </div>

        <div class="container" style="margin-top: 30px">
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

                <div style="text-align:right;margin-top:30px">
                    <button type="submit" class="btn  btn-success"><i
                            class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>