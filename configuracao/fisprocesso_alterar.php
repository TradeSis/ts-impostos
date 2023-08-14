<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisprocesso.php');

$processos = buscaProcesso($_GET['idProcesso']);

?>

<body class="bg-transparent">

    <div class="container p-4" style="margin-top:10px">

        <div class="row">
            <div class="col-sm-8">
                <h2 class="tituloTabela">Alterar Processo</h2>
            </div>
            <div class="col-sm-4" style="text-align:right">
                <a href="../configuracao/?tab=configuracao&stab=fisprocesso" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form class="mb-4" action="../database/fisprocesso.php?operacao=alterar" method="post">

            <div class="col-md-12 form-group mb-4">

                <label class='control-label' for='inputNormal'></label>
                <div class="for-group">
                    <input type="text" class="form-control" name="nomeProcesso" value="<?php echo $processos['nomeProcesso'] ?>">
                </div>
                <input type="text" class="form-control" name="idProcesso" value="<?php echo $processos['idProcesso'] ?>" style="display: none">
            </div>

            <div style="text-align:right; margin-top:20px">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
            </div>
        </form>


    </div>


</body>

</html>