<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisprocesso.php');

$processos = buscaProcesso($_GET['idProcesso']);

?>

<body class="bg-transparent">

    <div class="container" style="margin-top:10px">
       
        <div class="col-sm mt-4" style="text-align:right">
            <a href="../configuracao/?tab=configuracao&stab=fisprocesso" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
        </div>
        <div class="col-sm">
            <spam class="col titulo">Excluir Processo</spam>
        </div>

            <div class="container" style="margin-top: 10px">
                <form action="../database/fisprocesso.php?operacao=excluir" method="post">
                    <div class="col-md-12 form-group mb-4">

                    <label class='control-label' for='inputNormal'></label>
                    <div class="for-group">
                        <input type="text" class="form-control" name="nomeProcesso" value="<?php echo $processos['nomeProcesso'] ?>">
                    </div>
                    <input type="text" class="form-control" name="idProcesso" value="<?php echo $processos['idProcesso'] ?>" style="display: none">
                </div>
                    <div style="text-align:right">
                    <button type="submit" id="botao" class="btn btn-danger"><i class="bi bi-x-octagon"></i>&#32;Excluir</button>
                    </div>
                </form>
            </div>
        
    </div>


</body>

</html>