<?php
// gabriel 060623 15:06

include_once('../head.php');
?>


<body class="bg-transparent">

    <div class="container p-4" style="margin-top:10px">

        <div class="row">
            <div class="col-sm-8">
                <h2 class="tituloTabela">Inserir Natureza</h2>
            </div>
            <div class="col-sm-4" style="text-align:right">
                <a href="../configuracao/?tab=configuracao&stab=fisnatureza" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>
        
            <form class="mb-4" action="../database/fisnatureza.php?operacao=inserir" method="post">


                <div class="col-md-12 form-group">
                    <label class='control-label' for='inputNormal' style="margin-top: -20px;">Nome Natureza</label>
                    <div class="for-group">
                        <input type="text" class="form-control" name="nomeNatureza" autocomplete="off" required>
                    </div>
                </div>
                <div style="text-align:right">

                    <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
                </div>
            </form>
    

    </div>

</body>

</html>