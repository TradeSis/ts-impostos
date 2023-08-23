<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisnatureza.php');

$natureza = buscaNatureza($_GET['idNatureza']);

?>

<body class="bg-transparent">

    <div class="container p-4" style="margin-top:10px">

        <div class="row">
            <div class="col-sm">
                <h2 class="tituloTabela">Excluir Natureza</h2>
            </div>
            <div class="col-sm mt-4" style="text-align:right">
                <a href="../configuracao/?tab=configuracao&stab=fisnatureza" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>


        <form class="mb-4" action="../database/fisnatureza.php?operacao=excluir" method="post">
            <div class="col-md-12 form-group mb-4">

                <label class='control-label' for='inputNormal'></label>
                <div class="for-group">
                    <input type="text" class="form-control" name="nomeNatureza" value="<?php echo $natureza['nomeNatureza'] ?>">
                </div>
                <input type="text" class="form-control" name="idNatureza" value="<?php echo $natureza['idNatureza'] ?>" style="display: none">
            </div>
            
            <div style="text-align:right; margin-top:20px">
                <button type="submit" id="botao" class="btn btn-sm btn-danger"><i class="bi bi-x-octagon"></i>&#32;Excluir</button>
            </div>
        </form>

    </div>


</body>

</html>