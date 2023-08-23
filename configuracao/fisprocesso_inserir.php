<?php
// gabriel 060623 15:06

include_once('../head.php');
?>


<body class="bg-transparent">

    <div class="container p-4" style="margin-top:10px">

        <div class="row">
            <div class="col-sm">
                <h2 class="tituloTabela">Inserir Processo</h2>
            </div>
            <div class="col-sm mt-4" style="text-align:right">
                <a href="../configuracao/?tab=configuracao&stab=fisprocesso" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form class="mb-4" action="../database/fisprocesso.php?operacao=inserir" method="post">


            <div class="col-md-12 form-group">
                <label class='control-label' for='inputNormal' style="margin-top: -20px;">Nome Processo</label>
                <div class="for-group">
                    <input type="text" class="form-control" name="nomeProcesso" autocomplete="off" required>
                </div>
            </div>

            <div style="text-align:right;margin-top:20px">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
            </div>
        </form>


    </div>

</body>

</html>